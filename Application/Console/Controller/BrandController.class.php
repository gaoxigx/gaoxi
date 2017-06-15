<?php
namespace Console\Controller;
use Think\Controller;
header("Content-type:text/html;charset=utf-8");
class BrandController extends CommonController {
	protected $brand;

	function __construct(){
		parent::__construct();
		$this->brand=M("brand");
	}

	//品牌列表
    public function index(){
    	$count=M("brand")->count();    
    	$page=new \Think\Page($count,15);
    	$datalist=$this->brand->limit($Page->firstRow.','.$Page->listRows)->select();
    	$this->assign("list",$datalist);
    	$this->assign("page",$page->show());
    	$this->display();
    }
    //品牌编辑
    public function edit(){
    	$this->display();
    }
    //品牌更新
   	public function update(){
   		$this->ajaxreturn($data);
   	}
   	//品牌删除
   	public function delete(){
   		$id=I("id");
   		if(empty($id)){
   			$this->ajaxreturn(array("status"=>5));
   		}
   		try{
   			$sul=$this->brand->delete($id);
   			if($sul){
   				$data['status']=1;
   			}
   			else{
   				$data['status']=2;
   			}
   		}catch(\Exception $e){
   			$data['status']=3;
   		}

   			if($data['status']==1){
   				$this->success("数据删除成功");	
   			}else{
   				$this->error("数据有误");	
   			}
   				
   	}
   	//品牌增加
   	public function add(){
   		$this->display();
   	}
   	//品牌插入
   	public function insert(){ 
   		if(!empty(I("request.brandname"))){
   			$this->ajaxreturn(array('status'=>4));
   			exit();
   		}
   		if(!M("brand")->autoCheckToken(I(""))){
   			$this->ajaxreturn(array("status"=>5));
   			exit();
   		}
   		$requery['brandname']=I('brandname');	

   		try{
   			$sul=M("brand")->add($requery);
	   		if($sul){
	   			$data['status']=1;
	   		}else{
	   			$data['status']=2;
	   		}
   		}
   		catch(\Exception $e){
   			var_dump($e);exit();
   			$data['status']=3;
   		}
   		finally{
   			if($data['status']==1){
   				$this->success("数据增加成功");	
   			}else{
   				$this->error("数据有误");	
   			}
   		}

   		
   	}
}
