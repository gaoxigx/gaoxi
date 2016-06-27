<?php
    namespace Console\Model;
    use Think\Model\RelationModel;
    class OrderModel extends RelationModel{
        protected $_link = array(
            'Profile'=>array(
                'mapping_type'      => self::BELONGS_TO,
                'class_name'        => 'Profile',
                // 定义更多的关联属性
                ),
            );
        
    // 定义自动完成
    protected $_auto = array( 
         array('addtime','time',1,'function'), // 对addtime(修改时间)字段在更新的时候写入当前时间戳
         array('create_time','time',1,'function'), // 对create_time(创建时间)字段在更新的时候写入当前时间戳
     );
        
    }