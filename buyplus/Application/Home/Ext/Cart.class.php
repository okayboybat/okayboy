<?php

namespace Home\Ext;

/**
 * 购物车类
 */
class Cart
{	
	// 购物车中的商品列表 
	private $goods_list = [];

	public function getGoodslist()
	{

		// 先获商品的扩展信息(价格, 重量等)
		$this->extendGoodsList();
		
		
		return $this->goods_list;
	}

	/**
	 * 返回购物车信息
	 */
	public function getInfo()
	{	
		// 先获商品的扩展信息(价格, 重量等)
		$this->extendGoodsList();
		// 
		$total = $total_quantity = $total_weight = 0;
		foreach($this->goods_list as $goods) {
			$total += $goods['info']['sum_price'];// 总价格
			$total_quantity += $goods['quantity'];// 总数量
			$total_weight += $goods['info']['weight'];// 克
		}
		// 返回总金额, 和 商品总数量 
		return ['total'=>$total, 'total_quantity'=>$total_quantity, 'total_weight'=>$total_weight];
	}
	/**
	 * 利用购物车中商品, 获取商品的扩展信息
	 * @return [type] [description]
	 */
	private function extendGoodsList() {
		// 处理好每个商品的 (名称,重量, 缩略图)价格后, 再返回
		$m_goods = M('Goods');
		foreach($this->goods_list as &$goods) {
			// 得到当前商品的展示信息
			$info = $m_goods
					->field('g.name, g.weight, g.image_thumb, g.price, wu.title as wu_title')
					->alias('g')
					->join('left join __WEIGHT_UNIT__ wu using(weight_unit_id)')
					->find($goods['goods_id']);
			// 处理重量单位, 原则转换成一致单位 容易计算
			if ($info['weight']) {
				if ($info['wu_title'] == '千克') {
					$info['weight'] *= 1000;
				} elseif ($info['wu_title'] == '斤(500克)') {
					$info['weight'] *= 500;
				}
			}

			// 商品价格计算, 通过商品的全部选项计算出来商品的价格
			$m_goods_attribute_value = M('GoodsAttributeValue');
			$info['option_str'] = '';
			foreach($goods['option'] as $goods_attribute_value_id) {
				
				$row = $m_goods_attribute_value
							->field('price_operate, price_drift, ao.value, a.title')
							->alias('gav')
							->join('left join __ATTRIBUTE_OPTION__ ao using(attribute_option_id)')
							->join('left join __ATTRIBUTE__ a using(attribute_id)')
							->find($goods_attribute_value_id);
				// 价格浮动
				if ($row['price_operate'] == '1') {
					$info['price'] += $row['price_drift'];
				} else {
					$info['price'] -= $row['price_drift'];
				}

				// 选项参数
				$info['option_str'] .= $row['title'] . ':' . $row['value'] . ',';
			}
			
			// 商品总计
			$info['sum_price'] = $info['price'] * $goods['quantity'];
			// 独立到info字段中, 存储(持久化时, 不需要存储这些信息)
			$goods['info'] = $info;
		} 
	}

	/**
	 * [addGoods description]
	 * @param [type] $goods_id 商品id
	 * @param [type] $option   所选选项列表
	 * @param [type] $quantity 购买数量
	 */
	public function addGoods($goods_id, $option, $quantity)
	{
		// 形成商品的唯一标识
		$key = $this->generateKey($goods_id, $option);

		// 判断此商品是否存在
		if (isset($this->goods_list[$key])) {
			// 存在, 增加数量即可
			$this->goods_list[$key]['quantity'] += $quantity;
		} else {
			// 不存在, 添加
			$this->goods_list[$key] = [
				'goods_id'	=> $goods_id,
				'option'	=> $option,
				'quantity'	=> $quantity,
			];
		}


	}



	/**
	 * 利用 商品id, 和选择的选项, 生成购物车中商品的唯一标识
	 */
	private function generateKey($goods_id, $option)
	{
		// 连接选项部分
		$option_str = '';
		if ($option) {// 选项非空
			ksort($option);// 按照下标排序, 保证选项顺序一致
			foreach($option AS $id=>$value_id) {
				$option_list[] = $id . '-' . $value_id;
			}
			$option_str = implode(',', $option_list);	
		}
		return $goods_id . '|' . $option_str;
	}

	/**
	 * 删除商品
	 */
	public function removeGoods($key) {
		// 如果这个标识key的商品存在, 则删除之!
		if (isset($this->goods_list[$key])) {
			unset($this->goods_list[$key]);
		}
	}

	/**
	 * 更新购物车商品
	 * @param  [type] $key      [description]
	 * @param  [type] $quantity [description]
	 * @return [type]           [description]
	 */
	public function updateGoods($key, $quantity)
	{
		$this->goods_list[$key]['quantity'] = $quantity;
	}
	/**
	 * 构造方法
	 */
	public function __construct()
	{
		// 初始化购物车
		$this->initCart();
	}

	/**
	 * 初始化购物车
	 */
	private function initCart()
	{
		// 判断当前用户登录与否
		if($user = session('user')) {
			// 已登录
			// 先通过用户得到 对应的cart_id
			$cart_id = M('Cart')->getFieldByUserId($user['user_id'], 'cart_id');
			if (! $cart_id) {
				// 当前用户没有对应的购物车
				$this->goods_list = [];
				return;
			}
			// 存在对应购物车
			$m_cart_goods = M('CartGoods');
			$goods_rows = $m_cart_goods->where(['cart_id'=>$cart_id])->select();
			if (! $goods_rows) {// 车内是否存在商品
				$this->goods_list = [];
				return ;
			}
			foreach($goods_rows as $row) {
				// goods_id
				$arr = explode('|', $row['key']);// 21|10-18
				// option
				if ('' == $arr[1]) {
					$option = [];
				} else {
					// 有选项
					$option = [];
					array_map(function($item) use(& $option) {// $item == 10-1
						// list 使用是一个数组为多个变量赋值, 数组的0元素为第一个变量赋值, 1元素为第二个变量赋值.以此类推
						list($k, $v) = explode('-', $item);//[10, 1]
						$option[$k] = $v;
					}, explode(',', $arr[1]));	
				}
				$this->goods_list[$row['key']] = [
					'goods_id'	=> $arr[0],
					'option'	=> $option,
					'quantity'	=> $row['quantity'],
				];
			}


			
		} else {
			// 未登录
			// 判断 cookie中,是否存在购物车数据
			if (cookie('cart')) {
				$this->goods_list = unserialize(cookie('cart'));
			} else {
				$this->goods_list = [];
			}
		}

	}


	/**
	 * 购物车对象被销毁时, 自动被调用的方法
	 */
	public function __destruct()
	{
		// file_put_contents('./log.txt', var_export($this->goods_list, true));
		// 存储购物车中的数据
		$this->saveCart();
	}
	/**
	 * 存储购物车中的数据
	 * @return [type] [description]
	 */
	private function saveCart()
	{
		// 去掉不需要持久化的信息
		foreach($this->goods_list as $key=>$goods) {
			unset($this->goods_list[$key]['info']);
		}
		// 判断当前用户登录与否
		if($user = session('user')) {
			// 已登录
			// 判断当前用户拥有的购物车记录是否存在
			// 得到cart_id
			$cart = M('Cart')->where(['user_id'=>$user['user_id']])->find();
			if (! $cart) {
				// 该用户还没有与之对应的购物车, 则插入购物车
				$cart_id = M('Cart')->add(['user_id'=>$user['user_id']]);
			} else {
				// 购物车存在
				$cart_id = $cart['cart_id'];
			}

			$m_cart_goods = M('CartGoods');
			// 清除当前cart_id对应的商品记录
			$m_cart_goods->where(['cart_id'=>$cart_id])->delete();

			// 当前购物车内商品, 存储到购物车表
			foreach($this->goods_list as $key=>$goods) {
				$m_cart_goods->add([
					'cart_id'	=> $cart_id,
					'key'		=> $key,
					'quantity'	=> $goods['quantity']
					]);
			}
			
		} else {
			// 未登录
			cookie('cart', serialize($this->goods_list), 24*3600*60);
		}

	}
}