<?php
/*
 *  联系 表
 */
namespace Admin\Controller;
use Think\Controller;

class RelationController extends Controller
{
    public function relationAction()
    {
        if($_GET['id']){
           $id = $_GET['id'];
           $m_relation = M('Relation');
           $m_relation->delete($id);
           $this->redirect('/relation');
        }else{
            $m_relation = M('Relation');
            $result = $m_relation->select();
            $this->assign('relation',$result);
            $this->display();
        }
    }
}