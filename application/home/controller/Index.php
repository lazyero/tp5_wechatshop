<?php
namespace app\home\controller;
use think\Controller;
class Index extends Controller {
	public function index()
	{	
		$product=model("product");
		$banner=model("banner");
		// 微信授权获取用户信息
		if(!session("openid")){
			$user=model("user");
			$wechat=new WeChat();
        	$arr=$wechat->getUser();
        	session("nickname",$arr['nickname']);
        	session("sex",$arr['sex']);
        	session("headimgurl",$arr['headimgurl']);
        	session("openid",$arr['openid']);
        	//更新数据库用户信息
       		$user->save_user($arr);
    	}
        $product_list=$product->get_product_list();
		$banner_list=$banner->get_banner_list();
		$this->assign('product_list',$product_list);
		$this->assign('banner_list',$banner_list);	
		return $this->fetch();	
	}
	public function second()
	{
		$id=$_GET['id'];
		$product=model("product");
		$result=$product->get_product($id);
		$this->assign('result',$result);
		return $this->fetch();
	}
//     public function show_shopping(){
//         if(session('name')){
//             $this->display('shopping');

//         }else{
//             $this->display('/app/login');
//         }
//     }


//     public function show_second(){
//         $goods_id=I('get.gid');
//         $product=M('product');
//         $result=$product->where("goods_id='$goods_id'")->select();
//         $this->assign('result',$result);
//         $this->display('second');

//     }
// public function logout(){
//     session(null);
//     $this->display('/app/login');
// }

//     public function search(){
    
//         $product=M('product');
//         $name=I('post.goods_name');
//         $goods_name['goods_name']=array('like',"%$name%");
//         $result=$product->where($goods_name)->select();
//         // var_dump($result);exit;
//         $this->assign('result',$result);
//         $this->display('search');

//     }

    



}