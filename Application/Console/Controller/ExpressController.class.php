<?php
namespace Console\Controller;
use Think\Controller;

 $filepath=ROOT_PATH.'\ThinkPHP\Library\Vendor\Express\sf\class\SFforHttpPost.class.php';		 
require_once($filepath);
///顺丰api接口
class ExpressController extends CommonController  {
	//正试环境接口
	public function index(){

	}
	//接口测试
	public function preview(){

	}
	//得到token
	public function token(){
		echo 'dfsdf';
		Vendor('Express');
		$ex=new \Express();
		$ex->gettoken();
	}

	//生成单个快递单号
	public function oneOrder($id=0){
		set_time_limit(0);
		$orderid=$id;
		if($orderid==0){
			$this->error('订单不存在');
			exit();
		}
		$sender=C('SENDER');
		$orderinfo=M('order_info')->where('id=%d',$orderid)->find();

		if($orderinfo){
			$proOrder=M('order_goods')->where('order_no=%s',$orderinfo['order_no'])->select();

			if($proOrder){
				
		  		$post_data['orderid']='gx'.$orderinfo['order_no'];//订单号
		  		$post_data['express_type']=1;//快件类型1标准快递 2顺丰特惠 3电商特惠 7电商速配
		  		$post_data['j_company']=$sender['j_company'];//寄件方公司
		  		$post_data['j_contact']=$sender['j_contact'];//寄件方姓名
		  		$post_data['j_tel']=$sender['j_tel'];//寄件方电话
		  		$post_data['j_province']=$sender['j_province'];//寄件省市区省
		  		$post_data['j_city']=$sender['j_city'];//寄件省市区市
		  		$post_data['j_qu']=$sender['j_qu'];//寄件省市区区
		  		$post_data['j_address']=$sender['j_address'];//寄件方地址

		  		$post_data['d_company']=$orderinfo[''];//到件方公司
		  		$post_data['d_contact']="西瓜";//到件方姓名
		  		$post_data['d_tel']="15818618500";//到件方电话
		  		$post_data['d_province']="广东省";//到件省市区省
		  		$post_data['d_city']="广州";//到件省市区市
		  		$post_data['d_qu']="天河区";//到件省市区
		  		$post_data['d_address']="额太多的地方可以让你去";//到件方地址		  		

		  		$post_data['pay_method']=1;//付款方式 1寄付月结  2收方付款
		  		$post_data['custid']="5322059827";//付款帐号
		  		$post_data['daishou']="0";//代收金额

		  		//得到物品信息
		  		$things="";
		  		foreach ($proOrder as $k => $vl) {
		  			$things.=$vl['product'].' '.$vl['buynum'].'份';
		  		}

		  		$post_data['things']=$things;//物品
		  		$post_data['things_num']=1;//数量
		  		$post_data['remark']=$orderinfo['note'];//备注
		  		$post_data['OrderService_Mode']="JSON";//数据格式		
		 
		        unset($post_data["action"]);
		      
		        $SF = new \SFapi();
		        $Mode = $post_data["OrderService_Mode"];
		        unset($post_data["OrderService_Mode"]);

		    	$data = $SF->OrderService($post_data)->Send();
		    
		        if ($Mode == "JSON") {
		            $data = $SF->OrderService($post_data)->Send()->readJSON();
		        } else {
		            $data = $SF->OrderService($post_data)->Send()->webView();
		        }

		        if($Mode="JSON"){
		        	$sul=json_decode($data,true);
		        }else{
		        	$sul= json_decode(json_encode($xml),TRUE);
		        }

		        if($sul['data'][0]['childs'][1]['tag']=="ERROR"){
		        
		        	if($sul['data'][0]['childs'][1]['attr']['code']==8016){
		        		

		        		$jnorder=$this->OrderSearchService($post_data['orderid']);

		        		$daohuo=json_decode($jnorder,true);
		 		
		        		if($daohuo){
		        			$param['numberno']=$daohuo['data'][0]['childs'][1]['childs'][0]['attr']['mailno'];
		        			$jnorder=$orderinfo['order_no'];
		        			$param['status']=2;
		        			$odsul=$this->saveOrder($jnorder,$param);
		        			if($odsul){
		        				$this->error('已成功下单');		
		        			}
		        		}
		        		
		        		$this->error($sul['data'][0]['childs'][1]['childs']['0']['text']);		
		        	}else{
		        			
		        		$this->error($sul['data'][0]['childs'][1]['childs']['0']['text']);	
		        	}
		        	
		        }
		  
		        //$param['numberno']=
		        $param['status']=2;
		        $odsul=$this->saveOrder($jnorder,$param);
    			if($odsul){
    				$this->error('已成功下单');		
    			}
		        		

		  

			}else{
				$this->error('订单存在异常,请联系管理员审核');
			}

		}else{
			$this->error("订单不存在");
		}		
		
	}

	//下单成功保存运单号
	public function saveOrder($order_no,$param){
		if($order_no){		
			$sul=M('order_info')->where('order_no=%s',$order_no)->save($param);	
			return $sul;
			
		}else{
			return false;
		}
	}
	//订单号查询
	public function OrderSearchService($order_no){
		//$post_data = $_POST;
		$search_orderid = $order_no;
		$SF = new \SFapi();
		$Mode ='JSON';// $post_data["OrderSearchService_Mode"];
		//unset($post_data["OrderSearchService_Mode"]);
		if ($Mode == "JSON") {
            $data = $SF->OrderSearchService($search_orderid)->Send()->readJSON();
        }else{
            $data = $SF->OrderSearchService($search_orderid)->Send()->webView();
        }
        return $data;
	}


	//下订单接口
	public function orderServer(){
		//加截
		define('_ROOT', str_replace("\\", '/', dirname(__FILE__)));
		require_once (_ROOT . "/class/SFforHttpPost.class.php");
		Vendor('Express/sf/class/SFforHttpPost');
		
		require_once (_ROOT . "/class/SFprinter.class.php");
		require_once (_ROOT . "/class/Pclzip.class.php");

		$action = $_POST["action"];
		if (empty($action)) {
		    exit();
		}

		switch ($action) {
		//    下订单接口
		    case "OrderService":
		        $post_data = $_POST;
		        unset($post_data["action"]);
		        $SF = new SFapi();
		        $Mode = $post_data["OrderService_Mode"];
		        unset($post_data["OrderService_Mode"]);
		        if ($Mode == "JSON") {
		            $data = $SF->OrderService($post_data)->Send()->readJSON();
		        } else {
		            $data = $SF->OrderService($post_data)->Send()->webView();
		        }
		        echo $data;
		        break;
		    case "OrderFilterService":
		        $post_data = $_POST;
		        unset($post_data["action"]);
		        $SF = new SFapi();
		        $Mode = $post_data["OrderFilterService_Mode"];
		        unset($post_data["OrderFilterService_Mode"]);
		        if ($Mode == "JSON") {
		            $data = $SF->OrderFilterService($post_data)->Send()->readJSON();
		        } else {
		            $data = $SF->OrderFilterService($post_data)->Send()->webView();
		        }
		        echo $data;
		        break;
		    case "OrderSearchService":
		        $post_data = $_POST;
		        $search_orderid = $post_data["search_orderid"];
		        $SF = new SFapi();
		        $Mode = $post_data["OrderSearchService_Mode"];
		        unset($post_data["OrderSearchService_Mode"]);
		        if ($Mode == "JSON") {
		            $data = $SF->OrderSearchService($search_orderid)->Send()->readJSON();
		        }else{
		            $data = $SF->OrderSearchService($search_orderid)->Send()->webView();
		        }
		        echo $data;
		        break;
		    case "RouteService":
		        $post_data = $_POST;
		        $route_mailno = $post_data["route_mailno"];
		        $SF = new SFapi();
		        $Mode = $post_data["RouteService_Mode"];
		        unset($post_data["RouteService_Mode"]);
		        if ($Mode == "JSON") {
		            $data = $SF->RouteService($route_mailno)->Send()->readJSON();
		        }else{
		            $data = $SF->RouteService($route_mailno)->Send()->webView();
		        }
		        echo $data;
		        break;
		        break;

		    default:
		        break;
		}
	}

	//打印订单
	public function printOrder(){
		define('_ROOT', str_replace("\\", '/', dirname(__FILE__)));
		require_once (_ROOT . "/class/SFprinter.class.php");
		require_once (_ROOT . "/class/Pclzip.class.php");
		$action = $_POST["action"];
		if (empty($action)) {
		    exit();
		}

		switch ($action) {
		//    下订单接口
		    case "Printer":
		        $post_data = $_POST;
		        unset($post_data["action"]);

		        $pic = "save/old_no" . time() . ".png";
		        $olderpic = _ROOT . "/" . $pic;

		        $SF = new SFprinter();
		        $data = array(
		            "express_type" => $post_data["express_type"],
		            "mailno" => $post_data["mailno"],
		            "orderid" => $post_data["orderid"],
		            "j_company" => $post_data["j_company"],
		            "j_contact" => $post_data["j_contact"],
		            "j_tel" => $post_data["j_tel"],
		            "j_province" => $post_data["j_province"],
		            "j_city" => $post_data["j_city"],
		            "j_qu" => $post_data["j_qu"],
		            "j_address" => $post_data["j_address"],
		            "j_number" => $post_data["j_number"],
		            "d_company" => $post_data["d_company"],
		            "d_contact" => $post_data["d_contact"],
		            "d_tel" => $post_data["d_tel"],
		            "d_province" => $post_data["d_province"],
		            "d_city" => $post_data["d_city"],
		            "d_qu" => $post_data["d_qu"],
		            "d_address" => $post_data["d_address"],
		            "d_number" => $post_data["d_number"],
		            "pay_method" => $post_data["pay_method"],
		            "custid" => $post_data["custid"],
		            "daishou" => $post_data["daishou"], //代收款项
		            "remark" => $post_data["remark"],
		            "things" => $post_data["things"]
		        );
		        $SF->SFdata($data)->SFprint($olderpic);
		        $zipurl = "save/order" . time() . ".zip";
		        $archive = new PclZip($zipurl);
		//        $v_list = $archive->create($olderpic, PCLZIP_OPT_REMOVE_PATH, '', PCLZIP_OPT_ADD_PATH, '');
		        $v_list = $archive->create($pic, PCLZIP_OPT_REMOVE_PATH, 'save', PCLZIP_OPT_ADD_PATH, 'PrintOrder');
		        echo $zipurl;
		        die;

		        break;
		    default:
		        break;
		}
	}
}

?>