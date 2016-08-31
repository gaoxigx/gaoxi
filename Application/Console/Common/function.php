<?php


//通用获取判断
//
function getstautsnum($stauts){
    if ($stauts == 1) {
        return '是';
    }else{
        return '否';
    }

}

function getprotype(){
    $data=D('protype');
    $name = $data->where('1=1')->order('orderid')->select();
        // dump($name);
        // assign('protype',$name);
}
function getstaffname(){
    $model=D("staff");
    $data=$model->where('iswork!=4')->getfield('id,name',true);
    return $data;
}
function getstautel($param){
    if($param==1){
        return "使用";
    }else{
        return "维修";
    }
}
function getorderget($param){
    if($param==1){
        return "推广小号";
    }else{
        return "销售运营";
    }
}

function json_ary($json){
    return json_decode($json,true);
}

//通过组ID值 ，取组名称
    function Getrolename($orleid){ 
    $data=D('role'); 
    $name=$data->where('id='.$orleid)->find(); 
    return $name['rolename']; 
    } 

//通过产品类别ID值 ，取类别名称
    function Getprotypename($id){ 
    $data=D('protype'); 
    $name=$data->where('id='.$id)->find(); 
    return $name['typename']; 
    } 
//通过id获取纪经人，经纪人助理
    function get_id_to_perid($id=''){
    $data=D('controller');
    $name = $data-> field('username')->where('id='.$id)->find();
    return $name['username'];
}

//通过订单编号获取产品
    function get_orderno_to_pro($order_no){
        $datagoods = D('order_goods');
        $prolist = $datagoods-> field('product,price2,quality,grade,box,discount,buynum,tollsprice')->where('order_no="'.$order_no.'"')->order('id desc')->select(); 
        return $prolist;
    }

    function get_orderno_to_proname($order_no){
    $datagoods = D('order_goods');
    $name = $datagoods-> field('product,price2,quality,box,discount,buynum,tollsprice')->where('order_no="'.$order_no.'"')->order('id desc')->select();
//    dump($name);
//     echo M()->getLastSql();exit;
     
   
        foreach ($name as $k => $v) {
            echo "<tr>";
            echo '<td >'.$v['product'].'</td>';
            echo "<tr>";
            echo '</tr>';
            echo '<td>'.$v['quality'].'---'.$v['box'];
            echo '&nbsp;&nbsp;&nbsp;&nbsp;数量:<b>'.$v['buynum'].'</b></td>';
            echo '</tr>';
        }
    }
    //隐藏字段方法
    function hiddenstr($str){
        if(strlen($str)>9&&strlen($str)<=11){      
            return substr_replace($str,'***',3,mb_strlen($str)-6);
        }
        if(strlen($str)<=9){
            return substr_replace($str,'**',0,mb_strlen($str)-4);
        }
        
        return substr_replace($str,'******',9,strlen($str));

    }


    function pcsstr($index){
        $pcsary=array(
            1=>'两',
            2=>'斤',
            3=>'份',
        );
        return $pcsary[$index];



    }
/*
 * 时间搓转换成文本
 */
    function timestr($obj){
        $h=floor( $obj/3600);        
        $s=floor(($obj%3600)/60);
        $s=($s==0)?'00':$s;
        $str=$h.':'.$s;  
        return $str;
    }
    /*
    * 时间搓转换成文本
    * return array
    */
    function timearray($str='h',$obj){
        $h=floor( $obj/3600);        
        $s=floor(($obj%3600)/60);
        $s=($s==0)?'00':$s;
        $data['h'] = $h;
        $data['s'] = $s;
        
        return $data[$str];
    }
?>
