<?php
namespace app\common\controller;

/**
* 
*/
class Admin extends Base
{
	
	function __construct()
	{
		parent:: __construct();
		if(!session("admin_name")){
			$this->error('账号未登陆！','admin/Login/Index');
		}
	}
}