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
            unset($_POST['name']);
            $data = $asset->create();    
            if($data){    
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
        
}
?>