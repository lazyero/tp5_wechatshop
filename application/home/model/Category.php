<?php
namespace app\home\model;
use think\Model;
/**
* 
*/
class Category extends Model{
	public function getCategoryList()
	{
		$list=$this->select();
		$tree=$this->tree_list($list);
		return $tree;
	}

	public function getCategorySecondList($cid)
	{
		$list=$this->where('parent_id',$cid)->select();
   		return $list;
	}
	//递归
   private function tree_list(&$list,$parent_id=0){
   	$tree=array();
   	if(!$list){
   		return false;

   	}else {
   		foreach ($list as $value) {
   			if($parent_id==$value['parent_id']){
   				$value['child']=$this->tree_list($list,$value['cat_id']);
   				$tree[]=$value;
   			}
   		}
   		return $tree;
   	}
   }
}
