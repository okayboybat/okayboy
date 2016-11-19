<?php
/*
 *   个人信息表
 */
namespace Home\Controller;
use Think\Controller;

class PersonalController extends Controller
{
    public function personalAction()
    {
        $m_personal = M('Personal');
        $user_id = session('user')['user_id'];
        $map['user_id'] = $user_id;
        $result = $m_personal->where($map)->find();

        $this->assign('personallist',$result);
        
        $this->display();
    }
    public function percomileAction()
    {
        if(IS_POST){
            $m_per = M('Personal');
            if($m_per->create()){
                $personal_id = $_POST['personal_id'];
                
                $res = $m_per->where(['personal_id'=>"$personal_id"])->save();

                $this->redirect('./personal');
            }else{
                //跳转
                $this->error('保存失败',U('/percomile'),5);
            }
        }else{
            $m_per = M('Personal');
            $map['user_id'] = session('user')['user_id'];
            $result = $m_per
                    ->where($map)
                    ->find();
            
            $this->assign('perlist',$result);
            $this->display();
        }
    }
    public function helistAction()
    {
        if($_POST){
            $id = $_GET['id'];
            if(empty(trim($_POST['content']))){
                //跳转
                $this->error('留言不能为空',U('/helist?id='.$id),3);
            }
            $m_gustbook = M('Gustbook');
            $data['user_id'] = session('user.user_id');
            $data['content'] = $_POST['content'];
            $data['add_time'] = time();
            $gustbook_id = $m_gustbook->add($data);
            
            $m_personal_gustbook = M('Personal_gustbook');
            $data['personal_id'] = $_GET['id'];
            $data['gustbook_id'] = $gustbook_id;
            $personal_gustbook_id = $m_personal_gustbook->add($data);
            if(!$personal_gustbook_id){
                $this->error('添加到联合表失败',U('/helist?id='.$id),3);
            }
            $this->error('留言成功',U('/helist?id='.$id),3);
        }else{
            $map['user_id'] = $_GET['id'];
            if($map['user_id'] == session('user.user_id')){
                $this->redirect('./personal');
            }
            $m_personal = M('Personal');
            $result = $m_personal->where($map)->find();
            $this->assign('personallist',$result);
//             var_dump($result);
            $this->display();
        }
    }
    public function deluserAction()
    {
        session('user',null);
        $this->redirect('/index');
    }
}