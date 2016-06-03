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
    public function category($map=array()){
       $map['status']=1;
       $data=D('Category')->where($map)->select();                    
       return $data; 
    }  
    public function categoryone($map=array()){
       $map['status']=1;
       $data=D('Category')->where($map)->getfield('cate_id,cate_name',true);                    
       return $data; 
    }
    public function postleave($id){
      $map['cate_parent']=I('get.id');
      $map['status']=1;
      $data[]=D('Category')->where($map)->getfield('cate_id,cate_name',true);                    
      if($data){
        foreach ($data as $k => $v) {
          $this->postleave($k);
        }        
      }else{
        return $data;
      }
    }

 }