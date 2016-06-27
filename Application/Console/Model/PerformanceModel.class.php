<?php
namespace Console\Model;
use Think\Model;
class PerformanceModel extends Model{
	protected $tableName="Performance";
	protected $_validate=array();
	
	/**
	 *根据部门id查找部门下的所有成员id
	 *return string
	 */
	public function GetStaffByDep($departmentid){
		if(!isset($departmentid) || $departmentid<=0){
			return false;
		}
		$data = D('Staff')->field('id')->where('section=%d',array($departmentid))->select();
		foreach($data as $k=>$v){
			$agentids .= $v['id'].',';
		}
		$agentids = substr($agentids,0,-1);
		return $agentids;
	}

	/**
	 *获取业绩
	 *$agent:员工id
	 *$total_ruleid:筛选条件-1当天，2按月，3按年
	 *$is_rate:1需要算提成，0不需要
	 *$is_all:1统计年份或月份所有，0为筛选员工
	 *$year_num:统计年数，默认最近3年
	 *$cur_year：统计年份，默认当前年份
	 */
	public function GetPelPer($agent,$total_ruleid,$is_rate,$is_all,$year_num=3,$cur_year){
		if($is_all != 1){
			$map['agent'] = $agent;
			$is_one = strpos($agent,',');
			if($is_one > 0){
				$map['agent'] = array('in',$agent);
			}
		}
		
		if(!$total_ruleid){$total_ruleid=1;}
		$now_date = date('Y-m-d',time());
		if(!$cur_year){
			$cur_year = date('Y',time());
		}
		if($total_ruleid == 2){
			$group = "FROM_UNIXTIME(addtime,'%Y-%m')";
			$map4["FROM_UNIXTIME(addtime,'%Y')"] = $cur_year;
			$map4['_logic'] = 'and';
            $map['_complex'] = $map4;
			$date_rule = 'n';
		}else if($total_ruleid == 3){
			$group = "FROM_UNIXTIME(addtime,'%Y')";
			$map2["FROM_UNIXTIME(addtime,'%Y')"] = array('gt',date('Y',time()) - $year_num-1);
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
		if($is_rate == 1){
			$royalty_rate = D('Category')->table('nico_category c,nico_staff s')->where('c.cate_id=s.quarters and s.id='.$agent)->getField('c.royalty_rate');
		}
		if($is_all == 1 || $is_one > 0){
			$field = "addtime,sum(total_price) total_price";
		}else{
			$field = "addtime,agent,sum(total_price) total_price";
		}
		$Performance = D('OrderInfo')->where($map)->group($group)->getField($field,true);
		
		foreach($Performance as $k=>$v){
			if(isset($Performance[$k]['addtime'])){
				$Performance[$k]['addtime'] = date('Y-m-d H:i:s',$v['addtime']);
			}
			if($total_ruleid == 3 && isset($Performance[$k]['addtime'])){
				$Performance[$k]['year'] = date('Y',$v['addtime']);
			}
			if($is_rate == 1){
				$Performance[$k]['total_price'] = round($v['total_price']*$royalty_rate,2);
			}
			
			$Performancelist[date($date_rule,$k)] = $Performance[$k];
		}
		
		$data['total_ruleid'] = $total_ruleid;
		$data['list'] = $Performancelist;
		return $data;
	}
}
?>