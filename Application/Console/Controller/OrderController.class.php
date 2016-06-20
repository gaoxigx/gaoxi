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
    // dump($name);
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
//     dump($name);exit;
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
//
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
public function updatenumberno($id,$numberno){
$data["numberno"] = $numberno;
$data["status"] = 2;
    $order_info = D('order_info');
     $name = $order_info->where('id='.$id)->save($data);
     if($name){
        $this->success("物流单号更新成功");
    }else{
        $this->error('更新数据出错');
    }
}


//数据列表
public function Plist($name=''){
    $username = I('username');

    if($username){
        $map['order_no']  = array('like','%'.trim($username).'%');
        $map['product']  = array('like','%'.trim($username).'%');
        $map['protype']  = array('like','%'.trim($username).'%');  
        $map['username']  = array('like','%'.trim($username).'%');
        //$map['agent']  = array('like','%'.trim($username).'%');
        $map['mobile']  = array('like','%'.trim($username).'%');       
        $map['buyer_wechat']  = array('like','%'.trim($username).'%');
        $map['numberno']  = array('like','%'.trim($username).'%');             
        $map['_logic'] = 'or';
    }

    $user_id = session("user_id");
    if($user_id > 0){
        $map['agent']  = intval($user_id);
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
    $data=$User->select(); 

    
    $count = $User->where($map)->count();// 查询满足要求的总记录数
    $Page = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    $show = $Page->show();// 分页显示输出
    // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
    $list = $User->where($map)->order('id')->limit($Page->firstRow.','.$Page->listRows)->select();

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
     $this->getrolename('agent',38);
     $this->getrolename('assistant', 46);
     $this->getequipment_name();
     $this->getpayment();
    $data = M('product'); // 实例化User对象
    $list = $data->where('1=1')->order('id')->select();
    $this->assign('staff',getstaffname());
    $this->assign('prolist',$list);// 赋值数据集 
    $this->display();

}

//修改订单
public function edit($id){
    // $this->getprotype();
     $this->getrolename('agent',38);
  
     $this->getrolename('assistant',46);
     $this->getequipment_name();
     $this->getpayment();
     $user = M('order_info');
     $orderinfolist = $user->where('id='.$id)->order('id')->find();
//     var_dump($orderinfolist);exit;


     $this->assign('info',$orderinfolist);// 赋值数据集
     $data = M('order_goods'); // 实例化User对象
//     var_dump($data);exit;
     $list = $data->field('*,propic as pic1')->where("order_no='".$orderinfolist['order_no']."'")->order('id')->select();

     $this->assign('prolist',$list);// 赋值数据集
     $this->assign('staff',  getstaffname());
     
    // $this->display();
    $controller   =   M('order_info');
    // 读取数据
    $data =   $controller->find($id);
    if($data) {
        $this->assign('data',$data);// 模板变量赋值
    }else{
        $this->error('数据错误');
    }
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
                   
//                $this->GetProdect($info['order_no']);
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

//插入数据
public function insert(){

    $data = I('');
    $data['order_no'] = $this->build_order_no();
    $data['addtime'] = time();
    $data['status'] = 1;
    $data['payment_status'] = 0;
    // $dataList[] = array('name'=>'thinkphp','email'=>'thinkphp@g.com');
    $roleList   =   D('order_info');
    // $set = $roleList->create($data);

    if($roleList->create()) {
        $result =   $roleList->add($data);
        if($result) {
            foreach( $data['newslist'] as $k=>$v){  
                $dataList[] = array(
                'proid'=>$data["id".$v],
                'protype'=>$data["protype".$v],
                'pic'=>$data["pic".$v],
                'pic1'=>$data["pics".$v],
                'product'=>$data["product".$v],
                'price2'=>$data["price".$v],
                'buynum'=>$data["text_box".$v],
                'discount'=>$data["zhekou".$v],
                'tollsprice'=>$data["total".$v],
                'order_no'=>$data["order_no"],
                'addtime'=>time(),                
                );
            }
// dump($dataList);
// exit;
        $jumpUrl =U('Console/Order/Plist');
            $user=M('order_goods');
            $user->addAll($dataList);
            // $sset = $User->addAll($dataList);

            $this->success('订单表数据添加成功！',$jumpUrl);
        }else{
            $this->error('订单商表数据添加错误！');
        }

    }else{
        $this->error($roleList->getError());
    }
   
}


//更新数据
public function update(){
    $roleList   =   D('order_info');
    $data=$roleList->create();
    if($data) {
        $order_no=$data['order_no'];
        unset($data['order_no']);
        $result = $roleList->where('order_no=%s',array($order_no))->save($data);
        if($result) {
            $this->success('操作成功！');
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
    // dump($id);
    // exit;
    $role = M('order_info');
     $set = $role->where("id = ".$id)->find();
     // $set['oldid'] = $id;
     // unset($set['id']);
     // $set['addtime']=strtotime($set['addtime']);
     $set['price1']= floatval($set['price1']);
     $set['price2']= floatval($set['price2']);
     $set['price3']= floatval($set['price3']);
     // dump($set);
     // exit;
    D('product_copy')->add($set);
    // M('Equipment_copy')->add($set);

    $result = $role->delete($id);

     if($result) {
            $this->success('数据删除成功！');
        }else{
            $this->error('数据删除错误！');
        }
    // $this->display();
}

protected function top(){
    // $this->display();
}

private function hello3(){
    echo '这是private方法!';
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
        
        // dump($orderArray);
        
        // 打印测试===========================================
        // 设置报表标题
        $orderArray ['reportTitle'] = '这是用户基本表';
        
        // 报表中要得到的数据格式
        $xmlReportData = get_reports_xml_byarray ( $orderArray );
        $this->assign ( 'xmlReportData', $xmlReportData );
        // 要打印的报表文件
        $reportName = 'kdd_shentong.grf';
        $this->assign ( 'reportName', $reportName );
        
        // ===========================================
        
        // put_log($xmlReportData);
        
        // 显示模板
        $this->display ( 'Weixin:weixin_orders_print' );
    }
    

}
