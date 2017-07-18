<?php
namespace app\home\model;
use think\Model;
/**
* 
*/
class Useraddress extends Model{
	public function getUserList()
	{
		$result=$this->select();
		return $result;
	}
	public function saveUserAddress($openid,$data)
	{	

		
		$result=$this->where('openid',$openid)->select();
		if($result){
			//存在则更新用户信息
			$result=$this->where('openid',$openid)->update($data);
			return 1;
		}else{
			//不存在则添加用户信息
			$data['openid']=$openid;	
			$result=$this->insert($data);
		}
		return $result;
	}
	public function getAddress($openid)
	{	
		$result=$this->where('openid',$openid)->select();
		return $result;
	}
}
