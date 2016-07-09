<?php 
namespace Console\Controller;
use Think\Controller;
class SalaryController extends CommonController{
	public function index(){
		$keywords = I('keywords');
		$count_time = I('count_time');
		
		$map['iswork'] = array('neq',4);
		if($keywords != ''){
			$map1['name'] = array('like','%'.$keywords.'%');
			$map1['departmenttext'] = array('like','%'.$keywords.'%');
			$map1['_logic'] = 'or';
            $map['_complex'] = $map1;
			$param['keywords'] = $keywords;
			$parameter['keywords'] = $keywords;
		}
		
		$Staff = D('Staff');
		$count = $Staff->where($map)->count();// 查询满足要求的总记录数
		$Page = new \Think\Page($count,17,$parameter);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
		$staff = $Staff->where($map)->limit($Page->firstRow.','.$Page->listRows)->getField('id,name,departmenttext,quarters,posttext,entry_time,positivetime,become,iswork,salaryss,salaryzz,housing,traffic,catering,phone');
		if($count_time != ''){
			$cur_month_time = strtotime($count_time);
		}else{
			$cur_month_time = time();
		}
		
		$month_holiday = 4;
		$statutory_holiday = 0;//法定假期
		$param['cur_month_time'] = $cur_month_time;
		$param['now_time'] = time();
		$param['count_time'] = $count_time;
		
		
		foreach($staff as $k=>$v){
			if($v['become'] == 1){
				$base_pay = $v['salaryzz'];
			}else{
				$base_pay = $v['salaryss'];
			}
			$staff[$k]['base_pay'] = $base_pay;
			
			$royalty_rate = D('Category')->where('cate_id=%d',array($v['quarters']))->getField('royalty_rate');
			$staff[$k]['royalty_rate'] = $royalty_rate;
			
			$cur_month = date('Y-m',$cur_month_time);
			$map_per['agent'] = $v['id'];
			$map_per["FROM_UNIXTIME(addtime,'%Y-%m')"] = $cur_month;
			$Performance = D('OrderInfo')->where($map_per)->getField("FROM_UNIXTIME(addtime,'%Y-%m') addtime,agent,sum(total_price) total_price");
			$rate_total = round($Performance[$cur_month]['total_price']*$royalty_rate,2);
			$staff[$k]['rate_total'] = $rate_total;
			
			$first_last_time = $this->mFristAndLastTime($cur_month_time);
			$last_time = $first_last_time['lasttime'];
			$month_days = date('d',$last_time);
			
			$map_check['name'] = $v['name'];
			$map_check["FROM_UNIXTIME(count_time,'%Y-%m')"] = $cur_month;
			$check_info = D('check')->field('gh_id,late_mi,leave_mi,truant,leave,turn')->where($map_check)->find();
			
			$ondays = floor(($last_time - $v['entry_time'])/(3600*24));
			$positivetime_days = floor(($last_time - $v['positivetime'])/(3600*24));//转正的天数
			$real_days = explode('/',$check_info['turn']);
			$real_days = $real_days[1];
			
			if($ondays < $month_days){
				$catering = round($v['catering']/($month_days-$month_holiday-$statutory_holiday)*$real_days,2);
				$housing = round($v['catering']/($month_days-$month_holiday-$statutory_holiday)*$real_days,2);
			}else{
				$catering = $v['catering'];
				$housing = $v['housing'];
			}
			$staff[$k]['catering'] = $catering;
			$staff[$k]['housing'] = $housing;
			
			if($check_info['late_mi'] > 0 || $check_info['leave_mi'] > 0 || $check_info['truant'] > 0 || $check_info['leave'] > 0 || $ondays < $month_days){
				$attendance_bonus = 0;
			}else{
				$attendance_bonus = 100;
			}
			$staff[$k]['attendance_bonus'] = $attendance_bonus;
			
			$map_checkrecord['name'] = $v['name'];
			$map_checkrecord["FROM_UNIXTIME(datetime,'%Y-%m')"] = $cur_month;
			$checkrecord = D('CheckRecord')->field('gh_id,name,late_min,leave_min')->where($map_checkrecord)->select();
			$late_bonus = 0;
			$other_truant_hleave = 0;
			foreach($checkrecord as $k1=>$v1){
				if($v1['late_min'] > 0 && $v1['late_min'] <= 10){
					$late_bonus += 10;
				}else if($v1['late_min'] > 10 && $v1['late_min'] < 30){
					$late_bonus += 30;
				}else if($v1['late_min'] >= 30){
					$other_truant_hleave += round(($v1['late_min']/60),2);
				}
				if($v1['leave_min'] > 0 && $v1['leave_min'] <= 10){
					$late_bonus += 10;
				}else if($v1['leave_min'] > 10 && $v1['leave_min'] < 30){
					$late_bonus += 30;
				}else if($v1['leave_min'] >= 30){
					$other_truant_hleave += round(($v1['leave_min']/60),2);
				}
			}
			$staff[$k]['late_bonus'] = $late_bonus;
			
			$per_hleave = $check_info['leave'] * 8;//请假小时数
			$casual_leave = round($base_pay/($month_days-$month_holiday-$statutory_holiday)*($per_hleave/8),2);
			$staff[$k]['casual_leave'] = $casual_leave;
			
			$truant_hleave = $check_info['truant'] * 8+$other_truant_hleave;//旷工小时数
			$truant_leave = round($base_pay/($month_days-$month_holiday-$statutory_holiday)*($truant_hleave/8),2);
			$staff[$k]['truant_leave'] = $truant_leave;
			
			$deduct_total = round($casual_leave + $truant_leave + $late_bonus,2);
			$staff[$k]['deduct_total'] = $deduct_total;
			$fsalary = round($base_pay + $catering + $housing + $rate_total + $attendance_bonus - $deduct_total,2);
			$staff[$k]['fsalary'] = $fsalary<0?0:$fsalary;
		}
		
		$this->assign('list',$staff);
		$this->assign('page',$show);
		$this->assign('param',$param);
		$this->display();
	}
	
	/**
	 *
	 * 获取指定年月的开始和结束时间戳
	 *
	 * @param int $time 当月任意时间戳
	 * @return array(开始时间,结束时间)
	 */
	protected function mFristAndLastTime($time=0){
		$time = $time ? $time : time();
		$y = date('Y', $time);
		$m = date('m', $time);
		$d = date('t', $time);
		return array("firsttime"=>mktime(0,0,0,$m,1,$y),"lasttime"=>mktime(23,59,59,$m,$d,$y));
	}
}
?>