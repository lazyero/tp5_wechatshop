<?php
namespace app\home\model;
use think\Model;
/**
* 
*/
class Banner extends Model{
	public function get_banner_list()
	{
		$result=$this->select();
		return $result;
	}

}
