<?php
namespace Console\Model;
use Think\Model;
class CheckModel extends Model{
    // 定义自动验证
    protected $_validate    =   array(
      //  array(验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]),           
        array('name','require','缺少用户名'),
        array('name','','用户已存在',2,'unique',3),        
        array('money','require','请输入金额'),        
        );
    
    // 定义自动完成
    protected $_auto = array( 
       // array('password','autoPassword',self::MODEL_BOTH,'callback'),         
         array('gettime','time',self::MODEL_BOTH,'function'),        
         array('createtime','time',self::MODEL_BOTH,'function'),        
     );

 public function getColumn(){
        $columns[] = array(
                'column_name' => 'gh_id',
                'column_num' => 'A',
                'is_time' => '0'
        );
        $columns[] = array(
                'column_name' => 'name',
                'column_num' => 'B',
                'is_time' => '0'
        );

        $columns[] = array(
                'column_name' => 'bumen',
                'column_num' => 'C',
                'is_time' => '0'
        );
        $columns[] = array(
                'column_name' => 'hours_bz',
                'column_num' => 'D',
                'is_time' => '2'
        );
        $columns[] = array(
                'column_name' => 'hours_sj',
                'column_num' => 'E',
                'is_time' => '2'
        );
        $columns[] = array(
                'column_name' => 'late_tm',
                'column_num' => 'F',
                'is_time' => '0'
        );
        $columns[] = array(
                'column_name' => 'late_mi',
                'column_num' => 'G',
                'is_time' => '0'
        );
        $columns[] = array(
                'column_name' => 'leave_tm',
                'column_num' => 'H',
                'is_time' => '0'
        );
        $columns[] = array(
                'column_name' => 'leave_mi',
                'column_num' => 'I',
                'is_time' => '0'
        );
        $columns[] = array(
                'column_name' => 'overtime_ts',
                'column_num' => 'J',
                'is_time' => '2'
        );
        $columns[] = array(
                'column_name' => 'overtime_zc',
                'column_num' => 'K',
                'is_time' => '2'
        );
        $columns[] = array(
                'column_name' => 'turn',
                'column_num' => 'L',
                'is_time' => '0'
        );
        $columns[] = array(
                'column_name' => 'evection',
                'column_num' => 'M',
                'is_time' => '0'
        );
        $columns[] = array(
                'column_name' => 'truant',
                'column_num' => 'N',
                'is_time' => '0'
        );  
        $columns[] = array(
                'column_name' => 'leave',
                'column_num' => 'O',
                'is_time' => '0'
        );   
        
        return $columns;
    }
}
?>