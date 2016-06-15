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
    }