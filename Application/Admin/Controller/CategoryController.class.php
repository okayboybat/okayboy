<?php
/*
 *  分类 管理表
 */
namespace Admin\Controller;
use Think\Controller;

class CategoryController extends Controller
{
    public function catelistAction()
    {   
        $m_cate = M('Category');
        $row = $m_cate->select();

        $this->assign('list',$row);
        if(IS_POST){
            if($m_cate->create()){
                $cate_id = $m_cate->add();
                if($cate_id){
                  $this->redirect('/catelist');
                }
            }else{
                $this->error('添加失败',U('/catelist'),5);
            }
        }else{
            $this->display();
        }
    }
    public function delcateAction()
    {    
        $m_cate = M('Category');
        $row = $m_cate->select();
        $this->assign('list',$row);
        $this->display();
    }
    public function deletecateAction()
    {
        $m_cate = M('Category');
        $id = $_GET['id'];
        $res = $m_cate->delete($id);
        $this->redirect('/delcate');
    }
}