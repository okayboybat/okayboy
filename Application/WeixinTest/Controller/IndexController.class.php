<?php
namespace WeixinTest\Controller;
use Think\Controller;
use WeixinTest\Model\IndexModel;
class IndexController extends Controller 
{
		// define('APPID','wx31b15879e06af14c');
		// define('APPSECRET','a033742c7ade83ec459a17a31ac05aa5');
		//define your token
		// define("TOKEN", "weixin");

	const APPID='wx31b15879e06af14c';
	const APPSECRET='a033742c7ade83ec459a17a31ac05aa5';
	// const TOKEN='weixin';

	// 表示 qrcode 类型
	const QRCODE_TYPE_TEMP = 1;
	const QRCODE_TYPE_LIMIT = 2;
	const QRCODE_TYPE_LIMIT_STR = 3;

    public function indexAction()
    {
    	//第一次验证
		// $this->valid();
		// //非第一次验证
		$this->responseMsg();
	}
	public function responseMsg()
	{

		// 获取 请求 时post  xml 字符串
		// $_POST，不是key/value 型，因此不能 使用 该数组
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		// 如果 没接收 到 post 数据 ， 直接 结束
		if(empty($postStr)){
			die('');
		}
		//解析 该xml 字符串 。，， 利用 simplexml
		//	//禁止 xml 实体解析
		libxml_disable_entity_loader(true);
		// 从 字符串 获取 simplexml 对象
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
		//
		//判断 该消息 具体 事件类型，  通过元素，msgtype
		switch ($postObj->MsgType) {
			case 'event':
				# code...判断具体的 事件类型（关注，取消）
				$event = strtolower($postObj->Event);
				if($event == 'subscribe'){
					$this->_doSubscribe($postObj);
				}
				if($event == 'click'){
					$eventkey =strtolower($postObj->EventKey);
					// if('tianqi'==$eventkey){
					// $content = 'tianqi';
					// $m_index = new IndexModel();
					// $m_index->responseText($postObj,$content);
					// }
					if('beijing'==$eventkey)
					{
						$url = 'https://api.thinkpage.cn/v3/weather/now.json?key=7huj0uyepwiolvdn&location=beijing&language=zh-Hans&unit=c';
					$arr=$this->http_curl($url);
			  
					$content = $arr['results'][0]['location']['name'].':'.$arr['results'][0]['now']['text']."\n气温:".$arr['results'][0]['now']['temperature']."摄氏度\n最后更新时间:".$arr['results'][0]['last_update'];
					// $content = 'tuwen';
					$m_index = new IndexModel();
					$m_index->responseText($postObj,$content);	
					}
				}
				break;
			case 'text': 
				# code...  文本消息
				$this->_doText($postObj);
				break;
			case 'image': 
				# code...  图片消息
				$this->_doImage($postObj);
				break;
			case 'voice': 
				# code...  语音 消息
				$this->_doVoice($postObj);
				break;
			case 'video': 
				# code...  视频消息
				$this->_doVideo($postObj);
				break;
			case 'shortvideo': 
				# code...  短视频消息
				$this->_doShortVideo($postObj);
				break;
			case 'location': 
				# code...  位置消息
				$this->_doLocation($postObj);
				break;
			case 'link': 
				# code...  连接消息
				$this->_doLink($postObj);
				break;
			default:
				# code...
				break;
		}
	}
	/*
		关注就 发送 的 消息
	 */
	private function _doSubscribe($postObj)
	{
		$m_index = new IndexModel();
		$content = "我是天呐，感谢您的关注，欢迎光临我的测试公众号";
		$m_index->responseText($postObj,$content);
		// // 利用 消息发送 ， 完成 向 关注 用户 打招呼 功能
		// $fromUsername = $postObj->FromUserName;
	}
	private function _doText($postObj)
	{
		$m_index = new IndexModel();
		// 获取 用户 输入 的
		$content = $postObj->Content;

		//对内容进行判断
		if(strtolower(trim($postObj->Content))=='tuwen'){
			$arr = array(
						array('title'=>'天呐',
						'description' =>'天呐真酷',
						'picurl' 	=>'https://www.baidu.com/img/bd_logo1.png',
						'url'	=>'www.baidu.com'),
						array('title'=>'天呐2',
						'description' =>'天呐真酷2',
						'picurl' 	=>'https://www.baidu.com/img/bd_logo1.png',
						'url'	=>'www.qq.com')
					);
				
				$m_index->responsePicMsg($postObj,$arr);
				die;

		}else if('北京' == strtolower($content)){
			$url = 'https://api.thinkpage.cn/v3/weather/now.json?key=7huj0uyepwiolvdn&location=beijing&language=zh-Hans&unit=c';
			$arr=$this->http_curl($url);
			  
			$response_context = $arr['results'][0]['location']['name'].':'.$arr['results'][0]['now']['text']."\n气温:".$arr['results'][0]['now']['temperature']."摄氏度\n最后更新时间:".$arr['results'][0]['last_update'];
		}else{
			$response_context = '输入[北京]获取北京当前天气预报';
			// 可以 通过 别的应用 响应 给用户	
		}

		// 将 处理 好的 响应 数据 回复 给用户
		$m_index->responseText($postObj,$response_context);
	}
	private function _doImage($postObj)
	{
		$m_index = new IndexModel();
		$content = '你所上传的图片的Media_ID:' . $postObj->MediaId;
		$m_index->responseText($postObj,$content);
	}
	private function _doLocation($postObj)
	{
	   $url = "http://api.map.baidu.com/place/v2/search?query=%s&location=%s&radius=%s&output=%s&ak=%s";
	   $query = '银行';
	   $location = $postObj->Location_X.','.$postObj->Location_Y;
	   $redius = 2000;
	   $ouput = 'json';
	   $ak = 'SNXG6l6wxdpqAWVUbx9oAMcrPhUXXsee';
	   $url = sprintf($url,unlencode($query), $location,$redius,$ouput,$ak);
	   $content = $this->http_curl($url,$type='get',$data='',$ssl=false);
	   $content_obj = json_decode($content);
	   file_put_contents('./lc', $content_obj);
	   $con_list = array();
	   foreach ($content_obj->results as $res){
	       $r['name'] = $res->name;  
	       $r['address'] = $res->address;
	       $con_list = implode('-', $r);
	   }
	   $m_index = new IndexModel();
	   $content = implode("\n", $con_list);
	   
	  $m_index->responseText($postObj,$content);
	}

	/*
		用于  第一次 验证 url 合法性
	 */
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }	
	public function checkSignature()
	{
        // you must define TOKEN by yourself
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$token = 'weixin';
		$tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}

	/*
		获取 access_token
		str $token_file 存储 token 的临时文件
	 */
	public function getAccessTokenAction()
	{
		// 因为 token 是有 有效期的 所以 要考虑过期问题
		// $life_time = 7200; // 有效期
		// if(file_exists($token_file) && filemtime($token_file)>time()-$life_time){
		// 	// 存在 并且 有效
		// 	return file_get_contents($token_file);
		// }
		if(session('access_token') && session('expire_time')>time()){
			// 存在 并且 有效
			return( session('access_token'));
			
		}
		
		// 目标 url
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".self::APPID."&secret=".self::APPSECRET;
		//向 该 url ，， 发送 get 请求
		$result = $this->http_curl($url);

		if(!$result){
			return false;
		}
		$access_token = $result['access_token'];
		session('access_token',$access_token);
		session('expire_time',time()+7100);
		return( $access_token);
		// die;
		
	}
	/*
		获取 ticket
		有效期  1800 秒
		type  qr 码 类型
		expire  临时 qrcode  有效期  
		return  str  ticket
	 */
	public function getQRCodeTicket($content, $type=2, $expire=604800)
	{
		$access_token = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
		
		$type_list = array(
			self::QRCODE_TYPE_TEMP		=>	'QR_SCENE',
			self::QRCODE_TYPE_LIMIT		=>	'QR_LIMIT_SCENE',
			self::QRCODE_TYPE_LIMIT_STR	=>	'QR_LIMIT_STR_SCENE',
			);
		$action_name = $type_list[$type];
		switch ($type) {
			case self::QRCODE_TYPE_TEMP:
				# code...
				$data_arr['expire_seconds'] = $expire;
				$data_arr['action_name'] = $action_name;
				$data_arr['action_info']['scene']['scene_id'] = $content;

				break;
			case self::QRCODE_TYPE_LIMIT:
			case self::QRCODE_TYPE_LIMIT_STR:
				# code...
				$data_arr['action_name'] = $action_name;
				$data_arr['action_info']['scene']['scene_id'] = $content;
				
				break;
			
			default:
				# code...
				break;
		}
		$data = json_encode($data_arr);

		// $data = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": "'.$content.'"}}}';

		$result = $this->_requestPost($url, $data);

		if(!$result){
			return false;
		}
		// 及时 处理 响应 的数据  ticket 有效期  短
		$result_obj = json_decode($result);

		// var_dump($result);

		return $result_obj->ticket;
	}
	/*
		获取 qr  码
		$file  把 qr 码 存储 到文件
		type  qr 码 类型
		expire  临时 qrcode  有效期  
		return  qr 码
	 */
	public function getQRCode($content, $file=null, $type=2, $expire=604800)
	{
		// 获取 ticket
		$ticket = $this->getQRCodeTicket($content, $type=2, $expire=604800);
		$url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=$ticket";

		// 此时  result 就是图像
		$result = $this->_requestGet($url);
		if($file){
			file_put_contents($file,$result);
		}else{
			header('Content-Type: image/jpeg');
			echo $result;
		}

	}

	/**
	 * http_curl 发出curl请求
	 * @param  [type]  $url  [默认为get请求]
	 * @param  string  $type [默认为get请求]
	 * @param  string  $data [默认为空]
	 * @param  boolean $ssl  [默认为https网站]
	 * @return [type]        [默认回复为json格式数据]
	 */
	public function http_curl($url,$type='get',$data=[],$ssl=true)
	{
		// 使用 curl 发出 请求
		$curl = curl_init(); //  初始化
		// //设置 curl  选项
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		if($ssl){
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);			
		}
		if($type=='post')
		{
			//  是否为 post 请求
		curl_setopt($curl, CURLOPT_POST, true);
		// 处理 请求数据
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		$response = curl_exec($curl);
		
		if(curl_errno($curl))
		{
			//请求成功时，curl_errno($curl)为0
			return curl_errno($curl);
		}
		curl_close($curl);
		$response=json_decode($response,true);
		return $response;
	}
	/**
	 * 自定义菜单
	 * @return [type] [description]
	 */
	public function definedItemAction()
	{
		header('Content-type:text/html;charset=utf-8');
		$access_token = $this->getAccessTokenAction();
		$url ="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
		 // https://api.weixin.qq.com/cgi-bin/menu/create?access_token=ACCESS_TOKEN 

		// var_dump($url);
		// die;
		$postArr=array(
			'button'=>array(
			array(
				'name'=>urlencode('发图扫码'),
				'sub_button'=>array(
					array('name'=>urlencode('拍照或者相册发图'),
						'type'=>'pic_photo_or_album',
						'key'=>'tu1'),
					array('name'=>urlencode('扫码带提示'),
						'type'=>'scancode_waitmsg',
						'key'=>'tu2'),
					array('name'=>urlencode('扫码推事件'),
						'type'=>'scancode_push',
						'key'=>'tu3'),
					),
				),
			array(
				'name'=>urlencode('娱乐'),
				'sub_button'=>array(
					array('name'=>urlencode('天气'),
						'type'=>'click',
						'key'=>'beijing'),
					array('name'=>urlencode('百度首页'),
						'type'=>'view',
						'url'=>'http://www.baidu.com'),
					array('name'=>urlencode('发送位置'),
						'type'=>'location_select',
						'key'=>'location'),
					),
			),//第二个一级菜单
			array('name'=>urlencode('我的商城'),
				'type'=>'view',
				'url'=>'http://www.okayboy.tk/buyplus')//第三个一级菜单
			),
			);
		$postJson=urldecode(json_encode($postArr));
		// echo $postJson;
		// die;
		$res = $this->http_curl($url,$type='post',$data=$postJson);
		// var_dump($res);
		// echo '123';
		// die;

	}
	/**
	 * 群发接口
	 * @return [type] [description]
	 */
	public function sendMsgAction()
	{
		// echo 'abc';
		// die;
		header('Content-type:text/html;charset=utf-8');
		// 1：获取全局access_token
		 echo $access_token = $this->getAccessTokenAction();
		// var_dump($access_token);
		// die;
		// http请求方式: POST
	 $url = "https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token=".$access_token;

		// 2：组装群发接口数据array
		// 文本格式
		// {     
  //   "touser":"OPENID",
  //   "text":{           
  //          "content":"CONTENT"            
  //          },     
  //   "msgtype":"text"
		// }


		$contents=array(
				'touser'=>'ogTSSwQPsN25lXWIZv3fFWsEvVY0',
				'test'=>array('content'=>'hellokang,what is your name'),
				'msgtype'=>'text'//消息类型
			);
		// 3：将array转换成json格式
		$postJson = json_encode($contents,true);
		// var_dump($postJson);
		// die;
		// 4调用curl
		$res = $this->http_curl($url,$type='post',$data=$postJson);
		var_dump($res);
		
		// 图文消息格式
		// {
		//    "touser":"OPENID", 
		//    "mpnews":{              
		//             "media_id":"123dsdajkasd231jhksad"               
		//              },
		//    "msgtype":"mpnews" 
		// }
	}
}

