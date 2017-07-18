<?php
namespace app\home\model;
use think\Model;
/**
* 
*/
class Banner extends Model{
	public function getBannerlist()
	{
		$result=$this->select();
		return $result;
	}
}
