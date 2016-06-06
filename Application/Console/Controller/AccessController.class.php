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
        $pid = I('get.pid');
		$this->assign('pid',$pid);
		$this->display();
    }
    public function increase(){
        $m=D('node');
        $data=$m->create();        
        if($data){          
            $result=$m->add($data);
            if($result){
                $this->success('添加成功',U('Access/node'));
            }else{
                $this->error('数据有误'.$m->geterror());
            }
        }else{
           
            $this->error('数据有误'.$m->geterror());
        }
    }
	
	/**
	 *修改节点页面
	 */
	public function edit(){
		$id = I('get.id',0);
		$data = D('node')->where('id='.$id)->find();
		
		$prev_data = D('node')->where('pid=0')->select();
		$this->assign('prev_data',$prev_data);
		$this->assign('data',$data);
		$this->display();
	}
	
	/**
	 *修改节点保存
	 */
	public function savedata(){
		$id = I('post.id',0);
		$m=D('node');
        $data=$m->create();      
		
        if($data){          
            $result=$m->where('id='.$id)->save($data);
            if($result){
                $this->success('修改成功',U('Access/node'));
            }else{
                $this->error('修改失败'.$m->geterror());
            }
        }else{
           
            $this->error('数据有误'.$m->geterror());
        }
	}
	
    public function alter(){
           
    }
	
	/**
	 *删除一条节点数据
	 */
    public function delete(){
		$id = I('post.id',0);
		if(!$id){
			$this->error('此条数据不存在');
		}
		$delete = D('node')->where('id='.$id)->delete();
		
		if($delete > 0){
			echo json_encode(array('result'=>1));
		}else{
			echo json_encode(array('result'=>0));
		}
    }

    public function node(){
		$name = I('name');
		$remark = I('remark');
		if($name){
			$map['name']  = array('like','%'.trim($name).'%');
			
		}
		if($remark){
			$map['remark']  = array('like','%'.trim($remark).'%');
		}
		
		foreach( $map as $k=>$v){ 
			if( !$v )  
				unset( $arr[$k] );  
		} 
		
		$cateRow = D('node')->where($map)->order(array('id'=>'asc'))->select();//查询数据时对parent父级排序
        $treeObj = new \Lib\Tree();//引用Tree类
        $data = $treeObj->getTree($cateRow,$pid = 0, $col_id = 'id', $col_pid = 'pid', $col_cid = 'cate_haschild');//$col_id,$col_pid,$col_cid对应分类表category中的字段
       
		$this->assign("data",$data);
		$this->display();
	}

	public function getroleninfo(){
		$accid=I("id");
        $data=D('node');
        $name = $data->where("pid=%d",array($accid))->order(array('sort'=>'asc','id'=>'asc'))->select();   
		
		$this->ajaxreturn($name);
    }

}