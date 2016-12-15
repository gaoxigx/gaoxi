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
				
		  		$post_data['orderid']=$orderinfo['order_no'];//订单号
		  		$post_data['express_type']="2";//快件类型1标准快递 2顺丰特惠 3电商特惠 7电商速配
		  		$post_data["express_type"]=$orderinfo['express_type'];//快件类型 顺丰次日 顺丰隔日 顺丰次晨 顺丰即日
		  		$post_data['j_company']=$sender['j_contact'];//寄件方公司
		  		$post_data['j_contact']=$sender['j_contact'];//寄件方姓名
		  		$post_data['j_tel']=$sender['j_tel'];//寄件方电话
		  		$post_data['j_province']=$sender['j_province'];//寄件省市区省
		  		$post_data['j_city']=$sender['j_city'];//寄件省市区市
		  		$post_data['j_qu']=$sender['j_qu'];//寄件省市区区
		  		$post_data['j_address']=$sender['j_address'];//寄件方地址

		  		$post_data['d_company']=$orderinfo['company'];//到件方公司
		  		$post_data['d_contact']=trim($orderinfo['username']);//到件方姓名
		  		$post_data['d_tel']=$orderinfo['mobile'];//到件方电话
		  		$post_data['d_province']=$orderinfo['province'];//到件省市区省
		  		$post_data['d_city']=$orderinfo['city'];//到件省市区市
		  		$post_data['d_qu']=$orderinfo['qu'];//到件省市区
		  		$post_data['d_address']=$orderinfo['address'];//到件方地址		
		  		$post_data['pay_method']=$orderinfo['pay_method'];//"寄付月结";//付款方式 1寄付月结  2收方付款
		  		$post_data['custid']=$orderinfo['custid'];//付款帐号
		  		$post_data['daishou']=$orderinfo['daishou'];//代收金额

		  		
		  		//得到物品信息
		  		$things="";
		  		foreach ($proOrder as $k => $vl) {
		  			$things.=$vl['product'].' '.$vl['buynum'].'份';
		  		}

		  		$post_data['things']=$things;//物品
		  		$post_data['things_num']="1";//数量
		  		$post_data['remark']=$orderinfo['note'];//备注
			
		        $SF = new \SFapi();		
		          
		    	$data = $SF->OrderService($post_data)->Send()->readJSON();		    
		 		if(!$data){		 			
		 			$this->error('没有得到得运订单号');
		       		exit();
		 		}
		 		
		 		
		 		$data=json_decode($data,true);	
		       	if(empty($data['data'])){
		       		$this->error('没有得到订单信息');
		       		exit();
		    	}

		        if($data['data'][0]['childs'][1]['tag']=="ERROR"){		        	
		        	if($data['data'][0]['childs'][1]['attr']['code']==8016){ 
		        		$jnorder=$this->OrderSearchService($post_data['orderid']);		        	
		        		$daohuo=json_decode($jnorder,true);
		 				
		        		if($daohuo){
		        			$jnorder=$orderinfo['order_no'];
		        			$param['status']=2;
		        			$param['numberno']=$daohuo['data'][0]['childs'][1]['childs'][0]['attr']['mailno'];
					        $param['filter_result']=$daohuo['data'][0]['childs'][1]['childs'][0]['attr']['filter_result'];
					        $param['destcode']=$daohuo['data'][0]['childs'][1]['childs'][0]['attr']['destcode'];
					        $param['origincode']=$daohuo['data'][0]['childs'][1]['childs'][0]['attr']['origincode'];
					      

		        			$odsul=$this->saveOrder($jnorder,$param);

		        			if($odsul){
		        				//$this->success('已成功下单',U('Shipments/Plist'));
		        				$this->printOrder($orderid);
		        				exit();	
		        			}
		        		}
		        		
		        		$this->error($data['data'][0]['childs'][1]['childs']['0']['text']);		
		        	}else{
		        			
		        		$this->error($data['data'][0]['childs'][1]['childs']['0']['text']);	
		        	}
		        	
		        }

		        if($data['data'][0]['childs'][1]['tag']!="Body"){		        	
		        	$this->error($data['data'][0]['childs'][1]['childs']['0']['text']);	
		        	exit();
		        }

	
		        $param['status']=2;
    			$param['numberno']=$data['data'][0]['childs'][1]['childs'][0]['attr']['mailno'];
		        $param['filter_result']=$data['data'][0]['childs'][1]['childs'][0]['attr']['filter_result'];
		        $param['destcode']=$data['data'][0]['childs'][1]['childs'][0]['attr']['destcode'];
		        $param['origincode']=$data['data'][0]['childs'][1]['childs'][0]['attr']['origincode'];

		        $jnorder=$orderinfo['order_no'];
		
		        $odsul=$this->saveOrder($jnorder,$param);
		   
    			if($odsul){
    				$this->printOrder($orderid,0);
    				exit();
    				//$this->success('已成功下单',U('Shipments/Plist'));		
    			}
		        		

		  

			}else{
				$this->error('订单存在异常,请联系管理员审核');
			}

		}else{
			$this->error("订单不存在");
		}		
		
	}

	//批量生成订单
	public function batchOrder(){
		set_time_limit(0);
		$sender=C('SENDER');
		$oMap['status']=1;
		$orderinfo=M('order_info')->where($oMap)->find();
	
		$return['status']=0;
		if(!$orderinfo){	
			$return['status']=3;	
			$this->ajaxreturn($return);
			exit();
		}
		
		$pay_method=array(1=>'寄付月结',2=>'收方付款');
		$express_type=array(1=>'顺丰次日', 2=>'顺丰隔日', 5=>'顺丰次晨', 6=>'顺丰即日');

		if($orderinfo){

			$orderid=$orderinfo['id'];
			$proOrder=M('order_goods')->where('order_no=%s',$orderinfo['order_no'])->select();

			if($proOrder){
				
		  		$post_data['orderid']=$orderinfo['order_no'];//订单号
		  		$post_data['express_type']=$sender['express_type'];//快件类型1标准快递 2顺丰特惠 3电商特惠 7电商速配
		  		$post_data['j_company']=$sender['j_company'];//寄件方公司
		  		$post_data['j_contact']=$sender['j_contact'];//寄件方姓名
		  		$post_data['j_tel']=$sender['j_tel'];//寄件方电话
		  		$post_data['j_province']=$sender['j_province'];//寄件省市区省
		  		$post_data['j_city']=$sender['j_city'];//寄件省市区市
		  		$post_data['j_qu']=$sender['j_qu'];//寄件省市区区
		  		$post_data['j_address']=$sender['j_address'];//寄件方地址

		  		$post_data['d_company']=$orderinfo['company'];//到件方公司
		  		$post_data['d_contact']=$orderinfo['username'];//到件方姓名
		  		$post_data['d_tel']=$orderinfo['mobile'];//到件方电话
		  		$post_data['d_province']=$orderinfo['province'];//到件省市区省
		  		$post_data['d_city']=$orderinfo['city'];//到件省市区市
		  		$post_data['d_qu']=$orderinfo['qu'];//到件省市区
		  		$post_data['d_address']=$orderinfo['address'];//到件方地址		  		

		  		$post_data['pay_method']=$pay_method[1];//付款方式 1寄付月结  2收方付款
		  		$post_data['custid']="";//付款帐号
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

		    	$data = $SF->OrderService($post_data)->Send()->readJSON();
		

		        if(!$data){		 			
		 			$this->error('没有得到得运订单号');
		       		exit();
		 		}

		 
		 		$data=json_decode($data,true);

		       if(empty($data['data'])){
		       		$this->error('没有得到订单信息');
		       		exit();
		    	}

		        if($data['data'][0]['childs'][1]['tag']=="ERROR"){
		        
		        	if($data['data'][0]['childs'][1]['attr']['code']==8016){		        		

		        		$jnorder=$this->OrderSearchService($post_data['orderid']);		        	
		        		$daohuo=json_decode($jnorder,true);
		 				
		        		if($daohuo){
		        			$param['numberno']=$daohuo['data'][0]['childs'][1]['childs'][0]['attr']['mailno'];
		        			$jnorder=$orderinfo['order_no'];
		        			$param['status']=2;
		        			$param['express']=1;
		        			$odsul=$this->saveOrder($jnorder,$param);
		        			if($odsul){
		        				//$this->success('已成功下单',U('Shipments/Plist'));	

		        				$sulprint=$this->printOrder($orderid,$ajax=1);
		        				if($sulprint){
			    					$return['status']=1;
			    					$return['msg']='生成订单成功';
			    					$return['order_no']=$param['numberno'];
			    					$return['mailno']=$orderinfo['order_no'];
			    				}
			    				$this->ajaxreturn($return);	
			    				exit();
		        			}
		        		}
		        		
		        		$this->error($sul['data'][0]['childs'][1]['childs']['0']['text']);		
		        	}else{
		        			
		        		$this->error($sul['data'][0]['childs'][1]['childs']['0']['text']);	
		        	}
		        	
		        }
		  		
		        //$param['numberno']=
		        $param['express']=1;
		        $param['status']=2;
		        $odsul=$this->saveOrder($jnorder,$param);
    			if($odsul){
    				$sulprint=$this->printOrder($orderid,$ajax=1);
    				if($sulprint){
    					$return['status']=1;
    					$return['msg']='生成订单成功';
    					$return['order_no']=$param['numberno'];
    					$return['mailno']=$orderinfo['order_no'];
    				}
    				$this->ajaxreturn($return);	
    				exit();
    			}

			}else{
				$return['status']=1;
				$return['msg']="订单存在异常,请联系管理员审核";
				$this->ajaxreturn($return);
				exit();
			}

		}else{
			$return['status']=1;
			$return['msg']="订单不存在";
			$this->ajaxreturn($return);
			exit();
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

	public function order($route_mailno){
		$post_data = $_POST;
        $route_mailno = $post_data["route_mailno"];
        $SF = new SFapi();
        $Mode = $post_data["RouteService_Mode"];
        unset($post_data["RouteService_Mode"]);
     
        $data = $SF->RouteService($route_mailno)->Send()->readJSON();
     
        echo $data;
	}


	//下订单接口
	public function orderServer(){
		//加截
		define('_ROOT', str_replace("\\", '/', dirname(__FILE__)));
		require_once (_ROOT . "/class/SFforHttpPost.class.php");
		Vendor('Express/sf/class/SFforHttpPost');
		
		require_once (_ROOT . "/class/MySFprinter.class.php");
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

	public function printWebOrder($id,$ajax=0){
		if (empty($id)) {
			$this->error("请选择打印订单");
		    exit();
		}

		$orderdata=M('order_info')->find($id);

		if(!$orderdata){
			if($ajax==1){
				return false;
			}else{
				$this->error("订单不存了");
				exit();
			}
			
			
		}


		$proOrder=M('order_goods')->where('order_no=%s',$orderdata['order_no'])->select();
		if(!$proOrder){
			if($ajax==1){
				return false;
			}else{
				$this->error("订单没有产品请核实");
				exit();
			}
			
		}

		
        $post_data = $orderdata;
        unset($post_data["action"]);

        $pic = "Public/order/old_no" . time() . ".png";
        $olderpic =ROOT_PATH . "/" . $pic;
     
      
        $pay_method=array(1=>'寄付月结',2=>'收方付款');
		$express_type=array(1=>'顺丰次日', 2=>'顺丰隔日', 5=>'顺丰次晨', 6=>'顺丰即日');
        $sender=C('SENDER');
        $data = array(
            "mailno" => $orderdata['numberno'],//运单号
            "express_type" =>$express_type[$orderdata['express_type']],//快件类型 顺丰次日 顺丰隔日 顺丰次晨 顺丰即日
            "orderid" =>$orderdata['order_no'],//订单号
            "j_company" => $sender['j_company'],//寄件方公司
            "j_contact" => $sender['j_contact'],//寄件方姓名
            "j_tel" => $sender['j_tel'],//寄件方电话
            "j_province" => $sender['j_province'],
            "j_city" =>$sender['j_city'],
            "j_qu" => $sender['j_qu'],//寄件省市区            
            "j_address" => $sender['j_address'],//寄件方地址
            "j_number" => $sender['j_number'],//寄件地编号

            "d_company" => $orderdata['company'],//到件方公司
            "d_contact" =>$orderdata['username'],//到件方姓名
            "d_tel" => $orderdata['mobile'],//到件方电话
            "d_province" =>$orderdata['province'],//到件省市区
            "d_city" => $orderdata['city'],//到件省市区
            "d_qu" => $orderdata['qu'],//到件省市区
            "d_address" =>$orderdata['address'],//到件方地址
            "d_number" => $orderdata["destcode"],//到件地编号
            "pay_method" =>$pay_method[$orderdata['pay_method']],//付款方式
            "custid" => $orderdata["custid"],//付款帐号
            "daishou" => $orderdata["daishou"], //代收款项
            "remark" => $orderdata["remark"],//备注
            "things" => $orderdata["things"]//物件
        );
        $this->assign('data',$data);
        $this->display();
	}

	//打印订单
	public function printOrder($id,$ajax=0){
		
		require_once (ROOT_PATH . "\ThinkPHP\Library\Vendor\Express\sf\class\MySFprinter.class.php");
		require_once (ROOT_PATH . "\ThinkPHP\Library\Vendor\Express\sf\class\Pclzip.class.php");

		 
		if (empty($id)) {
			$this->error("请选择打印订单");
		    exit();
		}

		$orderdata=M('order_info')->find($id);

		if(!$orderdata){
			if($ajax==1){
				return false;
			}else{
				$this->error("订单不存了");
				exit();
			}
		}
		$proOrder=M('order_goods')->alias("og")->field("og.*,pro.things")->join('__PRODUCT__ as pro on og.proid=pro.id','left')->where('order_no=%s',$orderdata['order_no'])->select();
		if(!$proOrder){
			if($ajax==1){
				return false;
			}else{
				$this->error("订单没有产品请核实");
				exit();
			}
		}

		foreach ($proOrder as $kg => $vg) {
			if($kg%2==0){
				$orderdata["things"].="\n";
			}
			if($vg['things']==""){
				$orderdata["things"].=$vg['product'].$vg["quality"].$vg["grade"]."X".$vg["buynum"];
			}else{
				$orderdata["things"].=$vg['things'].$vg["quality"].$vg["grade"]."X".$vg["buynum"];
			}
		}
		if(strlen($orderdata['note'])>80){
			$ns=mb_substr($orderdata['note'],0,80);
			$nb=mb_substr($orderdata['note'],80,mb_strlen($orderdata['note']));
			$orderdata['note']=$ns."\n".$nb;

		}
		$orderdata["things"]=$orderdata["things"]."\n".$orderdata['note'];
        $post_data = $orderdata;
        unset($post_data["action"]);

        $pic = "Public/order/old_no" . time() . ".png";
        $olderpic =ROOT_PATH . "/" . $pic;
      
        $SF = new \SFprinter();
        $pay_method=array(1=>'寄付月结',2=>'收方付款');//寄付月结//收方付款//
		
		$express_type=array(1=>'顺丰次日', 2=>'顺丰隔日', 5=>'顺丰次晨', 6=>'顺丰即日');
        $sender=C('SENDER');
        $data = array(
            "mailno" => $orderdata['numberno'],//运单号
            "express_type" =>$express_type[$orderdata['express_type']],//快件类型 顺丰次日 顺丰隔日 顺丰次晨 顺丰即日
            "orderid" =>$orderdata['order_no'],//订单号
            "j_company" => $sender['j_company'],//寄件方公司
            "j_contact" => $sender['j_contact'],//寄件方姓名
            "j_tel" => $sender['j_tel'],//寄件方电话
            "j_province" => $sender['j_province'],
            "j_city" =>$sender['j_city'],
            "j_qu" => $sender['j_qu'],//寄件省市区            
            "j_address" => $sender['j_address'],//寄件方地址
            "j_number" => $sender['j_number'],//寄件地编号

            "d_company" => $orderdata['company'],//到件方公司
            "d_contact" =>$orderdata['username'],//到件方姓名
            "d_tel" => $orderdata['mobile'],//到件方电话
            "d_province" =>$orderdata['province'],//到件省市区
            "d_city" => $orderdata['city'],//到件省市区
            "d_qu" => $orderdata['qu'],//到件省市区
            "d_address" =>$orderdata['address'],//到件方地址
            "d_number" => $orderdata["destcode"],//到件地编号
            "pay_method" => $pay_method[$orderdata['pay_method']],//付款方式
            "custid" => $orderdata["custid"],//付款帐号
            "daishou" => $orderdata["daishou"], //代收款项
            "remark" => $orderdata["remark"],//备注
            "things" => $orderdata["things"]//物件
        );

        $SF->SFdata($data)->SFprint($olderpic);
     	

        $zipurl = "Public/order/" .$orderdata['order_no'] . ".zip";
        $archive = new \PclZip($zipurl);
		//$v_list = $archive->create($olderpic, PCLZIP_OPT_REMOVE_PATH, '', PCLZIP_OPT_ADD_PATH, '');
		
        $v_list = $archive->create($pic, PCLZIP_OPT_REMOVE_PATH, 'Public/order', PCLZIP_OPT_ADD_PATH, 'PrintOrder');
     
        if ($v_list == 0) {
        	die("Error : ".$archive->errorInfo(true));
     	}

     	$param['mailnoimg']=$pic;
     	$param['mailnozip']=$zipurl;
     	$param['status']=5;
     	if($id>0)
     		$sul=M("order_info")->where("id=%d",$id)->save($param);
       
        if($sul){
        	if($ajax==1){
				return true;
			}else{
				//$this->success('生成打印图片');
				echo '<img src="/'.$pic.'" />';
				exit();
			}
        }else{
        	if($ajax==1){
				return false;
			}else{
				$this->error('请重新生成图片');
				exit();
			}
        }
	}
}

?>