<?php


namespace Home\Controller;


use Home\Controller\CommonController;


class ShopController extends CommonController
{
	
	public function indexAction()
	{

		// 读取数据
		$m_goods = M('Goods');
		// 获取推荐商品
		$promote_cond = ['is_status'=>'1', 'is_promote'=>1];
		$promote_limit = UC('promote_limit');
		$promote_order = 'sort_number';
		$promote_list = $m_goods->where($promote_cond)
								->order($promote_order)
								->limit($promote_limit)
								->select();
		$this->assign('promote_list', $promote_list);

		// 获取新品
		$new_cond = ['is_status'=>'1', 'is_new'=>1];
		$new_limit = UC('new_limit');
		$new_order = 'create_at desc, sort_number';
		$new_list = $m_goods->where($new_cond)
								->order($new_order)
								->limit($new_limit)
								->select();
		$this->assign('new_list', $new_list);

		// 展示模板
		$this->display();
	}



	/**
	 * @router /goods/:goods_id
	 * 商品信息
	 */
	public function goodsAction($goods_id)
	{
		// 商品的基本数据
		$m_goods = M('Goods');
		$cond['is_status'] = '1';
		$goods = $m_goods->where($cond)->find($goods_id);
		$this->assign('goods', $goods);

		// 相册图像数据
		$m_goods_gallery = M('GoodsGallery');
		$cond = ['goods_id'=>$goods_id];
		$gallery_list = $m_goods_gallery->where($cond)->select();
		$this->assign('gallery_list', $gallery_list);

		// 商品选项数据
		$m_goods_attribute = M('GoodsAttribute');
		$cond = ['goods_id'=>$goods_id, 'is_option'=>1];
		$option_list = $m_goods_attribute
						->field('ga.goods_attribute_id, a.title')
						->alias('ga')
						->join('left join __ATTRIBUTE__ a Using(attribute_id)')
						->where($cond)
						->select();
		// 某个选项中的选项值有哪些
		$m_goods_attribute_value = M('GoodsAttributeValue');
		foreach($option_list as & $option) {
			$cond = ['gav.goods_attribute_id'=>$option['goods_attribute_id']];
			$option['value_list'] = $m_goods_attribute_value
									->field('gav.goods_attribute_value_id, ao.value')
									->alias('gav')
									->join('left join __ATTRIBUTE_OPTION__ ao Using(attribute_option_id)')
									->where($cond)
									->select();
		}
		$this->assign('option_list', $option_list);

		// 关联模型演示, 参见GoodsAttributeModel
		// $r_goods_attribute = D('GoodsAttribute');
		// $cond = ['goods_id'=>$goods_id, 'is_option'=>1];
		// $list = $r_goods_attribute->relation(true)
		// 				  ->where($cond)
		// 				  ->select();
		// var_dump($list);


		// 其他数据, 评论, 属性, 选项, 等等, 都使用ajax 延迟加载. 需要时再加载


		$this->display();
	}

	/**
	 * 用于获取ajax数据的方法
	 * @return [type] [description]
	 */
	public function goodsAjaxAction()
	{
		$goods_id = $_GET['goods_id'];
		switch ($_GET['type']) {
			case 'breadcrumb':
				$m_goods = D('Goods');
				// 所有的上级(祖先)分类
				$parents = $m_goods->getParents($goods_id);
				$this->ajaxReturn(['error'=>0, 'rows'=>$parents]);

			break;

			case 'attribute':

				$m_goods_attribute = M('GoodsAttribute');
				$rows = $m_goods_attribute->alias('ga')
										  ->field('a.title, gav.value, group_concat(ao.value) value_list')
										  ->join('left join __ATTRIBUTE__ a USING(attribute_id)')
										  ->join('left join __GOODS_ATTRIBUTE_VALUE__ gav USING(goods_attribute_id)')
										  // 考虑到选项的属性
										  ->join('left join __ATTRIBUTE_OPTION__ ao USING(attribute_option_id)')
										  ->where(['goods_id'=>$goods_id])
										  ->group('a.attribute_id')
										  ->select();
				$this->ajaxReturn(['error'=>0, 'rows'=>$rows]);

			break;
		}

	}
}