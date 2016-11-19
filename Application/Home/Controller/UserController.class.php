<?php
/*
 * 用户 控制器
 */
namespace Home\Controller;
use Think\Controller;
use Think\Verify;

class UserController extends Controller
{
    /*
     *  登录 
     */
    function loginAction()
    {
        if(IS_POST){
            // 校验验证码
            $verify = new Verify;
            if (!$verify->check($_POST['captcha'])) {
                // 验证失败
                $this->error('验证码失败', U('/login'), 1);
            }
            $m_user = M('User');
            $email = $_POST['email'];
            $password = $_POST['password'];
            $cond['email'] = $email;
            $row = $m_user
            ->where($cond)
            ->find();// 检索一条
            // 判断是否存在
            if ($row) {
                // 用户存在, 判断密码
                if (md5($password . $row['salt']) == $row['password']) {
                    // 校验通过
                    $m_personal = M('Personal');
                    $map['user_id'] = $row['user_id'];
                    $result = $m_personal
                                ->where($map)
                                ->find();
                    $row['nickname'] = $result['nickname'];
                    
                    // 设置 登录标识 session
                    // thinkphp中session默认开启
                    session('user', $row);// $_SESSION['user'] = $row;
                    // 跳转,用户中心
                    $this->redirect('/index');
                }
            }
            // 用户错误
            $this->error('账号信息错误', U('/login'), 2);
        }else{
            $this->display();
        }   
    }
    /*
     *  注册
     */
    function registerAction()
    {
        
        if(IS_POST){
            //校验验证码
            $verify = new Verify;
            if(!$verify->check($_POST['captcha'])){
                // 验证失败
                $this->error('验证码错误',U('/register'),3);
            }
//             $m_user = M('User');    //使用内置对象
            $m_user = D('User');        //使用 自定义对象
//             var_dump($m_user); die;
            if($m_user->create($_POST,4)){
                // 创建接收 数据 成功 
                //使用add 函数 添加数据  ，返回 该数据 的id
                $user_id = $m_user->add();  
                
                $m_personal = M('Personal');
                $data['user_id'] = $user_id;
                $m_personal->add($data);
                
                if($user_id){
                    //注册成功
                    $this->redirect('/login');
                }
            }else{
                    //跳转
                    $this->error('用户注册失败：<br>'.implode('<br>',$m_user->getError()),U('/register'),5);
                 }
        }else{
            $this->display();
        }
    }
    /*
     *   验证码
     */
    function captchaAction()
    {
        //实例 化 对象
        $verify = new Verify();
        // 配置属性
//         $verify->imageW = 367;// 宽度
        // $verify->imageH = 40;
//         $verify->useZh = true;// 中文
        $verify->length = 4;// 1个字符
        // 生成图像
        $verify->entry();
    }
}