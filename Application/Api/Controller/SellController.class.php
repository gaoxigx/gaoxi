<?php
namespace Api\Controller;
use Think\Controller;
class SellController extends Controller {
    public function index(){
        $list=M("stocklist")->alias("st")->join("__GOODS__ as gs on st.proid=gs.coding")->select();
        $this->ajaxreturn($list);

    }
    public function add(){
    	$data['proid']=I("request.code");
    	if(empty($data['proid'])){
    		$sul['status']=4;
    		$this->ajaxreturn($sul);
    		exit();
    	}
    	$codeing=M("stocklist");

    	$sul=M("goods")->where('coding='.$data['proid'])->find();    
    	if(!$sul){
    		$sul['status']=5;
    		$this->ajaxreturn($sul);
    		exit();
    	}
    	$data['qty']=I("request.number",1);
    	$data['createtime']=time();
    	$data['userid']=1;
    	
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
    public function info(){
    	
    }
}