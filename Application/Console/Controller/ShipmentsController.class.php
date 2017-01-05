<?php
namespace Console\Controller;
use Think\Controller;
header("Content-type:text/html;charset=utf-8");

class ShipmentsController extends CommonController {
	 /**
	 * 生成唯一订单号
	 */
	public function build_order_no()
	{
		$no = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
		//检测是否存在
		$db = M('order_info');
		$info = $db->where(array('order_no'=>$no))->find();
		(!empty($info)) && $no = $this->build_order_no();
		return $no;
	}

	//管理员组类别
	private function getrolename($rolename,$roleid){
		$data=D('staff');
		$name = $data->where('quarters='.$roleid)->order('id')->select();
	   
		$this->assign($rolename,$name);

	}

	//设备编号获取
	public function getEquipment(){

	   $id=I('id');
		if(!$id){
			return 0;
		}    
		$data=D('equipment');
		$result = $data->field('id,xinghao,bianhao')->where('staffid=%d',array($id))->order('id desc')->select();
		if(!$result)
		{
			return 0;
		}
		$this->ajaxReturn($result);
	}
	//设备编号获取
	private function getequipment_name(){
		$data=D('equipment');
		$name = $data->where('bianhao<>""')->order('id desc')->select();

		$this->assign('equipment',$name);
	}

	//通过产品id获取类别id,图片地址
	public function get_proid_to_typeid($id='',$typeid=''){
		$data=D('product');
		$name = $data-> field('protype,pic,pic1')->where('id='.$id)->order('id desc')->find();
		
		$pro = $name['protype'];
		$pic = $name['pic'];
		$pic1 = $name['pic1'];
		switch ($typeid) {
			case 1:
				return $pro;
				break;
			case 2:
				return $pic;
				break;
			case 3:
				return $pic1;
				break;
			
			default:
				# code...
				break;
		}    

	}

	//支付方式获取
	private function getpayment(){
		$data=D('payment');
		$name = $data->where('payment<>""')->order('id')->select();
		// dump($name);
		$this->assign('payment',$name);

	}
        
        //快递公司获取
	private function getexpress(){
		$data=D('express');
		$name = $data->where('express<>""')->order('id')->select();
		// dump($name);
		$this->assign('express',$name);
	}

	public function expressphoto(){
		//date_default_timezone_set("PRC");   //使用北京时间
		//只接受post请求
		if(strtolower($_SERVER['REQUEST_METHOD']) != 'post'){
			exit;
		}

		$folder = 'uploads/express/'.date('Ymd').'/';
		if(!is_dir($folder)){
			mkdir($folder);
		}
		$filename = date('YmdHis').rand().'.jpg';
		$original = $folder.$filename;

		$input = file_get_contents('php://input');
		if(md5($input) == '7d4df9cc423720b7f1f3d672b89362be'){
			exit;
		}
		$result = file_put_contents($original, $input);
		if (!$result) {
			echo '{"error":1,"message":"文件目录不可写";}';
			exit;
		}

		$info = getimagesize($original);
		if($info['mime'] != 'image/jpeg'){
			unlink($original);
			exit;
		}


		$origImage	= imagecreatefromjpeg($original);
		$newImage	= imagecreatetruecolor(154,110);
		imagecopyresampled($newImage,$origImage,0,0,0,0,154,110,520,370); 

		//imagejpeg($newImage,'uploads/thumbs/'.$filename);
		imagejpeg($newImage,$folder.'/small_'.$filename);

		$id=I('request.id');
		$imgpath=$data['expressimg']=$folder.'small_'.$filename;		
		$param['expressimg']=$imgpath;
		$param['expressimgs']=$folder.$filename;
		$res=M('order_info')->where('id=%d',array($id))->setfield($param);		
	    if($res){
	    	$data['status']=1;
			$data['status']="Success!";
			$data['filename']=$imgpath;
			$data['sfilename']=$original;
			$data['id']=$id;

	    	$this->ajaxreturn($data);
		    //echo '{"status":1,"message":"Success!","filename":"'.$imgpath.'","sfilename":"'.$original.'"}';
		}else{
			$data['error']=1;
			$data['message']="Sorry,something goes wrong.";
			$this->ajaxreturn($data);
		}
		exit();
	}
        
	
	//处理付款状态（确认和取消）
	public function Payment_status($id,$status){
		$order_info = D('order_info');
		$data['payment_status'] = 1; 
		if($status==0){
			$tip="确认付款成功！";
		}else{
			$tip="取消确认付款！";
		}
		$name = $order_info->where('id='.$id)->save($data);
		if($name){
			$this->success($tip);
		}else{
			$this->error('更新数据出错');
		}
	}
	
	//物流单号查询
	 public function numberno($no){
            require_once(ROOT_PATH."\ThinkPHP\Library\Lib\Kuaidi\KdApiSearchDemo.class.php");            
             $kd=new \Kuaidiniao();       
             $kd->setLogisticCode($no);
             $sul=$kd->getOrderTracesByJson();        
             $result=json_decode($sul,true)  ;          
         
		// $this->$this->display(??)
            $this->assign('data',$result);
            $this->display('kuaidi');
	 }

	 //插入物流单号到订单表
	public function updatenumberno(){
		$data["numberno"] =I('post.numberno');	
		$data['fhnote']=I('post.fhnote');
		$data['express']=I('post.express');
		$data["status"] = 2;
		$id=I('post.id');		
		if(empty($data["numberno"])&&!$id){			
			$sult['msg']='没有得到数据';
			$sult['status']=0;
			$this->ajaxreturn($sult);
		}
		$order_no=M('order_info')->where('id=%d',$id)->getField('order_no');

		$pro=M('order_goods')->where("order_no='%s'",$order_no)->select();
		foreach ($pro as $k => $v) {
			$param['salenum']=array('exp',"salenum+".$v['buynum']);
			M('product')->where("id=%d",$v['proid'])->save($param);			
		}

		$order_info = D('order_info');
		$name = $order_info->where('id='.$id)->save($data);
		 if($name){
		 	
		 	$sult['msg']='成功发货';
		 	$sult['status']=1;
			$this->ajaxreturn($sult);
			exit();
		}else{
			$sult['msg']='数据错误';
			$sult['status']=0;
			$this->ajaxreturn($sult);
			exit();
		}
	}

        
             //更新物流单号
    public function updatenumbernoss(){
            $roleList   =   D('order_info');
            $data=$roleList->create();
            if($data) {
                $data['numberno']=strtotime(I('post.numberno'));
                $data['express']=strtotime(I('post.express')); 
                $data['fhnote']=strtotime(I('post.fhnote')); 
                $result = $roleList->save($data);
                if($result) {
                    $this->success('操作成功！');
                }else{
                    $this->error('写入错误！');
                }
            }else{
                $this->error($roleList->getError());
            }
         }   
        
        //添加数据（发货备注）
        public function update(){
                $roleList = D('order_info');
		$data=$roleList->create();		
		if($data) {
			$fhnote=$data['fhnote'];
			unset($data['fhnote']);
			$result = $roleList->where('fhnote=%s',array($fhnote))->save($data);
			if($result) {
				$this->success('操作成功！');
			}else{
				$this->error('写入错误！');
			}
		}else{
			$this->error($roleList->getError());
		}
    }
        


	//数据列表
	public function Plist($name=''){
		$username = I('username');

		if($username){
            $map1['id'] =  array('like','%'.trim($username).'%');
			$map1['order_no']  = array('like','%'.trim($username).'%'); 
                        $map1['agent']  =M('staff')->where(array('name'=>array('like','%'.trim($username).'%')))->getField('id');
			$map1['username']  = array('like','%'.trim($username).'%');
                        $map1['mobile'] =  array('like','%'.trim($username).'%');
			$map1['_logic'] = 'OR';
			$map['_complex'] = $map1;
		}

		//$user_id = session("userid");
		if($user_id > 0 ){
		//	$where['id']=session("userid");
			$where['accounts']=session("username");
			$count=M('controller')->where($where)->count();
			if($count<=0){
				$user=D('Staff')->getthislevel();
				$map['agent']  = array('in',implode(',',$user));
			}
	    }
		foreach( $map as $k=>$v){  
			if( !$v )  
				unset( $arr[$k] );  
		}   

		//分页跳转的时候保证查询条件
		foreach($map as $key=>$val) {
			if(!$val){
				unset($map[$key]);
			}else{
				$Page->parameter[$key]   =   urlencode($val);
			 }
		}
		$User = M('order_info'); // 实例化User对象 

		$map['status']=1;
		//$map['payment_status']=1;
		$count = $User->where($map)->count();// 查询满足要求的总记录数
		$Page = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show = $Page->show();// 分页显示输出	  	

		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $User->where($map)->order('id asc')->limit($Page->firstRow.','.$Page->listRows)->select();		
		$staff=D('staff')->getfield('id,name',true);
		$catdata=D('Category')->categoryone();      
		
		$this->assign('staff',$staff);
		$this->assign('cat',$catdata);
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->display(); // 输出模板
	}

	//数据列表
	public function printOrder($name=''){
		$username = I('username');

		if($username){
            $map1['id'] =  array('like','%'.trim($username).'%');
			$map1['order_no']  = array('like','%'.trim($username).'%'); 
                        $map1['agent']  =M('staff')->where(array('name'=>array('like','%'.trim($username).'%')))->getField('id');
			$map1['username']  = array('like','%'.trim($username).'%');
                        $map1['mobile'] =  array('like','%'.trim($username).'%');
			$map1['_logic'] = 'OR';
			$map['_complex'] = $map1;
		}

		//$user_id = session("userid");
		if($user_id > 0 ){
		//	$where['id']=session("userid");
			$where['accounts']=session("username");
			$count=M('controller')->where($where)->count();
			if($count<=0){
				$user=D('Staff')->getthislevel();
				$map['agent']  = array('in',implode(',',$user));
			}
	    }
		foreach( $map as $k=>$v){  
			if( !$v )  
				unset( $arr[$k] );  
		}   

		//分页跳转的时候保证查询条件
		foreach($map as $key=>$val) {
			if(!$val){
				unset($map[$key]);
			}else{
				$Page->parameter[$key]   =   urlencode($val);
			 }
		}
		$User = M('order_info'); // 实例化User对象 

		$map['status']=5;
		//$map['payment_status']=1;
		$count = $User->where($map)->count();// 查询满足要求的总记录数
		$Page = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show = $Page->show();// 分页显示输出	  	

		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $User->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();		
		$staff=D('staff')->getfield('id,name',true);
		$catdata=D('Category')->categoryone();      
		
		$this->assign('staff',$staff);
		$this->assign('cat',$catdata);
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->display(); // 输出模板
	}

	//已发货订单
	public function ylist($name=''){
		$username = I('username');

		if($username){
			$map1['id']  = array('like','%'.trim($username).'%'); 
            $map1['agent']  =M('staff')->where(array('name'=>array('like','%'.trim($username).'%')))->getField('id');
			$map1['order_no']  = array('like','%'.trim($username).'%'); 
			$map1['username']  = array('like','%'.trim($username).'%');
                        $map1['mobile'] =  array('like','%'.trim($username).'%');
                        $map1['numberno'] =  array('like','%'.trim($username).'%');
			$map1['_logic'] = 'OR';
			$map['_complex'] = $map1;
		}


		//$user_id = session("userid");
		if($user_id > 0 ){
			//$where['id']=session("userid");
			$where['accounts']=session("username");
			$count=M('controller')->where($where)->count();
			if($count<=0){
				$user=D('Staff')->getthislevel();
				$map['agent']  = array('in',implode(',',$user));
			}
	    }
		foreach( $map as $k=>$v){  
			if( !$v )  
				unset( $arr[$k] );  
		}   
		
		//分页跳转的时候保证查询条件
		foreach($map as $key=>$val) {
			if(!$val){
				unset($map[$key]);
			}else{
			$Page->parameter[$key]   =   urlencode($val);
			  }
		}
		$User = M('order_info'); // 实例化User对象 
		$map['status']=2;

		$count = $User->where($map)->count();// 查询满足要求的总记录数
		$Page = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show = $Page->show();// 分页显示输出

	  

		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $User->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();	
	
		$staff=D('staff')->getfield('id,name',true);
		$catdata=D('Category')->categoryone();      
		
		$this->assign('staff',$staff);
		$this->assign('cat',$catdata);
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->display(); // 输出模板

	}

	//提交订单
	public function add(){
        $map['id']=session('userid');
        $map['accounts']=session('username');            
        $count=M('controller')->where($map)->count();            
            if($count<=0){                 
                $userdata=D('Staff')->getthislevel();
                $where['id']=array('in',implode(',',$userdata));
            }      
        $where['quarters']=array('in',"38,39,67");
        $where['iswork']=array('neq',4);            
        $staff=D('Staff')->where($where)->getField('id,name',true);           
//            
//            $this->getrolename('agent',68);
//            $this->getrolename('assistant', 67);
           // $this->getequipment_name();
        $this->getpayment();
		$data = M('product'); // 实例化User对象
		$list = $data->where('1=1')->order('id')->select();  
	    $this->assign('staff', $staff);
		$this->assign('prolist',$list);// 赋值数据集 
		$this->display();
	}

	//查看页面
    public function look(){
        $id=I('get.id');        
        $model = D('order_info');      
        if(empty($id)){            
            $this->error('请选择查看订单');
        } 
		$info = $model->where("id=%d",array($id))->find(); 
		$Equipment = D('Equipment')->where('staffid='.$info['agent'])->select();
		$payment=D('payment')->where('')->getfield('id,payment',true);
		$staff=D('staff')->getfield('id,name',true);
		$products=$this->GetProdect($info['order_no']);
		$this->assign('products',$products);             
		
		$this->assign('department',D('Category')->department());
		$this->assign('quarters',$quarters);
		$this->assign('subordinates',$subordinates);
		$this->assign('Equipment',$Equipment);
		$this->assign('order_nums',$order_nums);
		$this->assign('payment',$payment);
		$this->assign('staff',$staff);
		$this->assign('info',$info);
		$this->assign('id',$id);
		$this->display();

    }
    public function printlook(){
         $id=I('get.id');        
        $model = D('order_info');      
        if(empty($id)){            
            $this->error('请选择查看订单');
        } 
		$info = $model->where("id=%d",array($id))->find(); 
		$Equipment = D('Equipment')->where('staffid='.$info['agent'])->select();
		$payment=D('payment')->where('')->getfield('id,payment',true);
		$staff=D('staff')->getfield('id,name',true);
		
		$products=$this->GetProdect($info['order_no']);
		$this->assign('products',$products);
		
		$this->assign('department',D('Category')->department());
		$this->assign('quarters',$quarters);
		$this->assign('subordinates',$subordinates);
		$this->assign('Equipment',$Equipment);
		$this->assign('order_nums',$order_nums);
		$this->assign('payment',$payment);
		$this->assign('staff',$staff);
		$this->assign('info',$info);
		$this->assign('id',$id);
		$this->display();
    }
    //根据订单号查找产品
    private function GetProdect($order_no){
		$datagoods = D('order_goods');
		$products = $datagoods-> field('product,price2,buynum')->where('order_no="'.$order_no.'"')->order('id desc')->select();  
		return $products;            
    }

	




	//查找下级经济人 ajax请求
	public function getagent(){
		$id=I('id');
		if(empty($id)){        
			$this->ajaxreturn(0);
		}     
		$data=D('staff')->where('nibs=%d',array($id))->getfield('id,name',true);
		if($data){
			$this->ajaxreturn($data);    
		}else{
			$this->ajaxreturn(0);
		}
		
	}


	//print打印页面
	public function weixin_orders_print() {
		$id = I ( 'id' );
		$this->assign ( 'id', $id );
		
		$model = new WeixinApipayOrdersModel ();
		$orderArray = $model->get_info_byid ( $id );
		
		// 设置报表标题
		$orderArray ['reportTitle'] = '这是用户基本表';
		
		// 报表中要得到的数据格式
		$xmlReportData = get_reports_xml_byarray ( $orderArray );
		$this->assign ( 'xmlReportData', $xmlReportData );
		// 要打印的报表文件
		$reportName = 'kdd_shentong.grf';
		$this->assign ( 'reportName', $reportName );
		// put_log($xmlReportData);
		
		
		$this->display ( 'Weixin:weixin_orders_print' );// 显示模板
	}
	
    //修改物流单号
    public function editnote($id){
        
        $jumpUrl =U('Console/Shipments/ylist'); 
        $id = (int)$id;
        $model = D('order_info');
        if (IS_POST) {                       
            if ($id > 0) {
                $data=$model->create();
                $map['id']=$id;
                $result=$model->where($map)->save($data);
                if ($result){
                    $this->success('修改成功', $jumpUrl);
                } else {                    
                    $this->error('修改失败', $jumpUrl);
                }          
            } 
        } else {    
          
         if($id){
             $user = $model->where("id=".$id)->find(); 
         } 
//        var_dump($user);
         $this->assign('user',$user);
         $this->assign('id',$id);            
         $this->display();
        }
    } 
    public function bacthprint(){
    	$this->display();
    }         
        

    public function orderPrint(){
    	$map['status']=5;
    	$map['mailnoimg']=array('neq','');
    	$data=M('order_info')->where($map)->getField('id,mailnoimg',true);

    	if($data){
    		$return['status']=1;
    		$return['data']=$data;
    		$this->ajaxreturn($return);
    	}else{
    		$return['status']=0;
    		$this->ajaxreturn($return);
    	}
    }

    public function orderstatus($id){

    	if(empty($id)){
    		$this->ajaxreturn($result['status']=0);
    		exit();
    	}
    	$map['id']=$id;
    	$data=M('order_info')->where($map)->setField('status',2);

    	if($data){
    		$this->ajaxreturn($result['status']=1);
    		exit();
    	}    
    	$this->ajaxreturn($result['status']=0);
    	exit();
    }

    function expUser(){//导出Excel

        ob_end_clean();

         $dataAry[0][0]="下单日期";
         $dataAry[0][1]="分公司";
         $dataAry[0][2]="订单编号";
         $dataAry[0][3]="产品名称";
         $dataAry[0][4]="数量";
         $dataAry[0][5]="金额";
         $dataAry[0][6]="下单人";

         if(I("REQUEST.date")){
         	$starttime=strtotime(I("REQUEST.date"));
         }else{
         	$starttime=strtotime(date("Y-m-d",time()));
         }

         if(!I("date")){
            $starttime=strtotime(date("Y-m-d",time()));
            $endtime=time();
        }else{

            if(I('datet')==1){

                $starttime=strtotime(date("Y-m-d",strtotime(I("date"))));
                $endtime=strtotime(date("Y-m-d 23:59:59",strtotime(I("date"))));
            }else{
            	$starttime=strtotime(date("Y-m",strtotime(I("date"))));
                $endtime=strtotime(date("Y-m",strtotime("+1 month",strtotime(date("Y-m",strtotime(I("date")))))));
            }
        }
         
        //$map['ori.addtime']=array(array("gt",$starttime),array("lt",$endtime),'and');
        $map['ori.status']=1;

        $order=M('order_info')->alias("ori")
        ->field("FROM_UNIXTIME(ori.addtime,'%Y-%m-%d %H:%i:%S') as addtime,t.address,CONCAT(ori.order_no,' ') as order_no,0 as product,ori.pro_num,ori.total_price,t.username")
        ->join("__STAFF__ as t on t.id=ori.agent",'left')
        ->where($map)->select();
        foreach ($order as $k => $vl) {
            $where['order_no']=$vl['order_no'];
            $product=M("order_goods")->where($where)->select();
            $prt="";
            foreach ($product as $kp => $vp) {
            	$prt.=$vp["product"]."(".$vp["quality"].")".$vp["grade"]."(".$vp["box"].")"."，";
            }
            $order[$k]['product']=$prt;//implode("\n\r,",$product);
            unset($prt);
            $buynum=M("order_goods")->where($where)->getField('buynum',true);
            $order[$k]['pro_num']=implode("\n\r,",$buynum);
        }

        $dataAry=array_merge($dataAry,$order);
        outExcel($dataAry);
    }

     function yexpUser(){//导出Excel

        ob_end_clean();

         $dataAry[0][0]="下单日期";
         $dataAry[0][1]="分公司";
         $dataAry[0][2]="订单编号";
         $dataAry[0][3]="产品名称";
         $dataAry[0][4]="数量";
         $dataAry[0][5]="金额";
         $dataAry[0][6]="下单人";

         if(I("REQUEST.date")){
         	$starttime=strtotime(I("REQUEST.date"));
         }else{
         	$starttime=strtotime(date("Y-m-d",time()));
         }

         if(!I("date")){
            $starttime=strtotime(date("Y-m-d",time()));
            $endtime=time();
        }else{

            if(I('datet')==1){

                $starttime=strtotime(date("Y-m-d",strtotime(I("date"))));
                $endtime=strtotime(date("Y-m-d 23:59:59",strtotime(I("date"))));
            }else{
            	$starttime=strtotime(date("Y-m",strtotime(I("date"))));
                $endtime=strtotime(date("Y-m",strtotime("+1 month",strtotime(date("Y-m",strtotime(I("date")))))));
            }
        }
         
        $map['ori.addtime']=array(array("gt",$starttime),array("lt",$endtime),'and');
        $map['ori.status']=2;

        $order=M('order_info')->alias("ori")
        ->field("FROM_UNIXTIME(ori.addtime,'%Y-%m-%d %H:%i:%S') as addtime,t.address,CONCAT(ori.order_no,' ') as order_no,0 as product,ori.pro_num,ori.total_price,t.username")
        ->join("__STAFF__ as t on t.id=ori.agent",'left')
        ->where($map)->select();
        foreach ($order as $k => $vl) {
            $where['order_no']=$vl['order_no'];
            $product=M("order_goods")->where($where)->select();
            $prt="";
            foreach ($product as $kp => $vp) {
            	$prt.=$vp["product"]."(".$vp["quality"].")".$vp["grade"]."(".$vp["box"].")"."，";
            }
            $order[$k]['product']=$prt;//implode("\n\r,",$product);
            unset($prt);
            $buynum=M("order_goods")->where($where)->getField('buynum',true);
            $order[$k]['pro_num']=implode("\n\r,",$buynum);
        }

        $dataAry=array_merge($dataAry,$order);
        outExcel($dataAry);
    }

}
