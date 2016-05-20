<?php
namespace Console\Model;
use Think\Model;
class controllerModel extends Model {
    // 定义自动验证
    protected $_validate    =   array(
    	//array(验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]),
        array('accounts','require','用户名必须填写'),
        array('password','require','密码名必须填写',0,'',1),
        array('nickname','require','昵称必须填写'),
        array('username','require','姓名必须填写'),
        array('sex','require','性别名必须填写'),
        array('mobile','require','手机必须填写'),
        array('accounts','','用户名称已经存在！',2,'unique',1),// 在新增的时候验证name字段是否唯一

        );
    // 定义自动完成
    protected $_auto = array( 
         // array('estate',1),  // 新增的时候把status字段设置为1
         array('logincount',0),  // 新增的时候把status字段设置为1
         array('password','_md5',3,'callback') , // 对password字段在新增和编辑的时候使md5函数处理
         // array('password','_md5', 3, 'callback'),
         array('password','', 2, 'ignore'),


         // array('name','getName',3,'callback'), // 对name字段在新增和编辑的时候回调getName方法
         array('addtime','time',1,'function'), // 对update_time字段在更新的时候写入当前时间戳
         array('endtime','strtotime',1,'function'), // 对update_time字段在更新的时候写入当前时间戳
     );

    
    protected function _md5( $data ){
        if( !$data ){
            return '';
        }
        return md5( $data );
    }
 }