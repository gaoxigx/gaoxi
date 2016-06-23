<?php
namespace Console\Controller;
use Think\Controller;
header("Content-type:text/html;charset=utf-8");
class ExcelController extends CommonController {
		/*
         * 导入表格
         * $files:导入的文件数据
         * $columns:字段与表对应数组-二维
         * $table:保存数据的表
         * $is_add:1为允许添加数据,0为不允许添加-只能修改
         * $startRow:保存数据在表格起始行数
         * $count_time:1为获取统计时间,0:不需要，字段为calculated;
         */
        public function Excel($files,$columns,$table,$is_add,$startRow=2,$count_time){
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
				import("Org.Util.PHPExcel");
				$filename = $config['rootPath'].$info['excel']['savepath'].$info['excel']['savename'];
				
				$PHPExcel=new \PHPExcel();
				import("Org.Util.PHPExcel.Reader.Excel5");
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
	
	
    
}