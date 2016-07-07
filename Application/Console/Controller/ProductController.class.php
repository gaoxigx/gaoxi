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
    
    //产品列表
    public function Plist($name=''){
        $username = i('username');
        if($username){
            $map['product']  = array('like','%'.trim($username).'%');
            $map['purchaseper']  = array('like','%'.trim($username).'%');                 
            $map['_logic'] = 'or';
        }
        //选择分类查询
        $protype=I('protype');
        if($protype){
            $map['protype']  =$protype;
        }
        
        foreach( $map as $k=>$v){  
            if( !$v )  
                unset( $arr[$k] );  
        }   

        //分页跳转的时候保证查询条件
        foreach($username as $key=>$val) {
            if(!$val){
                unset($map[$key]);
            }else{
            $Page->parameter[$key]   =   urlencode($val);
              }
        }
        
        $User = M('Product'); // 实例化User对象
        $data=$User->select();
        $count = $User->where($map)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,6);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->setConfig('header','个会员');
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $User->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$val){
            if($val['purchaseper'] >0){
                $purchaseper_name = D('staff')->where('id='.$val['purchaseper'])->getField('name');
                $list[$key]['purchaseper_name'] = $purchaseper_name;
            }
        }
//        print_r($list);exit;
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
	$uploadimg = $this->uploadimg();
	$data['pic'] = $uploadimg['pic'];
	$data['pic1'] = $uploadimg['pic1'];
     
	$jumpUrl =U('Console/Product/Plist');
	$roleList   =   D('product');
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
}
