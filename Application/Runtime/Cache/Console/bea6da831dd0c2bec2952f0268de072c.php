<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="/Public/Console/css/style.css" rel="stylesheet" type="text/css" />
<link href="/Public/Console/css/page.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/Public/Console/js/jquery.js"></script>

<script type="text/javascript">
$(document).ready(function(){
  $(".click").click(function(){
  $(".tip").fadeIn(200);
  });
  
  $(".tiptop a").click(function(){
  $(".tip").fadeOut(200);
});

  $(".sure").click(function(){
  $(".tip").fadeOut(100);
});

  $(".cancel").click(function(){
  $(".tip").fadeOut(100);
});

});
</script>


</head>


<body>

	<div class="place">
    <span>位置：</span>
    <ul class="placeul">
    <li><a href="#">首页</a></li>
    <li><a href="#">订单管理</a></li>
    <li><a href="#">订单列表</a></li>
    </ul>
    </div>
    
    <div class="rightinfo">
    
    <div class="tools">
     
        <ul class="toolbar1">
        <!-- <li><span><img src="/Public/Console/images/t05.png" /></span>设置</li> -->
        </ul>

    <ul class="seachform">
    <form action="/index.php/Console/Order/Plist" method="post" id="form2" name="form2">
    <li><label>类别<b>*</b></label>  
            <select name="protype" class="sciselect">
            <option value="">全部</option>
            <?php if(is_array($protype)): $i = 0; $__LIST__ = $protype;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($voo["id"]); ?>"><?php echo ($voo["typename"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            </li>

    <li><label>产品名称</label><input type="text" class="scinpute" name="product"></li>
    <li><label>采购负责人</label><input type="text" class="scinpute" name="purchaseper"></li>

    <li><label>&nbsp;</label><input type="submit" value="查询" class="scbtn" name="">
    </li>
    
    </form></ul>
    </div>
    
    
    <table class="tablelist">
    	<thead>
    	<tr>
        <th><input name="" type="checkbox" value="" checked="checked"/></th>
        <th>ID<i class="sort"><img src="/Public/Console/images/px.gif" /></i></th>
        <th>订单编辑</th>
        <th>下单时间</th>
        <th style="width:150px;">产品</th>
        <th style="width:50px;">价格</th>
        <th style="width:50px;">折扣</th>
        <th style="width:50px;">数量</th>
        <th style="width:50px;">优惠价</th>
        <th>订单金额</th>
        <th>经纪人</th>
        <th>纪经人助理</th>
        <th>操作发货</th>
        <th>确认收款</th>
        <th>操作</th>
        </tr>
        </thead>
        <tbody>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>

       <td><input name="" type="checkbox" value="" /></td>
        <td ><?php echo ($vo["id"]); ?></td>
        <td><?php echo ($vo["order_no"]); ?></td>
        <td><?php echo (date('Y-m-d H:i:s',$vo["addtime"])); ?></td>
        <td colspan="5">
        <table class="tablelistpro" style="border:0px;">
            <?php echo (get_orderno_to_pro($vo["order_no"])); ?>
        </table>
        </td>
        <td><?php echo ($vo["total_price"]); ?></td>
        <td><?php echo (get_id_to_perid($vo["agent"])); ?></td>
        <td><?php echo (get_id_to_perid($vo["assistant"])); ?></td>
        <td>
    <?php switch($vo["payment_status"]): case "0": ?>已下单.<?php break;?>
        <?php case "1": switch($vo["status"]): case "1": ?>已下单 
                    <input type="text" style="height:23px;boder:1px;" id="suresmall3<?php echo ($vo["id"]); ?>" class="scinpute" name="numberno"> 
                    <input  name="<?php echo ($vo["id"]); ?>" data="<?php echo ($vo["payment_status"]); ?>" type="button"  class="suresmall3" value="发货" /><?php break;?>
                <?php case "2": ?>已发货 <?php echo ($vo["numberno"]); ?> <input onclick="javascript:window.location='numberno/no/<?php echo ($vo["numberno"]); ?>'" type="button"   value="查询" /><?php break; endswitch; break;?>
        <?php default: ?>已下单<?php endswitch;?></td>
        <td>
<?php if($vo["payment_status"] == 0): ?><input name="<?php echo ($vo["id"]); ?>" data="<?php echo ($vo["payment_status"]); ?>" type="button"  class="suresmall" value="点击确认" />
<?php else: ?>
    <input  type="button"  class="suresmall2" value="已付款" /><?php endif; ?>
        </td>
        <td><!-- <a href="/index.php/Console/Order/edit/id/<?php echo ($vo["id"]); ?>" class="tablelink">修改</a> --><a href="/index.php/Console/Order/edit/id/<?php echo ($vo["id"]); ?>" class="tablelink">查看</a></td>
        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        
        </tbody>
    </table>
    
   
   <div id="page" class="pagination">
　　<?php echo ($page); ?>
</div>
    
    
    <script type="text/javascript">
	// $('.tablelist tbody tr:odd').addClass('odd');
     $(document).ready(function(){
    
        //数字验证 
        // $("#getcode_num").click(function(){
        //     $(this).attr("src", 'verify.html?' + Math.random());
        // });
        
        $(".suresmall").click(function(){

            var id = $(this).attr('name');
            var data = $(this).attr('data');
            $("#form2").attr("action", "Payment_status/id/"+id+"/status/"+data).submit();

        });
        $(".suresmall3").click(function(event) {
            var id = $(this).attr('name');
            var data = $(this).attr('data');

if($("#suresmall3"+id ).val()==''){
    alert("物流单号不能为空！");
    return false;
} else{
    $("#form2").attr("action", "updatenumberno/id/"+id+"/numberno/"+$("#suresmall3"+id ).val()).submit();

}



    //  val()是jQuery获取元素值得一个函数


            // if($("#suresma113"+id+".val()") ==''){
            //     alert('物流单号不能为空！');
            //     return false;
            // }
        });
    });
    // 
	</script>




</body>

</html>