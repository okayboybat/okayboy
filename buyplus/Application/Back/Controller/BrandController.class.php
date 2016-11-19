<?php

namespace Back\Controller;
use Think\Controller;
use Vendor\Page;

/**
 * 后台的品牌管理控制器类
 */
class BrandController extends Controller
{

	/**
	 * 品牌列表动作
	 * @return [type] [description]
	 */
	public function listAction()
	{
		// 获取品牌数据
		$m_brand = M('Brand');

		// 条件, 搜索的处理
		$cond = [];// 默认没有条件

		// 判断请求数据中 是否存在 brand_title 存在说明需要搜索
		$brand_title = I('request.brand_title', '', 'trim');
		if ($brand_title !== '') {
			// 需要搜索, 数据建议去掉两端空格
			$cond['brand_title'] = ['like', "$brand_title%"];
		}
		// 分配模板, 为了记住搜索词
		$this->assign('brand_title', $brand_title);

		// 排序
		$sort_field = I('get.sortField', 'sort_number', '');
		$sort_type = I('get.sortType', 'asc', '');
		$sort = "$sort_field $sort_type";
		// 将当前的排序字段类型 分配到模板中
		$this->assign('sort', ['field'=>$sort_field, 'type'=>$sort_type]);

		// 分页
		$page = isset($_REQUEST['p']) ? ($_REQUEST['p']) : 1;
		$pagesize = 5;

		// 查询
		$list = $m_brand
			->where($cond)
			->order($sort)
			->page($page, $pagesize)
			->select();
		$this->assign('list', $list);

		// 处理分页连接
		$count = $m_brand->where($cond)->count();
		$page = new Page($count, $pagesize);
		// 增加额外的参数(在非GET请求情况下)
		// $this->paramter = array_merge($cond, ['sortField'=>$sort_field, 'sortType'=>$sort_type]);
		// 形成翻页HTML代码, 分配到模板中
		$this->assign('page_html', $page->show());

		// 展示
		$this->display(); 

	}
	/**
	 * 匹配添加
	 */
	public function addAction()
	{
		// 如果是提交数据
		if (IS_POST) {

			$m_brand = M('Brand');
			$validate = [
				['brand_title', 'require', '品牌名称必填'],
				['brand_title', '', '品牌名称已经存在', 0, 'unique'],
				['sort_number', 'require', '排序值必须存在'],
				['sort_number', '/^-?\d+$/', '排序值为整数', 0, 'regex'],
			];
			if ($m_brand->validate($validate)->create()) {
				// 验证通过
				$brand_id = $m_brand->add();

				// 成功, 跳转到列表页
				$this->redirect('list', [], 0);
			} else {
				$this->error('添加失败:'.$m_brand->getError(), U('add'));
			}
		} 
		// 如果是展示表单
		else {
			$this->display();
		}
	}

	/**
	 * 验证品牌标题是否重复
	 * @return [type] [description]
	 */
	public function checkTitleOnlyAction()
	{
		$m_brand = M('Brand');

		// 是否存在brand_id参数
		$brand_id = I('get.brand_id', 0);
		if ($brand_id == 0) {
			// 没有brand_id, 添加
			// 利用品牌名称检索记录
			$cond = ['brand_title'=>$_REQUEST['brand_title']];
		} else {
			// 存在brand_id, 更新编辑
			$cond['brand_title'] = $_REQUEST['brand_title'];
			$cond['brand_id'] = ['neq', $brand_id];
		}


		$row = $m_brand->where($cond)->find();
		// 一旦找到, 说明重复, 响应false, 否则响应true
		echo (bool)$row ? 'false' : 'true'; 
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
		$m_brand = M('Brand');
		// 判断当前操作类型
		$operate = $_POST['operate'];
		switch ($operate) {
			case 'delete':
				$cond['brand_id'] = ['in', $selected];
				$m_brand->where($cond)->delete();

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

		$m_brand = M('Brand');

		if (IS_POST) {

			$validate = [
				['brand_title', 'require', '品牌名称必填'],
				['brand_title', '', '品牌名称已经存在', 0, 'unique'],
				['sort_number', 'require', '排序值必须存在'],
				['sort_number', '/^-?\d+$/', '排序值为整数', 0, 'regex'],
			];
			if ($m_brand->validate($validate)->create()) {
				// 验证通过
				$m_brand->save();
				// 成功, 跳转到列表页
				$this->redirect('list', [], 0);
			} else {
				$this->error('更新失败:'.$m_brand->getError(), U('edit', ['brand_id'=>I('post.brand_id')]));
			}

				
		} else {
			// 获取当前数据
			$this->assign('brand', $m_brand->find($_GET['brand_id']));

			$this->display();
		}

	}
}
