<?php
/*
 *   心情 表
 */
namespace Home\Controller;
use Think\Controller;

class MoodController extends Controller
{
    public function moodAction()
    {
        if(IS_POST){
            $m_mood = M('Mood');           
            if(empty(trim($_POST['content']))){
                $this->error('内容不能为空', U('/mood'), 2);
                die;
            }
            
            $data['content'] = $_POST['content'];
            $data['user_id'] = session('user')['user_id']; 
            $data['add_time'] = time();
            $mood_id = $m_mood->add($data);
            
            if($mood_id){
                $this->redirect('/mood');
            }else{
                $this->error('发表失败', U('/mood'), 2);
            }
        }else{
            $m_mood = M('Mood');
            $map['user_id'] = session('user')['user_id'];
            $result = $m_mood->order('add_time desc')->where($map)->select();
            $this->assign('moodlist',$result);
            $this->display();
        }
    }
}