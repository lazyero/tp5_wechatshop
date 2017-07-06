<?php
namespace App\Controller;
use Think\Controller;
class CartController extends Controller {

    public function show_shopping(){
        if(session('name')){
            $name=session('name');
            $product=M('product');
            $cart=M('cart');
            $result=$product->join('cart on product.goods_id=cart.goods_id')->where(" cart.name='$name'")->select();
            $total=$cart->where("name='$name'")->sum('sprice');
            $count=$cart->where("name='$name'")->count();
            $this->assign('count',$count);
            $this->assign('total',$total);  
            $this->assign('result',$result);
            $this->display('shopping_cart');

        }else{
            $this->display('/app/login');
        }
    }

    public function shopping_cart(){
       
        if(session('name')){
        $data['goods_id']=I('post.gid');
        $goods_id=I('post.gid');
        $data['num']=I('post.num');
        $data['name']=session('name');
        $name=session('name');
        $product=M('product');
        $price=$product->where("goods_id='$goods_id'")->getField('shop_price');
       
        // var_dump($data['sprice']);exit;
        $cart=M('cart');
        $result_num=$cart->where("goods_id='$goods_id' and name='$name' ")->getField('num');
        if($result_num){
            $data['num']=$data['num']+$result_num;
            $data['sprice']=$data['num']*$price;
            // var_dump($data['num']);exit;
            $res=$cart->where("goods_id='$goods_id' and name='$name'")->save($data);
        }else{
            $data['sprice']=$data['num']*$price;
            $res=$cart->add($data);
        }
       
        if($res){

        $result=$product->join('cart on product.goods_id=cart.goods_id')->where(" cart.name='$name'")->select();
        // var_dump($result);exit;
       $total=$cart->where("name='$name'")->sum('sprice');
        $count=$cart->where("name='$name'")->count();
        session('count',$count);
        $this->assign('count',session('count'));
        $this->assign('total',$total);        
        $this->assign('result',$result);
        $this->display('shopping_cart');
        }else{
            $this->display('/app/login');
        }
    }else{
        $this->display('/app/login');
    }     
    }

    public function shoppingcart(){
        $name=session('name');
        $goods_id=I('post.gid');
        $data['num']=I('post.num');
        $product=M('product');
        $price=$product->where("goods_id='$goods_id'")->getField('shop_price');
        $data['sprice']=$data['num']*$price;
        $cart=M('cart');
        
        $res=$cart->where("goods_id='$goods_id' and name='$name'")->save($data);
    }
    public function delete(){
        $cart_id=I('post.id');
        // var_dump($cart_id);exit;
        $cart=M('cart');
        $id=explode(" ",$cart_id);
        foreach ($id as $value) {
         $result=$cart->where("cart_id='$value'")->delete();
        }

       
    }
    public function all_price(){
        $cart_id=I('get.ids');

        // var_dump($cart_id);
        session('cart_id',$cart_id);

        $cart=M('cart');
        $id=explode(" ",$cart_id);
        

        // var_dump($id);exit;
        // $ids['cart_id']=array('in',$id);
        for ($i=0; $i <count($id) ; $i++) { 
           
            $res[$i]=$cart->where("cart_id='$id[$i]'")->sum('sprice');
        }

        $res=array_sum($res);
        // var_dump($res);
        // echo($res);

        echo  $res;

    }
    public function shoppinglist(){
        $all_price=I('post.all_price');
        // var_dump($all_price);exit;
        $name=session('name');
        $address=M('address');
        $product=M('product');
        $cart=M('cart');
        $cart_id=session('cart_id');  
        $cart_ids=explode(" ",$cart_id);
        $id['cart_id']=array('in',$cart_ids);
        $result=$product->join('cart on product.goods_id=cart.goods_id')->where($id)->select();

        $number=$cart->where($id)->sum('num');
        // var_dump($number);exit;
        $result_address=$address->where("name='$name'")->select();
        $this->assign('all_price',$all_price);
        $this->assign('result',$result);
        $this->assign('number',$number);
        $this->assign('result_address',$result_address);
        $this->display('pay');
    }

    public function confirm(){
        $data['address']=I('post.address');
        $data['goods_num']=I('post.goods_num');
        $data['pay_type']=I('post.pay_type');
        $data['shopping_type']=I('post.shopping_type');
        $data['postscript']=I('post.postscript');
        $data['goods_amount']=I('post.goods_amount');
        $data['shipping_fee']=I('post.shipping_fee');
        $data['total']= $data['goods_amount']+  $data['shipping_fee'];
        // var_dump($data);exit;
        $product=M('product');
        $cart=M('cart');
        $cart_id=session('cart_id');  
        $cart_ids=explode(" ",$cart_id);
        $id['cart_id']=array('in',$cart_ids);
        $result=$product->join('cart on product.goods_id=cart.goods_id')->where($id)->select();

        $res=$cart->where($id)->delete();
       


        $this->assign('data',$data);
        $this->assign('result',$result);
        $this->display('confirm');
    }



}