<?php
namespace Console\Controller;
use Think\Controller;
header("Content-type:text/html;charset=utf-8");
import("Org.Util.PHPExcel");
import("Org.Util.PHPExcel.Reader.Excel5");
class ExcelController extends CommonController {
	/*
	 * 导入表格
	 * $files:导入的文件数据
	 * $columns:字段与表对应数组-二维
	 * $table:保存数据的表
	 * $is_add:1为允许添加数据,0为不允许添加-只能修改
	 * $startRow:保存数据在表格起始行数
	 * $count_time:1为获取统计时间,0:不需要，字段为calculated;
	 * $checkrecord:1为考勤记录表，默认为空
	 */
    public function Excel($files,$columns,$table,$is_add,$startRow=2,$count_time,$checkrecord){
		$config = array( 
			'maxSize'    =>    3145728,
			'rootPath'   =>    '.',
			'savePath'   =>    '/Uploads/',
			'saveName'   =>    array('uniqid',''),
			'exts'       =>    array('xls'),
			'autoSub'    =>    true,
			'subName'    =>    array('date','Ymd'),
		);
		$msg = '导入失败';
		$change_num = 0;
                
		if(!empty($files['name']) && !empty($columns)){
			$upload = new \Think\Upload($config);// 实例化上传类
			$info   =   $upload->upload();
			if(!$info) {// 上传错误提示错误信息
				$this->error($upload->getError());
			}else{
				$filename = $config['rootPath'].$info['excel']['savepath'].$info['excel']['savename'];
				if($checkrecord == 1){
					$exceldata = $this->ExcelRecord($filename,$columns,$table,$is_add,$startRow,$count_time);
				}else{
					$exceldata = $this->ExcelData($filename,$columns,$table,$is_add,$startRow,$count_time);
				}
				$change_num = $exceldata['change_num'];
				if($exceldata['msg'] != ''){
					$msg = $exceldata['msg'];
				}
			}	
		}else{ 
			if(empty($files)){
				 $msg = '上传文件不存在';
			}else if(empty($columns)){
				$msg = '请先设置表格格式';
			}else{
				$msg = '上传文件不存在';
			}
		}
		
		$result = $change_num > 0?1:0;
		if($change_num == 0){$msg = '数据未修改';}
		return array('result'=>$result,'change_num'=>$change_num,'msg'=>$msg);
	}
	
	/**
	 *表格处理-默认第一张表，有规则表
	 * $filename:导入的文件名称
	 * $columns:字段与表对应数组-二维
	 * $table:保存数据的表
	 * $is_add:1为允许添加数据,0为不允许添加-只能修改
	 * $startRow:保存数据在表格起始行数
	 * $count_time:1为获取统计时间,0:不需要，字段为calculated;
	 */
	protected function ExcelData($filename,$columns,$table,$is_add,$startRow=2,$count_time){
		$PHPExcel=new \PHPExcel();
		$PHPReader=new \PHPExcel_Reader_Excel5();
		$PHPExcel=$PHPReader->load($filename);
		//获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
		$currentSheet=$PHPExcel->getSheet(0);
		//获取总列数
		$allColumn=$currentSheet->getHighestColumn();
		//获取总行数
		$allRow=$currentSheet->getHighestRow();
		
		//循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
		for($currentRow=$startRow;$currentRow<=$allRow;$currentRow++){
			//从哪列开始，A表示第一列
			/*for($currentColumn='A';$currentColumn<=$allColumn;$currentColumn++){
				//数据坐标
				$address=$currentColumn.$currentRow;
				//读取到的数据，保存到数组$arr中
				$arr[$currentRow][$currentColumn]=$currentSheet->getCell($address)->getValue();
			}
			*/
								
			foreach($columns as $k=>$v){
				$data[$v['column_name']] = $currentSheet->getCell($v['column_num'].$currentRow)->getValue();
				if($v['is_time'] == 1){
					$data[$v['column_name']] = strtotime($data[$v['column_name']]);
				}else if($v['is_time'] == 2){
					$time2 = explode(':',$data[$v['column_name']]);
					$data[$v['column_name']] = $time2[0]*3600+$time2[1]*60;
				}
				if(isset($v['is_split']) && $v['is_split'] == 1){
					$split_columns = explode(',',$v['split_column']);
					$split_vals = explode($v['split_str'],$data[$v['column_name']]);
					for($i=0;$i<count($split_columns);$i++){
						$data[$split_columns[$i]] = $split_vals[$i];
					}
					unset($data[$v['column_name']]);
				}
				if($count_time == 1){
					$data['calculated'] = $currentSheet->getCell('B2')->getValue();
				}
				if($v['is_where'] == 1){
					$map[$v['column_name']]  = $data[$v['column_name']];   
				}
				
			}
			
			$map[$columns[0]['column_name']] = $data[$columns[0]['column_name']];
			$datainfo = D($table)->where($map)->find();
			
			if(!empty($datainfo)){
				$savedata = D($table)->where($columns[0]['column_name'].'=%d',array($data[$columns[0]['column_name']]))->save($data);
			}else{
				if($is_add == 1){ 
					$savedata = D($table)->add($data);
				}else{	
					$msg = '此条信息不存在';
				}
			}
			
			if($savedata >= 0){
				$change_num += $savedata;
		   }
		}
		return array('change_num'=>$change_num,'msg'=>$msg);
	}
	
	/**
	 *表格处理-默认第一张表，有规则表
	 * $filename:导入的文件名称
	 * $columns:字段与表对应数组-二维
	 * $table:保存数据的表
	 * $is_add:1为允许添加数据,0为不允许添加-只能修改
	 * $startRow:保存数据在表格起始行数
	 * $count_time:1为获取统计时间,0:不需要，字段为calculated;
	 */
	protected function ExcelRecord($filename,$columns,$table,$is_add,$startRow=2,$count_time){
		$PHPExcel=new \PHPExcel();
		$PHPReader=new \PHPExcel_Reader_Excel5();
		$PHPExcel=$PHPReader->load($filename);
		//获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
		$currentSheet=$PHPExcel->getSheet(0);
		//获取总列数
		$allColumn=$currentSheet->getHighestColumn();
		//获取总行数
		$allRow=$currentSheet->getHighestRow();
		
		//循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
		for($currentRow=$startRow;$currentRow<=$allRow;$currentRow++){
			if(strlen($allColumn) > 1 || $allColumn > 'Y'){
				$allColumn = 'Y';
			}
			
			if($currentRow%2 != 0){
				foreach($columns as $k=>$v){
					if($v['is_com'] == 1){
						$data[$v['column_name']] = $currentSheet->getCell($v['column_num'].$currentRow)->getValue();
						
						if($v['is_time'] == 1){
							$data[$v['column_name']] = strtotime($data[$v['column_name']]);
						}else if($v['is_time'] == 2){
							$time2 = explode(':',$data[$v['column_name']]);
							$data[$v['column_name']] = $time2[0]*3600+$time2[1]*60;
						}
						if(isset($v['is_split']) && $v['is_split'] == 1){
							$split_columns = explode(',',$v['split_column']);
							$split_vals = explode($v['split_str'],$data[$v['column_name']]);
							for($i=0;$i<count($split_columns);$i++){
								$data[$split_columns[$i]] = $split_vals[$i];
							}
							unset($data[$v['column_name']]);
						}
						if($count_time == 1){
							$data['calculated'] = $currentSheet->getCell('C3')->getValue();
							$data['date_month'] = date('Y-m',strtotime(substr($data['calculated'],0,strpos($data['calculated'],'~'))));
						}
						if($v['is_where'] == 1){
							$map[$v['column_name']]  = $data[$v['column_name']];   
						}
					}
				}
				$currentRow += 1;
			}
			
			if($currentRow%2 == 0){
				for($currentColumn='A';$currentColumn<=$allColumn;$currentColumn++){
					//数据坐标
					$address=$currentColumn.$currentRow;
					//读取到的数据，保存到数组$arr中
					$datetime = $currentSheet->getCell($currentColumn.'4')->getValue();
					if($datetime != ''){
						$data['record'][$datetime]=$currentSheet->getCell($currentColumn.$currentRow)->getValue();
					}
				}
			}
			
			foreach($data['record'] as $key=>$val){
				foreach($columns as $k=>$v){
					$one_data['calculated'] = $data['calculated'];
					$one_data[$v['column_name']] = $data[$v['column_name']];
					$datetime = $data['date_month'].'-'.$key;
					$one_data['datetime'] = strtotime($data['date_month'].'-'.$key);
					$time_a = strtotime($datetime.' '.substr($val,0,5).':00');
					$time_b = strtotime($datetime.' '.substr($val,-5).':00');
					$time_c = strtotime($datetime.' 12:00:00');
					
					if($time_a == ''){
						$time_a = 0;
					}
					if($time_b == ''){
						$time_b = 0;
					}
					if($time_a == $time_b && $time_a > $time_c){
						$working_time = 0;
						$leave_time = $time_a;
					}
					if($time_a == $time_b && $time_a <= $time_c){
						$working_time = $time_a;
						$leave_time = 0;
					}
					if($time_a != $time_b && ($time_a <= $time_c && $time_b <= $time_c)){
						$working_time = $time_a;
						$leave_time = 0;
					}
					if($time_a != $time_b && ($time_a > $time_c && $time_b > $time_c)){
						$working_time = 0;
						$leave_time = $time_b;
					}
					if($time_a != $time_b && ($time_a <= $time_c && $time_b > $time_c)){
						$working_time = $time_a;
						$leave_time = $time_b;
					}
					
					$one_data['working_time'] = $working_time;
					$one_data['leave_time'] = $leave_time;
					
					if($v['is_where'] == 1){
						$map[$v['column_name']]  = $one_data[$v['column_name']];   
					}
				}
				$islate = $this->Islate($one_data['dep_name'],$one_data['working_time'],$one_data['leave_time']);
				$one_data['late_min'] = $islate['late_min'];
				$one_data['leave_min'] = $islate['leave_min'];
				
				$map[$columns[0]['column_name']] = $one_data[$columns[0]['column_name']];
				$datainfo = D($table)->where($map)->find();
				
				if(!empty($datainfo)){
					$one_data['modifytime'] = time();
					$savedata = D($table)->where($map)->save($one_data);
				}else{
					if($is_add == 1){
						$one_data['createtime'] = time();
						$savedata = D($table)->add($one_data);
					}else{	
						$msg = '此条信息不存在';
					}
				}
				
				if($savedata >= 0){
					$change_num += $savedata;
			   }
				
			}
			
		}
		return array('change_num'=>$change_num,'msg'=>$msg);
	}
}