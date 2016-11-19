<?php

namespace Back\Controller;
use Think\Controller;
use Vendor\Page;

/**
 * 后台管理控制器类
 */
class LengthUnitController extends Controller
{

	/**
	 * LengthUnit列表动作
	 * @return [type] [description]
	 */
	public function listAction()
	{
		// 获取模型
		$model = M('LengthUnit');

		// 条件, 搜索的处理
		$cond = [];// 默认没有条件

		// 判断请求数据中 是否存在 brand_title 存在说明需要搜索
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
		$pagesize = 2;

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

			$model = M('LengthUnit');
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
		$model = M('LengthUnit');
		// 判断当前操作类型
		$operate = $_POST['operate'];
		switch ($operate) {
			case 'delete':
				$cond['length_unit_id'] = ['in', $selected];
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

		$model = M('LengthUnit');

		if (IS_POST) {

			$validate = [];
			if ($model->validate($validate)->create()) {
				// 验证通过
				$model->save();
				// 成功, 跳转到列表页
				$this->redirect('list', [], 0);
			} else {
				$this->error('更新失败:'.$model->getError(), U('edit', ['length_unit_id'=>I('post.length_unit_id')]));
			}
	
		} else {
			// 获取当前数据
			$this->assign('row', $model->find($_GET['length_unit_id']));

			$this->display();
		}

	}
}
