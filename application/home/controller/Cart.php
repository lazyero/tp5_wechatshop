<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
class Cart extends Controller {

    public function list_cart()
    {
        $openid=session("openid");
        $cartlist=Db::table('product')
                    ->alias('p')
                    ->join('cart c','p.goods_id = c.goods_id')
                    ->order('c.cart_id desc')
                    ->select();
        $this->assign('cartlist',$cartlist);
        return $this->fetch();
    }
    public function add_cart(){
        $data['goods_id']=input('post.gid');
        $data['num']=input('post.num');
        $data['openid']=session('openid');
        $cart=model('cart');
        $result=$cart->addCart($data);
        if ($result) {
             $this->redirect("Cart/list_cart");
        }
    }
    public function deleteCart(){
        $cart_ids=I('post.ids');
        $cart=model("cart");
        $cart_id_array=array_filter(explode(';',$cart_ids));
        $result=$cart->deleteCart($cart_id_array);
        echo $result;
    }
    public function sumOne()
    {   
        $cart_id=input("post.cart_id");
        $goods_id=input("post.goods_id");
        $num=input("post.num");
        $cart=model("cart");
        $product=model("product");
        $cart->updateNum($cart_id,$num);
        $result=$product->getProduct($goods_id);
        echo $result[0]['shop_price'];
    }
    public function sumAll(){
        $cart_ids=input('post.ids');
        $cart=model("cart");
        $product=model("product");
        $cart_id_array=array_filter(explode(';',$cart_ids));


        for ($i=0; $i <count($cart_id_array); $i++) {           
            $cart_array[]=$cart->getUserCart($cart_id_array[$i]);
            $product_array=$product->getProduct($cart_array[$i]['goods_id']);
            $shop_price=$product_array[0]['shop_price'];
            $sum[]=$cart_array[$i]['num']*$shop_price;
        }
        $res=array_sum($sum);
        echo  json_encode($res);
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