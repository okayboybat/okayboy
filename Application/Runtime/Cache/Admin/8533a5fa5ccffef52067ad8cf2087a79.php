<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
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
<link href="/Public/home/css/new.css" rel="stylesheet">
<!--[if lt IE 9]>
<script src="/Public/Home/js/modernizr.js"></script>
<![endif]-->
</head>
<body>

<header>
	<div><div style="float:right;margin-top:5px;">
		<?php if(session('admin') == true): ?>欢迎您！ <?php echo session('admin')['email'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<?php else: ?>
			<script language='javascript'>document.location = ''</script><?php endif; ?>
	</div></div>
  <div id="logo"><a href="/"></a></div>
  <nav class="topnav" id="topnav">
<?php if(session('admin') == true): ?><a href="<?php echo U('/relation');?>"><span>首页</span><span class="en">Honme</span></a> 
	 <a href="<?php echo U('/userlist');?>"><span>用户管理</span><span class="en">User</span></a>    
	 <a href="<?php echo U('/catelist');?>"><span>分类管理</span><span class="en">Cate</span></a>
	 <a href="<?php echo U('/article');?>"><span>文章管理</span><span class="en">Article</span></a>
<?php else: endif; ?>
  </nav>
</header>

<article class="blogs">
<h1 class="t_nav"><span>既然来了，那么就留下你的足迹吧！</span>
<a href="<?php echo U('/relation');?>" class="n1">RelCenter</a></h1>
<div class="bloglist left">
	<div class="personal">
	<h2>用户留言</h2>
		<?php if(is_array($relation)): $i = 0; $__LIST__ = $relation;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$rows): $mod = ($i % 2 );++$i;?><ul style="width:600px;font-size:28px;border-radius:10px;padding:10px;border:10px solid #C1C1C1;">
				<li>序号：<?php echo ($rows['relation_id']); ?>
				<a style="color:red;float:right;" href="<?php echo U('/relation?id='.$rows['relation_id']);?>">删除</a></li>
				<li>名字：<?php echo ($rows['username']); ?></li>
				<li>邮箱：<?php echo ($rows['email']); ?></li>
				<li>主题：<?php echo ($rows['theme']); ?></li>
				<li><div style="display:inline-block;">内容：</div>
				<div style="display:inline-block;width:500px;float:right"><?php echo ($rows['content']); ?></div></li>
				<div style="clear:both;"></div>
			</ul><br><?php endforeach; endif; else: echo "" ;endif; ?>
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
  <p><span>Design By:<a href="www.duanliang920.com" target="_blank">余温</a></span><span>网站地图</span><span><a href="/">网站统计</a></span></p>
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