<?php
namespace Console\Model;
use Think\Model;
class CategoryModel extends Model {
    protected $tablename="category";

    public function department($map=array()){
         if(!I('get.catid')){
            $map['cate_parent']=0;
        }else{
            $map['cate_parent']=I('get.catid');
        }        
        $map['is_show']=1;
        $data=D('Category')->where($map)->select();                    
        return $data;
    }    

 }