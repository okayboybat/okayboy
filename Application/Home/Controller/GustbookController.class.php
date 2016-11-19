<?php
/*
 *   留言板
 */
namespace Home\Controller;
use Think\Controller;
class GustbookController extends Controller
{
    public function gustbookAction()
    {
        if($_GET){
            $id = $_GET['id'];
            $m_gust = M('Gustbook');
            $m_gust->delete($id);
            $this->redirect('/gustbook',[],0);
        }else{
            $m_gust = M('Gustbook');
            $map['personal_id'] = session('user')['user_id'];
            $result = $m_gust
            ->alias('g')
            ->field('add_time,content,g.user_id id,gustbook_id')
            ->join("left join __PERSONAL_GUSTBOOK__ pg using(gustbook_id) left join personal p using(personal_id)")
            //             ->order('add_time desc')
            ->where($map)
            ->select();
            
            $m_personal = M('Personal');
            $arr = array();
            foreach($result as $rows){
                $id = $rows['id'];
                $res = $m_personal->field('nickname')->find($id);
                $rows['nickname'] = $res['nickname'];
                $arr[] = $rows;
            }
            $this->assign('gustbook',$arr);
            $this->display();
        }      
    }
}