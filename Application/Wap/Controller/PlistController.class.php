<?php
namespace Wap\Controller;
class PlistController extends \Think\Controller{
    public function Plist(){
        $product_name = I('product_name');
        $protype=I('protype');
		
        if($protype){
            $map['protype']  =$protype;
//            $parameter['protype'] = $protype;
        }
		
        if($product_name){
            $map['product']  = array('like','%'.trim($product_name).'%');
            $map['product_name'] = $product_name;
        }
        
        foreach( $map as $k=>$v){  
            if( !$v )  
                unset( $arr[$k] );  
        }   
        
		
        $Page = new \Think\Page($count,100,$map);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show = $Page->show();// 分页显示输出
       
        
        $data = M('Product')->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();    
        
//        echo M()->getLastSql();exit();
//        var_dump($data);exit();
        $this->assign('protype',$this->getprotype($protype));    
        $this->assign('list',$list);
        $this->assign('data',$data);
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

   //产品类别
    private function getprotype($portype){
        $data=M('protype');
//        if($portype){
//            $map['typename']= $portype;
//        }  
        $name = $data->where($map)->order('id desc')->select();      
        return $name;
    }    
    
    
}
