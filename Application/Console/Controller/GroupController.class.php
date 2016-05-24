<?php
namespace Console\Controller;
use Think\Controller;
header("Content-type:text/html;charset=utf-8");
class GroupController extends CommonController {
    public function roleList($name=''){
        // $this->show('','utf-8');
        $groupname = I('rolename');
        if($groupname){
          //  $condition['rolename'] = $groupname;
			$condition['rolename']=array('like','%'.$groupname.'%');
        }
        $User = M('role');
        $list = $User->limit(100)->where($condition)->order('id')->select();

        $this->assign('list',$list);
        $this->display();
    }
    public function controllerList(){
        // $this->show('','utf-8');
        // echo "string";
        
        //session('adminuser','ThinkPHP'); //session创建
        $name = session('adminuser');    //session读取
        // session('name',null); // 删除name
        // session(null); // 清空当前的session

        $this->assign('name',$name);
        $this->display();
    }
    public function insert(){

        $roleList   =   D('role');
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


    public function roleupdate($id=0){
    $roleList   =   M('role');
    $this->assign('vo',$roleList->find($id));

    $User = M('role');
    $list = $User->limit(100)->order('id')->select();
    $this->assign('list',$list);
    $this->display();
     }

    public function update(){
    $roleList   =   D('role');
    if($roleList->create()) {
        $result = $roleList->save();
        if($result) {
            $this->success('操作成功！',U('Group/roleList'));
        }else{
            $this->error('写入错误！');
        }
    }else{
        $this->error($Form->getError());
    }
 }
	/**
	 *审核
	 */
	public function editstatus(){
		$roleList   =   D('role');
		$id=!empty($_POST['id'])?$_POST['id']:'';
		$result = $roleList->where('id='.$id)->save(array('status'=>1));
		
		if($result) {
			echo json_encode(array('result'=>'1','msg'=>'审核成功！'));
		}else{
			echo json_encode(array('result'=>'0','msg'=>'审核失败！'));
		}
		
	}
 
    public function delete(){
        $id=i('id');
        // dump($id);
        // exit;
        $role = M('role');
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