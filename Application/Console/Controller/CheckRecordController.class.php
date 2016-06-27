<?php
namespace Console\Controller;
use Think\Controller;
class CheckRecordController extends CommonController{
	/**
     *考勤记录列表  
     */
	public function index(){
		$name = I('name');
		$dep_name = I('dep_name');
		$datetime = I('datetime');
		if($name != ''){
			$map['name'] = array('like','%'.$name.'%');
			$parameter['name'] = $name;
		}
		if($dep_name != ''){
			$map['dep_name'] = array('like','%'.$dep_name.'%');
			$parameter['dep_name'] = $dep_name;
		}
		
		if($datetime != '' && is_numeric($datetime)){
			$map['datetime'] = $datetime;
			$parameter['datetime'] = $datetime;
		}else if($datetime != ''){
			$map['datetime'] = strtotime($datetime);
			$parameter['datetime'] = strtotime($datetime);
		}
		
		$CheckRecord = D('CheckRecord');
		$count = $CheckRecord->where($map)->count();// 查询满足要求的总记录数
        
		
		$Page = new \Think\Page($count,17,$parameter);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $CheckRecord->where($map)->order('gh_id asc,datetime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach($list as $k=>$v){
			$data['calculated'] = $v['calculated'];
		}
		
		$this->assign('data',$data);
		$this->assign('map',$parameter);
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	} 
	
	/**
     *添加页面
     */
	public function add(){
		$department = D('Category')->where('cate_parent=0')->getField('cate_id,cate_name');
		
		$this->assign('department',$department);
		$this->display();
	}
	
	/**
     *修改页面
     */
	public function edit(){
		$id =I('id');
		$data = D('CheckRecord')->where('id=%d',array($id))->find();
		$department = D('Category')->where('cate_parent=0')->getField('cate_id,cate_name');
		
		$this->assign('department',$department);
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
