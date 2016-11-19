<?php
/*
 *  用户管理
 */
namespace Admin\Controller;
use Think\Controller;

class UserController extends Controller
{
    public function userlistAction()
    {
        $m_user = M('User');
        if($_GET['id']){
            $id = $_GET['id'];
            $m_user->delete($id);
            $this->redirect('/userlist');
        }else{      
            $result = $m_user->select();
            $this->assign('list',$result);
            $this->display();
        }
    }
}