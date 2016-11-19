<?php 
/*
    微信 公众 平台 开发
 */
class WeChat
{
	private $_appid;
	private $_appsecret;
	private $_token; // 公众平台 请求 需要的参数

	// 表示 qrcode 类型
	const QRCODE_TYPE_TEMP = 1;
	const QRCODE_TYPE_LIMIT = 2;
	const QRCODE_TYPE_LIMIT_STR = 3;

	public function __construct($id, $secret, $token)
	{
		$this->_appid = $id;
		$this->_appsecret = $secret;
		$this->_token = $token;
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
				$event = $postObj->Event;
				if($event == 'subscribe'){
					$this->_doSubscribe($postObj);
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
		// 利用 消息发送 ， 完成 向 关注 用户 打招呼 功能
		$fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $keyword = trim($postObj->Content);
        $time = time();
		$textTpl = "<xml>
				<ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[%s]]></MsgType>
				<Content><![CDATA[%s]]></Content>
				<FuncFlag>0</FuncFlag>
				</xml>";
		$contentStr = '感谢你的关注，会向你发送优惠信息';
		$msgType = "text";
		$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
        die($resultStr);
	}
	private function _doText($postObj)
	{
		// 获取 用户 输入 的
		$content = $postObj->Content;

		//对内容进行判断
		if('?' == $content){
			// 显示帮助信息
			$response_context = '输入对应序号或名称，获取相应资源'."\n".'[1]PHP'."\n".'[2]Java'."\n".'[3]C++';

		}else if('1' == strtolower($content) || 'php' == strtolower($content)){
			$response_context = 'PHP工程师培训:'."\n".'http://php.itcast.cn/';
		}else if('2' == strtolower($content) || 'java' == strtolower($content)){
			$response_context = 'java工程师培训:'."\n".'http://java.itcast.cn/';
		}else if('3' == strtolower($content) || 'c++' == strtolower($content)){
			$response_context = 'c++工程师培训:'."\n".'http://c.itcast.cn/';
		}else{
			$response_context = '输入对应序号或名称，获取相应资源'."\n".'[1]PHP'."\n".'[2]Java'."\n".'[3]C++';
			// 可以 通过 别的应用 响应 给用户
// 			$url = 'http://www.xiaohuangji.com/ajax.php';
// 			$data['para'] = $content;
// 			$response_context = $this->_requestPost($url,$data,false);
		}

		// 将 处理 好的 响应 数据 回复 给用户
		$this->_msgText($postObj->FromUserName, $postObj->ToUserName, $response_context);
	}
	private function _doImage($postObj)
	{
		$content = '你所上传的图片的Media_ID:' . $postObj->MediaId;
		$this->_msgText($postObj->FromUserName, $postObj->ToUserName, $content);
	}
	private function _doLocation($postObj)
	{
// 	    $content = '你的坐标是 ：经度'.$postObj->Location_Y.'，维度：'.$postObj->Location_X."\n".'你的位置为：'.$postObj->Label;
// 	    $this->_msgText($postObj->FromUserName, $postObj->ToUserName, $content);
	   $url = "http://api.map.baidu.com/place/v2/search?query=%s&location=%s&radius=%s&output=%s&ak=%s";
	   $query = '银行';
	   $location = $postObj->Location_X.'，'.$postObj->Location_Y;
	   $redius = 2000;
	   $ouput = 'json';
	   $ak = 'SNXG6l6wxdpqAWVUbx9oAMcrPhUXXsee';
	   $url = sprintf($url,unlencode($query), $location,$redius,$ouput,$ak);
	   $content = $this->_requestGet($url,false);
	   $content_obj = json_decode($content);
	   file_put_contents('./lc', $content_obj);
	   $con_list = array();
	   foreach ($content_obj->results as $res){
	       $r['name'] = $res->name;  
	       $r['address'] = $res->address;
	       $con_list = implode('-', $r);
	   }
	   $content = implode("\n", $con_list);
	   
	   $this->_msgText($postObj->FromUserName, $postObj->ToUserName, $content);
	}
	public function _msgText($FromUserName,$ToUserName,$response_context)
	{
		// 利用 消息发送 ， 完成 向 关注 用户 打招呼 功能
		$fromUsername = $FromUserName;
        $toUsername = $ToUserName;
        $time = time();
        $textTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Content><![CDATA[%s]]></Content>
					<FuncFlag>0</FuncFlag>
					</xml>";     
  		$msgType = "text";
    	$contentStr = $response_context;
    	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
    	echo $resultStr;
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
	private function checkSignature()
	{
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$token = $this->_token;
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
	public function getAccessToken($token_file='./access_token')
	{
		// 因为 token 是有 有效期的 所以 要考虑过期问题
		$life_time = 7200; // 有效期
		if(file_exists($token_file) && filemtime($token_file)>time()-$life_time){
			// 存在 并且 有效
			return file_get_contents($token_file);
		}
		
		// 目标 url
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->_appid}&secret={$this->_appsecret}";
		//向 该 url ，， 发送 get 请求
		$result = $this->_requestGet($url);

		if(!$result){
			return false;
		}
		//  响应 成功 ，，  json 格式 结果 要使用 json_decode 解码
		$result_obj = json_decode($result);

		// 把获取 的 token 写入文件
		file_put_contents($token_file,$result_obj->access_token);

		return $result_obj->access_token;
		
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
	private function _requestPost($url, $data, $ssl=true)
	{
		// 使用 curl 发出 请求
		$curl = curl_init(); //  初始化
		//设置 curl  选项
		curl_setopt($curl,CURLOPT_URL,$url); // url
		$user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:47.0) Gecko/20100101 Firefox/47.0';

		// user_agent ,请求代理
		curl_setopt($curl,CURLOPT_USERAGENT,$user_agent);
		// referer , 请求来源
		curl_setopt($curl, CURLOPT_AUTOREFERER, true); 
		// ssl 相关
		if($ssl){
			// 禁止 curl 从 服务器 验证
			curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
			//检查 服务器 ssl 证书 中 是否 存在 公共 名（common name）
			@curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, false);
		}
		// 处理 post 相关
		//  是否为 post 请求
		curl_setopt($curl, CURLOPT_POST, true);
		// 处理 请求数据
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		// 是否 处理 响应头
		curl_setopt($curl, CURLOPT_HEADER, false);
		// 是否 返回 响应结果
		curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
		//发出请求
		$response = curl_exec($curl);

		if($response === false){
			// 打印  错误 信息
			echo '<br>',curl_error($curl),'<br>';
			return false;
		}
		return $response;
	}
	/*
		发送 get 请求 的方法
		str  $url  Url
		bool  $ssl  是否 是 https 协议
		return  响应主体   str 类型
	 */
	private function _requestGet($url,$ssl=true)
	{
		// 使用 curl 发出 请求
		$curl = curl_init(); //  初始化

		//设置 curl  选项
		curl_setopt($curl,CURLOPT_URL,$url); // url
		$user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:47.0) Gecko/20100101 Firefox/47.0';

		// user_agent ,请求代理
		curl_setopt($curl,CURLOPT_USERAGENT,$user_agent);
		// referer , 请求来源
		curl_setopt($curl, CURLOPT_AUTOREFERER, true); 

		// ssl 相关
		if($ssl){
			// 禁止 curl 从 服务器 验证
			curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);

			//检查 服务器 ssl 证书 中 是否 存在 公共 名（common name）
			@curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 1);
		}
		// 是否 处理 响应头
		curl_setopt($curl, CURLOPT_HEADER, false);
		// 是否 返回 响应结果
		curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);

		//发出请求
		$response = curl_exec($curl);
		if($response === false){
			// 打印  错误 信息
			echo '<br>',curl_error($curl),'<br>';
			return false;
		}
		return $response;
	}
}