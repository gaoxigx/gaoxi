<?php
namespace Console\Controller;
use Think\Controller;
header("Content-type:text/html;charset=utf-8");
class EquipmentController extends CommonController {




//数据列表
    public function Equipment($name=''){

        // echo "string";
        // $this->show('','utf-8');
        $username = i('username');
        if($username){
            $where['xinghao']  = array('like','%'.trim($username).'%');
            $where['bianhao']  = array('like','%'.trim($username).'%');
            $where['daiyanren']  = array('like','%'.trim($username).'%');
            $where['mobile']  = array('like','%'.trim($username).'%');
            $where['weixinhao']  = array('like','%'.trim($username).'%');
            $where['fuzeren']  = array('like','%'.trim($username).'%');            
            $where['shiyongren']  = array('like','%'.trim($username).'%');                    
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
        $User = M('Equipment'); // 实例化User对象
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
//        var_dump($list);
//        exit();
        //echo $User->getLastSql();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display(); // 输出模板


    }

    

//添加设备信息
    public function insert(){
        $jumpUrl =U('Console/Equipment/Equipment');
        $roleList   =   D('equipment');
        if($roleList->create()) {
            $result =   $roleList->add();
            if($result) {
                $this->success('数据添加成功！', $jumpUrl);
            }else{
                $this->error('数据添加错误！', $jumpUrl);
            }
        }else{
            $this->error($roleList->getError());
        }
       
    }

//编辑
    public function edit($id=0){
    $controller   =   M('Equipment');
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
    $roleList   =   D('Equipment');
    if($roleList->create()) {
        $result = $roleList->save();
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
        $role = M('Equipment');
         $set = $role->where("id = ".$id)->find();
         // $set['oldid'] = $id;
         // unset($set['id']);
         dump($set);
         // exit;
        M('Equipment_copy')->add($set);
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

    public function tracking(){
        if(!I('get.id')){
            $this->error('请进入列表页，选择连接设备！');
        }
        $map['id']=I('get.id');
        $track=D('Equipment')->where($map)->find();
        $mapq['equipment_id']=I('get.id');
        $trackeq=D('Eqtracking')->where($mapq)->order('gettime asc')->select(); 
        $this->assign('dataq',$trackeq);
        $this->assign('vo',$track);
        $this->display();
    }
    public function trackadd(){    
        $stall=D('staff')->field('id,name')->select();   
        $this->assign('staff',$stall);
        $this->display();
    }
    public function trackincrease(){
        $track=D('Eqtracking');
        $data=$track->create();  
        if($data){
            $data['gettime']=time();
            $data['status']=1;
            $data['type']=1;
            $result=$track->add($data);
            $map['equipment_id']=$data['equipment_id'];
            $update['shiyongren']=$data['staff_name'];
            $sul==D('Equipment')->where($map)->save($update);
            if($result){
                $this->ajaxreturn(array('status'=>1,'msg'=>'添加成功'));
            }else{
                $this->ajaxreturn(array('status'=>2,'msg'=>'添加失败'));                
            }            
        }else{
            $this->ajaxreturn(array('status'=>2,'msg'=>'数据有误,请再操作一次'.$track->geterror()));
        }
        
    }
}
