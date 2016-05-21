<?php
namespace Console\Model;
use Think\Model;
class StaffModel extends Model {
    // 定义自动验证
    protected $_validate    =   array(
        //array(验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]),           
        array('username','require','缺少用户名'),
        array('username','','用户已存在',self::MUST_VALIDATE,'unique',self::MODEL_INSERT),
        array('password','require','缺少密码'),
        array('password','5,12','密码必须长度是5-12位',self::MUST_VALIDATE,'length'),
        array('password','passwordConfirm','两次密码不一致',0,'confirm'),        
        array('nickname','','改昵称已被使用过',self::MUST_VALIDATE,'unique',self::MODEL_INSERT),
        array('name','require','姓名必须填写'),     
        array('identity_card','15,18','请填写有效的身份证号码',self::MUST_VALIDATE,'length'),
        array('mobile','require','联系方式必须填写'),
        array('bank_account','16','银行卡号不正确',self::MUST_VALIDATE,'length'),
        );
    // 定义自动完成
    protected $_auto = array( 
       // array('password','autoPassword',self::MODEL_BOTH,'callback'),         
         array('addtime','time',1,'function'), // 对update_time字段在更新的时候写入当前时间戳
         array('create_time','time',self::MODEL_BOTH,'function'),        
         array('update_time','time',self::MODEL_BOTH,'function'),        
     );
//    protected  function autoPassword($value)
//    {
//        return password_hash($value,PASSWORD_BCRYPT);
//    }
 }