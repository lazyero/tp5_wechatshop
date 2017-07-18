<?php
namespace app\admin\model;
use think\Model;
Class admin extends Model{
	public function doLogin($name,$password)
	{
		$result=$this->where('admin_name',$name)->where('admin_pwd',$password)->find();
		return $result;
	}
}