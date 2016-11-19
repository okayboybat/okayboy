<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>余温个人博客</title>
<meta name="keywords" content="个人博客模板,博客模板" />
<meta name="description" content="优雅、稳重、大气,低调。" />
<link href="/Public/Home/css/index.css" rel="stylesheet">
<!--[if lt IE 9]>
<script src="/Public/Home/js/html5.js"></script>
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
<!--end header-->
<div class="banner">
  <section class="box">
    <ul class="texts">
      <p class="p1">纪念我们:</p>
      <p class="p2">终将逝去的青春</p>
      <p class="p3">By:少年</p>
    </ul>
  </section>
</div>
<!--end banner-->
<article>
  <h2 class="title_tj">
    <p>博主<span>推荐</span></p>
  </h2>
  <div class="bloglist left">
  
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
 $filepath = PUBLIC_PATH.'Static/Home/Article/'; $filename = $row['artcontent']; $content = file_get_contents($filepath.$filename); $str = substr($content,0,300); echo strip_tags($str,'<html><head><script><span><body><div><ul><li><p><span>').'...'; ?>
	      </p>
	      <a title="阅读全文" href="<?php echo U('/artcontent?id='.$row['article_id']);?>" target="_blank" class="readmore">阅读全文>></a>
	    </ul>
    <div class="clear"></div>
    </div>
   <!--end--><?php endforeach; endif; else: echo "" ;endif; ?>
  
  </div>
  <!--right-->
  <aside class="right"> 
    <div class="my">
     <h2>关于<span>博主</span></h2>
     <img src="/Public/Home/images/my.jpg" width="200" height="200" alt="博主">
       <ul>
        <li>博主：少年</li>
        <li>职业:web前端、网站运营</li>
        <li>简介：一个不断学习和研究，web前端和SEO技术的90后草根站长.</li>
        <li></li>   
       </ul>
    </div>
    <div class="bdsharebuttonbox"><a href="#" class="bds_more" data-cmd="more"></a><a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a><a href="#" class="bds_renren" data-cmd="renren" title="分享到人人网"></a><a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a></div>
    <div class="news">
    <h3 class="ph">
      <p>点击<span>排行</span></p>
    </h3>
    <ul class="paih">
      <li><a href="/" title="如何建立个人博客" target="_blank">如何建立个人博客</a></li>
      <li><a href="/" title="html5标签" target="_blank">html5标签</a></li>
      <li><a href="/" title="一个网站的开发流程" target="_blank">一个网站的开发流程</a></li>
      <li><a href="/" title="做网站到底需要什么?" target="_blank">做网站到底需要什么?</a></li>
      <li><a href="/" title="企业做网站具体流程步骤" target="_blank">企业做网站具体流程步骤</a></li>
    </ul>
    <h3>
      <p>用户<span>关注</span></p>
    </h3>
    <ul class="rank">
      <li><a href="/" title="如何建立个人博客" target="_blank">如何建立个人博客</a></li>
      <li><a href="/" title="一个网站的开发流程" target="_blank">一个网站的开发流程</a></li>
      <li><a href="/" title="关键词排名的三个时期" target="_blank">关键词排名的三个时期</a></li>
      <li><a href="/" title="做网站到底需要什么?" target="_blank">做网站到底需要什么?</a></li>
      <li><a href="/" title="关于响应式布局" target="_blank">关于响应式布局</a></li>
      <li><a href="/" title="html5标签" target="_blank">html5标签</a></li>
    </ul>
    
    <h3 class="links">
      <p>友情<span>链接</span></p>
    </h3>
    <ul class="website">
      <li><a href="#">个人博客</a></li>
      <li><a href="okayboy.tk">余温博客</a></li>
    </ul> 
    </div> 
</article>
<footer>
  <p><span>Design By:<a href="www.duanliang920.com" target="_blank">余温</a></span><span>网站地图</span><span><a href="/">网站统计</a></span></p>
</footer>
<script src="js/nav.js"></script>
<!--百度分享-->
<script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"1","bdSize":"32"},"share":{},"image":{"viewList":["qzone","tsina","tqq","renren","weixin"],"viewText":"分享到：","viewSize":"16"},"selectShare":{"bdContainerClass":null,"bdSelectMiniList":["qzone","tsina","tqq","renren","weixin"]}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>
</body>
</html>