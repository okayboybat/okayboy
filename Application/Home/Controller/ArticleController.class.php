<?php
/*
 *   文章 类
 */
namespace Home\Controller;
use Think\Controller;

class ArticleController extends Controller
{
    public function listAction()
    {
        $m_art = M('Article');
        $res = $m_art
            ->alias('art')
            ->field('art.article_id,art.content artcontent,art.user_id,art.title,art.add_time,p.nickname,c.cate_title,count(a.article_id) browse_number')
            ->join('left join __PERSONAL__ p using(user_id) left join __CATEGORY__ c using(cate_id) left join __ANSWER__ a using(article_id)')
            ->group('art.article_id')
            ->order('art.add_time desc')
//             ->limit(20)
            ->select();
        $this->assign('art',$res);
        $this->display();
    }
    public function addAction()
    {
        $m_cate = M('Category');
        $row = $m_cate->select();
        $this->assign('list',$row);
        
        if(IS_POST){
                $m_art = D('Article');
                if(empty(trim($_POST['content'])) || empty(trim($_POST['title']))){
                    $this->error('标题和内容不能为空', U('/articleAdd'), 2);
                    die;
                }
                $current = $_POST['content'];
                $public_path = PUBLIC_PATH.'/Static/Home/Article'; 
                $prefix = 'kk';
                $tmpfname = tempnam($public_path, $prefix);
                $filename = basename($tmpfname);
                file_put_contents($tmpfname, $current);        
                
                $data['title'] = $_POST['title'];
                $data['content'] = $filename;
                $data['summary'] = $_POST['summary'];
                $data['add_time'] = time();
                $data['user_id'] = session('user')['user_id'];
                $data['cate_id'] = $_POST['cate_id'];
                    
                $art_id = $m_art->add($data);
                
                if($art_id){
                    $this->redirect('/index');
                 }else{   
                    $this->error('文章发表失败' , U('/articleAdd'), 3);// 跳转
                }
        }else{
            $this->display();
        } 
    }
    public function artcontentAction()
    {
        if(IS_POST){
            $id= $_GET['id'];
            $m_answer = M('Answer');
            $data['answer_content'] = $_POST['answer_content'];
            $data['user_id'] = session('user')['user_id'];
            $data['answer_time'] = time();
            $data['article_id'] = $id;
            $m_answer->add($data);
            $this->redirect('/artcontent',['id'=>$id],0);
        }else{  
            $id= $_GET['id'];
            $m_art = M('Article');
            $result = $m_art
            ->alias('a')
            ->join("left join __PERSONAL__ p on a.user_id=p.user_id")
            ->where('article_id='.$id)
            ->find();
            
            $filename = $result['content'];
            $filepath = PUBLIC_PATH.'Static/Home/Article/';
            $content = file_get_contents($filepath.$filename);
            $this->assign('content',$content);
            $this->assign('article',$result);
            
            //所有 评论
            $m_answer = M('Answer');
            $id = $result['article_id'];
            $res = $m_answer
            ->alias('a')
            ->join("left join __PERSONAL__ p on a.user_id=p.user_id")
            ->where('article_id='.$id)
            ->select();
            $this->assign('answerlist',$res);
            
            $this->display();
        }
    }
}