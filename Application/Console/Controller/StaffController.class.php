<?php
namespace Console\Controller;
use Think\Controller;
header("Content-type:text/html;charset=utf-8");
class StaffController extends CommonController {

    //员工组分类
    private function getprotype(){
        $data=D('role');
        $name = $data->where('status=1')->order('id')->select();
//         dump($name);
        $this->assign('roletype',$name);
    }

    /**
     * 查找下级分类
    **/
    public function department(){
        $data=D('Category')->department();
        $this->ajaxReturn($data);
    }
    
    /*
     * 查询考勤人数
     */
    public function check(){
        $user = D('Staff');
        $data= $user->group('iswork')->getfield('iswork,count(*)');
            return $data;
//        $data['userCount1'] = $user->where('iswork = 1')->count();
//        $data['userCount2'] = $user->where('iswork = 2')->count();
//        $data['userCount3'] = $user->where('iswork = 3')->count();
//        $data['userCount4'] = $user->where('iswork = 4')->count();
        }

    
	/**
     * 查找下级包含子级分类
    **/
    public function allcate(){
		$id= I('get.catid');
		$data = $this->allcatedata($id);
		$this->ajaxReturn($data);
    }
	
	protected function allcatedata($id){
		$map['cate_parent']=$id;
		$map['status']=1;
		$data=D('Category')->field('cate_id,cate_name')->where($map)->select();    
		
		if(!empty($data)){
			foreach ($data as $k => $v) {
				$data2 = $this->allcatedata($v['cate_id']);
				if(!empty($data2)){
					//$data[$k]['child'] = $data2;
					$data = array_merge($data,$data2);
				}
			}        
		}   
		
		return $data;
	}
	
    
    public function deparlist(){
        if(I('post.accounts')){
            $map['cate_name']=array('like','%'.I('post.accounts').'%');
        }
        $data=D('Category')->department($map);
        $this->assign('data',$data);
        $this->display();
    }

//数据列表
    public function Staff($name=''){
		$ids = D('Staff')->getthislevel();
		$ids = implode($ids,',');
		
		if($ids !='' && session('roleidstaff') > 0){
			$map['id']  = array('in',trim($ids));
		}
		$department=I('department');
		if($department){
			$map['section']=$department;
			$this->assign('section',$department);
		}

		$quarters=I('quarters');
		if($quarters){
			$map['quarters']=$quarters;
			$this->assign('quarters',$quarters);
		}
		
        $username = i('username');
        if($username){
            $map1['nickname']  = array('like','%'.trim($username).'%');
            $map1['username']  = array('like','%'.trim($username).'%');
            $map1['name']  = array('like','%'.trim($username).'%');
            $map1['section']  = array('like','%'.trim($username).'%');
            $map1['identity_card']  = array('like','%'.trim($username).'%');
            $map1['mobile']  = array('like','%'.trim($username).'%');              
            $map1['_logic'] = 'or';
            $map['_complex'] = $map1;
        }
       if(isset($_GET['iswork'])){
           $map['iswork']=I('iswork');
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
        $User = M('Staff'); // 实例化User对象
        $data=$User->select();
		
        $count = $User->where($map)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->setConfig('header','个会员');
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $User->where($map)->order('section desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		
        $catdata=D('Category')->categoryone();  
        $education=array(0=>"请选择",1=>"大专", 2=>"本专",3=>"研究生",4=>"在校大专",5=>"在校本科",6=>"高中",7=>"中专",8=>"初中");
        $department=D('Category')->categoryone(array('cate_parent'=>0));
        
        $count_data = $this->check();
        $this->assign('education',$education);
        $this->assign('department',$department);
        $this->assign('cat',$catdata);
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('count_data',$count_data);
        $this->display(); // 输出模板


    }

 //添加页面
    public function add($value='')
    {
        $this->getprotype();
        $data=D('Category')->department();
        $this->assign('department',$data);
        $this->display();
    }
	/**
	 *查看当前用户个人信息
	 */
	public function UserInfo(){
		$userid = session('userid');
		$accounts = session('username');
		$userinfo = D('Controller');
        $data = $userinfo ->where('id='.$userid.' and accounts="'.$accounts.'"')->find();
		if(!empty($data)){
			$this->redirect('Personnel/AdminInfo', array('id' => $userid));
		}else{
			$userinfo = D('Staff');
			$data = $userinfo ->where('id='.$userid.' and username="'.$accounts.'"')->find();
			$this->redirect('Staff/lookover', array('id' => $userid));
		}
	}
 //查看页面
    public function lookover(){
        $id=I('get.id');        
        $model = D('Staff');     

        if($id){
             $user = $model->where("id=".$id)->find(); 
         } 
		
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
		
		$this->GetCateName();
		$this->GetEducationName();
		 $this->assign("stafftake",D('StaffTake')->tackfind(I("id")));
         $this->assign('department',D('Category')->department());

		 $this->assign('quarters',$quarters);
		 $this->assign('subordinates',$subordinates);
		 $this->assign('Equipment',$Equipment);
		 $this->assign('order_nums',$order_nums);
		 $this->assign('subordinatesUsers',$subordinatesUsers);
         $this->assign('user',$user);
         $this->assign('id',$id);
         $this->display();

    }
	
	/**
	 *所有类别名称，根据分类ID获取名称，键值为分类ID
	 */
	public function GetCateName(){
		$catdata=D('Category')->categoryone();  
		$this->assign('cat',$catdata);
	}
	
	/**
	 *所有学历名称，根据键值获取名称
	 */
	public function GetEducationName(){
		$education=array(0=>"请选择",1=>"大专", 2=>"本专",3=>"研究生",4=>"在校大专",5=>"在校本科",6=>"高中",7=>"中专",8=>"初中");
        $this->assign('education',$education);
	}
	
	/**
	 *根据类id查找人员
	 */
	public function GetSupervisor(){
		$cate_id = I('post.cate_id');
		//$user = D('Staff')->where('quarters=%d',array($cate_id))->getfield('id,name',true);		
		$user = D('Staff')->getfield('id,name',true);			
		$this->ajaxReturn($user);
	}
	
//插入员工数据
    public function insert(){
        $jumpUrl =U('Console/Staff/Staff');
        $roleList   =   D('Staff');
        $data=$roleList->create();
        if($data) {
            $data['password']=md5(I('post.password'));
            $data['entry_time']=strtotime(I('post.entry_time'));
            $data['graduation_date']=strtotime(I('post.graduation_date'));
            $data['birth_date']=strtotime(I('post.birth_date'));
			
			$catdata=D('Category')->categoryone();
			$subordinates_data = array();
			foreach($data['subordinates'] as $k=>$v){
				if($v == 0){
					unset($data['subordinates'][$k]);
				}else{	
					$subordinates_data[] = $v;
					$data['subordinatetexts'] .= $catdata[$v].',';
				}
			}
			
			$data['section'] = array_shift($subordinates_data);
			$data['departmenttext'] = $catdata[$data['section']];
			$data['quarters'] = array_pop($subordinates_data);
			$data['posttext'] = $catdata[$data['quarters']];
			
			$data['subordinatetexts'] = substr($data['subordinatetexts'],0,-1);
			$data['subordinates'] = implode(',',$data['subordinates']);
			
            $result =   $roleList->add($data);
			
            if($result) {
				//echo json_encode(array('result'=>1));
                $this->success('数据添加成功！', $jumpUrl);
            }else{
              //  echo json_encode(array('result'=>0));
			   $this->error('数据添加错误！', $jumpUrl);
            }
        }else{
           // echo json_encode(array('result'=>-1));
			$this->error($roleList->getError());
        }
       
    }

//修改员工资料
    public function edit($id=0){        
        $jumpUrl =U('Console/Staff/Staff'); 
        $id = (int)$id;
        $model = D('Staff');          
        if (IS_POST) {
            $id = I('post.id');           
            if ($id > 0) {           
            $data=$_POST;
                $map['id']=$id;
                if($data){
                    $data['entry_time']=strtotime(I('post.entry_time'));
                    $data['graduation_date']=strtotime(I('post.graduation_date'));
                    $data['birth_date']=strtotime(I('post.birth_date'));
                    $data['education']=I('post.education1');
					$data['starttime']=strtotime(I('post.starttime'));
					$data['endtime']=strtotime(I('post.endtime'));
					$data['positivetime']=strtotime(I('post.positivetime'));
					$data['leavetime']=strtotime(I('post.leavetime'));
					
					$catedata=D('Category')->categoryone();
					
					$subordinates_data = array();
					foreach($data['subordinates'] as $k=>$v){
						if($v == 0){
							unset($data['subordinates'][$k]);
						}else{	
							$subordinates_data[] = $v;
							$data['subordinatetexts'] .= $catedata[$v].',';
						}
					}
					
					$data['section'] = array_shift($subordinates_data);
					$data['departmenttext'] = $catedata[$data['section']];
					$data['quarters'] = array_pop($subordinates_data);
					$data['posttext'] = $catedata[$data['quarters']];
					
					$data['subordinatetexts'] = substr($data['subordinatetexts'],0,-1);
					$data['subordinates'] = implode(',',$data['subordinates']);
					
                    $result=$model->where($map)->save($data);                       
                    if ($result){
                        $this->success('修改成功', $jumpUrl);
                    } else {                    
                        $this->error('修改失败', $jumpUrl);
                    }
                }else {                    
                    $this->error('生成数据错误', $jumpUrl);
                }

            } 
        }else {            
         if($id){
             $user = $model->where("id=".$id)->find(); 
         } 
           
			if($user['subordinates']){      
                $str = strrpos($user['subordinates'],',');
				/*if($str){
					$index = $str;
				}else{
					$index = strlen($user['subordinates']);
				}
				
				$post=D('Category')->field('cate_parent,cate_id,cate_name')->where("cate_parent in(%s)",array(substr($user['subordinates'],0,$index)))->select();//getfield('cate_id,cate_name',true);
               
				foreach ($post as $k => $v) {
                   $potview[$v['cate_parent']][]=array('cate_id'=>$v['cate_id'],'cate_name'=>$v['cate_name']);
                }*/
				/*
				if($str > 0){
					$post=D('Category')->field('cate_parent,cate_id,cate_name')->where("cate_parent in(%s)",array(substr($user['subordinates'],0,$str)))->select();//getfield('cate_id,cate_name',true);
							   
					foreach ($post as $k => $v) {
					   $potview[$v['cate_parent']][]=array('cate_id'=>$v['cate_id'],'cate_name'=>$v['cate_name']);
					}
				}else{
					$potview=array();
				}*/
				if($user['quarters'] != ''){
					$potview = $this->allcatedata($user['section']);
				}
				
				
                $this->assign("post",$potview);
            }
            //$postview=array_combine(array_column($post,"cate_parent"),$post);

			$this->assign('department',D('Category')->department());
            
			$this->assign('user',$user);
			$this->assign('id',$id);
			$this->display();
        }
    }     
	
	/**
	 *密码重置-123456
	 */
	 public function resetpass(){
		$id = I('post.id',0);
		$data=array('password'=>md5('123456'));
		
		$controller = M('Staff');
		$staff_info = $controller->where('id='.$id)->find();
		if($data['password'] == $staff_info['password']){
			$result = 0;
			$msg = '已是默认密码';
		}else{
			$result = $controller->where('id='.$id)->save($data);
		}
		
		if(!$msg){
			$msg = '密码重置失败';
		}
		
		if($result > 0){
			echo json_encode(array('result'=>1));
		}else{
			echo json_encode(array('result'=>0,'msg'=>$msg));
		}
		
	}
	
//更修改密码
public function passupdate(){
$id = I('session.userid',0);
// echo getdate();
// exit;
    if(I('post.oldpassword')=='' && I('post.oldpassword')==''){
        $this->error('新旧密码必须填写！');
    }else{
        $oldpassword = md5(I('post.oldpassword'));
        $dataa["password"] = md5(I('post.password'));
        // dump(md5(I('post.password')));
        // exit();

        $controller = M('Staff');
        $data = $controller ->where('id='.$id.' and password="'.$oldpassword.'"')->find();
        if($data) {
                $result = $controller->where('id='.$id)->save($dataa);
                // dump($result);
                // exit();
                if($result) {
                    $this->success('密码更新成功！');
                }else{
                    $this->error('写入错误！');
                }
            }else{
            $this->error('原密码错误');
            }

    }

 }
//更新数据
    public function update(){
    $roleList   =   D('Staff');
    $data=$roleList->create();
    if($data) {
        $data['entry_time']=strtotime(I('post.entry_time'));
        $data['graduation_date']=strtotime(I('post.graduation_date'));
        $data['birth_date']=strtotime(I('post.birth_date')); 
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

 //删除数据
    public function delete(){
    $id=i('id');
        // dump($id);
        // exit;
        $role = M('Staff');
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
    
}