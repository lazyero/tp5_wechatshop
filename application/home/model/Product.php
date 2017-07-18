<?php
namespace app\home\model;
use think\Model;
/**
* 
*/
class Product extends Model{
	public function getProductList()
	{
		$result=$this->select();
		return $result;
	}
	public function getProduct($goods_id)
	{
		$result=$this->where('goods_id',$goods_id)->select();
		return $result;
	}
	
}
