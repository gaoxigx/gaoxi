<?php
namespace Console\Controller;
use Think\Controller;
header("Content-type:text/html;charset=utf-8");
class WecateController extends CommonController {

    public function index()
    {
        $this->display();
    }


//数据列表
    public function Wecate($name=''){

        // echo "string";
        // $this->show('','utf-8');
        $username = I('post.username');
        if($username){
            $map['wechat_id']  = array('like','%'.trim($username).'%');
            $map['username']  = array('like','%'.trim($username).'%');
            $map['_logic'] = 'or';
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
        $User = M('wecate'); // 实例化User对象
        $data=$User->select();
//        var_dump($data);
//        
//        exit();
        $count = $User->where($map)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->setConfig('header','个会员');
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $User->where($map)->order('id')->limit($Page->firstRow.','.$Page->listRows)->select();
//        var_dump($list);
//        exit();
        //echo $User->getLastSql();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display(); // 输出模板


    }

    
    

//添加粉丝号
    public function insert(){
        $jumpUrl =U('Console/Wecate/Wecate');
        $roleList   =   D('Wecate');
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

//修改粉丝信息
    public function edit($id=0){
        
        $jumpUrl =U('Console/Wecate/Wecate'); 
        $id = (int)$id;
        $model = D('Wecate');          
        if (IS_POST) {
            $id = I('post.id');
           
            if ($id > 0) {
                $data=$model->create();
//                var_dump($data);
//                exit();
                $map['id']=$id;
                $result=$model->where($map)->save($data);
                if ($result){
                    $this->success('修改成功', $jumpUrl);
                } else {                    
                    $this->error('修改失败', $jumpUrl);
                }          
            } 
        } else {            
         if($id){
             $user = $model->where("id=".$id)->find(); 
         } 
        
         $this->assign('user',$user);
         $this->assign('id',$id);            
         $this->display();
        }
    }     

//更新数据
    public function update(){
    $roleList   =   D('Wecate');
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
    public function wecate_delete(){
    $id=i('id');
        // dump($id);
        // exit;
        $role = M('Wecate');
         $set = $role->where("id = ".$id)->find();
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


  