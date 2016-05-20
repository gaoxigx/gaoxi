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
    $name = $datagoods-> field('product,price2,discount,buynum,tollsprice')->where('order_no="'.$order_no.'"')->order('id desc')->select();
    // dump($name);
    echo "<tr>";
    foreach ($name as $k => $v) {
        foreach($v as $key=>$value){
        if($key=='product'){
             echo '<td style="width:150px;">'.$value.'</td>';
        }else{
            echo '<td style="width:50px;">'.$value.'</td>';
        }
       
        }
        echo '</tr><tr>';
        }
    }
