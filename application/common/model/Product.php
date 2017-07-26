<?php
namespace app\common\model;
use think\Model;
/**
* 
*/
class Product extends Model
{
	public function getProducts($cat_id)
	{
		$product=$this->where('cat_id',$cat_id)->find();
      	return $product;
	}
	
}