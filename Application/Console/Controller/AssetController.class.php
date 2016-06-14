<?php 
namespace Console\Controller;
use Think\Controller;
class AssetController extends CommonController{
	public function index(){
		$list=D('Asset')->select();
		$this->assign('list',$list);
		$this->display();
	}
        
 //添加页面       
    public function add(){
        $jumpUrl = U('Console/Asset/index');
        $asset = D('Asset');            
        if(!IS_POST){
             $this->display(); 
             exit();
        }        
        $data = $asset->create();    
        if($data){    
            $data['status']=1;
            $data['createtime']=time();
            $data['gettime']=time();
            $result = $asset->add($data);
            if($result){
                $this->success('添加成功!',$jumpUrl);
            }else{
                $this->error('添加失败!');
            }
        } else {
          $this->error($asset->getError());
        }            
    } 
    public function  staffAsset(){   
    	$this->assign('asset',D('Asset')->getAsset());        
    	$this->display();
    }

    public function getStock($id){
        if(!$id){
            $id=session('userid');
        }
        $data['status']=D('Asset')->getStock($id);
        if($data['status']){
            $data['msg']='成功';
        }else{
            $data['msg']='失败';
        }        
        $this->ajaxreturn($data);
    }

    public function getShop($id){
        if(!$id){
            $id = session('userid');
        }
        $data['status']=D('Asset')->getStock($id);
        if($data['status']){
            $data['msg'] = '成功';            
        }else{
            $data['msg'] = '失败';
        }
        $this->ajaxReturn($data);
    }
    
    /**
     *领取设备
    **/
    public function StaffTake(){

        $staff_id=I('staff_id');
        $data=D('StaffTake')->StaffTake($staff_id);
        if($data['status']>0){
            $this->success('增加成功');    
        }else{
            $this->error($data['msg']);
        }        
    }

    /**
     *领取设备详情
    **/
    public function assetlist(){
        $asset_id=I('id');
        $data=D('StaffTake')->assetfind($asset_id);
        $this->assign('list',$data);
        $this->display();
    }

    /**
    *删除设备
    **/
    public function assetdel(){
        $this->ajaxreturn();
    }

    /**
    *设备回收
    **/
    public function assetrecycle(){
        $id=I('id');
        if(!$id){
            $this->error('回收失败');            
        } 
        $result=D('Asset')->resycle($id);
        if($result){
            $this->success('成功回收');
        }else{
            $this->error('回收失败');            
        }
    }

    /**
    *设备扣款
    **/
    public function assetpayment(){
        $data=D('Asset')->assetpayment($asset_id);
        if($data){
            $this->success('已列为捐款设备');
        }else{
            $this->success('操作失败');
        }
    }



        
}
?>