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
        $Verify->imageW = 140;
        $Verify->imageH = 38;
        $Verify->fontSize = 20;
        $Verify->useCurve = false;
        $Verify->useNoise = true;
        $Verify->length = 4;
        $Verify->entry();        
    }

        
    // 前端验证码较验
//  public function chk_code($code) {
//      $verify = new \Think\Verify ();
//      $res = $verify->check ( $code );
//      echo $res;
//  }
//        
        

    
    //登录验证
        
    public function dologin()
    {
        if (!IS_POST) {             
              //显示模板           
                if ($r = cookie('remember')) {
                    $r = json_decode($r, true);
                    $rememberUsername = $r['username'];
                    $rememberPassword = $this->authcode($r['password']);

                    $data['username'] = $rememberUsername;
                    $data['password'] = $rememberPassword;
                }
                $this->assign($data);
                $this->display();       
                exit();
        }
            
        //校对验证码
        $verify = new \Think\Verify();    
        if(!$verify->check($_POST['cpatcha'])){ 
            $this->error('验证码错误');
            exit;  
        }
        
  
            $model = D('controller');//实例化一个(对象),每个对象对应着一张数据表
            $StaffUser = D('staff');
            $username = I('post.username'); 
            $password = I('post.password');
            
            //验证用户名密码 (没有数据的时候:select返回空数组,find返回null);
            $admin_list = $model->field('username,id')->where("accounts = '$username' and  password = '".md5($password)."'")->find();  
            if (!empty($admin_list)) {    
                //写入session
                session ( 'userid', $admin_list ['id'] );
                session ( 'username', $username );
                session ( 'roleid', $admin_list ['roleid'] );
               // session('username', $admin_list); 
                $this->success('登录成功',  U('/Console/Index/main'));
                exit(); 
            }                
            $staff_list = $StaffUser->field('username,id,quarters')->where("username = '$username' and  password = '".md5($password)."'")->find(); 
            if (!empty($staff_list)){            
                session ( 'username', $username );                
                session ('roleidstaff', $staff_list ['quarters'] );  
                session($username, $staff_list);  
                $this->success('登录成功',  U('/Console/Index/main'));
                exit();  
            }         
            
            $this->error('用户名或密码不正确！');
    }       
          
        
    // 退出登录
    public function logout() {
            session ('username', null );
            session ('userid', null );
            session ('soleid', null );
            session_destroy (); // 清除服务器的sesion文件
            $this->success ( '退出成功', '/Console/Login/login.html' );
    }

    
}
