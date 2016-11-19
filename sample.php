<?php

if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
// define('APPID','wx31b15879e06af14c');
// define('APPSECRET','a033742c7ade83ec459a17a31ac05aa5');
// //define your token
// define("TOKEN", "weixin");

// 第一次  的 验证
// $wechat->valid();
// $wechat->getQRCode();
// $wechat->responseMsg();
define('BIND_MODULE', 'WeixinTest');

// 定义应用目录
define('APP_PATH','./Application/');

// define('SHOW_PAGE_TRACE', True);

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';
