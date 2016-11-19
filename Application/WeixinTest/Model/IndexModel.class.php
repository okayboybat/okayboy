<?php
namespace WeixinTest\Model;

class IndexModel
{

	public function responsePicMsg($postObj,$arr)
	{
		$toUser 	= $postObj->FromUserName;
		$fromUser 	= $postObj->ToUserName;
		$Pictmpl="<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<ArticleCount>".count($arr)."</ArticleCount>
					<Articles>";
			foreach ($arr as $key => $value) 
			{
				$Pictmpl.="<item>
							<Title><![CDATA[".$value['title']."]]></Title> 
							<Description><![CDATA[".$value['description']."]]></Description>
							<PicUrl><![CDATA[".$value['picurl']."]]></PicUrl>
							<Url><![CDATA[".$value['url']."]]></Url>
							</item>";
			}
			$Pictmpl.="</Articles>
						</xml> ";
			echo sprintf($Pictmpl,$toUser,$fromUser,time(),'news');
	}

	public function responseText($postObj,$content)
	{
		$toUser 	= $postObj->FromUserName;
		$fromUser 	= $postObj->ToUserName;
		$Texttmpl="<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Content><![CDATA[%s]]></Content>
					</xml>";
		$info =sprintf($Texttmpl,$toUser,$fromUser,time(),'text',$content);
		echo $info;
	}
}