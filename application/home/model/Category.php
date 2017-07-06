<?php
namespace app\home\model;
use think\Model;
/**
* 
*/
class category extends Model{
	public function get_category_list()
	{
		$list=$this->select();
		$tree=$this->tree_list($list);
		return $tree;
	}

	public function get_category_second_list($cid)
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
