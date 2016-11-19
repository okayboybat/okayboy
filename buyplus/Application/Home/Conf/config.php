<?php
return array(
	//'配置项'=>'配置值'
	'URL_MODEL' => 2, // 重写模式, 生成的URL中不包含index.php

	'DEFAULT_CONTROLLER'    =>  'Shop', // 默认控制器名称
    'DEFAULT_ACTION'        =>  'index', // 默认操作名称

	'TMPL_ACTION_ERROR'	=> 'Common:jump',// $this->error()
	'TMPL_ACTION_SUCCESS'	=> 'Common:jump',// $this->success()


    // 表单重复令牌配置
    'TOKEN_NAME' => '__hash__',
	'TOKEN_TYPE' => 'md5',
	'TOKEN_RESET' => true,

	'URL_ROUTER_ON'         =>  true,   // 是否开启URL路由
    'URL_ROUTE_RULES'       =>  array(
    	// 什么样的URL 对应 哪个控制器的动作
    	'index'	=> 'Shop/index',
    	'register' => 'User/register',
    	'login'	=> 'User/login',
    	'captcha'	=> 'User/captcha',
    	'center'	=> 'User/center',
        'goods/:goods_id' => 'Shop/goods',
        'goodsAjax' => 'Shop/goodsAjax',
        'addCart'   => 'Buy/addCart', // 加入购物车
        'cart'      => 'Buy/cart', // 购物车管理
        'cartAjax'  => 'Buy/cartAjax', //
        'checkout'  => 'Buy/checkout', // 结算生成订单呢
        'checkoutAjax'  => 'Buy/checkoutAjax', // checkout页面的ajax处理路由
        // 规则匹配越严谨的放在越靠前的位置
        'orderQueueAjax'    => 'Buy/orderQueueAjax',
        'orderQueue' => 'Buy/orderQueue',
        'orderInfo' => 'Buy/orderInfo',
        'order' => 'Buy/order',

    ), // 默认路由规则 针对模块
);