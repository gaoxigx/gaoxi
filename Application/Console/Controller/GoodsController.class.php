<?php

namespace Console\Controller;

use Think\Controller;

class GoodsController extends Controller {
    
    // 物流页面
    public function index() {
    	$data=D('goods');
        $sul = $data->order('id desc')->select();
        $this->assign('list',$sul);
        $this->display ();
    }

    public function edit(){
    	$this->getprotype();
		$controller = D('goods');
		//读取数据
		$data = $controller->find($id);
		$data['quality']=json_decode($data['quality'],true);
		$data['grade']=json_decode($data['grade'],true);
		$data['box']=json_decode($data['box'],true);	

		if($data){
			$this->assign('data',$data);
			$list = D('staff')->where('iswork!=%d',array(4))->field('id,name')->select();
		}elseif($id){
			$this->error('数据错误');
		}    
		$kind=M('kind')->select();
		$this->assign('kind',$kind);
		$this->assign('list',$list);
		$this->display();
    }
    //入库信息
    public function purche(){

    	$list=D("coding")->field("cd.*,gs.name")->alias("cd")->join("__GOODS__ gs on gs.coding=cd.code",'left')->order("cd.createtime","desc")->select();
    	$this->assign('list',$list);
    	$this->display();
    }

    //出库信息
    public function sell(){
    	$this->display();
    }

    	//更新数据
    public function update(){
		$data = I('');
		$id = I('id');
		$uploadimg = $this->uploadimg();
		if($uploadimg['pic'] != ''){
			$data['pic'] = $uploadimg['pic'];
		}
		if($uploadimg['pic1'] != ''){
			$data['pic1'] = $uploadimg['pic1'];
		}
                
        $quality=I('quality');
		foreach ($quality as $k => $vo) {
			$aryltmp=I('grade'.$k);
			$arylm=I('money'.$k);
			$weight=I('weight'.$k);

			foreach ($aryltmp as $key => $vl) {
					$tempary=array($arylm[$key],$weight[$key]);
					$grade[$vo][$vl]=$tempary;	
			}
			unset($data['weight'.$k]);
			unset($data['money'.$k]);
			unset($data['grade'.$k]);
		}
		unset($data['quality'.$k]);

		$data['grade']=json_encode($grade);
		$data['quality']=json_encode($quality);
		
		//礼盒数据
		$boxname=I('boxname');
		$boxvl=I('boxvl');
		foreach ($boxname as $k => $vo) {
			$box[$vo]=$boxvl[$k];	
		}
		unset($data['boxname']);
		unset($data['boxvl']);
		$data['box']=json_encode($box);
		$data['price']=I('price');	
		$roleList   =   D('goods');
		$jumpUrl =U('Console/Product/Plist');
		if($prodata=$roleList->create($data)) {	
			$result = $roleList->where('id=%d',array($id))->save($prodata);
			if($result) {
				$this->success('修改成功！',$jumpUrl);
			}else{
				$this->error('写入错误！');
			}
		}else{
			$this->error("写入错误！");
		}
	}

     private function getprotype(){
        $data=D('protype');
        $name = $data->where('typename<>""')->order('id desc')->select();
       
        $this->assign('protype',$name);

    }

    public function getaddtype(){
      $data   =   D('protype');
        if($data->create()) {
            $result =   $data->add();
            if($result) {
               $this->success('新增成功', '../Product/add');
            }else{
                $this->error('数据添加错误！');
            }
        }else{
            $this->error($data->getError());
        }
    }

    
    public function delete($id){
    	if($id){
    		$codeing=M("Goods");
    		try{
    			if($codeing->delete($id)){
    				$sul['status']=1;
    			}else{
    				$sul['status']=2;
    			}
    		}catch(\exception $ex){
    			$sul['status']=0;
    		}
    	}else{
    		$sul['status']=3;
    	}
    	if($sul['status']==1){
    		$this->success("成功删除");
    	}else{
    		$this->error("数据有误");	
    	}
    	
    }

    public function add(){
    	$this->getprotype();
        $list = D('staff')->where('iswork!=%d',array(4))->field('id,name')->select();
//        $kind = M('kind')->field('id,kindname')->select();
//        $this->assign('kind',$kind);
        $this->assign('list',$list);

        $this->display();
    }

    public function insert(){
    	$data = I('');
		$data['addtime'] = time();
		$data['sortnum'] =I('inputnum');

		$uploadimg = $this->uploadimg();
		if($uploadimg['pic'] != ''){
			$data['pic'] = $uploadimg['pic'];
		}
		if($uploadimg['pic1'] != ''){
			$data['pic1'] = $uploadimg['pic1'];
		}
		 
		$jumpUrl =U('Console/Goods/index');
		$roleList   =   D('Goods');

		
		$quality=I('quality');
		foreach ($quality as $k => $vo) {
			$aryltmp=I('grade'.$k);
			$arylm=I('money'.$k);
			$weight=I('weight'.$k);			
			foreach ($aryltmp as $key => $vl) {
				$tempary=array($arylm[$key],$weight[$key]);
				$grade[$vo][$vl]=$tempary;	
			}
			unset($data['weight'.$k]);
			unset($data['money'.$k]);
			unset($data['grade'.$k]);
		}
		unset($data['quality'.$k]);

		$data['grade']=json_encode($grade);
		$data['quality']=json_encode($quality);

		
		//礼盒数据
		$boxname=I('boxname');
		$boxvl=I('boxvl');
		foreach ($boxname as $k => $vo) {
			$box[$vo]=$boxvl[$k];	
		}
		unset($data['boxname']);
		unset($data['boxvl']);
		$data['box']=json_encode($box);	
		$data['price']=I('price');

		if($roleList->create()) {
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
    /**
	 *上传产品图片
	 */
	protected function uploadimg(){
		$file_image = $_FILES['pic'];
		$config = array(
			'maxSize'    =>    31457280,
			'rootPath'   =>    '.',
			'savePath'   =>    '/Uploads/',
			'saveName'   =>    array('uniqid',''),
			'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
			'autoSub'    =>    true,
			'subName'    =>    array('date','Ymd'),
		);
		$upload = new \Think\Upload($config);// 实例化上传类
		
		if($file_image['name'] != ''){
			$info   =   $upload->upload();
			if(!$info) {
				  $this->error($upload->getError());
			}else{
				foreach($info as $file){
					$data['pic'] = $file['savepath'].$file['savename'];
					$image = new \Think\Image(); 
					$image->open($config['rootPath'].$data['pic']);
					//将图片裁剪为400x400并保存为corp.jpg
					$image->thumb(200, 200)->save($config['rootPath'].$file['savepath'].'s'.$file['savename']);
					$data['pic1'] = $file['savepath'].'s'.$file['savename'];
				}
			}
		}
		return $data;
	}
}
