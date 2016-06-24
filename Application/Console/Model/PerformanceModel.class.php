<?php
namespace Console\Model;
use Think\Model;
class PerformanceModel extends Model{
	protected $tableName="Performance";
	protected $_validate=array();
	
	/**
	 *根据部门id查找部门下的所有成员id
	 *return array
	 */
	public function GetStaffByDep($departmentid){
		if(!isset($departmentid) || $departmentid<=0){
			return false;
		}
		$data = D('Staff')->field('id')->where('section=%d',array($departmentid))->select();
		return $data;
	}
	
	/**
	 *获取个人业绩
	 *$agent:员工id
	 *$total_ruleid:筛选条件-1当天，2按月，3按年
	 */
	public function GetPelPer($agent,$total_ruleid){
		$map['agent'] = $agent;
		if(!$total_ruleid){$total_ruleid=1;}
		
		$now_date = date('Y-m-d',time());
		if($total_ruleid == 2){
			$group = "FROM_UNIXTIME(addtime,'%Y-%m')";
			$date_rule = 'n';
		}else if($total_ruleid == 3){
			$group = "FROM_UNIXTIME(addtime,'%Y')";
			$map2["FROM_UNIXTIME(addtime,'%Y')"] = array('gt',date('Y',time()) - 2);
			$map2['_logic'] = 'and';
            $map3['_complex'] = $map2;
			$map3["FROM_UNIXTIME(addtime,'%Y')"] = array('elt',date('Y',time()));
			$map3['_logic'] = 'and';
            $map['_complex'] = $map3;
			$date_rule = 'Y';
		}else{
			$group = "FROM_UNIXTIME(addtime,'%H')";
			$map1["FROM_UNIXTIME(addtime,'%Y-%m-%d')"] = $now_date;
			$map1['_logic'] = 'and';
            $map['_complex'] = $map1;
			$date_rule = 'G';
		}
		
		$royalty_rate = D('Category')->table('nico_category c,nico_staff s')->where('c.cate_id=s.quarters and s.id='.$agent)->getField('c.royalty_rate');
		$Performance = D('OrderInfo')->where($map)->group($group)->getField("addtime,agent,sum(total_price) total_price",true);
		
		foreach($Performance as $k=>$v){
			$Performance[$k]['addtime'] = date('Y-m-d H:i:s',$v['addtime']);
			if($total_ruleid == 3){
				$Performance[$k]['year'] = date('Y',$v['addtime']);
			}
			
			$Performance[$k]['total_price'] = round($v['total_price']*$royalty_rate,2);
			$Performancelist[date($date_rule,$k)] = $Performance[$k];
		}
		
		$data['total_ruleid'] = $total_ruleid;
		$data['list'] = $Performancelist;
		return $data;
	}
}
?>