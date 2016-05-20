<?php
namespace Console\Model;
use Think\Model;
class WecateModel extends Model {
    // 定义自动验证
    protected $_validate    =   array(
        array('wecate_id','require','微信号必须填写'),
        array('名称','username','姓名必须填写'),
        array('sex','require','性别名必须填写'),
        array('wecate_id','','微信号已存在！',2,'unique',1),// 在新增的时候验证字段是否唯一
        );
    // 定义自动完成
    protected $_auto = array( 
         array('addtime','time',1,'function'), // 对update_time字段在更新的时候写入当前时间戳
         array('create_time','time',self::MODEL_BOTH,'function'),
     );

 }