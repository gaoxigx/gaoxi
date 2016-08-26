<?php
namespace Wap\Controller;
class ProductController extends \Think\Controller{
    public function product(){
        $map['id']=I('id');

        $data=M('Product')->where($map)->find();
//        var_dump($data);exit();
        $quality = json_decode($data['quality'],true);
        $grade = json_decode($data['grade'],true);
        $box = json_decode($data['box'],true);
        
        $this->assign('quality',$quality);
        $this->assign('grade',$grade);
        $this->assign('box',$box);
        $this->assign('data',$data);
        $this->display();
    }
    
}