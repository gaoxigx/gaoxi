<?php
namespace Console\Controller;
use Think\Controller;
class IndexController extends CommonController {
    public function index(){
        // $this->show('','utf-8');
        $this->display();
    }
    public function main(){
        // $this->show('','utf-8');
        $this->display();
    }
    public function left(){
        $this->display();
    }

    public function right(){
        $this->display();
    }

    public function top(){
        $name = session('username');
        $this->assign('adminuser',$name);
        $this->display('top');
    }
    
    //私有方法，控制器内部调用;
    private function hello3(){
        echo '这是private方法!';
    }
     
    public function defaultop(){
        $user = M('order_info');
        $list = $user->limit(6)->select();
        var_dump($list); exit();
        $this->assign('list',$list);
        $this->display('default');
    }
    
}