<?php
namespace Console\Model;
use Think\Model;
class StaffTakeModel extends Model{

	protected $tableName="staff_take";

	/**
	 *领取办公用品
	**/
	public function StaffTake($staff_id){
		if(!$staff_id){
			$result['status']=0;	
			$result['msg']='ID不存在';
			return $result;
		}	
		$data=$this->create();
		if($data){
			$data['addtime']=time();
			$result['status']=$this->where('staff_id=%d',array($staff_id))->add($data);
			D('Asset')->getStockDec($data['asset_id'],$data['number']);	//库存减1
			$result['msg']='成功';
		}else{
			$result['status']=0;	
			$result['msg']=$this->geterror();
		}
		return $result;
	}
	/**
	 *删除领取的办公用品
	**/
	public function Takedel($take_id){
		if(!$staff_id){
			$result['status']=0;	
			$result['msg']=$this->geterror();
			return $result;
		}
		$result=$this->where('id=%d',array($take_id))->delete();
		if($result){
			$result['status']=0;	
			$result['msg']=$this->geterror();
		}else{
			$result['status']=0;	
			$result['msg']=$this->geterror();
		}
		return $result;
	}
	/**
	 *查询领取办公用品
	**/
	public function tackfind($id){
		if(!$id){
			$id=session('userid');
		}
		$result=$this->alias('st')->field('st.*,na.name')->join('nico_asset as na on na.id=st.asset_id','left')->where("st.staff_id=%d",array($id))->select();
		return $result;
	}

	public function assetfind($id){
		if(!$id){
			return false;
		}
		$result=$this->alias('st')->field('st.*,ns.name,sum(st.number) as numberby,min(st.addtime) as statetime,max(st.addtime) as endtime')->join('nico_staff as ns on ns.id=st.staff_id','left')->where("st.asset_id=%d",array($id))->group('st.staff_id')->select();
		return $result;
	}


}
?>