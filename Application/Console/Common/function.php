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
        if(strlen($str)>6&&strlen($str)<10){     
            return substr_replace($str,'**',0,3);
        }

        if(strlen($str)<=10){
            return substr_replace($str,'**',0,3);           
        }

         if(strlen($str)<=12){
           return substr_replace($str,'***',3,strlen($str)-6);
        }

        return substr_replace($str,'***',18,strlen($str)-6);

    }

    function pcsstr($index){
        $pcsary=array(
            1=>'两',
            2=>'斤',
            3=>'份',
        );
        return $pcsary[$index];
    }

    function getexpresstext($index){
        $express=array(
            1=>'顺丰',
            2=>'圆通',
            3=>'中通',
            4=>'韵达',
            5=>'邮政'
            );
        return $express[$index];
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

    function cut_str($string, $sublen, $start = 0, $code = 'UTF-8'){
        if($code == 'UTF-8'){
            $pa = "/[x01-x7f]|[xc2-xdf][x80-xbf]|xe0[xa0-xbf][x80-xbf]|[xe1-xef][x80-xbf][x80-xbf]|xf0[x90-xbf][x80-xbf][x80-xbf]|[xf1-xf7][x80-xbf][x80-xbf][x80-xbf]/";
            preg_match_all($pa, $string, $t_string);

            if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen))."...";
                return join('', array_slice($t_string[0], $start, $sublen));
            }else{
            $start = $start*2;
            $sublen = $sublen*2;
            $strlen = strlen($string);
            $tmpstr = '';

            for($i=0; $i< $strlen; $i++)

            {

            if($i>=$start && $i< ($start+$sublen)){
                if(ord(substr($string, $i, 1))>129){
                    $tmpstr.= substr($string, $i, 2);
                }else{
                    $tmpstr.= substr($string, $i, 1);
                }
            }
            if(ord(substr($string, $i, 1))>129) $i++;
        }
        if(strlen($tmpstr)< $strlen ) $tmpstr.= "...";
        return $tmpstr;
        }
    }
    //两换成kg
    function psckg($param){
        return $param*50/1000;
    }
    
    
    //导出Execl表格
    function outExcel($dataArr, $fileName = '', $sheet = false) {
        
        ob_end_clean();        
        require_once VENDOR_PATH . 'download-xlsx.php';
        export_csv ( $dataArr, $fileName, $sheet );
        unset ( $sheet );
        unset ( $dataArr );
    }
    
 //导入表格   
 function importFormExcel($attach_id, $column, $dateColumn = array(),$filepath='') {
        $attach_id = intval ( $attach_id );
        $res = array (
                'status' => 0,
                'data' => '' 
        );
        if (empty ( $attach_id ) || ! is_numeric ( $attach_id )) {
            $res ['data'] = '上传文件ID无效！';
            return $res;
        }
        //$file = M ( 'file' )->where ( 'id=' . $attach_id )->find ();
        //$root = C ( 'DOWNLOAD_UPLOAD.rootPath' );
       // $filename = SITE_PATH . '/Uploads/Download/' . $file ['savepath'] . $file ['savename'];
        $filename=$filepath;
    
        // dump($filename);
        if (! file_exists ( $filename )) {
            $res ['data'] = '上传的文件失败';
            return $res;
        }
        $extend = $file ['ext']='xls';
   
        if (! ($extend == 'xls' || $extend == 'xlsx' || $extend == 'csv')) {
            $res ['data'] = '文件格式不对，请上传xls,xlsx格式的文件';
            return $res;
        }
        
        vendor ( 'PHPExcel' );
        vendor ( 'PHPExcel.PHPExcel_IOFactory' );
        vendor ( 'PHPExcel.Reader.Excel5' );
        
        switch (strtolower ( $extend )) {
            case 'csv' :
                $format = 'CSV';
                $objReader = \PHPExcel_IOFactory::createReader ( $format )->setDelimiter ( ',' )->setInputEncoding ( 'GBK' )->setEnclosure ( '"' )->setLineEnding ( "\r\n" )->setSheetIndex ( 0 );
                break;
            case 'xls' :
                $format = 'Excel5';
                $objReader = \PHPExcel_IOFactory::createReader ( $format );
                break;
            default :
                $format = 'excel2007';
                $objReader = \PHPExcel_IOFactory::createReader ( $format );
        }
  
        $objPHPExcel = $objReader->load ( $filename );
        $objPHPExcel->setActiveSheetIndex ( 0 );
        $sheet = $objPHPExcel->getSheet ( 0 );
        $highestRow = $sheet->getHighestRow (); // 取得总行数
        for($j = 2; $j <= $highestRow; $j ++) {
            $addData = array ();
            foreach ( $column as $k => $v ) {
                if ($dateColumn) {
                    foreach ( $dateColumn as $d ) {
                        if ($k == $d) {
                            $addData [$v] = gmdate ( "Y-m-d H:i:s", PHPExcel_Shared_Date::ExcelToPHP ( $objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->getValue () ) );
                        } else {
                            $addData [$v] = trim ( ( string ) $objPHPExcel->getActiveSheet ()->getCell ( $k . $j )->getValue () );
                        }
                    }
                } else {
                    $addData [$v] = trim ( ( string ) $objPHPExcel->getActiveSheet ()->getCell ( $k . $j )->getValue () );
                }
            }
            
            $isempty = true;
            foreach ( $column as $v ) {
                $isempty && $isempty = empty ( $addData [$v] );
            }
            
            if (! $isempty)
                $result [$j] = $addData;
        }
        $res ['status'] = 1;
        $res ['data'] = $result;
        return $res;
    }

    
      

?>
