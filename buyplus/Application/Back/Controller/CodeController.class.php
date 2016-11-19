<?php

namespace Back\Controller;
use Think\Controller;


class CodeController extends Controller
{
	/**
	 * 生成代码
	 * @return [type] [description]
	 */
	public function generateAction()
	{


		if (IS_POST) {

			// 一: 获取数据

			// 1, 获取需要生成代码表名
			$table = I('post.table', '');
			// 对table的合理性验证
			
			// 2, 模块名
			$module_name = 'Back';// 模块名

			// 3, 模型名, 根据表名获取: 1:下划线分割, 2:首字母大写, 3:连接
			$model_name =  implode('', array_map('ucfirst', explode('_', $table)));

			// 4, 模型对应的表结构
			$model = M($model_name);// 所操作的模型
			$fields = $model->getFields();
			// 整理成需要的结构
			$field_list = [];
			$pk_field = $fields['_pk'];
			$type_list = $fields['_type'];
			unset($fields['_pk'], $fields['_type']);
			foreach($fields as $field) {
				$field_list[] = [
					'name'	=> $field,
					'type'	=> $type_list[$field],
					'pk'	=> ($pk_field==$field?true:false),
					];
			}

			// 4, 中文标题
			$title = I('post.title', '');




			
			// 二, 生成控制器文件
			// 1, 拿到控制器代码模板
			$controller_template_file = COMMON_PATH . 'Code/DemoController.class.php';
			$controller_template = file_get_contents($controller_template_file);// require
			// 2, 替换其中的占位符
			// str_replace(查找, 替换, 字符串)
			$search = ['%MODULE%', '%MODEL%', '%PK_FIELD%'];
			$replace = [$module_name, $model_name, $pk_field];
			$controller_content = str_replace($search, $replace, $controller_template);
			// 3, 写入到正确的控制器类文件位置
			$controller_name = $model_name;// 通常控制器名与模型名保持一致
			// 确定存储目录
			$controller_path = APP_PATH . $module_name . '/Controller/';
			// 如果目录不存在, 需要创建
			if( !is_dir($controller_path)) {
				mkdir($controller_path, 0755, true);
			}
			$controller_file = $controller_path . $controller_name . 'Controller.class.php';
			// 执行写入
			file_put_contents($controller_file, $controller_content);
			

			// 三, 生成列表视图模板文件
			// 1,处理局部模板(表头和表主体)
			// 表格头 字段模板
			$table_head_list = '';
			$table_head_template_file = COMMON_PATH . 'Code/DemoTableHead.html';
			$table_head_template = file_get_contents($table_head_template_file);
			// 表格主体 字段模板
			$table_body_list = '';
			$table_body_template_file = COMMON_PATH . 'Code/DemoTableBody.html';
			$table_body_template = file_get_contents($table_body_template_file);
			// 遍历每一个字段信息, $field_list;
			foreach($field_list as $field) {
				// 如果字段是主键不予展示:
				if ($field['pk']) continue;

				// table head
				$search = ['%FIELD%'];
				$replace = [$field['name']];
				$table_head_list  .= str_replace($search, $replace, $table_head_template);
				// table body
				$search = ['%FIELD%'];
				$replace = [$field['name']];
				$table_body_list  .= str_replace($search, $replace, $table_body_template);
			}
			// 2, 处理整体模板
			$list_template_file = COMMON_PATH . 'Code/DemoList.html';
			$list_template = file_get_contents($list_template_file);
			$search = ['%TABLE_HEAD_LIST%', '%TABLE_BODY_LIST%', '%PK_FIELD%', '%TITLE%'];
			$replace = [$table_head_list, $table_body_list, $pk_field, $title];
			$list_template_content = str_replace($search, $replace, $list_template);

			// 3, 写入到对应的模板文件中
			$list_path = APP_PATH . $module_name . '/View/' . $controller_name . '/';
			if (!is_dir($list_path)) {
				mkdir ($list_path, 0755, true);
			}
			$list_file = $list_path . 'list.html';
			file_put_contents($list_file, $list_template_content);


			

			// 四, 生成添加视图模板
			// 1, 先处理局部模板
			// 遍历每一个字段信息, $field_list;
			$add_field_template_file = COMMON_PATH . 'Code/DemoAddField.html';
			$add_field_template = file_get_contents($add_field_template_file);
			$input_field_list = '';
			foreach($field_list as $field) {
				// 如果字段是主键, 不需要:
				if ($field['pk']) continue;

				$search = ['%FIELD%'];
				$replace = [$field['name']];
				$input_field_list .= str_replace($search, $replace, $add_field_template);
			}

			// 2, 整体替换
			$add_template_file = COMMON_PATH . 'Code/DemoAdd.html';
			$add_template = file_get_contents($add_template_file);
			$search = ['%TITLE%', '%INPUT_FIELD_LIST%'];
			$replace = [$title, $input_field_list];
			$add_template_content = str_replace($search, $replace, $add_template);

			// 3, 写入到模板文件
			$add_path = APP_PATH . $module_name . '/View/' . $controller_name . '/';
			if (!is_dir($add_path)) {
				mkdir($add_path, 0755, true);
			}
			$add_file = $add_path . 'add.html';
			file_put_contents($add_file, $add_template_content);



			// 五, 生成编辑视图模板
			// 1, 先处理局部模板
			// 遍历每一个字段信息, $field_list;
			$edit_field_template_file = COMMON_PATH . 'Code/DemoEditField.html';
			$edit_field_template = file_get_contents($edit_field_template_file);
			$input_field_list = '';
			foreach($field_list as $field) {
				// 如果字段是主键, 不需要:
				if ($field['pk']) continue;

				$search = ['%FIELD%'];
				$replace = [$field['name']];
				$input_field_list .= str_replace($search, $replace, $edit_field_template);
			}

			// 2, 整体替换
			$edit_template_file = COMMON_PATH . 'Code/DemoEdit.html';
			$edit_template = file_get_contents($edit_template_file);
			$search = ['%TITLE%', '%INPUT_FIELD_LIST%', '%PK_FIELD%'];
			$replace = [$title, $input_field_list, $pk_field];
			$edit_template_content = str_replace($search, $replace, $edit_template);

			// 3, 写入到模板文件
			$edit_path = APP_PATH . $module_name . '/View/' . $controller_name . '/';
			if (!is_dir($edit_path)) {
				mkdir($edit_path, 0755, true);
			}
			$edit_file = $edit_path . 'edit.html';
			file_put_contents($edit_file, $edit_template_content);
			
			

			
			

		}
		else {
			$this->display();
		}
	}
}