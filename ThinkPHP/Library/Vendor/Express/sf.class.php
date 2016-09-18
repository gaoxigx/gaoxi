<?php
///顺丰api接口
class xf {
	public $domain="open-sbox.sf-express.com";
	public $orderurl="";
	public $APPID="00019465";
	public $APPKEY="7ADD3E012E0EDC9E29C56D4337605D18";

   function __construct(){
   		domain(true);
   }
   //设置测试环境
   function domain($test=false){
	   	if($test=false){   		
	   	  	$this->domain="open-sbox.sf-express.com";//沙盒环境
	   	}else{
	   		$this->domain="open-prod.sf-express.com";//生产环境
	   	}
   }

   function 
}

?>