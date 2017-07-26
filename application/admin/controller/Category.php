<?php
namespace app\admin\controller;
use app\common\controller\Admin;
/**
* 
*/
class Category extends Admin
{

	public function categoryList()
	{
		return $this->fetch();
	}
	public function categorys()
	{	
		$category=model("Category");
		$categorys=$category->getCategorys();
		echo json_encode($categorys);
	}
	public function categoryAdd()
	{
    $category=model('Category');
    if($_POST){
        $name=input('post.name');
        $pid=input('post.pname');//父id
        
        $categorys=$category->getCategory($pid);//获取父类分类等级
        $level=$categorys[0]['level'];
        if ($level>2) {
             $this->error('父级分类不允许为3级分类！');
        }
        $datas['cat_name']=$name;
        $cat_id=$category->getCategoryAdd($datas);// 上传新类并获取新增分类的ID
        if ($pid==0) {
            $data["path"]="0,".$cat_id; //构造新类路径
        }else{
            $path=$categorys[0]['path'];
            $data["path"]=$path.",".$cat_id;//构造新类路径
        }
        $data['parent_id']=$pid;//父类ID 
        $data['level']=$level+1;//新增分类等级
        $res=$category->getUpdate($cat_id,$data);//更新新类数据
        if($res){ 
            $this->success('添加成功！');
        }else{
            $this->error('添加失败！');
        }   
    }else{
      $result=$category->getCategorys();
      foreach ($result as $key => $value) {
        $result[$key]['cat_name']=str_repeat("&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp;", $value['level']).$value["cat_name"];
      }

      $this->assign('result',$result);
      return  $this->fetch();
    }
	}

  public function categoryDel()
  {
    $cat_id=$_POST['cat_id'];
    $category=model("Category");
    $product=model("product");
    $category_res=$category->getCategory($cat_id);
    $product_res=$product->getProducts($cat_id);
    if ($category_res) {
            $this->error("该分类下存在子分类，不允许删除！");
        }elseif ($product_res) {
            $this->error("该分类下存在商品未删除，不允许删除！");
        }else{
             $res=$category->getDel($cat_id);
             if ($res) {
                $this->success('删除成功！');
             }else{
               $this->error('删除失败！');
             }
        }
  }
  public function categoryUpdate()
  {
    $category=model('Category'); 
    if ($_POST) {
        $id=I("post.lei_id");
        $data['name']=I("post.name");
        $data['orders']=I("post.orders");
        $res=$kqy->where("id='$id'")->save($data);
        if($res){   
                $this->success('修改成功！');
          }else{  
                $this->error('修改失败！');
          }  
    }else{
        $cat_id = $_GET['cat_id'];
        $category_res=$category->getCategory($cat_id); 
        echo json_encode($category_res);

    }
  }
	

}
