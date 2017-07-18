<?php
namespace app\admin\controller;
use think\Controller;
/**
* 
*/
class Common extends Controller
{
	
	function __construct()
	{
		parent:: __construct();
		if(!session("admin_name")){
			$this->error('账号未登陆！','Login/Index');
		}
	}
}