<?php

namespace Back\Controller;
use Think\Controller;
use Vendor\Page;
use Think\Upload;
use Think\Image;

/**
 * 后台管理控制器类
 */
class GoodsController extends Controller
{

	/**
	 * Goods列表动作
	 * @return [type] [description]
	 */
	public function listAction()
	{
		// 获取模型
		$model = M('Goods');

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


	protected function linked()
	{
		// 长度单位
		$this->assign('length_unit_list', M('LengthUnit')->select());

		// 重量单位
		$this->assign('weight_unit_list', M('WeightUnit')->select());

		// 税类型
		$this->assign('tax_list', M('Tax')->select());

		// 库存状态
		$this->assign('stock_status_list', M('StockStatus')->select());

		// 品牌
		$this->assign('brand_list', M('Brand')->select());

		// 分类
		$this->assign('category_list', D('Category')->getTreeList());

		// 属性分组(商品类型)
		$this->assign('attribute_group_list', M('AttributeGroup')->order('sort_number')->select());

	}

	/**
	 * 添加
	 */
	public function addAction()
	{
		// 如果是提交数据
		if (IS_POST) {
			// 设置过滤器, 防止HTML标记被强制转换
			C('DEFAULT_FILTER', '');

			// 实例化工具类对象, 工具类对象, 没有做单例, 避免重复实例化, 采用在统一的位置实例化
			$t_upload = new Upload;// use Think\Upload
			$t_image = new Image;// use Think\Upload

			// 实例化模型对象
			$model = M('Goods');

			// 自己添加验证规则
			$validate = [];

			// 处理数据
			$data = $_POST;
			// 默认数据
			$data['create_at'] = time();
			$data['update_at'] = time();
			// 布尔型数据的处理
			$data['is_subtract'] = isset($_POST['is_subtract']) ? 1 : 0;
			$data['is_shipping'] = isset($_POST['is_shipping']) ? 1 : 0;
			$data['is_status'] = isset($_POST['is_status']) ? 1 : 0;
			$data['is_new'] = isset($_POST['is_new']) ? 1 : 0;
			$data['is_promote'] = isset($_POST['is_promote']) ? 1 : 0;
			
			// 上传分类图像
			// 配置
			$t_upload->savePath = 'Goods/';// 业务逻辑子目录
			$t_upload->exts = ['jpg', 'gif', 'jpeg', 'png'];// 允许的类型
			// 执行上传
			$result = $t_upload->uploadOne($_FILES['image']);// 上传一个文件
			// 存储上传的文件名到数据库
			if ($result) {
				// 上传成功
				$data['image'] = $result['savepath'] . $result['savename'];
				// 为上传图片生成缩略图, 使用Image类
				$t_image->open('./Uploads/' . $data['image']);// 打开需要操作的图像文件
				// 需要创建存储缩略图的目录
				$thumb_path = './Public/Thumb/' . $result['savepath'];
				if (!is_dir($thumb_path)) {
					mkdir ($thumb_path, 0755, true);
				}
				$t_image
					->thumb(300, 340)// 生成缩略图
					->save('./Public/Thumb/' . $result['savepath'] . $result['savename']);// 存储起来
				// 存储到数据库
				$data['image_thumb'] = $result['savepath'] . $result['savename'];
			}

			if ($model->validate($validate)->create($data)) {
				// 验证通过, 商品数据入库
				$pk = $model->add();

				// 一: 添加商品相册图像
				$m_goods_gallery = M('GoodsGallery');

				$t_upload->savePath = 'Gallery/';// 业务逻辑子目录
				$gallery_list = $t_upload->upload([$_FILES['gallery_image']]);
				// 遍历上传结果
				foreach($gallery_list as $key=>$gallery) {
					// 如果成功, 则将当前相册图像信息存储与数据库中
					if ($gallery) {// $gallery, 单个上传相册图像文件的信息
						$row = ['image' => $gallery['savepath'] . $gallery['savename']];
						// 获取对应的排序信息
						$row['sort_number'] = $_POST['gallery_sort'][$key];
						// 制作不同尺寸的缩略图
						$t_image->open('./Uploads/' . $row['image']);// 打开要操作图像
						// 需要创建存储缩略图的目录
						$thumb_path = './Public/Thumb/' . $gallery['savepath'];
						if (!is_dir($thumb_path)) {
							mkdir ($thumb_path, 0755, true);
						}
						$t_image->thumb(600, 600)// 生成缩略图
								->save('./Public/Thumb/' . $gallery['savepath'] . 'big_' . $gallery['savename']);// 存储起来
						$t_image->thumb(300, 300)// 生成缩略图
								->save('./Public/Thumb/' . $gallery['savepath'] . 'small_' . $gallery['savename']);// 存储起来
						$row['image_big'] = $gallery['savepath'] . 'big_' . $gallery['savename'];
						$row['image_small'] = $gallery['savepath'] . 'small_' . $gallery['savename'];

						// 关联的商品ID
						$row['goods_id'] = $pk;

						// 将商品相册图像信息, 存储与数据库
						if ($m_goods_gallery->create($row)) {
							$m_goods_gallery->add();
						}

					}
				}

				// 二: 建立商品与属性(选项)的关联
				// 商品ID($pk), 属性ID的值($_POST['attribute'][key]=value, key就是属性ID, value就是属性值), 如果属性为选项类属性(select select_muiltiple) 属性的值, 就不是一个字符串, 而是属性预设值列表中的某个ID.
				// 由于属性值不同, 更新的字段不同, 字符串类属性值, 更新的是,value字段
				$attribute_list = I('post.attribute');// [attributr_id]=value
				$option_list = I('post.option', []);// 作为选项的属性ID列表
				$m_attribute = M('Attribute');
				$m_goods_attribute = M('GoodsAttribute');
				$m_goods_attribute_value = M('GoodsAttributeValue');
				foreach($attribute_list as $attribute_id=>$value) {
					// 建立商品与属性的关系, goods_attribute
					$row_ga = [
						'goods_id'=>$pk, 
						'attribute_id'=>$attribute_id, 
						'is_option'=> (in_array($attribute_id, $option_list) ? 1 : 0),
						];
					$pk_ga = $m_goods_attribute->add($row_ga);

					// 属性值, goods_attribute_value
					// 判断当前属性的类型, 去选择使用哪个字段, 存储属性值.
					// (text:value, select,select_multiple:attribute_option_id) 
					// 获取当前属性的类型
					$attr = $m_attribute
						->field('at.title type_title')
						->alias('a')
						->join('LEFT JOIN __ATTRIBUTE_TYPE__ at USING(attribute_type_id)')
						->where(['attribute_id'=>$attribute_id])
						->find();
					if ($attr['type_title'] == 'text') {
						$row_gav = ['goods_attribute_id'=>$pk_ga];
						$row_gav['value'] = $value;
						$m_goods_attribute_value->add($row_gav);
					} elseif ($attr['type_title'] == 'select') {
						$row_gav = ['goods_attribute_id'=>$pk_ga];
						$row_gav['attribute_option_id'] = $value;
						$m_goods_attribute_value->add($row_gav);
					} elseif ($attr['type_title'] == 'select_multiple') {
						// $value 是数组
						$row_gav_list = array_map(function($v) use($pk_ga) {
							return ['goods_attribute_id'=>$pk_ga, 'attribute_option_id'=> $v];
						}, $value);
						$m_goods_attribute_value->addAll($row_gav_list);
					}
					
				}

				// 成功, 跳转到列表页
				$this->redirect('list', [], 0);
			} else {
				$this->error('添加失败:'.$model->getError(), U('add'));
			}
		} 
		// 如果是展示表单
		else {
			// 初始化关联数据
			$this->linked();

			$this->display();
		}
	}

	public function optionAction()
	{
		if (IS_POST) {
			// 更新的表
			$m_goods_attribute_value = M('GoodsAttributeValue');
			// 遍历$_POST['option']
			foreach($_POST['option'] AS $pk_gav => $row) {
				$row['goods_attribute_value_id'] = $pk_gav;
				// $row中有主键, 自动就会更新
				// var_dump($row);
				$m_goods_attribute_value->save($row);
			}
			// die;

			$this->redirect('option', ['goods_id'=>I('post.goods_id')], 0);

		} else {
			$goods_id = I('get.goods_id');

			$this->assign('goods_id', $goods_id);
			// 通过goods_id, 获取选项列表
			$m_attribute = M('Attribute');
			$cond = [
				'ga.goods_id'	=>	$goods_id,
				'is_option'		=> 1,// 是选项
				];
			$option_list = $m_attribute
							->field('ga.goods_attribute_id, a.title, a.attribute_id')
							->alias('a')
							->join('left join __GOODS_ATTRIBUTE__ ga USING(attribute_id)')
							->where($cond)
							->select();
			$this->assign('option_list', $option_list);

			// 获取选项下的可选值列表
			$m_goods_attribute_value = M('GoodsAttributeValue');
			$option_value = [];// 按照选项, 存储选项值
			foreach($option_list AS $option) {
				$cond = ['gav.goods_attribute_id'=>$option['goods_attribute_id']];
				$value_list = $m_goods_attribute_value
								->field('gav.goods_attribute_value_id, gav.price_drift, gav.price_operate, ao.value')
								->alias('gav')
								// 先确定哪些选项需要获取对应的值
								// 再确定当前的选项的显示值为什么
								->join('join __GOODS_ATTRIBUTE__ ga USING(goods_attribute_id) LEFT join __ATTRIBUTE_OPTION__ ao USING(attribute_option_id)')
								->where($cond)
								->select();
				// 下标为选项的标识ID
				$option_value[$option['goods_attribute_id']] = $value_list;
			}
			$this->assign('option_value', $option_value);

			$this->display();
			
		}
	}


	public function getAttributeAction()
	{
		$attribute_group_id = $_GET['group_id'];
		// 利用属性分组ID, 获取属性列表
		$cond['attribute_group_id'] = $attribute_group_id;
		// 属性输入类型
		$rows = M('Attribute')
				->field('a.*, at.title type_title')
				->alias('a')
				->join('LEFT JOIN __ATTRIBUTE_TYPE__ at USING(attribute_type_id)')
				->where($cond)
				->select();
		// 如果是 选项类型, 则需要获取预设值
		foreach($rows as &$value) {
			if (in_array($value['type_title'], ['select', 'select_multiple'])) {
				// 是
				$value['option'] = M('AttributeOption')->where(['attribute_id'=>$value['attribute_id']])->select();
			}
		}
		$this->ajaxReturn(['error'=>0, 'rows'=>$rows]);
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
		$model = M('Goods');
		// 判断当前操作类型
		$operate = $_POST['operate'];
		switch ($operate) {
			case 'delete':
				$cond['goods_id'] = ['in', $selected];
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

		$model = M('Goods');

		if (IS_POST) {

			$validate = [];
			if ($model->validate($validate)->create()) {
				// 验证通过
				$model->save();
				// 成功, 跳转到列表页
				$this->redirect('list', [], 0);
			} else {
				$this->error('更新失败:'.$model->getError(), U('edit', ['goods_id'=>I('post.goods_id')]));
			}
	
		} else {
			// 获取当前数据
			$this->assign('row', $model->find($_GET['goods_id']));

			// 初始化关联数据
			$this->linked();

			$this->display();
		}

	}
}
