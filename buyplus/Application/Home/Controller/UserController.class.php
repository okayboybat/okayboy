<?php

// 声明当前文件的命名空间
namespace Home\Controller;

// 导入基础控制器类
use Home\Controller\CommonController;
use Think\Verify;
/**
 * 前台模块的 用户控制器类文件
 */
class UserController extends CommonController
{
	/**
	 * 注册动作
	 */
	public function registerAction()// registerAction
	{
		// 只有在执行register 时, 才开启 令牌验证
		C('TOKEN_ON', true);

		if (IS_POST) {
			// 是post表单提交
			// 获取user表的操作模型
			$m_user = D('User');// 使用自定义模型,来使用验证规则
			
			// create利用post数据, 设置插入到数据表的数据
			$validate_rule = [
				['username', 'require', '用户名不能为空'],
				['password', 'require', '密码必须'],
			];
			// 一旦调用了validate方法, 则模型的_validate属性被I忽略!
			// if ($m_user->validate($validate_rule)->create()) {
			// if ($m_user->create()) {
			// create的第一个参数, 就是关联的数据, 默认就是POST,
			// 第二个参数, 表示当前的特殊场景!
			if ($m_user->create($_POST, 4)) {
				// var_dump($m_user);
				// 创建数据成功
				// add方法将数据插入到数据表中
				// 返回最新的自动生成ID
				$user_id = $m_user->add();
				// var_dump($m_user);
				// var_dump($user_id);
				trace($user_id, '新用户ID');
				// 立即跳转方法, 重定向. 
				// 目标地址, 携带的额外参数, 停留时间, 0 立即跳转
				$this->redirect('/login', [], 0);// 重定向
			} else {
				// 数据创建失败
				$this->error('用户注册失败:<br> ' . implode('<br>', $m_user->getError()), U('/register'), 10);// 跳转
			}

		} else {
			// 不是
						 
			// 展示视图层模板
			$this->display();
		}

	}

	/**
	 * 登录
	 */
	public function loginAction()
	{

		if (IS_POST) {
			// 校验验证码
			$verify = new Verify;
			if (!$verify->check($_POST['captcha'])) {
				// 验证失败
				$this->error('验证码失败', U('/login'), 1);
			}

			// 校验用户合法性
			$account = $_POST['account'];
			$password = $_POST['password'];

			// 通过账号获取用户信息
			$m_user = M('User');
			// email = '账号' OR telephone = '账号'
			$cond['email'] = $account;
			$cond['telephone'] = $account;
			$cond['_logic'] = 'OR';
			$row = $m_user
				->where($cond)
				->find();// 检索一条
			// 判断是否存在
			if ($row) {
				// 用户存在, 判断密码
				if (md5($password . $row['salt']) == $row['password']) {
					// 校验通过
					// 设置 登录标识 session
					// thinkphp中session默认开启
					session('user', $row);// $_SESSION['user'] = $row;

					// 跳转,用户中心
					// 判断是否存在登录后的跳转URL
					if ($login_jump_url = session('login_jump_url')) {
						session('login_jump_url', null);// 用后即焚
						$this->redirect($login_jump_url[0], $login_jump_url[1], 0);
					} else {
						// 没有指定地址, 默认为用户中心
						$this->redirect('/center', [], 0);
					}

				}
			}
				// 用户错误
			$this->error('账号信息错误', U('/login'), 2);
			

		} else {
			$this->display();
		}
	}

	public function captchaAction()
	{
		// 实例化对象
		$verify = new Verify;
		// 配置属性
		$verify->imageW = 367;// 宽度
		// $verify->imageH = 40;
		$verify->useZh = false;// 中文
		$verify->length = 4;// 1个字符
		$verify->useCurve = false; //是否化曲线
		// 生成图像
		$verify->entry();
	}


	public function centerAction()
	{
		echo '欢迎: ', session('user.username'), '<br>';
	}

}