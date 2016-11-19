<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
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
	<a href="<?php echo U('/articleList');?>" class="n1">List</a>
	<?php if(session('user') == true): ?><a href="<?php echo U('/articleAdd');?>" class="n2">News</a>
	<?php else: ?>
		<a href="#" onclick="hai()" class="n2" onclick="">News</a>
		<script>
			function hai(){alert('请先登录!')}
		</script><?php endif; ?>
</h1>
<div class="bloglist left">
   <div id="gustbook" class="cate">
     	<?php if(is_array($art)): $i = 0; $__LIST__ = $art;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i;?><!--wz-->
    <div class="wz">
    <h3><?php echo ($row['title']); ?></h3>
	    <p class="dateview">
		    <span><?php echo (date("Y-m-d",$row['add_time'])); ?></span>
		    <span>作者：<a href="<?php echo U('/helist?id='.$row['user_id']);?>"><?php echo ($row['nickname']); ?></a></span>
		    <span>分类：[<a href="#"><?php echo ($row['cate_title']); ?></a>]</span>
		    <span>评论：<?php echo ($row['browse_number']); ?></span>
	    </p>
    <figure><img src="/Public/Home/images/001.jpg"></figure>
	    <ul>
	      <p>
	      	<?php
 $filepath = PUBLIC_PATH.'Static/Home/Article/'; $filename = $row['artcontent']; $content = file_get_contents($filepath.$filename); $str = substr($content,0,300); echo strip_tags($str,'<html><script><span><head><body><div><ul><li><p><span>').'...'; ?>
	      </p>
	      <a title="阅读全文" href="<?php echo U('/artcontent?id='.$row['article_id']);?>" target="_blank" class="readmore">阅读全文>></a>
	    </ul>
    <div class="clear"></div>
    </div>
    <br>
   <!--end--><?php endforeach; endif; else: echo "" ;endif; ?>
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