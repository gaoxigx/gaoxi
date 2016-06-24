<?php 
namespace Console\Controller;
use Think\Controller;
class PerformanceController extends CommonController{
	public function index(){
		$this->display();
	}
	/**
	 *员工报表
	 */
	public function Staff(){
		$department=D('Category')->field('cate_id,cate_name,cate_parent')->select();
		
		$staff=D('staff')->where('iswork != 4')->getfield('id,name,quarters,posttext,nibs',true);
		foreach ($staff as $k => $v) {
			$cat[$v['nibs']][]=$staff[$k];
		}
		
		$this->assign('department',$department);
		$this->assign('cat',$cat);
		$this->assign('staff',$staff);
        $this->display();
	}
	/**
	 *部门报表
	 */
	public function Department(){
		$department=D('Category')->where('cate_parent=%d',array(0))->field('cate_id,cate_name,cate_parent')->select();
		
		$this->assign('department',$department);
        $this->display();
	}
	
	/**
	 *根据员工id获取业绩
	 */
	public function GetPerformance(){
		$agent = I('agent');
		$total_ruleid = I('total_ruleid');
		
		$data = D('Performance')->GetPelPer($agent,$total_ruleid);
		$this->ajaxReturn($data);
	}
	
	/**
	 *根据部门id获取业绩
	 */
	public function GetDepPer(){
		$department = I('department');
		$total_ruleid= I('total_ruleid');
		if(isset($department) && $department>0){
			$agents = D('Performance')->GetStaffByDep($department);
		}
		foreach($agents as $k=>$v){
			$pelPer = D('Performance')->GetPelPer($v['id'],$total_ruleid);
			$departmentlist[] = $pelPer;
		}
		$taotal=array();
		foreach($departmentlist as $key=>$val){
			foreach($val['list'] as $k1=>$v1){
				$taotal[$k1]+=$v1['total_price'];
			}
		}
		
		$data['total_ruleid'] = $total_ruleid;
		$data['list'] = $taotal;
		$this->ajaxReturn($data);
	}
	
	
}
?>