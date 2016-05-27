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

class AccessController extends CommonController {
    //栏目列表页
    public function add(){
        $this->display();
    }
    public function increase(){
        $m=D('node');
        $data=$m->create();        
        if($data){          
            $result=$m->add($data);
            if($result){
                $this->success('添加成功');
            }else{
                $this->error('数据有误'.$m->geterror());
            }
        }else{
           
            $this->error('数据有误'.$m->geterror());
        }
    }

    public function alter(){
           
    }

    public function delete(){

    }

    public function node(){
        $result=D('node')->select();
        $this->assign("data",$result);
        $this->display();
    }



}