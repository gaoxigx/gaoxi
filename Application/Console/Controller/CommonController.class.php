<?php
namespace Console\Controller;
use Think\Controller;
class CommonController extends Controller{
	public function __construct(){
		header("Content-Type: text/html; charset=utf-8");
		parent::__construct();
		if(session('?username')){
		$this->assign('leftmenu',CONTROLLER_NAME);
		$this->assign('lml',ACTION_NAME);
		}else{
			$this->error('您还没有登录！','/Console/Login/login.html');
			
		}
	}
	public function tip($result,$title){
		if ($result) {
			$this->success($title.'成功！');
		}else{
			$this->error($title.'失败！');
		}
	}
	
}