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
    public function captcha() {
        ob_clean();
        $Verify = new \Think\Verify();
        $Verify->imageW = 150;
        $Verify->imageH = 40;
        $Verify->fontSize = 18;
        $Verify->useNoise = tuer;
        $Verify->entry();        
    }

        
	// 前端验证码较验
//	public function chk_code($code) {
//		$verify = new \Think\Verify ();
//		$res = $verify->check ( $code );
//		echo $res;
//	}
//        
        

    
    //登录验证
        
    public function dologin()
    {
        if (!IS_POST) { 
            $this->error('请重新提交数据');
            exit();
        }
            
        //校对验证码
        $verify = new \Think\Verify();    
        if(!$verify->check($_POST['cpatcha'])){ 
            $this->error('验证码错误');
            exit;  
        }
        
        if(!empty($_POST)){
            $model = D('controller');//实例化一个(对象),每个对象对应着一张数据表
            $username = I('post.username'); 
            $password = I('post.password');
            
            //验证用户名密码 (没有数据的时候:select返回空数组,find返回null);
            $admin_list = $model->field('username,id')->where("accounts = '$username' and  password = '".md5($password)."'")->find();    

            if (!empty($admin_list)) {            
                session ( 'userid', $admin_list ['id'] );
                session ( 'username', $username );
                session ( 'roleid', $admin_list ['roleid'] );
                 
                //记住密码
                if (isset($_POST['remember'])) {
                    $remeber = json_encode([
                        'username' => $username,
                        'password' => $this->authcode($_POST['password'], 'ENCODE'),
                    ]);
                    cookie('remember', $remeber);
                }                      
                //写入session
                session('username', $admin_list);            
      
                //更新登录信息
                $update = [
                   'id'=>$admin['id'],
                   'last_login_time'=>time(),
                   'last_login_ip'=>  get_client_ip(),
                ];        
                $model->save($update);          

                $this->success('登录成功',  U('/Console/Index/main'));
                exit();                  
            } 


            $this->error('用户名或密码不正确！');
            exit();
            
        } else {
                //显示模板
                $data = [];
                if ($r = cookie('remember')) {
                    $r = json_decode($r, true);
                    $rememberUsername = $r['username'];
                    $rememberPassword = $this->authcode($r['password']);

                    $data['username'] = $rememberUsername;
                    $data['password'] = $rememberPassword;
                }
                $this->assign($data);
                $this->display();
        }
         
    }       
        
        
        
//	// 登陆验证
//	public function dologin() {
//		$data = I ( 'post.' );
//		$user_name = $data ['username'];
//		$password = md5 ( $data ['password'] );
//
//		$table = D ('controller');
//		$result = $table->where ("accounts='" . $user_name . "' and password='" . $password . "'" )->find ();
//
//		// dump($result);
//		// exit;
//		if ($result) {
//			session ( 'userid', $result ['id'] );
//			session ( 'username', $user_name );
//			session ( 'roleid', $result ['roleid'] );
//		$this->success ( '登陆成功！', "/Console/Index/main" );
//
//		}else {
//			$this->success ( '用户名或密码不正确！' );
//		}
//	}


        
        
        
        
        
        // 退出登录
	public function logout() {
		session ('username', null );
		session ('userid', null );
		session ('soleid', null );
		session_destroy (); // 清除服务器的sesion文件
		$this->success ( '退出成功', '/Console/Login/login.html' );
	}
        
    
}
