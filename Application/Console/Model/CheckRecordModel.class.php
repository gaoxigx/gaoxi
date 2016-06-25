<?php
namespace Console\Model;
use Think\Model;
class CheckRecordModel extends Model{
    // 定义自动验证
    protected $_validate    =   array(
      //  array(验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]),           
        array('gh_id','require','缺少工号'),
        array('name','require','请输入姓名'),        
        array('dep_name','require','请输入部门名称'),        
        );
    
	public function getColumn(){
        $columns[] = array(
                'column_name' => 'gh_id',
                'column_num' => 'C',
                'is_com' => '1'
        );
        $columns[] = array(
                'column_name' => 'name',
                'column_num' => 'K',
                'is_com' => '1',
				'is_where'=>'1'
        );

        $columns[] = array(
                'column_name' => 'dep_name',
                'column_num' => 'U',
                'is_com' => '1'
        );
        $columns[] = array(
                'column_name' => 'datetime',
				'is_com' => '-1',
				'is_where'=>'1'
        );
         $columns[] = array(
                'column_name' => 'working_time',
				'is_com' => '-1'
        );
		$columns[] = array(
                'column_name' => 'leave_time',
				'is_com' => '-1'
        );
        
        return $columns;
    }
}
?>