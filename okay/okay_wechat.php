<?php 

// header('Content-type: text/html;charset=utf-8');

require './WeChat.class.php';

define('APPID','wx3a7a8311ba124f6c');
define('APPSECRET','f4e05ff7b3aacab8d5d8c54b4d4805de');
define('TOKEN','kk_okayboy');

$wechat = new WeCat(APPID,APPSECRET,TOKEN);

// 第一次  的 验证
$wechat->firstValid();


// var_dump($access_token);
// 
// $wechat->getQRCode(122);
// 
// $wechat->getQRCode(122,'./22.jpg');
// var_dump($result);