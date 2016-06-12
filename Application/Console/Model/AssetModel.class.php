<?php
namespace Console\Model;
use Think\Model;
class AssetModel extends Model{
    // 定义自动验证
    protected $_validate    =   array(
      //  array(验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]),           
        array('name','require','缺少用户名'),
        array('name','','用户已存在',2,'unique',3),
        array('usage','require','须填写用途'),
        array('money','require','请输入金额'),
        array('type','require','须填写类型'),
        );
    
    // 定义自动完成
    protected $_auto = array( 
       // array('password','autoPassword',self::MODEL_BOTH,'callback'),         
         array('gettime','time',self::MODEL_BOTH,'function'),        
         array('createtime','time',self::MODEL_BOTH,'function'),        
     );

    /**
    *返回资源头列车表
    **/
    public function getAsset(){        
      return $this->getfield('id,name',true);
    }
    /**
     *返回库存
    **/
    public function getStock($id){
      return $this->where('id=%d',array($id))->getfield('stock');
    }

    public function getStockDec($id,$num=1){
      return $this->where('id=%d',array($id))->setDec('stock',$num);
    }

}
?>