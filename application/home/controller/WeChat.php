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
		$appid=APPID;
		$secret=SECRET;
		//access_token暂存地址在和入口文件同级目录下
		$token_file='access_token.txt';
		//判断access_token文件是否已经过期，未过期直接返回文件内容
		$life_time=7200;
		if (file_exists($token_file) && time()-filemtime($token_file)<$life_time) {
			return file_get_contents($token_file);
		}

		//若是文件过期根据URL重新获取access_token
		$url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$secret";
		//发送GET请求
		$result=$this->request($url);
		if (!$result) {
			return false;
		}
		//获取响应返回结果
		$arr=json_decode($result,true);
		//将access_token保存在文件中
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
		// access_token	调用接口凭证,这里通过code换取的是一个特殊的网页授权access_token,与基础支持中的access_token（该access_token用于调用其他接口）不同
		// openid	普通用户的标识，对当前公众号唯一
		//避免重复发送授权链接。已经获取用户信息，刷新页面，获得重新返回该页面，导致相同的授权链接再次请求，产生错误。
		//把获取的用户信息保存，每次发送请求前，检查是否已经获取，如果已经获取用户信息，就不再请求。
		//提示errcode":40163,"errmsg":"code been used。说明code被使用过一次了，code只能用一次。
		//判断是否发送获取code连接
		if (input('code')) {
			//获取到code判断code是否已经被使用过
			if (!cache('code_array')){
				//通过code获取access_token
            	$code = input('code');
            	$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
            	$result=$this->request($url);
				if (!$result) {
					return false;
				}
				$arr=json_decode($result,true);
				cache('code_array',$arr,7200);
			
			}
			//获取access_token，openid
			$code_array=cache('code_array');
			$access_token=$code_array['access_token'];
			$openid=$code_array['openid'];
			$url="https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
			$result=$this->request($url);
			if (!$result) {
				return false;
			}
			$arr=json_decode($result,true);			
            return $arr;
			
		}else{
			//发送获取code连接
			$url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=http://demo57.i-120.cn&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
			//发送GET请求
			header("Location: ".$url);
			exit();//必要
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