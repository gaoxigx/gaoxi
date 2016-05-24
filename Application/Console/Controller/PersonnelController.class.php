<?php
namespace Console\Controller;
use Think\Controller;
header("Content-type:text/html;charset=utf-8");
class PersonnelController extends CommonController {

    //管理员组分类
    private function getprotype(){
        $data=D('role');
        $name = $data->where('rolename<>""')->order('id')->select();
        // dump($name);
        $this->assign('roletype',$name);

    }

    //数据列表
    public function PerList($name=''){
        // echo "string";
        // $this->show('','utf-8');
        $map = I('');
        
		foreach( $map as $k=>$v){  
			if( !$v )  
				unset( $arr[$k] );  
		}   

		//分页跳转的时候保证查询条件
		foreach($map as $key=>$val) {
			if(!$val){
				unset($map[$key]);
			}else{
				$Page->parameter[$key]   =   urlencode($val);
			}
		}
// dump($map);
    $User = M('controller'); // 实例化User对象
    $count = $User->where($map)->count();// 查询满足要求的总记录数
    $Page = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    $Page->setConfig('header','个会员');
    $show = $Page->show();// 分页显示输出
    // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
    $list = $User->where($map)->order('id')->limit($Page->firstRow.','.$Page->listRows)->select();
    $this->assign('list',$list);// 赋值数据集
    $this->assign('page',$show);// 赋值分页输出
    $this->display(); // 输出模板


    }

 //添加页面
    public function add($value='')
    {
        $this->getprotype();
        $this->display();
    }

//插入数据
    public function insert(){

        $roleList   =   D('controller');
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
    $controller   =   M('controller');
    // 读取数据
    $data =   $controller->find($id);
    if($data) {
        $this->assign('data',$data);// 模板变量赋值
    }else{
        $this->error('数据错误');
    }
    $this->display();


     }
	
	public function passadd(){
		$id = I('get.id',0);
		if($id >0){
			$controller = M('controller');
			$data = $controller ->where('id='.$id)->find();
			$this->assign('data',$data);
		}
        
		$this->display();
	}
	
	/**
	 *管理员列表-修改密码
	 */
public function passupdateall(){
	if(I('post.id') > 0){
		$id = I('post.id',0);
	}

    if(I('post.password')==''){
        $this->error('新密码必须填写！');
    }else{
        $dataa["password"] = md5(I('post.password'));
        
        $controller = M('controller');
        $data = $controller ->where('id='.$id)->find();
        if($data) {
                $result = $controller->where('id='.$id)->save($dataa);
                // dump($result);
                // exit();
                if($result) {
                    $this->success('密码重置成功！',U('Personnel/PerList'));
                }else{
                    $this->error('密码重置失败！');
                }
        }else{
            $this->error('此用户不存在');
        }

    }

 }
	
//更修改当前用户密码
public function passupdate(){
	$id = I('session.userid',0);
	
// echo getdate();
// exit;
    if(I('post.password')=='' && I('post.oldpassword')==''){
        $this->error('新旧密码必须填写！');
    }else{
        $oldpassword = md5(I('post.oldpassword'));
        $dataa["password"] = md5(I('post.password'));
        // dump(md5(I('post.password')));
        // exit();

        $controller = M('controller');
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
    $roleList   =   D('controller');
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
        $role = M('controller');
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