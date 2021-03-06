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
		     
		$staff=D('staff')->where('iswork != 4')->getfield('id,name,section,departmenttext,quarters,posttext,nibs',true);
		foreach ($staff as $k => $v) {
			$cat[$v['nibs']][]=$staff[$k];
			$dep_staff[$v['section']]['section']= $v['section'];
			$dep_staff[$v['section']]['departmenttext']= $v['departmenttext'];
			$dep_staff[$v['section']]['staff'][] = $staff[$k];
		}
		
		$this->assign('cat',$cat);
		$this->assign('staff',$dep_staff);
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
        *月份报表
        */
	public function MonthPer(){
            $year_num = 10;
            $cur_year = date('Y',time());
            for($i=0;$i<$year_num;$i++){
               $year[] = $cur_year-$i; 
            }
            $this->assign('yearlist',$year);
             $this->display();
	}    
        
	/**
	 *年份报表
	 */
	public function YearPer(){
        $this->display();
	}
	
	/**
	 *根据员工id获取业绩
	 */
	public function GetPerformance(){
		$agent = I('agent');
		$total_ruleid = I('total_ruleid');
		
		$data = D('Performance')->GetPelPer($agent,$total_ruleid,1);
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
		$data = D('Performance')->GetPelPer($agents,$total_ruleid);
		$this->ajaxReturn($data);
	}
        
        /*
         * 获取月份报表
         */
	public function GetMonthPer(){
		$total_ruleid = I('total_ruleid');
                $cur_year = I('cur_year');
                $data = D('Performance')->GetPelPer('',$total_ruleid,0,1,'',$cur_year);
		$this->ajaxReturn($data);
	}
        
        
	/**
	 *获取年份报表
	 */
	public function GetYearPer(){
		$total_ruleid = I('total_ruleid');
                $data = D('Performance')->GetPelPer('',$total_ruleid,0,1,10);
		$this->ajaxReturn($data);
	}
	
}
?>