<?php
return array(
    // 开启 重写模式
    'URL_MODEL'     =>  2,
    
    // 设置默认 的控制器 和方法
    'DEFAULT_CONTROLLER'    =>  'Index',
    'DEFAULT_ACTION'        =>  'index',
    
    //开启 路由 
    'URL_ROUTER_ON'     =>  true,
    'URL_ROUTE_RULES'   =>  array(
        // 什么样 的 url 对应  什么 控制器 和方法
        'index'         =>  'Index/index',
        'register'      =>  'User/register',
        'login'         =>  'User/login',
        'captcha'       =>  'User/captcha',
        'about'         =>  'Relation/about',
        'gustbook'      =>  'Gustbook/gustbook',
        'gustbooklist'  =>  'Gustbook/gustbooklist',
        'articleAdd'    =>  'Article/add',
        'articleList'   =>  'Article/list',
        'artcontent'    =>  'Article/artcontent',
        'personal'      =>  'Personal/personal',
        'percomile'     =>  'Personal/percomile',
        'helist'        =>  'Personal/helist',
        'deluser'       =>  'Personal/deluser',
        'mood'          =>  'Mood/mood',
        'newmood'       =>  'Mood/newmood',
    ),
);