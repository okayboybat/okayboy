<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>留言板―余温博客</title>
<meta name="keywords" content="个人博客,余温个人博客," />
<meta name="description" content="" />
<link href="/Public/Home/css/index.css" rel="stylesheet">
<link href="/Public/Home/css/style.css" rel="stylesheet">
<link href="/Public/Home/css/article.css" rel="stylesheet">
<link href="/Public/Home/css/ty.css" rel="stylesheet">
<!--[if lt IE 9]>
<script src="/Public/Home/js/modernizr.js"></script>
<![endif]-->
</head>
<body>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>余温博客</title>
<meta name="keywords" content="个人博客,余温个人博客," />
<meta name="description" content="" />

<link href="/Public/Home/css/index.css" rel="stylesheet">
<link href="/Public/Home/css/style.css" rel="stylesheet">
<link href="/Public/Home/css/article.css" rel="stylesheet">
<link href="/Public/Home/css/ty.css" rel="stylesheet">
<link href="/Public/home/css/board.css" rel="stylesheet">
<link href="/Public/home/css/new.css" rel="stylesheet">

<!-- 可视化文本编辑器 -->
<link href="/Public/Test/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="/Public/Test/css/froala_editor.min.css" rel="stylesheet" type="text/css">

<!--[if lt IE 9]>
<script src="/Public/Home/js/modernizr.js"></script>
<![endif]-->

<style>
       #editor {width: 80%; margin: auto; text-align: left; }
	   #editor li{text-align: center;margin-top:10px;}
</style>

</head>
<body>

<header>
	<div><div style="float:right;margin-top:5px;">
		<?php if(session('user') == true): ?>欢迎您！ <?php echo session('user')['nickname'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<?php else: ?>
			<a href="<?php echo U('/login');?>">登录</a>&nbsp;&nbsp;&nbsp;
			<a href="<?php echo U('/register');?>">注册</a>&nbsp;&nbsp;&nbsp;<?php endif; ?>
	</div></div>
  <div id="logo"><a href="/"></a></div>
  <nav class="topnav" id="topnav">
      <a href="<?php echo U('/index');?>"><span>首页</span><span class="en">Honme</span></a>
      <a href="<?php echo U('/about');?>"><span>关于博主</span><span class="en">About</span></a>
      
      <a href="<?php echo U('/articleList');?>"><span>文章</span><span class="en">Article</span></a>
           
      <?php if(session('?user')): ?><a href="<?php echo U('/mood');?>"><span>心情日记</span><span class="en">Diary</span></a>
      		<a href="<?php echo U('/gustbook');?>"><span>留言版</span><span class="en">Gustbook</span></a>
			<a href="<?php echo U('/personal');?>"><span>个人空间</span><span class="en">GeRen</span></a>
		<?php else: ?>
			<a href="#" onclick="hai()"><span>心情日记</span><span class="en">Diary</span></a>
			<a href="#" onclick="hai()"><span>留言版</span><span class="en">Gustbook</span></a>
			<a href="#" onclick="hai()"><span>个人空间</span><span class="en">GeRen</span></a>
			<script>
				function hai(){alert('请先登录!')}
			</script><?php endif; ?>
  </nav>
</header>
<article class="blogs">
<h1 class="t_nav"><span>既然来了，那么就留下你的足迹吧！</span>
<a href="" class="n1">DataCenter</a></h1>

<div class="bloglist left">
	<div class="personal">
		<ul>
			<li><span>昵称：</span><?php echo ($personallist['nickname']); ?></li>
			<li><span>签名：</span><?php echo ($personallist['autograph']); ?></li>
			<li><span>性别 ：</span>
				<?php if($personallist['gender'] == 1): ?>男
				<?php else: ?>
					女<?php endif; ?>
			</li>
			<li><span>生日：</span><?php echo ($personallist['birthday']); ?></li>
			<li><span>所在地：</span><?php echo ($personallist['localtion']); ?><li>
			<li><span>手机：</span><?php echo substr_replace($personallist['phone'],'****',3,4);?></li>
			<li><h3><a href="<?php echo U('/?????id='.$personallist['personal_id']);?>">他发表的文章</a></h3><li>
			<div style="width:600px;">
     		 	<h2>给他留言</h2>
		        <form action="<?php echo U('/helist?id='.$personallist['personal_id']);?>" method="post">
		            <textarea rows="5" cols="90" name="content"></textarea>
		            <?php if(session('?user')): ?><input type="submit" value="Publish" style="position:relative; left:600px;top: -70px;">
		            <?php else: ?>
		            	<a style="position:relative;left:600px;top:-70px;" onclick="hai()">留言</a>
			    		<script>
							function hai(){alert('请先登录!')}
						</script><?php endif; ?>
		        </form> 
      		</div> 
		</ul>
	</div>	
</div>
<!--right-->
<aside class="right">
 <div id="bdshare" class="bdshare_t bds_tools_32 get-codes-bdshare"><a class="bds_tsina"></a><a class="bds_qzone"></a><a class="bds_tqq"></a><a class="bds_renren"></a><span class="bds_more"></span><a class="shareCount"></a></div>
   <div class="rnav">
      <ul>
       <li class="rnav1"><a href="#">SEO基础入门</a></li>
       <li class="rnav2"><a href="#">SEO进阶优化</a></li>
       <li class="rnav3"><a href="#">SEO实战案例</a></li>
       <li class="rnav4"><a href="#">SEO心得笔记</a></li>
     </ul>      
    </div>
    
<div class="news">
    <h3 class="ph">
      <p>精心<span>推荐</span></p>
    </h3>
    <ul class="paih">
      <li><a href="/" title="如何建立个人博客" target="_blank">如何建立个人博客</a></li>
      <li><a href="/" title="一个网站的开发流程" target="_blank">一个网站的开发流程</a></li>
      <li><a href="/" title="关键词排名的三个时期" target="_blank">关键词排名的三个时期</a></li>
      <li><a href="/" title="做网站到底需要什么?" target="_blank">做网站到底需要什么?</a></li>
      <li><a href="/" title="关于响应式布局" target="_blank">关于响应式布局</a></li>
    </ul>  
    </div> 
</aside>

</article>
<footer>
  <p><span>Design By:<a href="www.okayboy.tk" target="_blank">余温</a></span><span>网站地图</span><span><a href="/">网站统计</a></span></p>
</footer>

<script src="/Public/Home/js/nav.js"></script>
  <!-- Baidu Button BEGIN -->
   
    <script type="text/javascript" id="bdshare_js" data="type=tools&amp;uid=6574585" ></script> 
    <script type="text/javascript" id="bdshell_js"></script> 
    <script type="text/javascript">
document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + Math.ceil(new Date()/3600000)
</script> 
    <!-- Baidu Button END -->   
</body>
</html>