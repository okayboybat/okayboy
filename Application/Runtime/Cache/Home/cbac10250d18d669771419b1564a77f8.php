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
<h1 class="t_nav"><span>写下看见的！</span>
		<a href="<?php echo U('/articleList');?>" class="n2">List</a>
		<a href="<?php echo U('/articleAdd');?>" class="n1">News</a>
</h1>
<section id="editor">
   	<div id='edit' style="margin-top: 30px;">
      <form class="two" action="<?php echo U('/ArticleAdd');?>" method="post">
        <input type="hidden" value="<?php echo session('user')['user_id'];?>" name="user_id">
      	<ul>
        	<li style="font-size:30px">发布文章</li>
     		<li><input placeholder="标题" type="text" name="title" style="width:80%;height:30px"><select style="height:30px;width:18%" name="cate_id">
		     					<option value='1'>默认分类</option>
		     					<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i;?><option value="<?php echo ($row['cate_id']); ?>"><?php echo ($row['cate_title']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
     				</select></li>
     		<li><textarea placeholder="文章内容" class="editor" id="edit" name="content"></textarea></li>
     		<li><input type="submit" value="确认发布" style="width:20%;height:50px;"></li>
		</ul>  
    </div>
</section>

</article>

<script src="/Public/Test/js/libs/jquery-1.11.1.min.js"></script> 
<script src="/Public/Test/js/froala_editor.min.js"></script> 
<!--[if lt IE 9]>
    <script src="/Public/Test/js/froala_editor_ie8.min.js"></script>
<![endif]--> 
<script src="/Public/Test/js/plugins/tables.min.js"></script> 
<script src="/Public/Test/js/plugins/lists.min.js"></script> 
<script src="/Public/Test/js/plugins/colors.min.js"></script> 
<script src="/Public/Test/js/plugins/media_manager.min.js"></script> 
<script src="/Public/Test/js/plugins/font_family.min.js"></script> 
<script src="/Public/Test/js/plugins/font_size.min.js"></script> 
<script src="/Public/Test/js/plugins/block_styles.min.js"></script> 
<script src="/Public/Test/js/plugins/video.min.js"></script> 
<script src="/Public/Test/js/langs/zh_cn.js"></script>

 <script>
   $(function() {
       $('.editor').editable({
           inlineMode: false,
           theme: 'gray',
           //模版
           height: '200px' ,//高度
     });
  })
</script>