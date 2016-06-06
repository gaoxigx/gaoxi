<?php
namespace Console\Model;
use Think\Model;
class EquipmentModel extends Model {
    // 定义自动验证
    protected $_validate    =   array(
    	//array(验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]),
        array('xinghao','require','设备型号必须选择'),
        array('bianhao','require','设备编号必须填写'),
        array('daiyanren','require','设备绑定代言人必须填写'),
        array('sex','require','性别名必须填写'),
        array('mobile','require','设备号码(手机)必须填写'),
        // array('kaijipassword','require','开机密码必须填写'),
        array('bianhao','','设备编号已经存在！',2,'unique',1),// 在新增的时候验证name字段是否唯一

        );
    // 定义自动完成
    protected $_auto = array( 
         array('addtime','time',1,'function'), // 对update_time字段在更新的时候写入当前时间戳
     );

 }