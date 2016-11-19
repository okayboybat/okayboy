<?php
/*
 *   联系表
 */
namespace Home\Controller;
use Think\Controller;

class RelationController extends Controller
{
    public function aboutAction()
    {
        if(IS_POST){
            $m_relation = M('Relation');
            if($m_relation->create()){
                $m_relation->add();
                $this->redirect('/about');
            }else{
                $this->error('发送失败',U('/about'),3);
            }
        }else{
            $this->display();
        } 
    }
}