<?php
namespace app\home\model;
use think\Model;
/**
* 
*/
class Product extends Model{
	public function get_product_list()
	{
		$result=$this->select();
		return $result;
	}
	public function get_product($id)
	{
		$result=$this->where('goods_id',$id)->select();
		return $result;
	}
}
