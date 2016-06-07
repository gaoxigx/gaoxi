<?php
/**
 * alltosun.com  CategoryController.class.php
 * ============================================================================
 * 版权所有 (C) 2014-2016 GoCMS内容管理系统
 * 官方网站:   http://www.gouguoyin.cn
 * 联系方式:   QQ:245629560
 * ----------------------------------------------------------------------------
 * 许可声明：这是一个开源程序，未经许可不得将本软件的整体或任何部分用于商业用途及再发布。
 * ============================================================================
 * $Author: 勾国印 (phper@gouguoyin.cn) $
 * $Date: 2015-3-11 下午4:00:17 $
 * $Id$
*/
namespace Console\Controller;
use Think\Controller;
// use Lib\Tree;
header("Content-type:text/html;charset=utf-8");
class CategoryController extends CommonController {
    //栏目列表页

    public function PList(){
       $cateObj = D('category');
        $cateRow = $cateObj->order(array('cate_parent'=>'asc'))->select();//查询数据时对parent父级排序
        $treeObj = new \Lib\Tree();//引用Tree类
        $row = $treeObj->getTree($cateRow,$pid = 0, $col_id = 'cate_id', $col_pid = 'cate_parent', $col_cid = 'cate_haschild');//$col_id,$col_pid,$col_cid对应分类表category中的字段
        // dump($row);
        $this->assign('cateRow', $row);
        // dump($row);
        $this->display('PList');
    }

public function add($pid=0){
//添加时把父级分类的“cate_haschild”字段更新为1
    $nodeModel=D('node');
    $node=$nodeModel->field('id,title,name')->select();        
    $this->assign("node",$node);
    $this->assign('pid',$pid);
    $this->display();

}


public function insert(){
        // $data = I();
        // $cate_id = $I['post.cate_parent'];
        $data['cate_id'] = $_POST['cate_parent'];//必须是主键
        $data['cate_haschild'] = 1;

        $data['cate_name']=I('cate_name');
        $data['cate_name2']=I('cate_name2');
        $data['cate_haschild']=I('cate_haschild');        
        $data['is_show']=I('post.is_show');
        $data['status']=I('post.status');
        $data['cate_content']=I('post.cate_content');


        $data['uptime']=time();        
        $data['addtime']=time();    
        $data['__hash__']=I('__hash__');   

        $roleList   =   D('category');
        $data=$roleList->create();
        if($data){
            $data['addtime']=time();
            $data['uptime']=time();
            unset($data['__hash__']);        
            $result =   $roleList->add($data);
            if($result || $upresult) {
                $uid=$result;
                $node=I('node');                
                $map['id']=array('in',implode(',',$node));
                $result=D('node')->field('id as node_id,title as module,'.$uid.' role_id')->where($map)->select();         
                $result=D('Access')->addAll($result);   

                $this->success('数据添加成功！',U('Category/PList'));
            }else{
                $this->error('数据添加错误！');
            }
        }else{
            $this->error($roleList->getError());
        }

       
    }


public function edit($cate_id){
    $roleList   =   M('category');
    $nodeModel=D('node');
    $node=$nodeModel->where('pid=0')->field('id,title,name,pid')->select();
	foreach($node as $k=>$v){
		$node_child=$nodeModel->where('pid='.$v['id'])->field('id,title,name,pid')->select();
		$node[$k]['child'] = $node_child;
	}
	
    $selectNode=D('Access')->where('role_id='.$cate_id)->getfield('node_id,module',true);        
    $this->assign('vo',$roleList->find($cate_id));
    $this->assign("node",$node);
    $this->assign('selectNode',$selectNode);
    $this->display();

}
 public function update(){
    $roleList   =   D('category');    
    $data['cate_name']=I('cate_name');
    $data['cate_name2']=I('cate_name2');
    $data['cate_haschild']=I('cate_haschild');
    $data['cate_sort']=I('cate_sort');
    $data['is_show']=I('post.is_show');
    $data['status']=I('post.status');
    $data['uptime']=time();        
    $data['__hash__']=I('__hash__');   

    $uid=I('cate_id');
    $data=$roleList->create($data);        
    if($data) {        
        $data['addtime']=time();
        unset($data['__hash__']);                   
        $result = $roleList->where('cate_id='.$uid)->save($data); 

        if($result) {      
            $accessModel=D('Access');
            
            $node=I('node');
            if(!empty($node)&&isset($node)){
                $result=$accessModel->where('role_id='.$uid)->delete();
                $map['id']=array('in',implode(',',$node));
                $result=D('node')->field('id as node_id,title as module,'.$uid.' role_id')->where($map)->select();   
                //$result=>M()->query('select id as node_id,title as module,'.$uid.' as role_id where exists() );     
                $result=D('Access')->addAll($result);   
            }
            $this->success('修改成功！',U('Category/PList'));
        }else{
            $this->error('写入错误！');
        }
    }else{
        $this->error($roleList->getError());
    }
    
 }

    public function delete($cate_id){
        $user = M('category');
        $data = $user->find($cate_id);
        // if ($data['cate_haschild']) {
        //     $this->error('存在下级分类，不能删除！');            
        // }else{
        // $this->success('测试删除成功！');
        $result = $user->delete($cate_id);        
        if($result) {
            $this->success('数据删除成功！');
        }else{
            $this->error('数据删除错误！');
        }
        //}
       
    }
    
    //员工分类
    private function getrolename($rolename,$roleid){
        $data=D('category');
        $name = $data->where('roleid='.$roleid)->order('id')->select();
        // dump($name);
        $this->assign($rolename,$name);

    }

    public function getroleninfo(){
        $roleid=I("id");
         $data=D('category');
         $name = $data->where("cate_parent=%d",array($roleid))->order('cate_id')->select();    
         $this->ajaxreturn($name);
    }
}