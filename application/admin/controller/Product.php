<?php
namespace app\admin\controller;
use think\Controller;
/**
* 
*/
class Index extends Common
{

	public function productList()
	{
		return $this->fetch();
	}
	

}
