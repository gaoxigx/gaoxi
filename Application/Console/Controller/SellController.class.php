<?php

namespace Console\Controller;

use Think\Controller;

class SellController extends Controller {
    
    // 物流页面
    public function index() {
    	$sell=D("stocklist");
    	$count = $sell->where($map)->count();// 查询满足要求的总记录数
		
        $Page = new \Think\Page($count,15,$parameter);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show = $Page->show();// 分页显示输出
    	$list=$sell->limit($Page->firstRow.','.$Page->listRows)->select();
    	$this->assign("list",$list);    
        $this->assign('page',$show);// 赋值分页输出
    	$this->display();
    }

    public function insert(){
    	$data['proid']=I("post.code");
    	$data['qty']=I("post.number");
    	$data['createtime']=time();
    	$data['userid']=session("userid");
    	$codeing=M("stocklist");
    	if($data){
    		try{
    			$sul1=$codeing->add($data);
    			if($sul1){
    				$sul['status']=1;
    				$sul['id']=$sul1;
    			}else{
    				$sul['status']=2;
    			}
    			
	    	}catch(\Exception $ex){
	    		$sul['status']=0;
	    	}
    		
    	}else{
    		$sul['status']=3;
    	}
    	$this->ajaxreturn($sul);
    }

     public function delete($id){
    	if($id){
    		$codeing=M("stocklist");
    		try{
    			if($codeing->delete($id)){
    				$sul['status']=1;
    			}else{
    				$sul['status']=2;
    			}
    		}catch(\exception $ex){
    			$sul['status']=0;
    		}
    	}else{
    		$sul['status']=3;
    	}
    	$this->ajaxreturn($sul);    	
    }
}
