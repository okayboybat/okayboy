<?php
return array(  

    //开启 路由 
    'URL_ROUTER_ON'     =>  true,
    // 设置默认 的控制器 和方法
    'DEFAULT_CONTROLLER'    =>  'Admin',
    'DEFAULT_ACTION'        =>  'login',
    
    'URL_ROUTE_RULES'   =>  array(
        // 什么样 的 url 对应  什么 控制器 和方法
        'adminlogin'    =>  'Admin/login',
        'userlist'      =>  'User/userlist',
        'relation'      =>  'Relation/relation',
        'catelist'      =>  'Category/catelist',
        'delcate'       =>  'Category/delcate',
        'deletecate'    =>  'Category/deletecate',
        'article'       =>  'Article/article',
        
    ),
);