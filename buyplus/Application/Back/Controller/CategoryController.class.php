<?php

namespace Back\Controller;
use Think\Controller;
use Vendor\Page;
use Think\Upload;
use Think\Image;


/**
 * 后台管理控制器类
 */
class CategoryController extends Controller
{

	/**
	 * Category列表动作
	 * @return [type] [description]
	 */
	public function listAction()
	{
		// 获取模型
		$model = M('Category');

		// 条件, 搜索的处理
		$cond = [];// 默认没有条件

		// 排序
		$sort_field = I('request.sortField', 'sort_number', '');
		$sort_type = I('request.sortType', 'asc', '');
		$sort = "$sort_field $sort_type";

		// 查询
		$list = $model
			->where($cond)
			->order($sort)
			->select();
		// 所有分类查询得到
		// 使用递归程序, 将数据进行缩进处理(排序, 记录deep)		
		$tree = $this->tree($list);

		$this->assign('list', $tree);
		// 展示
		$this->display(); 

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

	/**
	 * 添加
	 */
	public function addAction()
	{
		$model = M('Category');

		// 如果是提交数据
		if (IS_POST) {
			// 自己添加验证规则
			$validate = [];
			$data = $_POST;
			// 处理数据
			// 布尔型数据的处理
			$data['is_used'] = isset($_POST['is_used']) ? 1 : 0;
			$data['is_nav'] = isset($_POST['is_nav']) ? 1 : 0;
			// 上传分类图像
			$t_upload = new Upload;// use Think\Upload
			// 配置
			$t_upload->savePath = 'Category/';// 业务逻辑子目录
			$t_upload->exts = ['jpg', 'gif', 'jpeg', 'png'];// 允许的类型
			// 执行上传
			$result = $t_upload->uploadOne($_FILES['image']);// 上传一个文件
			// 存储上传的文件名到数据库
			if ($result) {
				// 上传成功
				$data['image'] = $result['savepath'] . $result['savename'];
				// 为上传图片生成缩略图, 使用Image类
				$t_image = new Image;
				$t_image->open('./Uploads/' . $data['image']);// 打开需要操作的图像文件
				// 需要创建存储缩略图的目录
				$thumb_path = './Public/Thumb/' . $result['savepath'];
				if (!is_dir($thumb_path)) {
					mkdir ($thumb_path, 0755, true);
				}
				$t_image
					->thumb(100, 100)// 生成缩略图
					->save('./Public/Thumb/' . $result['savepath'] . $result['savename']);// 存储起来
				// 存储到数据库
				$data['image_thumb'] = $result['savepath'] . $result['savename'];
			}

			if ($model->validate($validate)->create($data)) {
				// 验证通过
				$pk = $model->add();

				// 删除缓存
				// 配置缓存
				S(['type'=>'memcache',
				   'host'=> UC('memcached_host'), 
				   'port'=>UC('memcached_port')]
				);
				S('category_nested', null);

				// 成功, 跳转到列表页
				$this->redirect('list', [], 0);
			} else {
				$this->error('添加失败:'.$model->getError(), U('add'));
			}
		} 
		// 如果是展示表单
		else {
			// 获取当前所有的分类
			$this->assign('category_list', []);
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
		$model = M('Category');
		// 判断当前操作类型
		$operate = $_POST['operate'];
		switch ($operate) {
			case 'delete':
				$cond['category_id'] = ['in', $selected];
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

		$model = M('Category');

		if (IS_POST) {

			$validate = [];
			if ($model->validate($validate)->create()) {
				// 验证通过
				$model->save();
				// 成功, 跳转到列表页
				$this->redirect('list', [], 0);
			} else {
				$this->error('更新失败:'.$model->getError(), U('edit', ['category_id'=>I('post.category_id')]));
			}
	
		} else {
			// 获取当前数据
			$this->assign('row', $model->find($_GET['category_id']));

			$this->display();
		}

	}
}
