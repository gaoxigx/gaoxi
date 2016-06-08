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

class IncomeController extends CommonController {

    //物流单号查询
     public function numberno($no){
        echo "物流信息查询：".$no;
        // $this->$this->display(??)
        // $this->display('plist');
     }    
    

    //进账单列表
    public function Plist(){
        $User = M('order_info'); // 实例化User对象 
        $data=$User->select(); 


        $count = $User->where($map)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->setConfig('header','个会员');
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $User->where($map)->order('id')->limit($Page->firstRow.','.$Page->listRows)->select();

        $payment = D('payment')->getfield('id,payment',true);
        $staff=D('staff')->getfield('id,name',true);
        
        $catdata=D('Category')->categoryone();      

        $this->assign('payment',$payment);
        $this->assign('staff',$staff);
        $this->assign('cat',$catdata);
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display(); // 输出模板
    }
    
    //支付方式获取
    private function getpayment(){
        $data=D('payment');
        $name = $data->where('payment<>""')->order('id')->select();
        // dump($name);
        $this->assign('payment',$name);

    }

    //处理付款状态（确认和取消）

    public function Payment_status($id,$status){
        $order_info = D('order_info');
        $data['payment_status'] = 1; 
        if($status==0){
            $tip="确认付款成功！";
        }else{
            $tip="取消确认付款！";
        }
        $name = $order_info->where('id='.$id)->save($data);
        if($name){
            $this->success($tip);
        }else{
            $this->error('更新数据出错');
        }
    }    
    
}