<?php
namespace app\home\controller;
use think\Controller;
class User extends Controller {
    public function index(){
        $openid=session("openid");
        $user=model("user");
        $result=$user->getUser($openid);
        $this->assign('result',$result);
        return $this->fetch();
    }

    public function add_address()
    {   
        $useraddress=model("useraddress");
        $data['address_city']=input("post.address_city");
        $data['address_address']=input("post.address_address");
        $data['user_name']=input("post.user_name");
        $data['tel']=input("post.tel");
        $openid=session("openid");
        $result=$useraddress->save_user_address($openid,$data);
        

    }

     
    public function address(){
        $useraddress=model("useraddress");
        $openid=session("openid");
        $result=$useraddress->get_address($openid);
        foreach ($result as $key => $value) {
            $address=$value['address_city'].$value['address_address']."联系方式：".$value['tel']."收件人：".$value['user_name'];
        }
        $this->assign('address',$address);
        return $this->fetch();
    }

    
}