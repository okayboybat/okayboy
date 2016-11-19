<?php
namespace Home\Model;
use Think\Model;

class GoodsModel extends Model
{

	public function getParents($goods_id)
	{
		// 获取直属分类
		// $row = $this->field('category_id')->find($goods_id);
		// $category_id = $row['category_id'];
		// 间写成下面的代码
		$category_id = $this->getFieldByGoodsId($goods_id, 'category_id');

		// 调用递归方法, 获得分类直到顶级分类
		return $this->getCategoryPath($category_id);
	}

	public function getCategoryPath($category_id) {
		static $parents = [];
		// 先获得当前分类信息
		$m_category = M('Category');
		$category = $m_category->find($category_id);
		// 生成该分类的URL地址, 假设 /category/:category_id
		$category['url'] = U('/category/'.$category['category_id']);
		// 存储起来, 在数组的头部插入元素, 避免后边倒序问题
		array_unshift($parents, $category);
		// 判断是否为顶级分类
		if ($category['parent_id'] != '0') {
			// 不是顶级分类
			$this->getCategoryPath($category['parent_id']);
		}

		return $parents;
	}
}