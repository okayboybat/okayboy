<?php
/*
 *  用户模型
 */
namespace Home\Model;
use Think\Model;

class UserModel extends Model
{
    //开启 批量 多字段 验证
    protected $patchValidate = true;
    protected $_validate = [
        // 邮箱 验证 规则
        // 数据     规则      错误提示
        ['email','require','邮箱不能为空'],
        ['email','email','请输入正确的邮箱'],
        ['email','','邮箱已存在',0,'unique'],
        //密码 验证
        ['password','require','密码不能为空'],
        ['password','6,30','密码长度为6到30',0,'length'],
        ['password','pwdCheck','密码最少包含，大写，小写，数字中的两种',0,'callback'],
        // 验证两次密码 是否输入 一致
        // 注册 密码 和修改 密码时 才用的上 该验证 ，所以指定验证 条件 为 自定义 的 4
        ['password','confirm','密码两次输入不一致',0,'confirm',4],        
    ];
    //盐值
    protected $temp_salt;
    //使用 自动 添加 数据  自动 完成
    protected $_auto = [
        // 字段   规则    条件
        ['salt','makeSalt','4','callback'],
        ['password','makePassword','4','callback'],
        ['add_time','addtime','4','callback'],
    ];
    /*
     *  生成  注册时间
     */
    public function addtime()
    {
        $time = time();
        return $time;
    }
    /*
     *  生成盐值
     */
    public function makeSalt()
    {
        //利用时间戳 的 md5 值 ，截取 4 位
        return $this->temp_salt = substr(md5(time()),0,4);
    }
    /*
     *  密码 混合 盐值
     */
    public function makePassword($password)
    {
        return md5($password.$this->temp_salt);
    }
    /*
     *  校验 密码强度
     */
    public function pwdCheck($password)
    {
        $i = 0;
        if(preg_match('/[A-Z]/',$password)){
            ++$i;
        }
        if(preg_match('/[a-z]/',$password)){
            ++$i;
        }
        if(preg_match('/[0-9]/',$password)){
            ++$i;
        }
        return $i >= 2;  //返回 bool 值
    }
}