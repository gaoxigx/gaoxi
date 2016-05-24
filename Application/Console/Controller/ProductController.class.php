<?php
namespace Console\Controller;
use Think\Controller;
header("Content-type:text/html;charset=utf-8");
class ProductController extends CommonController {
    //产品类别
    private function getprotype(){
        $data=D('protype');
        $name = $data->where('typename<>""')->order('id desc')->select();
        // dump($name);
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
    
    //数据列表
    public function Plist($name=''){
         $username = i('username');
        if($username){
            $where['product']  = array('like','%'.trim($username).'%');
            $where['purchaseper']  = array('like','%'.trim($username).'%');                 
            $where['_logic'] = 'or';
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
//        var_dump($data);
//        
//        exit();
        $count = $User->where($where)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->setConfig('header','个会员');
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $User->where($where)->order('id')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->getprotype();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display(); // 输出模板


    }
//添加
    public function add(){
         $this->getprotype();
         $this->display();
    }
    

//插入数据
    public function insert(){


    // 上传文件 
    $data = I('');
    $data['addtime'] = time();

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
    // 上传文件 

    $info   =   $upload->upload();

    if(!$info) {// 上传错误提示错误信息
        // $this->error($upload->getError());
    }else
        {// 上传成功 获取上传文件信息
           
            foreach($info as $file){
                $data['pic'] = $file['savepath'].$file['savename'];
                $image = new \Think\Image(); 
                $image->open($config['rootPath'].$data['pic']);
                //将图片裁剪为400x400并保存为corp.jpg
                $image->thumb(200, 200)->save($config['rootPath'].$file['savepath'].'s'.$file['savename']);
                $data['pic1'] = $file['savepath'].'s'.$file['savename'];
            }
        }    
 
        $roleList   =   D('product');
        if($roleList->create()) {
            $result =   $roleList->add($data);
            if($result) {
                $this->success('数据添加成功！');
            }else{
                $this->error('数据添加错误！');
            }
        }else{
            $this->error($roleList->getError());
        }
       
    }



//编辑
    public function edit($id=0){
        $this->getprotype();
    $controller   =   M('product');
    // 读取数据
    $data =   $controller->find($id);
    if($data) {
        $this->assign('data',$data);// 模板变量赋值
    }else{
        $this->error('数据错误');
    }
    $this->display();


     }


//更新数据
    public function update(){
    $data = I('');


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
    // 上传文件 

    $info   =   $upload->upload();
    if(!$info) {// 上传错误提示错误信息
         // $this->error($upload->getError());
    }else
        {// 上传成功 获取上传文件信息
            foreach($info as $file){
            $data['pic'] = $file['savepath'].$file['savename'];
            $image = new \Think\Image(); 
            $image->open($config['rootPath'].$data['pic']);
            //将图片裁剪为400x400并保存为corp.jpg
            $image->thumb(200, 200)->save($config['rootPath'].$file['savepath'].'s'.$file['savename']);
            $data['pic1'] = $file['savepath'].'s'.$file['savename'];
            }
        }
    

// dump($data);
// exit();
    $roleList   =   D('product');
    if($roleList->create()) {
        $result = $roleList->save($data);
        if($result) {
            $this->success('操作成功！');
        }else{
            $this->error('写入错误！');
        }
    }else{
        $this->error($roleList->getError());
    }
 }

 //删除数据
    public function delete(){
    $id=i('id');
        // dump($id);
        // exit;
        $role = M('product');
         $set = $role->where("id = ".$id)->find();
         // $set['oldid'] = $id;
         // unset($set['id']);
         // $set['addtime']=strtotime($set['addtime']);
         $set['price1']= floatval($set['price1']);
         $set['price2']= floatval($set['price2']);
         $set['price3']= floatval($set['price3']);
         // dump($set);
         // exit;
        D('product_copy')->add($set);
        // M('Equipment_copy')->add($set);

        $result = $role->delete($id);

         if($result) {
                $this->success('数据删除成功！');
            }else{
                $this->error('数据删除错误！');
            }
        // $this->display();
    }

    protected function top(){
        // $this->display();
    }

    private function hello3(){
        echo '这是private方法!';
    }
}
