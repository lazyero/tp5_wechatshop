<?php
namespace app\home\controller;
use think\Controller;
define("APPID", "wxa25838a26483f4b3");
define('SECRET', "cb8f5a6d09fd85c2a955252c7b893706");
class WeChat extends Controller {
	

    /**
	*获取access_token
	*/
	
	
	public function getAccessToken()
	{
		//判断access_token是否已经过期
		
		$appid=APPID;
		$secret=SECRET;
		$token_file='access_token.txt';
		$life_time=30;
		if (file_exists($token_file) && time()-filemtime($token_file)<$life_time) {
			return file_get_contents($token_file);
		}
		//生成URL
		$url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$secret";

	
		//发送GET请求
		$result=$this->request($url);
		if (!$result) {
			return false;
		}
		//获取响应返回结果

    	
    	
		$arr=json_decode($result,true);
		file_put_contents($token_file, $arr['access_token']);
		return $arr['access_token'];

	}

	public function create_menu(){
		$token=$this->getAccessToken();
		// $media_image_id=$this->add_image();
		// $media_voice_id=$this->add_voice();
		// $media_video_id=$this->add_video();
		// var_dump($token);
		$url="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$token";
		$json='{
		     "button":[{
			           "name":"文件",
			           "sub_button":[{	
			               "type":"view",
			               "name":"php",
			               "url":"http://www.runoob.com/php"
			            },
			            {
			               "type":"view",
			               "name":"jquery",
			               "url":"http://www.w3school.com.cn/jquery/"
			            },
			            {
			               "type":"view",
			               "name":"js",
			               "url":"http://www.w3school.com.cn/js/"
			            },
			            {
			               "type":"view",
			               "name":"CI",
			               "url":"http://codeigniter.org.cn/user_guide/"
			            },
			            {
			               "type":"view",
			               "name":"thinkphp",
			               "url":"http://document.thinkphp.cn/manual_3_2.html/"
			            }]
		       },
		       {
			           "name":"工具",
			           "sub_button":[{	
			               "type": "pic_photo_or_album", 
		                    "name": "拍照或者相册发图", 
		                    "key": "rselfmenu_1_1", 
		                    "sub_button":[]
			            },
			            {
			                "name": "发送我的位置", 
				            "type": "location_select", 
				            "key": "rselfmenu_2_0"
			            }]
			    },
		       {
			           "name":"个人中心",
			           "sub_button":[{	
			               "type":"view",
			               "name":"个人中心",
			               "url":"http://demo57.i-120.cn/"
			            }]
			    }';
		$res=$this->request($url,$json);
		 // var_dump($res);exit;
		 $obj=json_decode($res,true);
		 // var_dump($obj);
		 if ($obj) {
		 	return "自定义菜单生成成功";
		 }else{
		 	return "自定义菜单生成失败";
		 }
	}

	//获取用户基本信息
	public function getUser()
	{	
		$appid=APPID;
		$secret=SECRET;

		// access_token	调用接口凭证
		// openid	普通用户的标识，对当前公众号唯一
		// lang	返回国家地区语言版本，zh_CN 简体，zh_TW 繁体，en 英语

		//避免重复发送授权链接。已经获取用户信息，刷新页面，获得重新返回该页面，导致相同的授权链接再次请求，产生错误。把获取的用户信息保存在session中，每次发送请求前，检查是否已经获取，如果已经获取用户信息，就不再请求。提示errcode":40163,"errmsg":"code been used。说明code被使用过一次了，code只能用一次。
		
		if (input('code')) {

			if (session("user_array")) {

				$user_array=session("user_array");
				$access_token=$user_array['access_token'];
				$openid=$user_array['openid'];
				//由于access_token拥有较短的有效期，当access_token超时后，可以使用refresh_token进行刷新，refresh_token有效期为30天，当refresh_token失效之后，需要用户重新授权。
				// $url="https://api.weixin.qq.com/sns/auth?access_token=$access_token&openid=$openid";
				// $result=$this->request($url);
				// 	if (!$result) {
				// 		return false;
				// 	}
				// 	$arr=json_decode($result,true);
				// 	return $arr['errmsg'];
				// 	exit();
				if(!$access_token){
					$url="https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=$appid&grant_type=refresh_token&refresh_token=$refresh_token";
					$result=$this->request($url);
					if (!$result) {
						return false;
					}
					$arr=json_decode($result,true);
					session("user_array",$arr);
				}
				
			}else{
				//通过code获取access_token
            	$code = $_GET['code'];
            	$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";

            	$result=$this->request($url);
            
				if (!$result) {
					return false;
				}
				$arr=json_decode($result,true);
				session("user_array",$arr);
			
			}
			//获取access_token，openid
			$user_array=session("user_array");
			$access_token=$user_array['access_token'];
			$openid=$user_array['openid'];
			$url="https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
			$result=$this->request($url);
				if (!$result) {
					return false;
				}
				$arr=json_decode($result,true);			
            	return $arr;
		}else{
			
			$url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=http://demo57.i-120.cn&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
			//发送GET请求
			 header("Location: ".$url);

			exit();
		 }

	
	}


















	// public function add_image(){
	// 	$redis=new redis();
	// 	$redis->connect('127.0.0.1',6379);
	// 	if ($redis->get('media_image_id')) {
	// 		return $redis->get('media_image_id');
	// 	}
	// 	$token=$this->get_accesstoken();
	// 	$url="https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=".$token."&type=image";
	// 	$jpg=realpath('./image.jpg');
	//  	$data['media']='@'.$jpg;
	//  	// var_dump($data);
	//  	$res=$this->request($url,$data);
	//  	// var_dump($res);
	//  	$obj=json_decode($res,true);
	//  	var_dump($obj);exit;
	//  	$redis->set('media_image_id',$obj['media_id'],7200);
	//  	$media_image_id=$obj['media_id'];
	//  	return $media_image_id;
	// }
	// public function add_voice(){
	// 	$redis=new redis();
	// 	$redis->connect('127.0.0.1',6379);
	// 	if ($redis->get('media_voice_id')) {
	// 		return $redis->get('media_voice_id');
	// 	}
	// 	$token=$this->get_accesstoken();
	// 	$url="https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=".$token."&type=voice";
	// 	$MP3=realpath('./music.mp3');
	//  	$data['media']='@'.$MP3;
	//  	// var_dump($data);
	//  	$res=$this->request($url,$data);
	//  	// var_dump($res);
	//  	$obj=json_decode($res,true);
	//  	$redis->set('media_voice_id',$obj['media_id'],7200);
	//  	// var_dump($obj);
	//  	$media_voice_id= $obj['media_id'];
	//  	return $media_voice_id;
	// }




	// public function add_video(){
	// 	$redis=new redis();
	// 	$redis->connect('127.0.0.1',6379);
	// 	if ($redis->get('media_video_id')) {
	// 		return $redis->get('media_video_id');
	// 	}
	// 	$token=$this->get_accesstoken();
	// 	$url="https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=".$token."&type=video";
	// 	$MP4=realpath('./video.mp4');
	//  	$data['media']='@'.$MP4;
	//  	// var_dump($data);exit;
	//  	$res=$this->request($url,$data);
	//  	var_dump($res);exit;
	//  	$obj=json_decode($res,true);
	//  	// var_dump($obj);exit;
	//  	$redis->set('media_video_id',$obj['media_id'],7200);
	//  	// var_dump($obj);
	//  	$media_video_id= $obj['media_id'];
	//  	return $media_video_id;
	// }


	/*
	*发送GET请求方法
	*@param string $url URL
	*@param bool $ssl  是否为https协议
	*@return string   响应主体内容 
	*/

	private function request($url,$data=null){
		$curl=curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);

        //设定为不验证证书和host
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

        if(!empty($data)){
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        // 将curl_exec()获取的信息以文件流的形式返回，而不是直接输出
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);

        $output=curl_exec($curl);
        if (false===$output) {
        	echo "<br/>",curl_error($curl),"<br/>";
        	return false;
        }
        curl_close($curl);
        return $output;
	}
}