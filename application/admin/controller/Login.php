<?php 
namespace app\admin\controller;
use think\Controller;
/**
* 
*/
class Login extends Controller
{
	
	public function index()
	{	
		if($_POST){

			$name=input('post.name');
			$password=md5(trim(input('post.password')));
			$captcha=input('post.captcha');
			if(!captcha_check($captcha)){
				$this->error('验证码错误！');
			};
			if (model('Admin')->doLogin($name,$password)) {
				session('admin_name',$name);
				$this->success('登陆成功','Index/index');
			}else{
				$this->error('账号或密码错误！');
			}
				
		}
		return $this->fetch();
	
	}

	public function logout()
	{
		session("admin_name",null);
		$this->redirect('Index/index');
	}

}