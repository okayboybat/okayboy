<?php

namespace Home\Controller;
use Think\Controller;

class CommonController extends Controller
{
	public function __construct()
	{
		// 被重写的构造方法的执行
		// parent, 表示父类
		// ::__constuct(), 调用父类的构造方法
		parent::__construct();

		// Home 模块的数据的初始化
		$this->initCategory();
	}

	// 分类数据的处理方法
	protected function initCategory()
	{
		// 展示分类
		$m_category = D('Category');
		$nested = $m_category->getCategoryNested();
		// var_dump($nested);
		$this->assign('category_nested', $nested);

	}
}