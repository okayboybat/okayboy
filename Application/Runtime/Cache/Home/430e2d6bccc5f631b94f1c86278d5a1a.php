<?php if (!defined('THINK_PATH')) exit();?>
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

<div class="moodlist">
  <h1 class="t_nav"><span>记忆着曾经那些点点滴滴的往事！</span>
  <a href="<?php echo U('/mood');?>" class="n1">Mood</a></h1>
  <div class="bloglists">
  
	  <div>
	  	<h2>发表心情</h2>
	  		<form action="<?php echo U('/mood');?>" method="post">
	  			<textarea cols="80" rows="4" name="content" style=""></textarea>
	  			<?php if(session('user') == true): ?><input type="submit" value="publish" style="position:relative;
	  			top:-8px;left:-50px;">
	  			<?php else: ?>
	  				<botton style="position:relative;
	  			top:-8px;left:-50px;" onclick="hai()">publish</botton>
		  			<script>
						function hai(){alert('请先登录!')}
					</script><?php endif; ?>
	  		</form>
	  </div>
  	<?php if(is_array($moodlist)): $i = 0; $__LIST__ = $moodlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$rows): $mod = ($i % 2 );++$i;?><ul class="arrow_box">
	     <div class="sy">
	      <p> <?php echo ($rows['content']); ?></p>
	      </div>
	      <span class="dateviews"><?php echo (date("Y-m-d",$rows['add_time'])); ?></span>
	    </ul><?php endforeach; endif; else: echo "" ;endif; ?>
  	
    <ul class="arrow_box">
     <div class="sy">
      <p> 梦想，因我们永不放弃</p>
      </div>
      <span class="dateviews">2016-6-22</span>
    </ul>
     
  </div>
</div>

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