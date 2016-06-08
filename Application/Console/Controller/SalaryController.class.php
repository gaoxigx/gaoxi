<?php 
namespace Console\Controller;
use Think\Controller;
class SalaryController extends CommonController{
	public function index(){
		$staff=D('staff')->select();
		$this->assign('list',$staff);
		$this->display();
	}
}
?>