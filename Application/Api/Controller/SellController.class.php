<?php
namespace Api\Controller;
use Think\Controller;
class SellController extends Controller {
    public function index(){
        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
    }
    public function add(){
    	$data['proid']=I("request.code");
    	if(empty($data['proid'])){
    		$sul['status']=4;
    		$this->ajaxreturn($sul);
    		exit();
    	}
    	$data['qty']=I("request.number",1);
    	$data['createtime']=time();
    	$data['userid']=1;
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
}