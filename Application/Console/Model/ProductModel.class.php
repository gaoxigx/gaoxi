<?php
namespace Console\Model;
use Think\Model;
class productModel extends Model {
    // 定义自动验证
    protected $_validate    =   array(
        //array(验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]),
        array('protype','require','类别必须填写'),
        array('product','require','产品名称必须填写'),
        array('price1','require','成本价格必须填写'),
        array('price2','require','标准价格必须填写'),
        array('price3','require','最高价格必须填写'),
        array('discount','require','最低折扣必须填写'),
        array('inputnum','require','进货数量必须填写'),
        array('sortnum','require','剩余库存必须填写'),
        array('salenum','require','销售数量必须填写'),
        array('purchaseper','require','采购负责人必须填写'),
        );
    // 定义自动完成
    protected $_auto = array( 
         array('addtime','time',1,'function'), // 对update_time字段在更新的时候写入当前时间戳
     );

 }