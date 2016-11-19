<?php

namespace Back\Controller;
use Think\Controller;
use Vendor\Page;

/**
 * 后台管理控制器类
 */
class AttributeController extends Controller
{

	/**
	 * Attribute列表动作
	 * @return [type] [description]
	 */
	public function listAction()
	{
		// 获取模型
		$model = M('Attribute');

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
			$model = M('Attribute');
			// 自己添加验证规则
			$validate = [];
			if ($model->validate($validate)->create()) {
				// 验证通过
				$pk = $model->add();

				// 属性添加成功, 处理属性的预设值
				// 先获取当前属性输入类型
				$m_attribute_type = M('AttributeType');
				$type = $m_attribute_type->find($_POST['attribute_type_id']);
				if (in_array($type['title'], ['select', 'select_multiple'])) {
					// 具有选项的属性, 处理选项的预设值
					$option_list_str = I('post.option_list');
					// 使用换行符 分割['windows 7', 'windows 8', '']
					if (false !== strpos($option_list_str, "\r\n")) {
						// 存在 \r\n换行
						$option_list = explode("\r\n", $option_list_str);
					} else {
						$option_list = explode("\n", $option_list_str);
					}
					// 遍历option_list, 形成一个一次性可以插入的二维数组
					// 匿名函数中, 如果需要使用外层作用域的变量, 可以使用use(变量列表)的语法,将外部变量导入到匿名函数内部
					$row_list = array_map(function($option) use($pk) {// $pk(匿名内) = $pk(addAction内)
						return ['attribute_id'=>$pk, 'value'=>$option];
					}, $option_list);
					// addAll(), 一次性插入多条记录, 需要提供二维数组, 二维数组的每个元素, 一条记录数据
					M('AttributeOption')->addAll($row_list);
				}

				// 成功, 跳转到列表页
				$this->redirect('list', [], 0);
			} else {
				$this->error('添加失败:'.$model->getError(), U('add'));
			}
		} 
		// 如果是展示表单
		else {
			// 需要的数据
			$this->assign('group_list', M('AttributeGroup')->order('sort_number')->select());
			$this->assign('type_list', M('AttributeType')->select());

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
		$model = M('Attribute');
		// 判断当前操作类型
		$operate = $_POST['operate'];
		switch ($operate) {
			case 'delete':
				$cond['attribute_id'] = ['in', $selected];
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

		$model = M('Attribute');

		if (IS_POST) {

			$validate = [];
			if ($model->validate($validate)->create()) {
				// 验证通过
				$model->save();
				// 成功, 跳转到列表页
				$this->redirect('list', [], 0);
			} else {
				$this->error('更新失败:'.$model->getError(), U('edit', ['attribute_id'=>I('post.attribute_id')]));
			}
	
		} else {
			// 获取当前数据
			$this->assign('row', $model->find($_GET['attribute_id']));

			$this->display();
		}

	}
}
