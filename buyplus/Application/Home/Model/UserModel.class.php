<?php
/**
 * Home模块的 user表的自定义模型
 */
namespace Home\Model;

use Think\Model;


class UserModel extends Model
{

	protected $_auto = [
		// 字段, 填充值
		['user_level_id', 1, '4'],
		['salt', 'makeSalt', '4', 'callback'], 
		// 字段, 填充规则参数, 填充时机, 填充规则
		// ['password', 'md5', '3', 'function']
		['password', 'makePassword', '4', 'callback'], 
	];
	// 批量, 多字段 验证!
	protected $patchValidate = true;

	protected $_validate = [
		// 邮箱的两条验证规则
		['email', 'require', '邮箱不能为空'],
		['email', 'email', '邮箱格式不正确'],
		['email', '', '邮箱不能重复', 0, 'unique'],
		// ['email', 'unique', '邮箱已经注册'],
		// 用户名
		['username', 'require', '用户名不能为空'],
		['username', '1,16', '用户名要求1到16个字符', 0, 'length'], 

		// 密码
		['password', 'require', '密码必须'],
		['password', '6,30', '密码的长度在6-30之间', 0, 'length'],
		['password', 'passwordStrongCheck', '密码要求包含大写,小写,数字中的两种', 0 ,'callback'],
		// 两次输入的密码一致
		// 当前字段, 比较字段, 错误提示, 条件, 规则
		['password', 'confirm', '两次输入密码一致', 0 ,'confirm', 4],// 最后一个元素4表示, 注册时修改密码时, 才需要验证

	
	];

	/**
	 * 生成密码的盐值
	 */
	protected function makeSalt()
	{
		// 先利用 时间戳 的 md5 值, 从中截取4位, 作为盐值
		return $this->temp_salt = substr(md5(time()), mt_rand(0, 28), 4);
	}

	protected $temp_salt;
	/**
	 * 生成密码
	 */
	protected function makePassword($password)
	{
		
		return md5($password . $this->temp_salt);
	}
	/**
	 * 自定义的 密码强度校验方法
	 * @return [type] [description]
	 */
	protected function passwordStrongCheck($password)
	{
		$level = 0;
		// 判断是否存在大写字母
		if (preg_match('/[A-Z]/', $password)) {
			++ $level;
		}
		// 判断是否存在小写字母
		if (preg_match('/[a-z]/', $password)) {
			++ $level;
		}
		// 判断是否存在数字
		if (preg_match('/[0-9]/', $password)) {
			++ $level;
		}

		return $level >= 2;// 返回bool值

	}

}