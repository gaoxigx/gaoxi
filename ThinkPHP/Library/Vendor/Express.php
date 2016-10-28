<?php
///顺丰api接口
class Express {
	public $domainat="open-sbox.sf-express.com";
	public $orderurl="";
	public $appid="00019465";
	public $appkey="7ADD3E012E0EDC9E29C56D4337605D18";

   function __construct(){
   		$this->domain(true);
   }
   //设置测试环境
   public function domain($test=false){
	   	if($test==false){   		
	   	  	$this->domainat="open-sbox.sf-express.com";//沙盒环境
	   	}else{
	   		$this->domainat="open-prod.sf-express.com";//生产环境
	   	}
   }

   function gettoken(){
   		// $url="https://open-sbox.sf-express.com/public/v1.0/security/access_token/sf_appid/".$this->appid."/sf_appkey/".$this->appkey;
   		// $url="http://bspoisp.sit.sf-express.com:11080/bsp-oisp/sfexpressService";
   		// $url="https://bspoisp.sit.sf-express.com:11443/bsp-oisp/sfexpressService";
   		$url="http://bspoisp.sit.sf-express.com:11080/bsp-oisp/sfexpressService";
   		//$url="http://bspoisp.sit.sf-express.com:11080/bsp-oisp/ws/sfexpressService?wsdl";
   		//$url="http://bsp-oisp.test.sf-express.com:6080/bsp-oisp/ws/sfexpressService";
   		$data="<Request service='OrderService' lang='zh-CN'>
   			<Head>BSPdevelop</Head>
   			<Body>
   			<Order j_company='华为' j_contact='客服4号' j_tel='010-1111112' j_mobile='13800138000' j_province='北京' j_city='北京' j_county='朝阳区' j_address='北京市朝阳区科学园科健路338号'   d_company='顺丰速运' d_contact='西门俊宇' d_tel='无' d_mobile='17002930913' d_province='广东省' d_city='深圳市' d_county='福田区' d_address='广东省深圳市福田区新洲十一街万基商务大厦10楼'  orderid ='XJFS_07110098' express_type ='0' pay_method ='1' parcel_quantity ='1' custid ='7551878519' cargo_total_weight ='2.35' sendstarttime ='2016-09-13 17:36:54' order_source ='西门府' remark ='' > 
   				<Cargo   Name='扇子' count='2' unit='台' weight='0.02' amount='100' currency='CNY' source_area='中国'   ></Cargo> 
   			</Order>
   			</Body>
   			</Request>
   			verifyCode:rWn6kNLb+wa0lRYGIuHmYQ==";
   		$result=$this->httpcurl($url,$data);
   		var_dump($result);

   } 
   function httpcurl($url,$xmldata){
   		$ch = curl_init();
   		 // 2. 设置选项，包括URL
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt($ch, CURLOPT_HTTPHEADER, 'Content-type: text/xml');
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $xmldata );

		 $output = curl_exec($ch);
		 if($output === FALSE ){
		 	echo "CURL Error:".curl_error($ch);
		 }
		 var_dump($output);
		 // 4. 释放curl句柄
		 curl_close($ch);
		 return $output;

   }
}

?>