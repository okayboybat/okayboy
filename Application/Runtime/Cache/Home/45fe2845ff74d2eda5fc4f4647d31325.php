<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>关于博主</title>
	<link rel="stylesheet" type="text/css" href="/Public/Home/about/css/main.css">
</head>
<body>
<aside>
	<a class="cur_a"><span>首页</span></a>
	<a><span>关于我</span></a>
	<a><span>技能</span></a>
	<a><span>经验</span></a>
	<a><span>联系我</span></a>
</aside>	
<section class="page_one" id="page1">
	<nav>
		<div class="nav_wrap">
			<div class="resume_logo">
				<a href="index.html">Resume</a>
			</div>
			<div class="nav_bar">
				<ul class="nav_bar_ul">
					<li><a href="<?php echo U('/index');?>">Home</a></li>
				</ul>
			</div>
		</div>
	</nav>
	
	<div class="cen_con">
		<div class="portrait">
            <img onmousemove="this.src='/Public/Home/about/images/22.png'"/ onmouseout="this.src='/Public/Home/about/images/portrait1.jpg'"/ src="/Public/Home/about/images/portrait1.jpg">
        </div>
		<div class="cen_text">
			<h2>世上只有想不通的人，没有走不通的路。</h2>
			<hr>
			<h3>段齐凯</h3>
			<h3>php工程师</h3>
			<h3>求职上海 / 北京</h3>
			<h3>18659801344</h3>
		</div>
		
	</div>
	<div class="down_arrow">
		<a class="scroll"><span></span></a> 
	</div>
	
</section>

<section class="page_two" id="page2">
	<div class="con_wrap">
		<div class="tit_wrap">
			<h1>关于我</h1>
			<div class="scissors">
				<span></span>
			</div>
			<h2> 如果debugging是一种消灭bug的过程,那编程就一定是把bug放进去的过程。</h2>
		</div>		
		<div class="myinfo">
			<table>
				<tbody>
					<tr>
						<td rowspan="6">
							<img src="/Public/Home/about/images/portrait1.jpg">
						</td>
						<td>姓名 | 段齐凯</td>
						<td>手机 | 18659801344</td>
					
					</tr>
					<tr>
						<td>性别 | 男</td>
						<td>邮箱 | okayboybat@163.com</td>
					</tr>
					<tr>
						<td>出生 | 1993.09.24</td>
						
						<td>QQ号 | 874065603</td>
					</tr>
					<tr>
						<td>居住 | 北京市海淀区</td>
						<td>户籍 | 江西上饶市</td>
					</tr>
					<tr>
						<td>学历 | 高中</td>
						<td>学校 | 蓝天学院</td>
					</tr>
					<tr>
						<td>专业 | 计算机科学与技术</td>
						<td>英语 | 英语2级</td>
					</tr>
					<tr>
						<td colspan="3">
							<p>项目：</p><br>
							<a href="http://www.okayboy.tk/">个人博客</a>

						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="down_arrow">
		<a class="scroll"><span></span></a> 
	</div>
</section>
<section class="page_three" id="page3">
	<div class="con_wrap">
		<div class="tit_wrap">
			<h1>技能</h1>
			<div class="scissors">
				<span></span>
			</div>
			<h2>代码，我们眼中的世界，别人眼中的天书。</h2>	
		</div>
		<div class="skill_con">
			<div class="canvas_wrap">
				<div class="canvas_con">
					<div class="text_con">
						<p class="percent">60%</p>
						<p class="chart_title">HTML5</p>
					</div>
					<canvas id="html5" width=160 height=160></canvas>
				</div>
				<div class="canvas_con">
					<div class="text_con">
						<p class="percent">60%</p>
						<p class="chart_title">CSS3</p>
					</div>
					<canvas id="css3" width=160 height=160></canvas>
				</div>
				<div class="canvas_con">
					<div class="text_con">
						<p class="percent">75%</p>
						<p class="chart_title">JS</p>
					</div>
					<canvas id="js" width=160 height=160></canvas>
				</div>
				<div class="canvas_con">
					<div class="text_con">
						<p class="percent">80%</p>
						<p class="chart_title">JQUERY</p>
					</div>
					<canvas id="jq" width=160 height=160></canvas>
				</div>
				<div class="canvas_con">
					<div class="text_con">
						<p class="percent">70%</p>
						<p class="chart_title">php</p>
					</div>
					<canvas id="bs" width=160 height=160></canvas>
				</div>
				<div class="canvas_con">
					<div class="text_con">
						<p class="percent">80%</p>
						<p class="chart_title">tp</p>
					</div>
					<canvas id="ps" width=160 height=160></canvas>
				</div>
			</div>
			<div class="text_wrap">
				<p>1.  了解HTML5，CSS3，JavaScript技术，开发符合W3C标准的前端网页。</p>
				<p>2.  了解使用jQuery，bootstrap等前端框架技术。</p>
				<p>3.  了解Ajax的运行机制，能使用ajax进行数据交互。</p>
				<p>4.  了解W3C标准，对表现与数据分离、Web语义化等有较为深刻的理解。</p>
				<p>5.  可搭建 win7，8，10， 下 的 wamp 环境</p>
				<p>6.  熟练使用php基础语法</p>
				<p>7.  熟练使用mysql，各种sql语句，可自行解决bug。</p>
				<p>8.  熟练使用mysqli 和 pdo 操作mysql</p>
				<p>9.  了解 mysql 优化 ，可尝试</p>
				<p>10. 可以自定义基础 mvc 框架。</p>
				<p>11. 熟练使用smarty模板引擎</p>
				<p>12. 熟练使用tp，可单独完成模块的复杂逻辑业务 并尝试 独自开发完整项目</p>
				<p>13. 了解 linux 可搭建lamp ， lnmp 环境，熟练使用 linux 基础操作指令</p>
				<p>14. 了解 memcache 内存缓存技术，可完成服务器搭建，即基础操作</p>
				<p>15. 了解 redis 内存缓存数据库技术，可完成服务器搭建，即基础操作</p>
			</div>	
		</div>
		
	</div>
	<div class="down_arrow">
		<a class="scroll"><span></span></a> 
	</div>
</section>
<section class="page_four" id="page4">
    <div class="con_wrap">
		<div class="tit_wrap">
			<h1>工作经验</h1>
			<div class="scissors">
				<span></span>
			</div>
			<h2>我现在做的一切仅仅是为了不让未来的自己后悔。</h2>
		</div>
	  	<div class="work_con">
	  		<div class="programe">
		    	<div class="work_time">0个月<br>个人博客</div>
			    <div class="work_text">
			      	<div class="triangle-left"></div>
			      	<div class="exCon">
				        <h4>开发时间：2016 /5--2016 /8</h4>
				        <h5>开发工具：zendstudio，sublime text</h5>
				        <p>项目描述：</p>
				        <p>该项目是关于个人博客，，有个人空间，提供个人信息修改，和保密功能，提供个人留言板，博主专用留言板，心情、日记等记录，可发表各类文章，提供分类功能，后台用户管理，文章管理，分类管理功能</p>
				        <p> 该项目，初始使用mvc开发，，后改thinkphp开发</p>
				        <p>责任描述：</p>			        
				        <p>1. 数据库，数据表设计 </p>
				        <p>2. 页面，大部分来源于抓取 ，一部分自己写</p>
						<p>3. 个人独立项目</p>	       
					</div>
			    </div> 
			</div>
			
		</div>
    </div>
	<div class="down_arrow">
		<a class="scroll"><span></span></a> 
	</div>
</section>
<section class="page_five" id="page5">
	<div class="con_wrap">
		<div class="tit_wrap">
			<h1>联系我</h1>
			<div class="scissors">
				<span></span>
			</div>	
			<h2>耐得住寂寞，做得成大事。</h2>
		</div>
		<div class="contact_detail">
			<ul class="con_style">
				<li>
					<span></span>
					<p>北京市海淀区</p>
				</li>
				<li>
					<span></span>
					<p>874065603@qq.com</p>
				</li>
				<li>
					<span></span>
					<p>18659801344</p>
				</li>
			</ul>
			<div class="contact_info">
				<form action="<?php echo U('/about');?>" method="post">
				<label>
					<input type="text" name="username" id="username" placeholder="你的名字" required="">
					<p  id="check_username"></p>
				</label>
				<label>
					<input type="text" name="email" id="usermail" placeholder="你的邮箱" required="">
					<p id="check_usermail"></p>
				</label>	
				<label>
					<input type="text" name="theme" id="usertheme" placeholder="主题" required="" maxlength="25">
					<p id="check_usertheme"></p>
				</label>	
				<label>
					<textarea name="content" id="usercon" placeholder="内容" required=""></textarea>
					<p id="check_usercon" ></p>
				</label>					
					<input type="submit" value="发送">
				</form>
			</div>
		</div>
	</div>	
</section>
</body>
<script type="text/javascript" src="/Public/Home/about/js/main.js"></script>
</html>