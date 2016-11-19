<?php
/*
 *   首页
 */
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller 
{
    public function indexAction(){
        $m_art = M('Article');  
        
        $res = $m_art
            ->alias('art')
            ->field('art.article_id,art.content artcontent,art.user_id,art.title,art.add_time,p.nickname,c.cate_title,count(a.article_id) browse_number')
            ->join('left join __PERSONAL__ p using(user_id) left join __CATEGORY__ c using(cate_id) left join __ANSWER__ a using(article_id)')
            ->group('art.article_id')
            ->order('browse_number desc')
            ->limit(20)
            ->select();
        
        $this->assign('art',$res);
        $this->display();
    }
}