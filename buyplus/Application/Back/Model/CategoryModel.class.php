<?php


namespace Back\Model;
use Think\Model;

class CategoryModel extends Model
{
	/**
	 * 获取树状列表
	 * @return [type] [description]
	 */
	public function getTreeList()
	{
		return $this->tree($this->order('sort_number')->select());
	}

	/**
	 * 获取树状列表
	 * @return [type] [description]
	 */
	public function tree($list, $category_id=0, $deep=0)
	{
		// 声明静态的局部变量, 存储找到的子分类
		static $tree = [];
		// 遍历每个分类
		foreach($list as $row) {
			// 判断当前分类是否为子分类
			if ($row['parent_id'] == $category_id) {
				// 如果是
				// 记录当前分类的深度
				$row['deep'] = $deep;
				// 存储到tree中
				$tree[] = $row;
				// 递归找当前子分类继续的子分类
				$this->tree($list, $row['category_id'], $deep+1);
			}
		}

		return $tree;
	}
}