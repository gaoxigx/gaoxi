<?php
namespace Console\Controller;
use Think\Controller;
class CommonController extends Controller{
	public function __construct(){
		header("Content-Type: text/html; charset=utf-8");

		parent::__construct();
		if(session('username')){
			$this->assign('leftmenu',CONTROLLER_NAME);
			$this->assign('lml',ACTION_NAME);
		}else{
			//$this->error('您还没有登录！',);
			redirect(U('Console/Login/login'));
		}

		if(!$this->access()){			
			$this->error('您无权操作该控制器');
		}


	}
	public function tip($result,$title){
		if ($result) {
			$this->success($title.'成功！');
		}else{
			$this->error($title.'失败！');
		}
	}	


 	private function access(){
 		$page=CONTROLLER_NAME."/".ACTION_NAME; 
 
 		$t=explode(',',strtoupper(C('NOT_AUTH_MODULE')));
 		if(in_array(strtoupper($page),$t)){
 			return true;
 		}

 		$map['role_id']=session('roleidstaff');
 		if(!$map['role_id']){
 			return true;
 		}

 		$role=D('node')->where($map)->getField('title',true); 		
 		if(!in_array($page,$role)){
 			return true;
 		}
 		$result=D('access')->where($map)->getField('module',true);
 		if($result){
 			if(in_array($page,$result)){
 				return true;
 			}
 		}
 		return false;
 	}
	
	public function GetStaffInfo($id){
		$staff_info = D('Staff')->where('id=%d',array($id))->find();
		return $staff_info;
	}
	
	/**
	 *返回迟到早退分钟，return array
	 *$depname:部门名称
	 *$vworktime:上班打卡时间，时间戳格式
	 *$vleavetime:下班打卡时间，时间戳格式
	 */
	protected function Islate($depname,$vworktime,$vleavetime){
		$dep_name = ($depname == '行政部')?'行政人事部':$depname;
		$worktime = D('Category')->field('cate_id,gotime,totime')->where("cate_name='".$dep_name."'")->find();
		
		$gotime = $worktime['gotime'];
		$totime = $worktime['totime'];
		if($gotime != ''){
			$work_time = strtotime(date('H:i',$gotime));
		}else{
			$work_time = strtotime('09:00');
		}
		$worked_time = $vworktime != 0?strtotime(date('H:i',$vworktime)):0;
		$late_min = ($worked_time-$work_time)/60;
		if($worked_time > $work_time && $worked_time > 0){
			$ll_arr['late_min'] = $late_min;
		}else{
			$ll_arr['late_min'] = 0;
		}
		
		if($totime != ''){
			$to_time = strtotime(date('H:i',$totime));
		}else{
			$to_time = strtotime('18:00');
		}
		$leave_time = $vleavetime != 0?strtotime(date('H:i',$vleavetime)):0;
		if($leave_time < $to_time && $leave_time > 0){
			$leave_min = ($to_time-$leave_time)/60;
			if($leave_min > 0){
				$ll_arr['leave_min'] = $leave_min;
			}
		}else{
			$ll_arr['leave_min'] = 0;
		}
		return $ll_arr;
	}
}