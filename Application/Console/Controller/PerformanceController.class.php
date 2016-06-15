<?php 
namespace Console\Controller;
use Think\Controller;
class PerformanceController extends CommonController{
	public function index(){
		$this->display();
	}
	public function staff(){
		$department=D('Category')->field('cate_id,cate_name,cate_parent')->select();
		
		
		$staff=D('staff')->getfield('id,name,quarters,nibs',true);
		foreach ($staff as $k => $v) {
			$cat[$v['nibs']][]=$staff[$k];
		}
		
		$this->assign('department',$department);
		$this->assign('cat',$cat);

		$this->assign('staff',$staff);
        $this->display();
	}

}
?>