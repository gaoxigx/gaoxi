<?php
namespace Console\Controller;
use Think\Controller;
header("Content-type:text/html;charset=utf-8");
class EquipmentController extends CommonController {
    
    //数据列表
    public function Equipment($name=''){
         $staff=D('staff')->where('iswork!=4')->getField('id,name',true);
         $staffid=D('staff')->where('iswork!=4')->getthislevel();//当前职员下的员工；
 
        $this->assign('staff',$staff);
		$addr_id = i('addr_id');
		$username = i('username');
		if($addr_id != 0 && $addr_id != ''){
			//$where['addr_id'] = $addr_id;
			$parameter['addr_id'] = $addr_id;
		}

	
        if($username){            
            
            $parameter['username'] = $username;
           
			$where['cdkey']  = array('like','%'.trim($username).'%');
			$where['xinghao']  = array('like','%'.trim($username).'%');
            $where['bianhao']  = array('like','%'.trim($username).'%');
            $where['daiyanren']  = array('like','%'.trim($username).'%');
            $where['et.mobile']  = array('like','%'.trim($username).'%');
            $where['weixinhao']  = array('like','%'.trim($username).'%');
            $where['yxemail']  = array('like','%'.trim($username).'%');
            $where['fuzeren']  = array('like','%'.trim($username).'%');            
            $where['shiyongren']  = array('like','%'.trim($username).'%');   
            $where['_logic'] = 'or';
            $tw=$where;
        }
        
        if($addr_id){
            $where1['_complex'] = $where;
            
            $where1['addr_id'] = $addr_id;
            $tw=$where1;
        }
       
        
        foreach( $map as $k=>$v){  
            if( !$v )  
                unset( $arr[$k] );  
        }   

        //分页跳转的时候保证查询条件
        foreach($username as $key=>$val) {
            if(!$val){
                unset($map[$key]);
            }else{
                $Page->parameter[$key]   =   urlencode($val);
            }
        }
        $User = M('Equipment'); // 实例化User对象
        if(session('roleidstaff')){

            $strmap=implode(',', $staffid);            
            if(!empty($strmap)&&isset($strmap)&&strlen($strmap)>0){ 
                if(session('userid')==107){

                }else{
                    $where['staffid']=array("in",implode(',', $staffid));//  
                }
            }else{
                $where['staffid']=$staffid;//session('userid');
            }
            
        }    

//        var_dump($where);exit();
        
        $count = $User->alias('et')->where($tw)->count();// 查询满足要求的总记录数
        
        $Page = new \Think\Page($count,15,$parameter);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->setConfig('header','个会员');
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $User->field('et.*,sf.section,sf.departmenttext,sf.quarters,sf.posttext')->alias('et')
                ->join('nico_staff as sf on sf.id=et.staffid','left')->where($tw)
                ->order('et.storzd desc,et.fuzerenid desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
		$this->assign('count',$count);
        $this->assign('param',$parameter);
        $this->display(); // 输出模板
    }
    public function img(){
        $Verify = new \Think\Verify();
        $Verify->entry(1);
    }
    
    public function add(){

        //得到所有职员
        $where['quarters']=array('in',"38,39,67");
        $where['iswork']=array('neq',4);   
//        $staff=D('staff')->where('iswork!=4')->select();
        $staff=D('Staff')->where($where)->getField('id,name',true);
        $this->assign('staff',$staff);
        //得到所有部门
        $department=D('Category')->department();
        $this->assign('staff', $staff);
        $this->assign('department',$department);
        $this->assign('userid',session('userid'));
        $this->display();
    }
//添加设备信息
    public function insert(){
		$jumpUrl =U('Console/Equipment/Equipment');
        $roleList   =   D('equipment');
        $data=$roleList->create();
        if($data) {
            $result =   $roleList->add($data);
            if($result) {
                $this->success('数据添加成功！',$jumpUrl);
            }else{
                $this->error('数据添加错误！');
            }
        }else{
            $this->error($roleList->getError());
        }
       
    }

//编辑
    public function edit($id=0){
        $controller   =   M('Equipment');
        
        $data =   $controller->find($id);
        if($data) {
            $staff=D('staff')->where('iswork!=4')->select();
            $department=D('Category')->department();		
            $this->assign('staff',$staff);
            $this->assign('department',$department);
            $this->assign('data',$data);
        }else{
            $this->error('数据错误');
        }
        $this->display();

    }


//更新数据
    public function update(){
        $roleList   =   D('Equipment');
		$jumpUrl =U('Console/Equipment/Equipment');
        if($roleList->create()) {
            $result = $roleList->save();
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
        $role = M('Equipment');
        $set = $role->where("id = ".$id)->find();
        M('Equipment_copy')->add($set);
        $result = $role->delete($id);
		
        if($result) {
            $this->success('数据删除成功！');
        }else{
            $this->error('数据删除错误！');
        }
    }

    protected function top(){
        // $this->display();
    }

    private function hello3(){
        echo '这是private方法!';
    }

    public function tracking(){
        $id=I('get.id');
		if(!I('get.id')){
            $this->error('请进入列表页，选择连接设备！');
        }
        $map['id']=I('get.id');
        $track=D('Equipment')->where($map)->find();
		$staffinfo = D('Staff')->where('id='.$track['staffid'])->find();
		$track['shiyongren'] = $staffinfo['username'];
        $mapq['equipment_id']=I('get.id');
        $trackeq=D('Eqtracking')->where($mapq)->order('gettime asc')->select();
		foreach($trackeq as $k=>$v){
			$staffinfo = D('Staff')->where('id='.$v['shiftid'])->find();
			$trackeq[$k]['shift_staffname'] = $staffinfo['username'];
		}
		
        $eqtrack = $this->getEqtrack($id);
		$login_user = session ('userid');
		
		
		$this->assign('eqtrack',$eqtrack);
		$this->assign('login_user',$login_user);
		$this->assign('dataq',$trackeq);
        $this->assign('vo',$track);
        $this->display();
    }
	
	protected function getEqtrack($id){
		$data = D('Eqtracking')->where('status!=1 and equipment_id='.$id)->select();
		foreach($data as $k=>$v){
			$Equipmentinfo = D('Equipment')->where('id='.$v['equipment_id'])->find();
			$data[$k]['xinghao'] = $Equipmentinfo['xinghao'];
		}
		return $data;
	}
	
    public function staffequipment(){
        if(!I('get.id')){
            $this->error('请进入列表页，选择连接设备！');
        }
        $map['staffid']=I('get.id');
        $track=D('Equipment')->field('et.*,sf.section,sf.departmenttext,sf.quarters,sf.posttext')->alias('et')
                ->join('nico_staff as sf on sf.id=et.staffid','left')->where($map)->select();
        $this->assign('data',$track);
        $this->display();
    }
    public function trackadd(){    
        $id = I('id');
		$Equipmentinfo=D('Equipment')->where('id='.$id)->field('id,staffid')->find();
		$staffinfo = D('Staff')->where('id='.$Equipmentinfo['staffid'])->find();
		$Equipmentinfo['shift_staffname'] = $staffinfo['username'];
		
		$stall=D('staff')->where('iswork!=4')->field('id,name')->select(); 
		
        $this->assign('staff',$stall);
		$this->assign('Equipmentinfo',$Equipmentinfo);
        $this->display();
    }
    public function trackincrease(){

        $track=D('Eqtracking');
        $data=$track->create();  
        if($data){
           try{
                M()->startTrans();
                $data['gettime']=time();
                $data['status']=2;
                $data['type']=1;
				
				$shiftinfo_map['equipment_id'] = $data['equipment_id'];
				$shiftinfo_map['staff_id'] = $data['shiftid'];
				$shiftinfo = D('Eqtracking')->where($shiftinfo_map)->order('gettime desc')->find();
				
				if(!empty($shiftinfo) && $shiftinfo['status'] != 1){
					$this->ajaxreturn(array('status'=>2,'msg'=>'此设备还有未领取的信息'));
				}else{
					$result=$track->add($data);
				}
				
                $map['id']=$data['equipment_id'];

                $update['staffid']=$data['staff_id'];
                $update['shiyongren']=$data['staff_name'];
                $update['roleeffect']=$data['get'];
                $sul=D('Equipment')->where($map)->save($update);

                if($result&&$sul){                    
                    M()->comment();
                    $this->ajaxreturn(array('status'=>1,'msg'=>'添加成功'));
                }else{
                    M()->rollback();
                    $this->ajaxreturn(array('status'=>2,'msg'=>'添加失败'.$sul));        
                    exit();
                }

            }catch(Exception $e){
                M()->rollback();
                $this->ajaxreturn(array('status'=>2,'msg'=>'添加失败'));                
            }
          
        }else{
            $this->ajaxreturn(array('status'=>2,'msg'=>'数据有误,请再操作一次'.$track->geterror()));
        }
        
    }
	//确认领取设备
	public function verify(){
		$id=I('id');
		$status=I('status');
		$data['status'] = $status;
		$data['gettime'] = time();
		
		$Eqtracking = D('Eqtracking')->where('id=%d',array($id))->setField($data);
		if($Eqtracking){
			$this->ajaxreturn(array('result'=>1,'msg'=>'领取成功'));
		}else{
			$this->ajaxreturn(array('result'=>0,'msg'=>'领取失败'));
		}
	}
	
	
    public function lookfpind(){
        $this->display();
    }
    
//查看设备详情页面
    public function eqlook(){
        $id=I('get.id');        
        $model = D('Equipment');     

        if($id){
             $user = $model->alias('et')->where("et.id=".$id)->join('nico_staff as sf on sf.id=et.staffid','left')->find(); 
         } 
		$eqtrack = $this->getEqtrack($id);
		$Equipment = D('Equipment')->where('staffid='.$id)->select();
		$order_nums = D('OrderInfo')->where('agent='.$id)->count();
		
		if($user['quarters'] > 0){
			$section_map['cate_id']=$user['quarters'];
			$quarters = D('Category')->field('cate_name')->where($section_map)->find();
			
			$map['cate_parent']=$user['quarters'];
			$subordinates = D('Category')->categoryone($map);
			
			$ids = D('Staff')->getotherlevel($id);
			$ids = implode($ids,',');
			if($ids !=''){
				$subordinatesUsers = D('Staff')->where('id in ('.$ids.')')->select();
			}
		}             
                
            $mapq['equipment_id']=I('get.id');
            $trackeq=D('Eqtracking')->where($mapq)->order('gettime asc')->select();
                    foreach($trackeq as $k=>$v){
                            $staffinfo = D('Staff')->where('id='.$v['shiftid'])->find();
                            $trackeq[$k]['shift_staffname'] = $staffinfo['username'];
                    }

		$login_user = session ('userid'); 
		$this->assign('dataq',$trackeq);
		$this->assign('login_user',$login_user);
		$this->assign('eqtrack',$eqtrack);
		$this->assign('quarters',$quarters);
		$this->assign('subordinates',$subordinates);
		$this->assign('Equipment',$Equipment);
		$this->assign('subordinatesUsers',$subordinatesUsers);
        $this->assign('user',$user);
        $this->assign('id',$id);
        $this->display();

    }
    
}
