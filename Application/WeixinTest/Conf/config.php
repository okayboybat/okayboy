<?php
return array(
	//'配置项'=>'配置值'
	'URL_MODEL' =>	2,//url重写模式
	'URL_ROUTER_ON' 	=>	true,
	'DEFAULT_CONTROLLER'    =>  'Index', // 默认控制器名称
    'DEFAULT_ACTION'        =>  'index', // 默认操作名称
	// 'TMPL_ACTION_ERROR'	=>'common:jump',//$this->error();
	// 'TMPL_ACTION_SUCCESS'	=>'common:jump',//$this->success();
	'URL_ROUTE_RULES' 	=>	array(
		//什么样的URL对应什么样的控制器
		//因为该配置是在Home下的，所以默认都是Home，可以不用配置Module
		//以后没加一个方法，再配置一次就行
		'register' => 'User/register',
		'login' 	=> 'User/login',
		'captcha'	=> 'User/captcha',
    	'center'	=> 'User/center',

		),
	    // 表单重复令牌配置
    'TOKEN_NAME' => '__hash__',
	'TOKEN_TYPE' => 'md5',
	'TOKEN_RESET' => true,
		
);