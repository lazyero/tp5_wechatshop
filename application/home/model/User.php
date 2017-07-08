<?php
namespace app\home\model;
use think\Model;
/**
* 
*/
class User extends Model{
	public function get_user_list()
	{
		$result=$this->select();
		return $result;
	}
	public function save_user($arr)
	{	

		$data['nickname']=$arr['nickname'];
        $data['sex']=$arr['sex'];
        $data['headimgurl']=$arr['headimgurl'];
        //查看用户信息是否存在
		$result=$this->where('openid',$arr['openid'])->select();
		if($result){
			//存在则更新用户信息
			$result=$this->where('openid',$arr['openid'])->update($data);
			return 1;
		}else{
			//不存在则添加用户信息
			$data['openid']=$arr['openid'];	
			$data['addtime']=date("Y-m-d H:i:s",time());
			$result=$this->insert($data);
		}
		return $result;
	}
	public function getUser($openid)
	{	
		$result=$this->where('openid',$openid)->select();
		return $result;
	}

	public function update_address($openid,$data)
	{
		$result=$this->where('openid',$openid)->update($data);
		return $result;
	}
}
