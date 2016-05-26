<?php

namespace Console\Controller;

use Think\Controller;
header("Content-Type: text/html; charset=utf-8");
class LoginController extends Controller {
	
	// 登陆页面
	public function index() {
		$this->display ();
	}
	// 获取验证码
	public function verify() {
		$verify = new \Think\Verify ();
		$res = $verify->entry ();
	}
	// 前端验证码较验
	public function chk_code($code) {
		$verify = new \Think\Verify ();
		$res = $verify->check ( $code );
		echo $res;
	}
	// 登陆验证
	public function dologin() {
		$data = I ( 'post.' );
		$user_name = $data ['username'];
		$password = md5 ( $data ['password'] );

		$table = D ('controller');
		$result = $table->where ("accounts='" . $user_name . "' and password='" . $password . "'" )->find();

		if ($result) {
			session ( 'userid', $result ['id'] );
			session ( 'username', $user_name );
			session ( 'roleid', $result ['roleid'] );
			$this->success ( '登陆成功！', "/Console/Index/main" );
			exit();
		}

		$resultStaff=D('staff')->where("username='".$user_name."' and password='".$password."'")->find();
		if($resultStaff){
			session ( 'userid', $resultStaff ['id'] );
			session ( 'username', $user_name );
			session ( 'roleid', $resultStaff ['section'] );
			$this->success ( '登陆成功！', "/Console/Index/main" );
			exit();
		}

		
		$this->success ( '用户名或密码不正确！' );
		
	}
	// 退出
	public function logout() {
		session ('username', null );
		session ('userid', null );
		session ('soleid', null );
		session_destroy (); // 清除服务器的sesion文件
		$this->success ( '退出成功', '/Console/Login/login.html' );
	}
}
