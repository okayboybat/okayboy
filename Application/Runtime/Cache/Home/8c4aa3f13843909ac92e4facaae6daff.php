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
  <h1 class="t_nav">
  <span>您当前的位置：<a href="#">首页</a>&nbsp;&gt;&nbsp;
  <a href="#">日记</a></span>
  <a href="/" class="n1">文章内容</a>
  </h1>
  <div class="index_about">
    <h2 class="c_titile"><?php echo ($article['title']); ?></h2>
    <p class="box_c"><span class="d_time">发布时间：<?php echo (date("Y-m-d",$article['add_time'])); ?></span><span>编辑：<?php echo ($article['nickname']); ?></span><span>互动QQ群：<a href="http://jq.qq.com/?_wv=1027&k=2Egtq0s"> 75979967</a></span></p>
    <ul class="infos">
      	<?php echo ($content); ?>  
    </ul>
    <div class="keybq">
    <p><span>关键字词</span>：爱情,犯错,原谅,分手</p>
    
    </div>
    <div class="ad"> </div>
    <div class="nextinfo">
      <p>上一篇：<a href="#">程序员应该如何高效的工作学习</a></p>
      <p>下一篇：<a href="#">柴米油盐的生活才是真实</a></p>
    </div>
    
    <div>
    <br><div><h2>评论</h2>
    	<form action="<?php echo U('/artcontent?id='.$article['article_id']);?>" method="post">
    		<textarea cols=80 rows=5 name="answer_content"></textarea>
    		<?php if(session('user') == true): ?><input type="submit" value="评论" style="position:relative;left:-40px;top:-10px;">
    		<?php else: ?>
    		<span style="position:relative;left:-40px;top:-10px;" onclick="hai()">评论</span>
    		<script>
				function hai(){alert('请先登录!')}
			</script><?php endif; ?>
    	</form></div>
    	<div>
    		<?php if(is_array($answerlist)): $i = 0; $__LIST__ = $answerlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$rows): $mod = ($i % 2 );++$i;?><br><div style="font-size:22px;">
    				<div style="float:left;width:68px;height:100px;text-align:center;">
    				<img src="/Public/Home/images/touxiang.jpg"><?php echo ($rows['nickname']); ?></div>
    				<div style="float:left;width:500px;px;height:100px;margin-left:30px;">
    				<div style="background:#C7EDCC; color:red;">发表于：<?php echo (date("Y-m-d",$rows['answer_time'])); ?></div>
    				<div style="margin:10px;"><?php echo ($rows['answer_content']); ?></div></div>
    				<div style="clear:both;"></div>
    			</div><?php endforeach; endif; else: echo "" ;endif; ?>
    	</div>
    </div>
    
    <div class="otherlink">
      <h2>相关文章</h2>
      <ul>
        <li><a href="#" title="相遇就是缘分">相遇就是缘分</a></li>
        <li><a href="#" title="相遇就是缘分">相遇就是缘分</a></li>
        <li><a href="#" title="相遇就是缘分">相遇就是缘分</a></li>
        <li><a href="#" title="世上最美好的爱情">世上最美好的爱情</a></li>
        <li><a href="#" title="爱情没有永远，地老天荒也走不完">爱情没有永远，地老天荒也走不完</a></li>
        <li><a href="#" title="爱情的背叛者">爱情的背叛者</a></li>
      </ul>
    </div>
  </div>
  <aside class="right">
    <!-- Baidu Button BEGIN -->
    <div id="bdshare" class="bdshare_t bds_tools_32 get-codes-bdshare">
    <a class="bds_tsina"></a>
    <a class="bds_qzone"></a>
    <a class="bds_tqq"></a>
    <a class="bds_renren"></a>
    <span class="bds_more"></span>
    <a class="shareCount"></a></div>
    <script type="text/javascript" id="bdshare_js" data="type=tools&amp;uid=6574585" ></script> 
    <script type="text/javascript" id="bdshell_js"></script> 
    <script type="text/javascript">
document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + Math.ceil(new Date()/3600000)
</script> 
    <!-- Baidu Button END -->
    <div class="blank"></div>
    <div class="news">
      <h3>
        <p>栏目<span>最新</span></p>
      </h3>
      <ul class="rank">
        <li><a href="/">一个网站的开发流程</a></li>
        <li><a href="/">一个网站的开发流程</a></li>
        <li><a href="/">一个网站的开发流程</a></li>
        <li><a href="/">做网站到底需要什么?</a></li>
        <li><a href="/">一个网站的开发流程</a></li>
        <li><a href="/">一个网站的开发流程</a></li>
      </ul>
      <h3 class="ph">
        <p>点击<span>排行</span></p>
      </h3>
      <ul class="paih">
        <li><a href="/">一个网站的开发流程</a></li>
        <li><a href="/">一个网站的开发流程</a></li>
        <li><a href="/">一个网站的开发流程</a></li>
        <li><a href="/">做网站到底需要什么?</a></li>
        <li><a href="/">一个网站的开发流程</a></li>
      </ul>
    </div>
  </aside>
</article>
<footer>
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