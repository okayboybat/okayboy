<?php
/*
 *  文章 管理表
 */
namespace Admin\Controller;
use Think\Controller;

class ArticleController extends Controller
{
    public function articleAction()
    {
        if($_GET['id']){
            $id = $_GET['id'];
            $m_art = M('Article');
            $res = $m_art->delete($id);
            if($res){
                $this->redirect('/article');
            }else{
                $this->error('删除失败',U('/article'),3);
            }
            
        }else{
            $m_art = M('Article');
            $result = $m_art->field('article_id,title,add_time')->select();
            $this->assign('list',$result);          
            $this->display();
        }
    }
}