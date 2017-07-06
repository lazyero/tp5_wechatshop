<?php
namespace app\home\controller;
use think\Controller;
class User extends Controller {
    public function index(){
        $openid=session("openid");
        $user=model("user");
        $result=$user->getUser($openid);
        $this->assign('result',$result);
        return $this->fetch();
    }
     

    public function register(){
    	$data['name']=I('post.name');
        $data['password']=I('post.password');
        $password2=I('post.password2');
        $data['email']=I('post.email');
        $name=I('post.name');

        if($data['password']==$password2){
            $user=M('user');
            $result=$user->where('name="$name"')->find();
            if($result){
                $this->assign('res','账号已存在');
                $this->display('reg');
            }else{
            $res=$user->add($data);
            if($res){
                $result='注册成功';
                $this->assign('res',$result);
                $this->display('login');
            }
        }
        }

    }



    public function login(){
        $name=I('post.name');
        $password=I('post.password');
        $user=M('user');
        $res=$user->where("name='$name'")->where("password='$password'")->find();
        // var_dump($res);exit;
        if($res){
           session('name',$name);
           $session=session('name');
          
           $data=$user->where("name='$name'")->select();
           // var_dump($data);exit;
            $name=session('name');
            $address=M('address');
            $result=$address->where("name='$name'")->limit(1)->order("id desc")->getField('address,saddress');
            // var_dump($result);exit;
            $res_address1=array_keys($result);
            $res_address2=array_values($result);
            $res_address1=$res_address1[0];
            $res_address2=$res_address2[0];
            // var_dump($res_address1);exit;
            $this->assign('res_address1',$res_address1);
            $this->assign('res_address2',$res_address2);
            $this->assign('user',$data);
            $this->assign('name',$session);
            $this->display('setting');
        }else{
              $this->assign('res','账号或密码错误');
               $this->display('login');
        }
    }
    public function test_email(){
        $name=I('post.name');
        $email=I('post.email');
        $user=M('user');
        $res=$user->where("name='$name'AND email='$email'")->find();
        if(res){
            $this->assign('name',$name);
            $this->display('password');
        }else{
            echo "<script>alert('输入信息有误');</script>";
        }
    }
    public function password(){
        $name=I('post.name');
        $data['password']=I('post.password');
        $user=M('user');
        $res=$user->where("name='$name'")->save($data);
        if(res){
            $this->assign('res',"密码修改成功");
            $this->display('login');
        }
    }

    public function upload_tx(){
        $config = array(
            'maxSize'    =>    3145728,
            'rootPath'   =>    './Public/app/touxiang/',
            'savePath'   =>    '',
            'autoSub'    => false,
            'saveName'   =>    array('uniqid',''),
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),


            
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
        
        $this->error($upload->getError());
        }else{// 上传成功 获取上传文件信息
        foreach($info as $file){
        $str1='/app/touxiang/'.$file['savename']; 

        }
        }

        //上传图片
        $model=M('user');
        $info=$upload->upload();
        $data['image']=$str1;
        //图片缩略图
        // $simage= new \Think\Image();
        // $simage->open("./uploads/".$file['savepath'].$file['savename']);

        // $simage->thumb(100,100)->save("./Uploads/touxiang/".$file["savename"]);
        // $data['simage']="/uploads/touxiang/".$file['savepath'].$file['savename'];
        $user=M('user');
        $name=session('name');
        // var_dump($name);exit;
        $res=$user->where("name='$name'")->save($data);
        if($res){
            $name=session('name');
            $address=M('address');
            $result=$address->where("name='$name'")->limit(1)->order("id desc")->getField('address,saddress');
            // var_dump($result);exit;
            $res_address1=array_keys($result);
            $res_address2=array_values($result);
            $res_address1=$res_address1[0];
            $res_address2=$res_address2[0];
            // var_dump($res_address1);exit;
            $this->assign('res_address1',$res_address1);
            $this->assign('res_address2',$res_address2);
            $data_user=$user->where("name='$name'")->select();
            $this->assign('user',$data_user);
            $this->assign('tx',$data['image']);
            $this->display('setting');
        }
    }

    public function logout(){
        // echo "string";exit;
        session(null);
        $this->display('login');
    }
    public function address(){
        $data['name']=session('name');
        $data['sname']=I('sname');
        $data['phone']=I('post.phone');
        $data['code']=I('post.code');
        $data['email']=I('post.email');
        $data['guhua']=I('post.guhua');
        $data['saddress']=I('post.saddress');
        $address=M('address');
        $data['address']=I('post.address');
        $res=$address->add($data);
        if($res){
          $name=session('name');
            $address=M('address');
            $result=$address->where("name='$name'")->limit(1)->order("id desc")->getField('address,saddress');
            // var_dump($result);exit;
            $res_address1=array_keys($result);
            $res_address2=array_values($result);
            $res_address1=$res_address1[0];
            $res_address2=$res_address2[0];
            // var_dump($res_address1);exit;
            $this->assign('res_address1',$res_address1);
            $this->assign('res_address2',$res_address2);
            $this->display('setting');
        }
    }

    public function upload(){
    	$upload= new \Think\Upload();
    	$upload->maxSize=3145728;
    	$upload->exts=array('jpg','png','gif');
    	$upload->rootPath='/Uploads/';
    	$upload->savePath='';
    	$info=$upload->upload();
    }


  

    //创建验证码
    public function create_img(){
    	$arr=array(
    		'fontSize'=>14,
    		'length'=>4,	
    		// 'fontttf'=>'7.ttf',

    		// 'useZh'=>true,
    		'imageW'=>150,
    		'imageH'=>35,
    		// 'expire'=>2000,
    	

    		);
    	$Verify=new \Think\Verify($arr);
    	$Verify->entry();

    }

//显示用户列表
    public function show_table_managed(){
    	$user=M('user');
    	$result=$user->select();

    	$this->assign('result',$result);

    	$this->display('table_managed');
    }


  
    //查看用户信息
  function show_user(){
  	$id=I('post.id');
  	$user=M('user');
  	$res=$user->where("id='$id'")->select();
  	// echo  json_encode($res);
  	echo $res;
  }


    //删除用户
    public function user_delete(){
    	// $id=$_POST['id'];
    	$id=I('post.id');
    	$user=M('user');
    	$result=$user->where("id='$id'")->delete();
    	echo  $result;
    }
    //批量删除用户
    public function delete_all(){

    	$arry=I('post.arry');

    	$user=M('user');
    	$array=explode(',', $arry);
    	
    	foreach ($array as  $id) {
    		$result=$user->where("id='$id'")->delete();
    	}
    	echo  $result;
    }

    public function pdf(){

    	$pdf=$_POST['pdf'];
    	// echo "<script>alert('wwwwwwwwwww');</script>";
    	pdf($pdf);
    }
    public function qrcode(){
    	$logo=$_POST['logo'];
    	$url=I('post.qrcode');
    	qrcode($url);
    }



}