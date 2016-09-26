<?php
namespace Console\Controller;
use Think\Controller;
header("Content-type:text/html;charset=utf-8");

class OrderController extends CommonController {
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

	public function catlist(){
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性	
		$count=M('product')->count();
		$Page = new \Think\Page($count,50);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show = $Page->show();// 分页显示输出	
		
		$data=M('product')->limit($Page->firstRow.','.$Page->listRows)->order('viewcount desc')->select();

		$this->assign('page',$show);// 赋值分页输出
		$this->assign('data',$data);
		$this->display();	
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
		echo "物流信息查询：".$no;
		// $this->$this->display(??)
		// $this->display('plist');
	 }

	 //插入物流单号到订单表
	public function updatenumberno(){

		$id=I('post.id');
		$data["numberno"] = I("post.numberno");
		 
		if(!$data["numberno"] && !$id){
			$data['status']=0;
			$data['msg']="数据错误";
		}

		$data["status"] = 2;
		$order_info = D('order_info');
		$name = $order_info->where('id='.$id)->save($data);
		if($name){
			 	$data['status']=1;
			 	$data['msg']="发布加成功";

		}else{
		 		$data['status']=0;
			 	$data['msg']="发布数据错误";
		}
	}


	//数据列表
	public function Plist($name=''){
		$username = I('username');

		if($username){             
                        $map1['id']  = trim($username);                             
                        $map1['agent']  =M('staff')->where(array('name'=>array('like','%'.trim($username).'%')))->getField('id'); 
			$map1['order_no']  = array('like','%'.trim($username).'%'); 
			$map1['username']  = array('like','%'.trim($username).'%'); 
			$map1['_logic'] = 'OR';
			$map['_complex'] = $map1;
		}
          
                
		$user_id = session("userid");
		if($user_id > 0 ){
			$where['id']=session("userid");
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

        
        
	//未发货订单
	public function wfhorder($name=''){
		$username = I('username');

		if($username){
                        $map1['id']  = trim($username); 
                        $map1['agent']  =M('staff')->where(array('name'=>array('like','%'.trim($username).'%')))->getField('id');     
			$map1['order_no']  = array('like','%'.trim($username).'%'); 
			$map1['username']  = array('like','%'.trim($username).'%'); 
			$map1['_logic'] = 'OR';
			$map['_complex'] = $map1;
		}

                
		$user_id = session("userid");
		if($user_id > 0 ){
			$where['id']=session("userid");
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
        
        
	//
	public function  withdrawList(){
		$username = I('username');

		if($username){
                        $map1['id']  = trim($username); 
                        $map1['agent']  =M('staff')->where(array('name'=>array('like','%'.trim($username).'%')))->getField('id'); 
			$map1['order_no']  = array('like','%'.trim($username).'%'); 
			$map1['username']  = array('like','%'.trim($username).'%'); 
			$map1['_logic'] = 'OR';
			$map['_complex'] = $map1;
		}

		$user_id = session("userid");
		if($user_id > 0 ){
			$where['id']=session("userid");
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
		$map['status']=4;
		$User = M('order_info'); // 实例化User对象 		
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

	//审核列表
	public function shPlist($name=''){
		$username = I('username');

		if($username){
			$map1['id']  = trim($username); 
                        $map1['agent']  =M('staff')->where(array('name'=>array('like','%'.trim($username).'%')))->getField('id');  
			$map1['order_no']  = array('like','%'.trim($username).'%'); 
			$map1['username']  = array('like','%'.trim($username).'%'); 
			$map1['_logic'] = 'OR';
			$map['_complex'] = $map1;
		}


		$user_id = session("userid");
		if($user_id > 0 ){
			$where['id']=session("userid");
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
		$map['status']=3;
		$User = M('order_info'); // 实例化User对象 		
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
            if(!$staff){
                  $staff=D('Staff')->getField('id,name',true);
            }
  
//            
//            $this->getrolename('agent',68);
//            $this->getrolename('assistant', 67);
           // $this->getequipment_name();
            $this->getpayment();
		$data = M('product'); // 实例化User对象
//                var_dump($data);exit();
                 
		$promap['ct.uid']=session('userid');

		//$list = $data->alias('pro')->field('ct.*,pro')->join('__CART__ as ct on ct.proid=pro.id','left')->where($promap)->order('pro.id')->select(); 
		$list=M('cart')->alias('ct')->field('ct.*,pro.pic1,pro.product')->join('__PRODUCT__ as pro on ct.proid=pro.id','left')->where($promap)->order('ct.id')->select();

                $this->assign('staff', $staff);
		$this->assign('prolist',$list);// 赋值数据集 

		$this->display();
	}

	//修改订单
	public function edit($id){
		 $this->getrolename('agent',38);

		 $this->getrolename('assistant',46);
		 $this->getequipment_name();
		 $this->getpayment();
		 $orderinfo = M('order_info');
		 $orderinfolist = $orderinfo->where('id='.$id)->find();	
		 if($orderinfolist['status']==2){
		 	$this->success('订单已确认发货，不允许修改');
		 	exit();
		 }

		$ordergoods = M('order_goods'); // 实例化User对象
		$list = $ordergoods->alias('og')
				 ->join('nico_product as np on np.id = og.proid')
				 ->field('og.*,np.pic as pic1,og.buynum as number')
				 ->where("og.order_no=%s",array($orderinfolist['order_no']))
				 ->order ('id')
				 ->select();
		
		$this->assign('info',$orderinfolist);
		$this->assign('prolist',$list);
		$this->assign('staff',  getstaffname());
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
		$products = $datagoods->where('order_no="'.$order_no.'"')->order('id desc')->select();  
		return $products;            
    }

	//插入数据
	public function insert(){
		$data = I('');
		$data['order_no'] = $this->build_order_no();
		$data['addtime'] = time();
		$data['status'] = 1;
		$data['payment_status'] = json_encode(I('payment_status'));
	
		$roleList   =  D('order_info');

		$mid['c.uid']=session('userid');
		$catdata=M('cart')->alias('c')->field('c.*,p.id as proid,p.protype,p.pic,p.pic1,p.product')->join('__PRODUCT__ p on c.proid=p.id')->where($mid)->select();

		if(!$catdata){
			$this->error('请先添加购买产品',U('Order/catlist'));
			exit();
		}


		if($roleList->create()) {						
			M()->startTrans();
				$tlm=0;
				foreach( $catdata as $k=>$v){  
					$dataList[] = array(
						'proid'=>$v['proid'],							
						'protype'=>$v['protype'],
						'pic'=>$v['pic'],
						'pic1'=>$v['pic1'],
						'product'=>$v['product'],
						'price2'=>$v['money'],
						'money'=>$v['money'],
						'buynum'=>$v['number'],
						'discount'=>$v['number'],
						'tollsprice'=>$v['number']*$v['money'],
						'quality'=>$v['quality'],
						'grade'=>$v['grade'],
						'box'=>$v['box'],
						'order_no'=>$data["order_no"],
						'addtime'=>time(),   
						'numkg'=>$v['numkg']             
					);
					$arm=explode('-',$v['money']);
					$tlm=$arm[0]>$arm[1]?$tlm+$arm[1]:$tlm+$arm[0];
				}	
				if($data['total_price']<$tlm){
					$data['status']=3;//订单异常
				}	

				$result=$roleList->add($data);
				if(!$result) {
					M()->rollback();
					$this->error('订单商表数据添加错误！'.M()->geterror());
				}
	
				//删除购物车产品
				M("cart")->where('uid=%d',session('userid'))->delete();	
				//增加产品图片
				$result=M('order_goods')->addAll($dataList);
		

				if($result){
					M()->commit();	
					$this->success('订单表数据添加成功！',U('Console/Order/Plist'));
				}else{
					$this->error('订单商表数据添加错误！'.M()->geterror());
					M()->rollback();
				}
		}else{
			$this->error('生成数据错误'.$roleList->getError());
		}
	   
	}


	//更新数据
	public function update(){
		$roleList   =   D('order_info');
		$data=$roleList->create();
		$jumpUrl =U('Console/Order/Plist');		
		if($data) {
			$order_no=$data['order_no'];
			unset($data['order_no']);
			$result = $roleList->where('order_no=%s',array($order_no))->save($data);
			if($result) {
				$this->success('操作成功！',$jumpUrl);
			}else{
				$this->error('写入错误！');
			}
		}else{
			$this->error($roleList->getError());
		}
	}

	//删除数据
	public function delete(){
		$id=i('id');
		$role = M('order_info');
		$result = $role->delete($id);

		if($result) {
			$this->success('数据删除成功！');
		}else{
			$this->error('数据删除错误！');
		}
	}

	public function setstatu(){
		$id=I('id');
		$data['status']=0;
		if(empty($id)){        			
			$this->ajaxreturn($data);
			exit();
		}     

		$status= D('order_info')->where('id=%d',array($id))->getfield('status');
		if($status==3){
			$sul=D('order_info')->where('id=%d',array($id))->setField('status',1);
			if($sul){
				$data['status']=1;
			}
		}

		$this->ajaxreturn($data);
		exit();

	}
	//撤消
	public function withdraw(){
		$id=I('id');

		$data['status']=0;
		if(empty($id)){        			
			$this->ajaxreturn($data);
			exit();
		}
		$status= D('order_info')->where('id=%d',array($id))->getfield('status');


		if($status==1){
			//撤消
			$sul=D('order_info')->where('id=%d',array($id))->setField('status',4);
			if($sul){
				$data['status']=1;
			}
		}

		$this->ajaxreturn($data);
		exit();

	}



	//查找下级经济人 ajax请求
	public function getagent(){
		$id=I('id');
                
 		if($username){
			$map1['order_no']  = array('like','%'.trim($username).'%'); 
			$map1['username']  = array('like','%'.trim($username).'%'); 
			$map1['_logic'] = 'OR';
			$map['_complex'] = $map1;
		}
               
                
		if(empty($id)){        
			$this->ajaxreturn(0);
		}  
                
        $map['nibs']=$id;
        $map['iswork']=array("neq",4);

		$data=D('staff')->where($map)->getfield('id,name',true);

		if($data){
			$this->ajaxreturn($data);    
		}else{
			$this->ajaxreturn(0);
		}
		
	}

	public function cart(){
		$data['proid']=I('id');
		if(!$data['proid']){
			$this->error('您没有选择对应产品');
			exit();
		}
		
		$data['uid']=session('userid');
		If(!$data['uid']){
			$this->error('请重新登入');
			exit();
		}
		$data['catetime']=time();
		$data['quality']=trim(I('quality'));
		$data['grade']=trim(I('grade'));
		$data['money']=I('money');
		$data['number']=I('nmb');
		$data['numkg']=I('numkg');

		$map['status']=$data['status']=1;
		$cart=M('cart');
		$map['proid']=$data['proid'];
		$map['uid']=session('userid');		
		$map['quality']=I('quality');
		$map['grade']=I('grade');

		$count=$cart->where($map)->count();
		if($count>0){
			$this->error('该产品已存在购物车');
			exit();
		}
		$sul=$cart->add($data);
		if($sul){
			$this->success('已加入购物车',U('Order/add'));
			exit();
		}else{
			$this->error('增加失败');
			exit();
		}
	}

	public function productinfo(){
		$data['id']=I('id');
		if(!$data['id']){
			$this->error('您没有选择对应产品');
			exit();
		}		
		$data['catetime']=time();
		$data['quality']=I('quality');
		$data['grade']=I('grade');
		$data['money']=I('money');
		$data['numkg']=I('numkg');		
		$data['buynum']=I('nmb');

		$map['og.id']=$data['id'];
		$orderGoodes=M('order_goods');

		$result=$orderGoodes->alias("og")->field('or.id')->join('__ORDER_INFO__ as `or` on or.order_no=og.order_no','left')->where($map)->find();

		if(!$result){
			$this->error('该产品不存在订单中');
			exit();
		}

		$sul=$orderGoodes->save($data);


		if($sul){
			$this->success('修改完成',U('Order/edit',array('id'=>$result['id'])));
			exit();
		}else{
			$this->error('增加失败');
			exit();
		}
	}

	public function cartdel(){
		$map['proid']=I('id');		
		if(!$map['proid']){
			$this->error('您没有选择对应产品');
			exit();
		}	
		$map['uid']=session('userid');
		$sul=M('cart')->where($map)->delete();
		if($sul){
			$this->success('删除成功');
			exit();
		}else{
			$this->error('删除失败');
			exit();
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
	
	public function issue(){
		$url="https://{domain}/rest/v1.0/order/access_token/{access_token}/sf_appid/{sf_appid}/sf_appkey/{sf_appkey}";
		$this->display();
	}
        

        
//        public function exportExcel($expTitle,$expCellName,$expTableData){
//        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
//        $fileName = $_SESSION['account'].date('_YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
//        $cellNum = count($expCellName);
//        $dataNum = count($expTableData);
//        vendor("PHPExcel.PHPExcel");
//       
//        $objPHPExcel = new PHPExcel();
//        $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
//        
//        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
//       // $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'  Export time:'.date('Y-m-d H:i:s'));  
//        for($i=0;$i<$cellNum;$i++){
//            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]); 
//        } 
//          // Miscellaneous glyphs, UTF-8   
//        for($i=0;$i<$dataNum;$i++){
//          for($j=0;$j<$cellNum;$j++){
//            $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), $expTableData[$i][$expCellName[$j][0]]);
//          }             
//        }  
//        
//        header('pragma:public');
//        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
//        header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
//        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
//        $objWriter->save('php://output'); 
//        exit;   
//    }    

/**
     *
     * 导出Excel
     */
    function expUser(){//导出Excel
        
//$dataAry = array(
//    array(NULL, 2010, 2011, 2012),
//    array('Q1',   12,   15,   21),
//    array('Q2',   56,   73,   86),
//    array('Q3',   52,   61,   69),
//    array('Q4',   30,   32,    0),
//   );
// 
//create_xls($dataAry);
//        
//        $dataAry=M('order_info')->where('id<100')->find();
        $dataAry[0][0]="sdfsdfsd";
        $dataAry[0][1]="sdfsdfsd";
        $dataAry[0][2]="sdfsdfsd";
        $dataAry[0][3]="sdfsdfsd";
        $dataAry[0][4]="sdfsdfsd";
        outExcel($dataAry);
        
//        $xlsName  = "User";
//        $xlsCell  = array(
//        array('addtime','成交日期'),
//        array('total_price','金额'),
//        array('payment_method','支付方式'),
//        array('username','联系人'),
//        array('mobile','联系人电话'),
//        array('address','联系人地址'),
//        array('note','备注'),
//        array('agent','开单人')
//        );
//        $xlsModel = M('order_info');
//    
//        $xlsData  = $xlsModel->Field('id,addtime,total_price,payment_method,username,mobile,address,note,agent')->select();
//        
//        foreach ($xlsData as $k => $v)
//        {
//            $xlsData[$k]['payment_method']=$v['payment_method']==1?'微信':'其他支付方式';
//        }
//        $this->exportExcel($xlsName,$xlsCell,$xlsData);
         
    }
        
}
