<?php
namespace Console\Model;
use Think\Model;
class StaffModel extends Model {

    // 定义自动验证
    protected $_validate    =   array(
      //  array(验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]),           
        array('username','require','缺少用户名'),
        array('username','','用户已存在',2,'unique',3),
        array('password','require','缺少密码'),
        array('password','5,12','密码必须长度是5-12位',2,'length'),
        array('password','passwordConfirm','两次密码不一致',0,'confirm',1),        
        array('nickname','','改昵称已被使用过',2,'unique',3),
        array('name','require','姓名必须填写'),     
        array('identity_card','15,18','请填写有效的身份证号码',2,'length'),
        array('mobile','require','联系方式必须填写'),
        //array('bank_account','16','银行卡号不正确',2,'length'),
        );
    // 定义自动完成
    protected $_auto = array( 
       // array('password','autoPassword',self::MODEL_BOTH,'callback'),         
         array('addtime','time',1,'function'), // 对update_time字段在更新的时候写入当前时间戳
         array('create_time','time',self::MODEL_BOTH,'function'),        
         array('update_time','time',self::MODEL_BOTH,'function'),        
     );

    /**
     *查询当前用户下级人员     
    **/
    public function getthislevel(){      
        return $this->getlevel(array(session('userid'))); 
    }
    public function getotherlevel($userid){ 
        return $this->getlevel($userid);
    }
    private function getlevel($staffidarr){                        
        foreach ($staffidarr as $k => $v) {            
            $field=$this->where("nibs=%d",array($v))->getField('id',true);             
            if($field){
                $data=$this->getlevel($field);
            }
            $data[]=$v;
        } 
        return $data;  
    }
    
    public function getColumn(){
        $columns[] = array(
                'column_name' => 'number',
                'column_num' => 'A',
                'is_time' => '0'
        );
        $columns[] = array(
                'column_name' => 'name',
                'column_num' => 'B',
                'is_time' => '0',
				'is_where'=>'1'
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