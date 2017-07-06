<?php
namespace app\home\controller;
use think\Controller;
class Classify extends Controller {

	

//一级
   public function classify(){
         $category=model("category");
         $tree=$category->get_category_list();
   		$this->assign('list',$tree);
   		return $this->fetch();
   }




//二级
   public function classify_second(){
   	$cid=$_GET['cid'];
   	$category=model("category");
      $product=model('product');
      $list=$category->get_category_second_list($cid);

   	foreach ($list as   $k=>  &$val) {
   		$cat_id=$val['cat_id'];
   		$res=$product->get_product($cat_id);
   		$val['product']=$res;
   		}
   	$this->assign('cid',$cid);
   	$this->assign('list',$list);
   	return $this->fetch();
   }


}