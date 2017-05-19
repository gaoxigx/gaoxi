<?php

namespace Console\Controller;

use Think\Controller;

class LogisticsController extends Controller {
    
    // 物流页面
    public function index() {
        $this->display ();
    }
    
    public function delete($id){
    	if($id){
    		$codeing=M("coding");
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

    public function insert(){
    	$data['code']=I("post.code");
    	$data['number']=I("post.number");
    	$data['createtime']=time();
    	$data['userid']=session("userid");
    	$codeing=M("coding");
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
    
}
