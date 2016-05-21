<?php
namespace Console\Controller;
use Think\Controller;
header("Content-type:text/html;charset=utf-8");
class StaffController extends CommonController {

    //员工组分类
    private function getprotype(){
        $data=D('role');
        $name = $data->where('status=1')->order('id')->select();
        // dump($name);
        $this->assign('roletype',$name);
    }

    /**
     * 查找下级分类
    **/
    public function department(){
        $data=D('Category')->department();
        $this->ajaxReturn($data);
    }

    public function deparlist(){
        if(I('post.accounts')){
            $map['cate_name']=array('like','%'.I('post.accounts').'%');
        }
        $data=D('Category')->department($map);
        $this->assign('data',$data);
        $this->display();
    }

//数据列表 这里的名称写错了
    public function Staff($name=''){
           
        $username = i('username');
        if($username){
            $where['nickname']  = array('like','%'.trim($username).'%');
            $where['username']  = array('like','%'.trim($username).'%');
            $where['name']  = array('like','%'.trim($username).'%');
            $where['section']  = array('like','%'.trim($username).'%');
            $where['identity_card']  = array('like','%'.trim($username).'%');
            $where['mobile']  = array('like','%'.trim($username).'%');              
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
        $User = M('Staff'); // 实例化User对象
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

 //添加页面
    public function add($value='')
    {
        $this->getprotype();
        $data=D('Category')->department();
        $this->assign('department',$data);
        $this->display();
    }

//插入数据
    public function insert(){

        $roleList   =   D('Staff');
        if($roleList->create()) {
            $result =   $roleList->add();
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
    $controller   =   M('Staff');
    // 读取数据
    $data =   $controller->find($id);
    if($data) {
        $this->assign('data',$data);// 模板变量赋值
    }else{
        $this->error('数据错误');
    }
    $this->display();


     }

//更修改密码
public function passupdate(){
$id = I('session.userid',0);
// echo getdate();
// exit;
    if(I('post.oldpassword')=='' && I('post.oldpassword')==''){
        $this->error('新旧密码必须填写！');
    }else{
        $oldpassword = md5(I('post.oldpassword'));
        $dataa["password"] = md5(I('post.password'));
        // dump(md5(I('post.password')));
        // exit();

        $controller = M('Staff');
        $data = $controller ->where('id='.$id.' and password="'.$oldpassword.'"')->find();
        if($data) {
                $result = $controller->where('id='.$id)->save($dataa);
                // dump($result);
                // exit();
                if($result) {
                    $this->success('密码更新成功！');
                }else{
                    $this->error('写入错误！');
                }
            }else{
            $this->error('原密码错误');
            }

    }

 }
//更新数据
    public function update(){
    $roleList   =   D('Staff');
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
        $role = M('Staff');
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