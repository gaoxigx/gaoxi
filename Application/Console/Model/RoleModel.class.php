<?php
namespace Console\Model;
use Think\Model;
class roleModel extends Model {
    // 定义自动验证
    protected $_validate    =   array(
        array('rolename','require','组名称必须'),
        );
    // 定义自动完成
    protected $_auto    =   array(
        array('addtime','time',1,'function'),
        // array('status','1'),
        );

 }