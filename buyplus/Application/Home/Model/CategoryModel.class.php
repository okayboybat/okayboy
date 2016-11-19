<?php

namespace Home\Model;
use Think\Model;

class CategoryModel extends Model
{


	public function getCategoryNested()
	{
		// 配置缓存
		// S(['type'=>'memcache',
		//    'host'=> UC('memcached_host'), 
		//    'port'=> UC('memcached_port')]
		// );
		// 测试缓存是否存在
		// if (false === ($category_nested = S('category_nested'))) {
			// 缓存不存在, 数据库中查询, 并递归获取结果
			// 获取所有需要展示的分类
			$cond['is_used'] = 1;// 可用
			$cond['is_nav'] = 1;// 可出现在导航栏
			$list = $this
				->where($cond)
				->order('sort_number')
				->select();	
			// 递归处理 
			$category_nested = $this->nested($list);
			// 生成缓存
		// 	S('category_nested', $category_nested);
		// }
		// 返回数据
		return $category_nested;


	}

	/**
	 * [nested description]
	 * @param  array  $rows        所有分类
	 * @param  integer $category_id 当前分类ID
	 * @return array 	嵌套格式的数据例如: 
	 * [
	 * 		['category_id'=>'5', 'title'=>'眼镜', 'children'=>[
	 * 	 			['category_id'=>'6', 'title'=>'男士眼镜', 'children'=>[
	 * 	 				[....]
	 * 	 			]],
	 * 	 			['category_id'=>'7', 'title'=>'飞行员眼镜', 'children'=>[]],
	 * 			]
	 * 		],
	 * 		['category_id'=>'11', 'title'=>'图书', 'children'=>[...]]
	 * ]
	 */
	protected function nested($rows, $category_id=0)
	{
		$nested = [];
		// 遍历所有的分类
		foreach($rows as $row) {
			// 查找当前分类的子分类
			if ($row['parent_id']==$category_id) {
				// 是子分类
				// 找到当前子分类下子分类
				$row['children'] = $this->nested($rows, $row['category_id']);
				// 记录当前分类的子分类
				$nested[] = $row;
			}
		}

		// 返回当前分类下子分类
		return $nested;
	}
}