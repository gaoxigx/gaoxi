<?php
namespace Console\Controller;
use Think\Controller;
header("Content-type:text/html;charset=utf-8");
class ExcelController extends CommonController {
	public function Excel($files){
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
		if(!empty($files['name'])){
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
				for($currentRow=2;$currentRow<=$allRow;$currentRow++){
					//从哪列开始，A表示第一列
					/*for($currentColumn='A';$currentColumn<=$allColumn;$currentColumn++){
						//数据坐标
						$address=$currentColumn.$currentRow;
						//读取到的数据，保存到数组$arr中
						$arr[$currentRow][$currentColumn]=$currentSheet->getCell($address)->getValue();
					}
					*/
					$columns=$this->getColumn();
					foreach($columns as $k=>$v){
						$data[$v['column_name']] = $currentSheet->getCell($v['column_num'].$currentRow)->getValue();
						if($v['is_time'] == 1){
							$data[$v['column_name']] = strtotime($data[$v['column_name']]);
						}
					}
					$map['number'] = $data['number'];
					$map['name'] = $data['name'];
					$staffinfo = D('Staff')->where($map)->find();
					if(!empty($staffinfo)){
						$savedata = D('Staff')->where('number=%d',array($data['number']))->save($data);
						$$savedata = 0;
						if($savedata >= 0){
							$change_num += $savedata;
						}
					}else{
						$msg = '此条信息不存在';
					}
				}
			}	
		}else{
			$msg = '上传文件不存在';
		}
		
		$result = $change_num > 0?1:0;
		if($change_num == 0){$msg = '数据未修改';}
		return array('result'=>$result,'change_num'=>$change_num,'msg'=>$msg);
	}
	
	protected function getColumn(){
		$columns[] = array(
			'column_name' => 'number',
			'column_num' => 'A',
			'is_time' => '0'
		);
		$columns[] = array(
			'column_name' => 'name',
			'column_num' => 'B',
			'is_time' => '0'
		);
		
		/*$columns[] = array(
			'column_name' => 'posttext',
			'column_num' => 'C',
			'is_time' => '0'
		);*/
		$columns[] = array(
			'column_name' => 'become',
			'column_num' => 'D',
			'is_time' => '0'
		);
		$columns[] = array(
			'column_name' => 'iswork',
			'column_num' => 'E',
			'is_time' => '0'
		);
		$columns[] = array(
			'column_name' => 'entry_time',
			'column_num' => 'F',
			'is_time' => '1'
		);
		$columns[] = array(
			'column_name' => 'starttime',
			'column_num' => 'G',
			'is_time' => '1'
		);
		$columns[] = array(
			'column_name' => 'endtime',
			'column_num' => 'H',
			'is_time' => '1'
		);
		$columns[] = array(
			'column_name' => 'positivetime',
			'column_num' => 'I',
			'is_time' => '1'
		);
		$columns[] = array(
			'column_name' => 'leavetime',
			'column_num' => 'J',
			'is_time' => '1'
		);
		return $columns;
	}
    
}