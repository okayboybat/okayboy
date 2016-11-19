<?php
/*
 *   管理  表
 */
namespace Admin\Controller;
use Think\Controller;

class AdminController extends Controller
{
    public function loginAction()
    {
        if(IS_POST){
            
            $m_admin = M('Admin');
            
            $email = $_POST['email'];
            $password = $_POST['password'];
            $cond['email'] = $email;
            $row = $m_admin
                ->where($cond)
                ->find();// 检索一条
            // 判断是否存在 
            if ($row) {
                // 用户存在, 判断密码
                if (md5($password) == $row['password']) {
                    // 校验通过
                    // 设置 登录标识 session
                    // thinkphp中session默认开启
                    session('admin', $row);// $_SESSION['user'] = $row;
                    // 跳转,用户中心
                    $this->redirect('/relation', [], 0);
                }
            }
            // 用户错误
            $this->error('账号信息错误', U('/adminlogin'), 2);
        }else{
            $this->display();
        }
    }
}