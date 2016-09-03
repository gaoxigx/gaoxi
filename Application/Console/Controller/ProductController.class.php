<?php
namespace Console\Controller;
use Think\Controller;
header("Content-type:text/html;charset=utf-8");
class ProductController extends CommonController {
    //产品类别
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

    
   //添加产品类别 
    public function getaddclass(){
      $data   =   D('protype');
        if($data->create()) {
            $result =   $data->add();
            if($result) {
               $this->success('新增成功', '../Product/classes');
            }else{
                $this->error('数据添加错误！');
            }
        }else{
            $this->error($data->getError());
        }
    }
    
    public function details(){
    	$proid=I('id');
    	$map['id']=$proid;
    	$data=M('product')->where($map)->find();
    	M('product')->where($map)->setInc("viewcount");

    	$data['grade']=json_decode($data['grade'],true);    	
    	$this->assign('data',$data);
    	$this->display();
    }

    public function setOrderGoodsPage(){
    	$id=I('id');
    	if(empty($id)){
    		$this->success('订单没有出过该产品');
    		exit();
    	}
    	$px="__PRODUCT__";
    	$result=D('order_goods')->alias('og')->field("pr.*,og.buynum,og.id as gid")->join("$px as pr on pr.id=og.proid",'left')->where('og.id=%d',$id)->find();
    	$result['grade']=json_decode($result['grade'],true);    	
    	if(!$result){
    		$this->success('产品已不存在');
    		exit();
    	}    	
    	$this->assign('data',$result);
    	$this->display();
    }
    
    //产品列表
    public function Plist($name=''){
        $product_name = i('product_name');
		$protype=I('protype');
		
		if($protype){
            $map['protype']  =$protype;
			$parameter['protype'] = $protype;
        }
		
		if($product_name){
            $map['product']  = array('like','%'.trim($product_name).'%');
			$parameter['product_name'] = $product_name;
        }
        
        foreach( $map as $k=>$v){  
            if( !$v )  
                unset( $arr[$k] );  
        }   

        $User = M('Product'); // 实例化User对象
        $count = $User->where($map)->count();// 查询满足要求的总记录数
		
        $Page = new \Think\Page($count,15,$parameter);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show = $Page->show();// 分页显示输出
       
        $list = $User->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->order('salenum desc')->select();
        foreach($list as $key=>$val){
            if($val['purchaseper'] >0){
                $purchaseper_name = D('staff')->where('id='.$val['purchaseper'])->getField('name');
                $list[$key]['purchaseper_name'] = $purchaseper_name;
            }
        }

        $this->getprotype();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display(); // 输出模板
    }
	
	//添加产品
    public function add(){
        $this->getprotype();         
        $list = D('staff')->where('iswork!=%d',array(4))->field('id,name')->select();
        $this->assign('list',$list);
        $this->display();
    }
    

	//插入数据
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
		 
		$jumpUrl =U('Console/Product/Plist');
		$roleList   =   D('product');

		
		$quality=I('quality');
		foreach ($quality as $k => $vo) {
			$aryltmp=I('grade'.$k);
			$arylm=I('money'.$k);
			foreach ($aryltmp as $key => $vl) {
					$grade[$vo][$vl]=$arylm[$key];	
			}
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

	//编辑    
	public function edit($id = 0){		
		$this->getprotype();
		$controller = D('product');
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
		$this->assign('list',$list);
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
			foreach ($aryltmp as $key => $vl) {
					$grade[$vo][$vl]=$arylm[$key];	
			}
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
		
		$roleList   =   D('product');
		$jumpUrl =U('Console/Product/Plist');
		if($roleList->create()) {
			$result = $roleList->where('id=%d',array($id))->save($data);
			if($result) {
				$this->success('修改成功！',$jumpUrl);
			}else{
				$this->error('写入错误！');
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
	
 //删除数据
    public function delete(){
		$id=i('id');
        $role = M('product');
        $result = $role->delete($id);

         if($result) {
			$this->success('数据删除成功！');
		}else{
			$this->error('数据删除错误！');
		}
    }
    
    
     //删除产品分类
    public function deletept(){
        $id=i('id');
        $controller   =   M('Product');
        
        $data =  $controller->where('protype=%d',$id)->find();
        if($data ){
            $this->error('分类里面有产品，不允许删除！');
            exit();
        }
        $classes = M('protype');
        $result = $classes->delete($id);
        if($result) {
                  $this->success('数据删除成功！');
         }else{
                  $this->error('数据删除错误！');
          }

    }

    public function examine(){
		$id = I('id');
        $controller   =   M('Product');
        $data =  $controller->find($id);
		
		$staffinfo = $this->GetStaffInfo($data['purchaseper']);
		$data['purchaseper_name'] = $staffinfo['username'];
		
        if($data) {
            $this->assign('productinfo',$data);// 模板变量赋值
        }else{
            $this->error('数据错误');
        }
        $this->display();        
    }
    
    
    //产品分类
    public function classes(){
        $product_name = i('product_name');
		$protype=I('protype');
		
		if($protype){
            $map['protype']  =$protype;
			$parameter['protype'] = $protype;
        }
		
		if($product_name){
            $map['product']  = array('like','%'.trim($product_name).'%');
			$parameter['product_name'] = $product_name;
        }
        
        foreach( $map as $k=>$v){  
            if( !$v )  
                unset( $arr[$k] );  
        }   

        $User = M('protype'); // 实例化User对象
        $count = $User->where($map)->count();// 查询满足要求的总记录数
		
        $Page = new \Think\Page($count,20,$parameter);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show = $Page->show();// 分页显示输出
       
        $list = $User->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->order('typename desc')->select();
//        var_dump($list);exit();
        foreach($list as $key=>$val){
            if($val['purchaseper'] >0){
                $purchaseper_name = D('staff')->where('id='.$val['purchaseper'])->getField('name');
                $list[$key]['purchaseper_name'] = $purchaseper_name;
            }
        }

        $this->getprotype();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display(); // 输出模板
    }
    
}
