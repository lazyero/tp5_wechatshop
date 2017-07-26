<?php
namespace app\common\model;
use think\Model;
/**
* 
*/
class Category extends Model{

   
   public function getCategorys()  
   {
      $categorys=$this->order('path,cat_id')->select();
      return $categorys;
   }
   public function getCategoryPid($parent_id)
   {
      $category_pid=$this->where('parent_id',$parent_id)->select();
      return $category_pid;
   }
   public function getCategory($cat_id)
   {
      $category=$this->where('cat_id',$cat_id)->select();
      return $category;
   }
   // public function getPath($cat_id)
   // {
   //    $path=$this->where('cat_id',$cat_id)->value('path');
   //    return $path;
   // }
   public function getUpdate($cat_id,$data)
   {
      $res=$this->where('cat_id',$cat_id)->update($data);
      return $res;
   }
   public function getDel($cat_id)
   {
      $delete=$this->where('cat_id',$cat_id)->delete();
      return $delete;
   }
   public function getCategoryAdd($datas)
   {
      $cat_id=$this->save($datas);
      return $cat_id;
   }
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
