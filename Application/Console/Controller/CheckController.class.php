<?php
namespace Console\Controller;
use Think\Controller;
class CheckController extends CommonController{
    /**
     *导入表格
     */
    public function ExcelCheck(){
            $files = $_FILES['excel'];
            $excel = new \Console\Controller\ExcelController();
            $columns = D('check')->getColumn();
            $result = $excel->Excel($files,$columns,'check',1,5,1);
            if($result['result'] > 0){
                    $this->success('导入成功');
            }else{
                    $this->error($result['msg']);
            }
    }

    
//列表    
public function index(){
	$keywords = I('keywords');
	$count_year = I('count_year');
	$count_month = I('count_month');
	
	if($count_year != '' && $count_year != 0 && ($count_month == '' || $count_month == 0)){
		$map["FROM_UNIXTIME(count_time,'%Y')"] = $count_year;
		$parameter['count_year'] = $count_year;
		
	}else if($count_month != '' && $count_month != 0 && ($count_year == '' || $count_year == 0)){
		$count_year = date('Y',time());
		$map['count_time'] = strtotime($count_year.'-'.$count_month);
		
		$parameter['count_year'] = $count_year;
		$parameter['count_month'] = $count_month;
	}else if($count_year != '' && $count_year != 0 && $count_month != '' && $count_month != 0){
		$map['count_time'] = strtotime($count_year.'-'.$count_month);
		
		$parameter['count_year'] = $count_year;
		$parameter['count_month'] = $count_month;
	}
	
	if($keywords != ''){
		$map1['name'] = array('like','%'.$keywords.'%');
		$map1['bumen'] = array('like','%'.$keywords.'%');
		$map1['_logic'] = 'or';
		$map['_complex'] = $map1;
		$parameter['keywords'] = $keywords;
	}
	$count = D('check')->where($map)->count();// 查询满足要求的总记录数
      
	$Page = new \Think\Page($count,17,$parameter);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    $show = $Page->show();// 分页显示输出
	
	$list = D('check')->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
    foreach($list as $k=>$v){
        $data['calculated'] = $v['calculated'];
    }
    
	
	
    $this->assign('data',$data);
    $this->assign('list',$list);
	$this->assign('parameter',$parameter);
	$this->assign('page',$show);
    $this->display();
}  
    
    
//添加
public function add(){
    $jumpUrl = U('Console/Check/index');
    $check = D('check');
    if(!IS_POST){
        $check_list = D('check')->select();
		$cur_year = date('Y',time());
		
		$this->assign('cur_year',$cur_year);
        $this->assign('check_list',$check_list);
        $this->display();
        exit;
    }
    $super = $check->create();
    if($super){
        $super['hours_bz']=$super['hours_bz']*3600+$super['hours_bz1']*60;
        $super['hours_sj']=$super['hours_sj']*3600+$super['hours_bz1']*60;
        $super['overtime_ts']=$super['overtime_ts']*3600+$super['overtime_ts1']*60;
        $super['overtime_zc']=$super['overtime_zc']*3600+$super['overtime_zc1']*60;
        unset($super['hours_sj1']);
        unset($super['hours_bz1']);
        unset($super['overtime_ts1']);
        unset($super['overtime_zc1']);
		
		$count_time = I('count_year');
		if(I('count_month') != 0){
			$count_time .= '-'.I('count_month');
		}
		$super['count_time'] = strtotime($count_time);
		
        $iphone = $check->add($super);
        if($iphone){
            $this->success('添加成功',$jumpUrl);
        }  else {
           $this->error('添加失败'); 
        }
    }
    else {
		$this->assign('data',$super);
        $this->error($super->getError());    
      }
}

//修改
    public function edit(){
        $jumpUrl =U('Console/Check/index'); 
        $check = D('check'); 
        $id =I('id');
        if (IS_POST) {
            if ($id > 0) {
                $super=$check->create();  
                $super['hours_bz'] = $super['hours_bz']*3600 + I('hours_bz1')*60;
                $super['hours_sj'] = $super['hours_sj']*3600 + I('hours_sj1')*60;  
                $super['overtime_ts'] = $super['overtime_ts']*3600 + I('overtime_ts1')*60;
                $super['overtime_zc'] = $super['overtime_zc']*3600 + I('overtime_zc1')*60;
				
				$count_time = I('count_year');
				if(I('count_month') != 0){
					$count_time .= '-'.I('count_month');
				}
				$super['count_time'] = strtotime($count_time);
				
                $iphone=$check->save($super);
                if ($iphone){
                    $this->success('修改成功', $jumpUrl);
                } else {                    
                    $this->error('修改失败',$jumpUrl);
                }          
            } 
        } else {                                    
            $super=D('check')->where('id='.$id)->find();
            $super['count_month'] = date('n',$super['count_time']);
//            $s5_data = D('s5')->select();
//            $this->assign('s5_data',$s5_data);
            $this->assign('info',$super);
            $this->display();
        }
    }   

    
     //删除数据
    public function delete(){
        $id = I('id');
        $role = M('check');
        
        $iphone = $role->delete($id);
        if($iphone) {
            $this->success('数据删除成功！');
        }else{
            $this->error('数据删除错误！');
        }
    }

}
