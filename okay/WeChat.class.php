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
	/*
		用于  第一次 验证 url 合法性
	 */
	public function firstValid()
	{
		// 检验 签名 的合法 性
		if($this->_checkSignature()){
			// 签名合法 告诉 微信 公众平台 服务器
			echo $_GET['echostr'];
		}
	}
	/*
		验证 签名
		return  bool
	 */
	private function _checkSignature()
	{
		//  获得 由微信 公众 平台 请求 的验证 数据
		$signature = $_GET['signature'];
		$timestamp = $_GET['timestamp'];
		$nonce = $_GET['nonce'];
		// 将 时间戳 ，随机字符串， token 按照 字母顺序 排序 连接
		$tmp_arr = array($this->_token,$timestamp,$nonce);
		sort($tmp_arr,SORT_STRING); // 字典 顺序
		$tmp_str = implode($tmp_arr);  // 连接
		$tmp_str = shal($tmp_str); // shal 签名

		return true;
		if($signature == $tmp_str){
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
		// 处理 响应 的数据  ticket 有效期  短
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