<?php

namespace Back\Controller;
use Think\Controller;

/**
 * 后台管理控制器
 */
class ManageController extends Controller
{
	// 系统配置
	public function settingAction()
	{

		// 获取配置项分组
		$m_setting_group = M('SettingGroup');
		$group_list = $m_setting_group->select();
		$this->assign('group_list', $group_list);

		// 获取配置项, 并分组展示
		$m_setting = M('Setting');
		// 同时获取当前配置的类型
		$setting_list = $m_setting
						->alias('s')
						->field('s.*, st.type_title')
						->join('LEFT JOIN __SETTING_TYPE__ st USING(setting_type_id)')
						->select();
		// 整理配置项, 分组展示
		// [3=>[3组配置项列表], 5=>[5组配置项列表]]
		// 操作需要 settingOption模型
		$m_setting_option = M('SettingOption');
		foreach($setting_list as $setting) {
			// 获取选项类型配置项的 预设值
			if (in_array($setting['type_title'], ['select', 'checkbox'])) {
				// 需要获取预设值
				// 找到预设值列表, 存储到当前配置项的option元素
				$setting['option'] = $m_setting_option
							->where(['setting_id'=>$setting['setting_id']])
							->select();				
			}
			// 如果是复选框类型
			if ('checkbox' == $setting['type_title']) {
				// 增加元素, 存储值数组形式
				$setting['value_list'] = explode(',', $setting['value']);
			}

			// $setting['setting_group_id'], 当前配置项分组ID
			// 以组ID作为下标
			// 增加一个元素,存储当前的配置项
			$setting_group[$setting['setting_group_id']][] = $setting;
		}
		$this->assign('setting_group', $setting_group);

		$this->display();
	}

	/**
	 * 配置项更新
	 * @return [type] [description]
	 */
	public function settingUpdateAction()
	{
		// 获取所有的配置项
		$setting_list = $_POST['setting'];
		
		// 获得Setting模型
		$m_setting = M('Setting');

		// 遍历所有的配置项, 逐一去更新
		foreach($setting_list as $setting_id=>$value) {
			// 如果为checkbox类型, 值是数组类型
			if(is_array($value)) {
				$value = implode(',', $value);
			}

			// 更新对应的配置项即可
			$data = ['setting_id'=>$setting_id, 'value'=>$value];
			if ($m_setting->create($data)) {
				$m_setting->save();
			}
		}

		// 判断是否需要处理全部取消
		if (isset($_POST['remove'])) {
			// 存在需要取消的
			foreach($_POST['remove'] as $setting_id) {
				// 当前配置取消 更新value为空字符串
				$data = ['setting_id'=>$setting_id, 'value'=>''];
				if ($m_setting->create($data)) {
					$m_setting->save();
				}
			}

		}

		// 重定向配置项展示页
		$this->redirect('setting', [], 0);

	}
}