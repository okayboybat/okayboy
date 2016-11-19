<?php
namespace Home\Controller;


use Home\Controller\CommonController;

use Home\Ext\Cart;// 导入购物车类
use Redis;

class BuyController extends CommonController
{
	/**
	 * 添加商品到购物车
	 */
	public function addCartAction()
	{
		$cart = new Cart;

		// 获取
		$goods_id = I('request.goods_id');// 商品ID
		$option = I('request.option', []);// 所选选项
		$quantity = I('request.quantity');// 购买数量

		// 向购物车中加入商品
		$cart->addGoods($goods_id, $option, $quantity);
		
		if (IS_AJAX) {

			$this->ajaxReturn(['error'=>0]);

		} else {

		// 	// 跳转到购物车管理
			$this->redirect('/cart', [], 0);
		}
	}

	/**
	 * 购物车管理
	 * @return [type] [description]
	 */
	public function cartAction()
	{
		// 获取购物车
		$cart = new Cart;// use Home\Ext\Cart
		// 获取购物车中的商品列表
		$goods_list = $cart->getGoodsList();
		$this->assign('goods_list', $goods_list);
		// 获取购物车信息(总价格, 总商品数量...)
		$cart_info = $cart->getInfo();
		$this->assign('cart_info', $cart_info);

		$this->display();
	}

	/**
	 * 购物车ajax处理
	 */
	public function cartAjaxAction()
	{

		$cart = new Cart;

		switch (I('request.type')) {
			case 'miniCart':
				// 获取购物车信息
				$this->assign('info', $cart->getInfo());
				// 展示 miniCart模板
				$this->display('miniCart');
			break;

			case 'hasOption': 
				$goods_id = I('request.goods_id');
				$m_goods_attribute = M('GoodsAttribute');
				$row = $m_goods_attribute->where(['goods_id'=>$goods_id, 'is_option'=>'1'])->find();
				if ($row) {
					$this->ajaxReturn(['result'=>'yes', 'url'=>U('/goods/'.$goods_id)]);
				} else {
					$this->ajaxReturn(['result'=>'no']);
				}
			break;
			case 'remove':
				// 删除商品
				$cart->removeGoods(I('request.key'));
				// 重新获取购物车统计信息
				$info = $cart->getInfo();

				$this->ajaxReturn(['error'=>0, 'info'=>$info]);
			break;
		}
	}

	/**
	 * 处理 checkout动作中 关于ajax的操作
	 * @return [type] [description]
	 */
	public function checkoutAjaxAction()
	{
		$user = session('user');

		switch (I('request.type')) {
			case 'addAddress':
				$m_user_address = M('UserAddress');
				// 将当前用户的其他地址, 设为非默认
				$condition = ['user_id'=>$user['user_id'], 'is_default'=>'1'];
				$data = ['is_default'=>'0'];
				$m_user_address->where($condition)->save($data);

				// 添加地址, 并设置为默认
				$data = I('request.');// 获取request中的全部数据, 类似的post., get.
				$data['user_id'] = $user['user_id'];
				$data['is_default'] = '1';
				$m_user_address->add($data);

				// 返回地址列表
				// 获取用户的送货地址列表
				$m_user_address = D('UserAddress');// 使用关联模型获得, 地区的名字
				$condition = ['user_id'=>$user['user_id']];
				$address_list = $m_user_address->relation(true)->where($condition)->select();
				$this->ajaxReturn(['error'=>0, 'address_list'=>$address_list]);
				


			break;

			case 'region':
				$region_id = I('request.region_id');
				// 获取该地区下, 子地区
				$m_region = M('Region');
				$condition = ['parent_id'=>$region_id];

				$list = $m_region->where($condition)->select();
				$this->ajaxReturn(['error'=>0, 'list'=>$list]);
			break;
		}

	}

	/**
	 * 结算, 订单
	 */
	public function checkoutAction()
	{
		// 校验用户是否登录
		if (! $user = session('user')) {
			// 请重新登录
			// session中的数据, 依据跳转方法需要的参数传递即可
			session('login_jump_url', ['/checkout', []]);
			$this->redirect('/login', [], 0);
		}


		// 获取用户的送货地址列表
		$m_user_address = D('UserAddress');// 使用关联模型获得, 地区的名字
		$condition = ['user_id'=>$user['user_id']];
		$address_list = $m_user_address->relation(true)->where($condition)->select();
		$this->assign('address_list', $address_list);


		// 配送列表
		$condition = ['is_used'=>1];
		$shipping_list = M('Shipping')->where($condition)->order('sort_number')->select();
		// 实例化每种配送方式, 得到具体信息
		foreach($shipping_list as $key=>$shipping) {
			$shipping_class = 'Home\Ext\Shipping\\' . ucfirst($shipping['key']);
			$shipping_object = new $shipping_class;
			$shipping_list[$key]['price'] = $shipping_object->getPrice();
		}
		$this->assign('shipping_list', $shipping_list);


		// 支付列表
		$condition = ['is_used'=>1];
		$this->assign('payment_list', M('Payment')->where($condition)->order('sort_number')->select());

		// 购物车信息
		// 获取购物车
		$cart = new Cart;// use Home\Ext\Cart
		// 获取购物车中的商品列表
		$goods_list = $cart->getGoodsList();
		$this->assign('goods_list', $goods_list);
		// 获取购物车信息(总价格, 总商品数量...)
		$cart_info = $cart->getInfo();
		$this->assign('cart_info', $cart_info);
		

		// 展示
		$this->display();
	}


	/**
	 * 生成订单操作
	 * @return [type] [description]
	 */
	public function orderQueueAction()
	{
		// 校验用户是否登录
		if (! $user = session('user')) {
			// 请重新登录
			// session中的数据, 依据跳转方法需要的参数传递即可
			session('login_jump_url', ['/checkout', []]);
			$this->redirect('/login', [], 0);
		}
		
		// 订单的处理
		$order_data['user_id'] = $user['user_id'];
		$order_data['user_address_id'] = I('post.shipping_address');
		$order_data['shipping_key'] = I('post.shipping_method');
		$order_data['payment_key'] = I('post.payment_method');
		$order_data['order_status_id'] = '1';// 完成
		$order_data['shipping_status_id'] = '1';// 未发货
		$order_data['payment_status_id'] = '1';// 未支付
		$order_data['create_at'] = time();
		$order_data['update_at'] = time();

		// 获取购物车相关的数据
		$cart = new Cart;
		$goods_list = $cart->getGoodsList();
		$cart_info = $cart->getInfo();

		// 获取到订单信息后, 存入队列, 立即响应
		$redis = new Redis;
		$host = '127.0.0.1';
		$port = '6379';
		$result = $redis->connect($host, $port);
		// 获取等待的数量
		$order_length = $redis->llen('order_list');
		$this->assign('order_length', $order_length);
		// 入队列
		$redis->lpush('order_list', serialize([$order_data, $goods_list, $cart_info]));
		// 同时记录当前用户该订单的处理状态, 完成 或 未完成
		$redis->set('order_' . $user['user_id'], '1');// 订单还在处理中

		$this->assign('user', $user);
		// 立即响应给用户
		$this->display();
		die;

	}
	// 轮询的ajax的请求
	public function orderQueueAjaxAction()
	{
		$user = session('user');
		$redis = new Redis;
		$host = '127.0.0.1';
		$port = '6379';
		$result = $redis->connect($host, $port);
		$user_id = I('request.user_id', $user['user_id']);

		// 判断该订单是否处理完毕
		if ($redis->get('order_'.$user_id)) {
			// 没有处理完, 获取当前处理进度
			$this->ajaxReturn(['complete'=>false, 'length'=>$redis->llen('order_list')]);
		} else {
			// 处理完毕, 订单处理完毕
			// 
			$this->ajaxReturn(['complete'=>true]);
		}


	}

	public function orderQueueProcessAction()
	{
		$redis = new Redis;
		$host = '127.0.0.1';
		$port = '6379';
		$result = $redis->connect($host, $port);

		// 守护进程的方式进行处理
		while(true) {
			sleep(3);
			$order = $redis->rpop('order_list');
			if(! $order) {
				continue;
			}

			$order = unserialize($order);

			$order_data = $order[0];
			$goods_list = $order[1];
			$cart_info = $order[2];

			// 商品总价
			$order_data['goods_total'] = $cart_info['total'];

			// 获取配送的详细信息
			$shipping_class = 'Home\Ext\Shipping\\' . ucfirst($order_data['shipping_key']);
			$shipping = new $shipping_class;
			// 配送费
			$order_data['shipping_price'] = $shipping->getPrice();
			// 订单总价
			$order_data['total'] = $order_data['goods_total'] + $order_data['shipping_price'];

			$m_goods = M('Goods');
			// 库存量的检测
			foreach($goods_list as $key=>$goods) {
				// 商品库存, (没有做, 每种选项一个库存)
				$goods_id = $goods['goods_id'];
				$quantity = $goods['quantity'];
				$goods_quantity = $m_goods->getFieldByGoodsId($goods_id);
				if ($goods_quantity < $quantity) {
					// 该商品的库存不足
					// 记录下来哪些商品失败了
					$faild_goods[$key] = $goods;
					// 删除当前商品列表中的该商品
					unset($goods_list[$key]);
				}
			}

			if (empty($goods_list)) {
				$this->error('所购买的所有商品, 库存不足, 请持续关注', U('/cart'), 2);
			}

			// 插入一条记录到order表中, 再插入对应的order_goods即可! 
			$m_order = M('Order');
			if (! $m_order->create($order_data)) {
				// 订单验证失败
				$this->error('订单生成失败', U('/checkout'), 1);
			}
			// 插入订单
			if ($order_id = $m_order->add($order_data)) {

				// 插入当前订单的相关商品
				foreach($goods_list as $key=>$goods) {
					$rows[] = [
						'order_id'	=> $order_id,
						'key'	=> $key,
						'goods_id'	=> $goods['goods_id'],
						'quantity'	=> $goods['quantity'],
						'price'	=> $goods['info']['price'],
					];
					// 如果设计的是, 在生成订单时, 削减库存, 在该位置, 就应该处理了.
					$m_goods->where(['goods_id'=>$goods['goods_id']])->setDec('quantity', $goods['quantity']);// setDec 在原有基础上减, setInc,在原有基础上加
				}

				$m_order_goods = M('OrderGoods');
				$m_order_goods->addAll($rows);
			}

			// 订单处理完毕, 将订单标识, 删除掉
			$redis->delete('order_' . $order_data['user_id']);
			echo 'Order Ok!' , "\n";
		}
	}

	/**
	 * 生成订单操作
	 * @return [type] [description]
	 */
	public function orderAction()
	{
		// 校验用户是否登录
		if (! $user = session('user')) {
			// 请重新登录
			// session中的数据, 依据跳转方法需要的参数传递即可
			session('login_jump_url', ['/checkout', []]);
			$this->redirect('/login', [], 0);
		}

		// 订单的处理
		$order_data['user_id'] = $user['user_id'];
		$order_data['user_address_id'] = I('post.shipping_address');
		$order_data['shipping_key'] = I('post.shipping_method');
		$order_data['payment_key'] = I('post.payment_method');
		$order_data['order_status_id'] = '1';// 完成
		$order_data['shipping_status_id'] = '1';// 未发货
		$order_data['payment_status_id'] = '1';// 未支付
		$order_data['create_at'] = time();
		$order_data['update_at'] = time();

		// 获取购物车相关的数据
		$cart = new Cart;
		$goods_list = $cart->getGoodsList();
		$cart_info = $cart->getInfo();
		// 商品总价
		$order_data['goods_total'] = $cart_info['total'];

		// 获取配送的详细信息
		$shipping_class = 'Home\Ext\Shipping\\' . ucfirst($order_data['shipping_key']);
		$shipping = new $shipping_class;
		// 配送费
		$order_data['shipping_price'] = $shipping->getPrice();
		// 订单总价
		$order_data['total'] = $order_data['goods_total'] + $order_data['shipping_price'];

		$m_goods = M('Goods');
		// 库存量的检测
		foreach($goods_list as $key=>$goods) {
			// 商品库存, (没有做, 每种选项一个库存)
			$goods_id = $goods['goods_id'];
			$quantity = $goods['quantity'];
			$goods_quantity = $m_goods->getFieldByGoodsId($goods_id);
			if ($goods_quantity < $quantity) {
				// 该商品的库存不足
				// 记录下来哪些商品失败了
				$faild_goods[$key] = $goods;
				// 删除当前商品列表中的该商品
				unset($goods_list[$key]);
			}
		}

		if (empty($goods_list)) {
			$this->error('所购买的所有商品, 库存不足, 请持续关注', U('/cart'), 2);
		}

		// 插入一条记录到order表中, 再插入对应的order_goods即可! 
		$m_order = M('Order');
		if (! $m_order->create($order_data)) {
			// 订单验证失败
			$this->error('订单生成失败', U('/checkout'), 1);
		}
		// 插入订单
		if ($order_id = $m_order->add($order_data)) {

			// 插入当前订单的相关商品
			foreach($goods_list as $key=>$goods) {
				$rows[] = [
					'order_id'	=> $order_id,
					'key'	=> $key,
					'goods_id'	=> $goods['goods_id'],
					'quantity'	=> $goods['quantity'],
					'price'	=> $goods['info']['price'],
				];
				// 如果设计的是, 在生成订单时, 削减库存, 在该位置, 就应该处理了.
				$m_goods->where(['goods_id'=>$goods['goods_id']])->setDec('quantity', $goods['quantity']);// setDec 在原有基础上减, setInc,在原有基础上加
			}

			$m_order_goods = M('OrderGoods');
			$m_order_goods->addAll($rows);
		}

		// 插入完毕
		// 跳转结果: 
		// 1, 订单摘要, 提示进行后续操作(例如支付, 其他的工作). 
		// 2, 跳转到用户中心的订单管理
		// 跳转到订单信息
		$this->redirect('/orderInfo', [], 0);


	}


	public function orderInfoAction()
	{

		echo '订单信息, 请继续操作';
	}
}