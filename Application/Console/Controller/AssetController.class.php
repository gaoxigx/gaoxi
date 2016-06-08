<?php 
namespace Console\Controller;
use Think\Controller;
class AssetController extends CommonController{
	public function index(){
		$list=D('Asset')->select();
		$this->assign('list',$list);
		$this->display();
	}
}
?>