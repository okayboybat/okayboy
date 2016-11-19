<?php
namespace Weixin\Controller;
use Think\Controller;
use Weixin\Model\IndexModel;
class IndexController extends Controller 
{
    public function indexAction()
    {
       $timestamp = $_GET['timestamp'];
		$nonce = $_GET['nonce'];
		$token= 'weixin';
		$signature = $_GET['signature'];
		$array = array($timestamp,$nonce,$token);
		$echostr = $_GET['echostr'];
		sort($array);
		//2：将排序后的三个参数拼接后用sha1加密
		$tmpstr = implode('', $array);
		$tmpstr = sha1($tmpstr);
		// 3：将加密后的字符串与signature进行对比，判断请求是否来自微信
		if ($tmpstr==$signature && $echostr)
		{
			//第一次接入weixin时，参数中会有echostr这个参数，以后就不会有了
			echo $echostr;
			exit;
		}else
		{
			//非第一次验证
			$this->responseMsg();
		}
	}

	//接收事件推送并回复
	public function responseMsg()
	{
		//获取到微信推送过来的post数据（XML）数据格式

		$postArr = $GLOBALS['HTTP_RAW_POST_DATA'];

		// 2:处理消息类型格式，并设置回复类型和内容
		//用户关注时，微信推送到我们的服务器的消息类型格式如下：
		// <xml>
		// <ToUserName><![CDATA[toUser]]></ToUserName>
		// <FromUserName><![CDATA[FromUser]]></FromUserName>
		// <CreateTime>123456789</CreateTime>
		// <MsgType><![CDATA[event]]></MsgType>
		// <Event><![CDATA[subscribe]]></Event>
		// </xml>
		// 3将xml格式转换为字符串格式对象
		$postObj = simplexml_load_string($postArr);
		$m_index = new IndexModel();
		// 然后可以得到$postObj->FromUserName;$postObj->ToUserName;$postObj->MsgType
		if(strtolower($postObj->MsgType)=='event')
		{
			//如果是关注的subscribe事件
			if(strtolower($postObj->Event)=='subscribe')
			{
				//回复用户信息
				
				// $time 		= time();
				// $MsgType 	= 'text';
				$content 	="用户ID:$postObj->FromUserName"."---公众号ID：$postObj->ToUserName"."---推送事件类型：$postObj->MsgType"."---事件名称$postObj->Event"; 
				//回复用户的模板如下
				$m_index->responseText($postObj,$content);
			}
		}

		//当用户发送纯文本时，系统会推送如下格式的消息到我们的服务器
		 // <xml>
		 // <ToUserName><![CDATA[toUser]]></ToUserName>
		 // <FromUserName><![CDATA[fromUser]]></FromUserName> 
		 // <CreateTime>1348831860</CreateTime>
		 // <MsgType><![CDATA[text]]></MsgType>
		 // <Content><![CDATA[this is a test]]></Content>
		 // <MsgId>1234567890123456</MsgId>
		 // </xml>

		if(strtolower($postObj->MsgType)=='text' && strtolower(trim($postObj->Content))=='tuwen1')
		{


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
		}else
		{			
			switch (strtolower(trim($postObj->Content)))
			{

				case 'tianna':
					$content ="天呐您好！";					
					break;				
				case 4:
					$content ="<a href='www.baidu.com'>点击我去百度</a>";
					break;
				case '北京':
					$content =$this->curlAction('beijing');
					break;					
				default:
					$content ="暂时无该关键字";
					break;
			}
			$m_index->responseText($postObj,$content);
						
	    }
	}

	public function curlAction($city)
	{
		// $city = I('city');
		// var_dump($city);
		// die;
		// 1:init
		$ch = curl_init();
		$url = 'https://api.thinkpage.cn/v3/weather/now.json?key=7huj0uyepwiolvdn&location='.$city.'&language=zh-Hans&unit=c';
		// 2:设置curl的参数
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		// 3:采集
		$res = curl_exec($ch);
		// 4:关闭
		curl_close($ch);
		// 返回结果
		// 		{
		//   "results": [{
		//   "location": {
		//       "id": "C23NB62W20TF",
		//       "name": "西雅图",
		//       "country": "US",
		//       "timezone": "America/Los_Angeles",
		//       "timezone_offset": "-07:00"
		//   },
		//   "now": {
		//       "text": "多云", //天气现象文字
		//       "code": "4", //天气现象代码
		//       "temperature": "14", //温度，单位为c摄氏度或f华氏度
		//       "feels_like": "14", //体感温度，单位为c摄氏度或f华氏度
		//       "pressure": "1018", //气压，单位为mb百帕或in英寸
		//       "humidity": "76", //相对湿度，0~100，单位为百分比
		//       "visibility": "16.09", //能见度，单位为km公里或mi英里
		//       "wind_direction": "西北", //风向文字
		//       "wind_direction_degree": "340", //风向角度，范围0~360，0为正北，90为正东，180为正南，270为正西
		//       "wind_speed": "8.05", //风速，单位为km/h公里每小时或mph英里每小时
		//       "wind_scale": "2", //风力等级，请参考：http://baike.baidu.com/view/465076.htm
		//       "clouds": "90", //云量，范围0~100，天空被云覆盖的百分比 #目前不支持中国城市#
		//       "dew_point": "-12" //露点温度，请参考：http://baike.baidu.com/view/118348.htm #目前不支持中国城市#
		//   },
		//   "last_update": "2015-09-25T22:45:00-07:00" //数据更新时间（该城市的本地时间）
		//   }]
		// }
		$arr = json_decode($res,true);
		$content = $arr['results'][0]['location']['name'].':'.$arr['results'][0]['now']['text']."\n气温:".$arr['results'][0]['now']['temperature']."摄氏度\n最后更新时间:".$arr['results'][0]['last_update'];
		return $content;
	}

	public function getwxAccessTokenAction()
	{
		// 1:init
		$appid = 'wx1d8764832e6b0c92';
		$appsecret = 'f39eb3330849ae580bcce3b22929165f';
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;

		// 2:设置curl的参数
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		// 3:调用接口
		$output = curl_exec($ch);
		// 4:关闭
		curl_close($ch);
		// var_dump($url);
		if( curl_errno($ch))
		{
			var_dump(curl_errno($ch));
			// echo '123';
		}else
		{
			$arr = json_decode($output,true);
			var_dump($arr);
			// echo '234';
		}

	}	 
}

