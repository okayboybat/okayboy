<?php

namespace Back\Controller;
use Think\Controller;
use Vendor\Page;

/**
 * 后台管理控制器类
 */
class ShippingController extends Controller
{

	/**
	 * Shipping列表动作
	 * @return [type] [description]
	 */
	public function listAction()
	{
		// 一: 获取数据库中已经存在的配送方式
		// 获取模型
		$model = M('Shipping');

		// 条件, 搜索的处理
		$cond = [];// 默认没有条件

		// 判断请求数据中 是否存在 keyword 存在说明需要搜索
		$search['keyword'] = I('request.keyword', '', 'trim');
		if ($search['keyword'] !== '') {
			// 需要搜索, 数据建议去掉两端空格
			$cond['keyword'] = ['like', "{$search['keyword']}%"];
		}
		// 分配模板, 为了记住搜索词
		$this->assign('search', $search);

		// 排序
		$sort_field = I('request.sortField', '', '');
		$sort_type = I('request.sortType', '', '');
		$sort = "$sort_field $sort_type";
		$sort = $sort == ' ' ? '' : $sort;// 防止没有排序字段
		// 将当前的排序字段类型 分配到模板中
		$this->assign('sort', ['field'=>$sort_field, 'type'=>$sort_type]);

		// 分页
		$page = isset($_REQUEST['p']) ? ($_REQUEST['p']) : 1;
		$pagesize = 10;

		// 查询
		$list = $model
			->where($cond)
			->order($sort)
			->page($page, $pagesize)
			->select();

		// 二, 配送方式目录中, 真正存在的配送插件
		$shipping_path = APP_PATH . 'Home/Ext/Shipping/';
		// 遍历目录下的全部文件, 将其载入
		$handle = opendir($shipping_path);
		while ($filename = readdir($handle)) {
			if ($filename == '.' || $filename == '..') continue;
			// 载入每个文件, 获取每个每个类名
			$shipping_list[] = require $shipping_path . $filename;

		}

		// 三 整合数据库中记录与目录中的文件 对应关系
		// 1, 文件存在, 记录不存在, 2, 文件不存在, 记录存在, 3, 文件记录都存在
		foreach($shipping_list as $name) {
			$name = 'Home\Ext\Shipping\\' . $name;
			// 思考, 所获取的文件, 不满足配送方式定义呢(没有实现I-Shipping接口)?
			// 没有实现该接口, 不予处理
			if (! is_subclass_of($name, 'Home\Ext\I_Shipping')) continue;

			$shipping = new $name; //Home\Ext\Shipping\EMS, 可变类名在使用时, 需要将命名空间全路径写出来
			$key = $shipping->getKey();// 得到唯一标识
			$key_list[] = $key;// 记录下所有的存在文件的key
			// 判断是否存在于 记录中
			$flag = false;// 不存在
			foreach($list as $row) {
				if ($row['key'] == $key) {
					// 已经存在
					$flag = true;
					// 不需要做额外的管理
				}
			}
			if ($flag) {
				// 记录中存在
			} else {
				// 记录中不存在
				// 添加到list中
				$list[] = [
					'title'	=> $shipping->getTitle(),
					'description'	=> $shipping->getDescription(),
					'is_used'	=> 0,
					'is_default'	=> 0,
					'sort_number'	=> 0,
					'key'	=> $shipping->getKey(),
					'is_new'	=> true,// 是新的记录
				];
			}
			
		}

		// 找到那些 记录存在, 但是文件不存在的配送方式
		foreach($list as &$row) {
			if (! in_array($row['key'], $key_list)) {
				$row['is_delete'] = true;
			}
		}
		


		$this->assign('list', $list);

		// 处理分页连接
		$count = $model->where($cond)->count();
		$page = new Page($count, $pagesize);
		// 增加额外的参数(在非GET请求情况下)
		// $this->paramter = array_merge($cond, ['sortField'=>$sort_field, 'sortType'=>$sort_type]);
		// 形成翻页HTML代码, 分配到模板中
		$this->assign('page_html', $page->show());

		// 展示
		$this->display(); 

	}
	/**
	 * 添加
	 */
	public function addAction()
	{
		// 如果是提交数据
		if (IS_POST) {

			$model = M('Shipping');
			// 自己添加验证规则
			$validate = [];
			if ($model->validate($validate)->create()) {
				// 验证通过
				$pk = $model->add();

				// 成功, 跳转到列表页
				$this->redirect('list', [], 0);
			} else {
				$this->error('添加失败:'.$model->getError(), U('add'));
			}
		} 
		// 如果是展示表单
		else {
			$this->display();
		}
	}


	/**
	 * 批量操作
	 */
	public function multiAction()
	{

		// 获取所有操作的ID
		$selected = I('post.selected', [], '');
		// 如果没有任何的主键ID被选中, 则不执行后续操作
		if (empty($selected)) {
			$this->redirect('list', [], 0);
		}

		// 操作的模型
		$model = M('Shipping');
		// 判断当前操作类型
		$operate = $_POST['operate'];
		switch ($operate) {
			case 'delete':
				$cond['shipping_id'] = ['in', $selected];
				$model->where($cond)->delete();
				break;
			// 其他的批量操作
		}

		// 跳转list
		$this->redirect('list', [], 0);
	}


	/**
	 * 编辑
	 */
	public function editAction()
	{

		$model = M('Shipping');

		if (IS_POST) {

			$validate = [];
			if ($model->validate($validate)->create()) {
				// 验证通过
				$model->save();
				// 成功, 跳转到列表页
				$this->redirect('list', [], 0);
			} else {
				$this->error('更新失败:'.$model->getError(), U('edit', ['shipping_id'=>I('post.shipping_id')]));
			}
	
		} else {
			// 获取当前数据
			$this->assign('row', $model->find($_GET['shipping_id']));

			$this->display();
		}

	}
}
