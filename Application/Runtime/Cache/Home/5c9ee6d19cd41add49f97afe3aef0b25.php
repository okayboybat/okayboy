<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en" class="no-js">

<head>

<meta charset="utf-8">
<title>余温 </title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- CSS -->

<link rel="stylesheet" href="/Public/Home/login/css/supersized.css">
<link rel="stylesheet" href="/Public/Home/login/css/login.css">
<link href="/Public/Home/login/css/bootstrap.min.css" rel="stylesheet">
<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
	<script src="/Public/Home/login/js/html5.js"></script>
<![endif]-->
<script src="/Public/Home/login/js/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="/Public/Home/login/js/jquery.form.js"></script>
<script type="text/javascript" src="/Public/Home/login/js/tooltips.js"></script>
<script type="text/javascript" src="/Public/Home/login/js/login.js"></script>
</head>

<body>

<div class="page-container">
	<div class="main_box">
		<div class="login_box">
			<div class="login_logo">
				<img src="/Public/Home/login/images/logo.png" >
			</div>
		
			<div class="login_form">
				<form action="<?php echo U('/login');?>" id="login_form" method="post">
					<div class="form-group">
						<label for="j_username" class="t">邮　箱：</label> 
						<input id="email" value="" placeholder="邮箱" name="email" type="text" class="form-control x319 in" 
						autocomplete="off">
					</div>
					<div class="form-group">
						<label for="j_password" class="t">密　码：</label> 
						<input id="password" value="" placeholder="密码" name="password" type="password" 
						class="password form-control x319 in">
					</div>
					<div class="form-group">
						<label for="j_captcha" class="t">验证码：</label>
						 <input id="j_captcha" placeholder="输入验证码" name="captcha" type="text" class="form-control x164 in">
						<img id="captcha_img" alt="点击更换" title="点击更换" src="<?php echo U('/captcha');?>" class="m" onclick="this.src='<?php echo U('/captcha');?>'+'?'+Math.random()">
					</div>
					<div class="form-group">
						<label class="t"></label>
						<label for="j_remember" class="m">
						<input id="j_remember" type="checkbox" value="true">&nbsp;记住登陆账号!</label>
						&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo U('/register');?>">注册帐号</a>
					</div>
					<div class="form-group space">
						<label class="t"></label>　　　
						<input type="submit"  id="submit_btn" 
						class="btn btn-primary btn-lg" value="&nbsp;登&nbsp;录&nbsp">
						<input type="reset" value="&nbsp;重&nbsp;置&nbsp;" class="btn btn-default btn-lg">
					</div>
				</form>
			</div>
		</div>
		<div class="bottom">Copyright &copy; 2014 - 2015 <a href="#">系统登陆</a></div>
	</div>
</div>

<!-- Javascript -->

<script src="/Public/Home/login/js/supersized.3.2.7.min.js"></script>
<script src="/Public/Home/login/js/supersized-init.js"></script>
<script src="/Public/Home/login/js/scripts.js"></script>
<div style="text-align:center;">
</div>
</body>
</html>