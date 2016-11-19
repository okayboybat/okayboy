<?php
/**
  * wechat php test
  */
// header("Con")
require './WeChat.class.php';

define('APPID','wx3a7a8311ba124f6c');
define('APPSECRET','f4e05ff7b3aacab8d5d8c54b4d4805de');
//define your token
define("TOKEN", "weixin");

$wechat = new WeChat(APPID, APPSECRET, TOKEN);

// 获取 qr 码

$wechat->getQRCode(123,null,2);
