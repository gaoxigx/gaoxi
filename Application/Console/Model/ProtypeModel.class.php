<?php
    namespace Console\Model;
    use Think\Model;
    class ProtypeModel extends Model{
       protected $_validate = array(
         array('typename','require','类别名称必须填写'), //默认情况下用正则进行验证
         array('typename','','名称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
       );

    // 定义自动完成
    protected $_auto    =   array(
        array('orderid','10'),
        );

 }