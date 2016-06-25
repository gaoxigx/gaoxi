<?php
namespace Console\Controller;
use Think\Controller;
class CheckRecordController extends CommonController{
	/**
     *考勤记录列表  
     */
	public function index(){
		$list = D('CheckRecord')->where($map)->order('gh_id asc,datetime desc')->select();
		foreach($list as $k=>$v){
			$data['calculated'] = $v['calculated'];
		}
		
		$this->assign('data',$data);
		$this->assign('list',$list);
		$this->display();
	} 
	
	/**
     *修改页面
     */
	public function edit(){
		$id =I('id');
		$data = D('CheckRecord')->where('id=%d',array($id))->find();
		
		$this->assign('data',$data);
		$this->display();
	}
	
    /**
     *保存数据
     */
    public function SaveData(){
		$jumpUrl = U('Console/CheckRecord/index');
		$CheckRecord = D('CheckRecord');
		$id =I('id');
		
		$data = $CheckRecord->create();
		$data['datetime'] = strtotime($data['datetime']);
		$data['working_time'] = strtotime($data['working_time']);
		$data['leave_time'] = strtotime($data['leave_time']);
		
		if($id > 0){
			$data['modifytime'] = time();
			$result = $CheckRecord->where('id=%d',array($id))->save($data);
		}else{
			$data['createtime'] = time();
			$result = $CheckRecord->add($data);
		}
		
		if($result){
            $this->success('保存成功',$jumpUrl);
        }else{
			$this->error('保存失败'); 
        }
	}

    /**
     *删除数据
     */
    public function delete(){
        $id = I('id');
        $CheckRecord = D('CheckRecord');
        $result = $CheckRecord->delete($id);
		
        if($result) {
            $this->success('删除成功！');
        }else{
            $this->error('删除失败！');
        }
    }
	
	/**
     *导入表格-导入考勤记录
     */
    public function ExcelCheckRecord(){
            $files = $_FILES['excel'];
            $excel = new \Console\Controller\ExcelController();
            $columns = D('CheckRecord')->getColumn();
            $result = $excel->Excel($files,$columns,'CheckRecord',1,5,1,1);
            if($result['result'] > 0){
                $this->success('导入成功');
            }else{
                $this->error($result['msg']);
            }
    }
}
