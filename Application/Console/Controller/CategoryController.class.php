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
$this->assign('pid',$pid);
$this->display();

}


public function insert(){
        // $data = I();
        // $cate_id = $I['post.cate_parent'];
        $data['cate_id'] = $_POST['cate_parent'];//必须是主键
        $data['cate_haschild'] = 1;

        $roleList   =   D('category');
        $data=$roleList->create();
        if($data){
            $data['addtime']=time();
            $data['uptime']=time();
            $result =   $roleList->add($data);
            if($result || $upresult) {
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
    $this->assign('vo',$roleList->find($cate_id));


    $this->display();

}
 public function update(){
    $roleList   =   D('category');
    $data=$roleList->create();
    if($data) {
        $data['addtime']=time();
        $result = $roleList->save($data);
        if($result) {
            $this->success('修改成功！',U('Category/PList'));
        }else{
            $this->error('写入错误！');
        }
    }else{
        $this->error($Form->getError());
    }
 }

    public function delete($cate_id){

        $user = M('category');
        $data = $user->find($cate_id);
        if ($data['cate_haschild']) {
            $this->error('存在下级分类，不能删除！');
            
        }else{
// $this->success('测试删除成功！');
            $result = $user->delete($cate_id);
            
            if($result) {
                $this->success('数据删除成功！');
            }else{
                $this->error('数据删除错误！');
            }
        }
       
    }

}