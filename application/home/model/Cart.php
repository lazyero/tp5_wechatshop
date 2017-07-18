<?php
namespace app\home\model;
use think\Model;
/**
* 
*/
class Cart extends Model{
	
	public function getCartlist()
	{
		$result=$this->select();
		return $result;
	}
	public function getCart($openid)
	{
		$result=$this->where('openid',$openid)->select();
		return $result;
	}
	public function addCart($data)
	{
		$result=$this->insert($data);
		return $result;
	}
	public function updateNum($cart_id,$num)
	{
		$result=$this->where('cart_id',$cart_id)->update(['num'=>$num]);
		return $result;
	}
	public function getUserCart($cart_id)
	{
		$result=$this->where('cart_id',$cart_id)->find();
		return $result;
	}
	public function deleteCart($cart_id_array)
	{
		foreach ($cart_id_array as $key => $cart_id) {
			$result=$this->where('cart_id',$cart_id)->delete();
		}
	}

	public function getCartNum($openid)
	{
		$result=$this->where('openid',$openid)->count();
		return $result;
	}
	
}
