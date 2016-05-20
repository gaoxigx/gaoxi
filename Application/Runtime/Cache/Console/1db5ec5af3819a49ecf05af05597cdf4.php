<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="/Public/Console/css/style.css" rel="stylesheet" type="text/css" />
<link href="/Public/Console/css/select.css" rel="stylesheet" type="text/css" />
<link href="/Public/Console/css/lanrenzhijia.css" rel="stylesheet" type="text/css" />
<script src="/Public/Console/js/jquery.1.4.2-min.js"></script>
<script src="/Public/Console/js/Calculation.js"></script>



</head>

<body>

    <div class="place">
    <span>位置：</span>
    <ul class="placeul">
    
    <li><a href="#">首页</a></li>
    <li><a href="#">订单管理</a></li>
    <li><a href="#">添加订单</a></li>
    </ul>
    </div>
    
    <div class="formbody">
    <div id="usual1" class="usual"> 
    
    <div class="itab">
        <form name="form1" id="form1" method='post' action="/index.php/Console/Order/insert">
    <ul> 

    <li><a href="#tab1" class="selected">发布信息</a></li> 
   <input type="hidden" name="order_no"  value="2016051599535210">
    </ul>
    </div> 
    
    <div id="tab1" class="tabson">
    
    <div class="formtext">请正确填写发布信息！</div>
    <ul class="forminfo">
    <li><label>经纪人<b>*</b></label>  
        <div class="vocation uew-select">
            <select name="agent" class="uew-select-value">
                <?php if(is_array($agent)): $i = 0; $__LIST__ = $agent;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo1["id"]); ?>"><?php echo ($vo1["username"]); ?>(<?php echo ($vo1["accounts"]); ?>)</option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </li>

    <li><label>经纪助理编号<b>*</b></label>  
            <div class="vocation uew-select">
                <select name="assistant" class="uew-select-value">
                    <?php if(is_array($assistant)): $i = 0; $__LIST__ = $assistant;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo2["id"]); ?>"><?php echo ($vo2["username"]); ?>(<?php echo ($vo2["accounts"]); ?>)</option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
    </li>
    <li><label>设备编号<b>*</b></label>  
        <div class="vocation uew-select">
            <select name="equipment" class="uew-select-value">
                <?php if(is_array($equipment)): $i = 0; $__LIST__ = $equipment;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo3): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo3["id"]); ?>"><?php echo ($vo3["xinghao"]); ?>(<?php echo ($vo3["bianhao"]); ?>)</option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </li>

        <li><label>支付方式<b>*</b></label>  
            <div class="vocation uew-select">
                <select name="Payment_method" class="uew-select-value">
                    <?php if(is_array($payment)): $i = 0; $__LIST__ = $payment;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo4): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo4["id"]); ?>"><?php echo ($vo4["payment"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
    </li>

    <li><label>买家微信号<b>*</b></label><input name="buyer_wechat" type="text" class="dfinput" value=""  /></li>
    <li><label>订单来源<b>*</b></label><input name="source" type="text" class="dfinput" value=""  /></li>
    <li><label>收件人姓名<b>*</b></label><input name="username" type="text" class="dfinput" value=""  /></li>
    <li><label>收件人电话<b>*</b></label><input name="mobile" type="text" class="dfinput" value=""  /></li>
    <li><label>收件人地址<b>*</b></label><input name="address" type="text" class="dfinput" value=""  /></li>
    <li><label>订单备注<b>*</b></label><input name="note" type="text" class="dfinput" value=""  /></li>
    <li><label><b>购买商品</b></li>
    </ul>

    <script>
$(document).ready(function () {

    //jquery特效制作复选框全选反选取消(无插件)
    // 全选        
    $(".allselect").click(function () {
        $(".gwc_tb2 input[aajq=newslist]").each(function () {
            $(this).attr("checked", true);
            $(this).next().css({ "background-color": "#3366cc", "color": "#ffffff" });
        });
        GetCount();
    });

    //反选
    $("#invert").click(function () {
        $(".gwc_tb2 input[aajq=newslist]").each(function () {
            if ($(this).attr("checked")) {
                $(this).attr("checked", false);
                //$(this).next().css({ "background-color": "#ffffff", "color": "#000000" });
            } else {
                $(this).attr("checked", true);
                //$(this).next().css({ "background-color": "#3366cc", "color": "#000000" });
            } 
        });
        GetCount();
    });

    //取消
    $("#cancel").click(function () {
        $(".gwc_tb2 input[aajq=newslist]").each(function () {
            $(this).attr("checked", false);
            // $(this).next().css({ "background-color": "#ffffff", "color": "#000000" });
        });
        GetCount();
    });

    // 所有复选(:checkbox)框点击事件
    $(".gwc_tb2 input[aajq=newslist]").click(function () {
        if ($(this).attr("checked")) {
            //$(this).next().css({ "background-color": "#3366cc", "color": "#ffffff" });
        } else {
            // $(this).next().css({ "background-color": "#ffffff", "color": "#000000" });
        }
    });

    // 输出
    $(".gwc_tb2 input[aajq=newslist]").click(function () {
        // $("#total2").html() = GetCount($(this));
        GetCount();
        //alert(conts);
    });
});
//******************
function GetCount() {
    var conts = 0;
    var aa = 0;
    $(".gwc_tb2 input[aajq=newslist]").each(function () {
        if ($(this).attr("checked")) {
            for (var i = 0; i < $(this).length; i++) {
                conts += parseInt($(this).next().val());
                aa += 1;
            }
        }
    });
    $("#shuliang").val(aa);
    $("#zong1").val((conts).toFixed(2));
    $("#jz1").css("display", "none");
    $("#jz2").css("display", "block");
}
</script>
<div class="gwc" style=" margin:auto;">
<table cellpadding="0" cellspacing="0" class="gwc_tb1">
    <tr>
      <td class="tb1_td1"><input id="Checkbox1" type="checkbox"  class="allselect"/></td>
      <td class="tb1_td1">全选</td>
      <td class="tb1_td3">商品</td>
      <td class="tb1_td4">标准价</td>
      <td class="tb1_td5">数量</td>
      <td class="tb1_td6">优惠折扣</td>
      <td class="tb1_td8">优惠总价</td>
      <td class="tb1_td7">操作</td>
    </tr>
  </table>
  <!---商品加减算总数---->
  <?php if(is_array($prolist)): $i = 0; $__LIST__ = $prolist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pvo): $mod = ($i % 2 );++$i;?><script>
$(function () {
//删除商品
$(".mya").click(function()
{
$(this).parent().parent().parent().remove();
GetCount();
});

$("#zhekou<?php echo ($i); ?>").change(function(){
setTotal(); GetCount();

});

var t = $("#text_box<?php echo ($i); ?>");//产品数量
var p = $("#price<?php echo ($i); ?>");//单价
var z = $("#zhekou<?php echo ($i); ?>");//折扣

$("#add<?php echo ($i); ?>").click(function () {
t.val(parseInt(t.val()) + 1)
setTotal(); GetCount();
})
$("#min<?php echo ($i); ?>").click(function () {
t.val(parseInt(t.val()) - 1)
if(t.val()<1){t.val(1)}//数量小于1为1
setTotal(); GetCount();
})
function setTotal() {
$("#total<?php echo ($i); ?>").val(parseInt((parseInt(t.val()) * p.val() * z.val()*0.1)).toFixed(2));
 $("#tolnums<?php echo ($i); ?>").val(parseInt(t.val()) * p.val() * z.val()*0.1);
}
setTotal();
    })
    </script>
<table cellpadding="0" cellspacing="0" class="gwc_tb2">
<tr>
<td class="tb2_td1">
  <input type="checkbox" value="<?php echo ($i); ?>" aajq="newslist" name="newslist[]" id="newslist-<?php echo ($i); ?>" />
<input type="hidden" name="tolnums" id="tolnums<?php echo ($i); ?>" value="">
</td>
<td class="tb2_td2"><a href="#"><img src="/Public/console/images/img1.jpg"/></a></td>
<td class="tb2_td3">
<input   name="product<?php echo ($i); ?>" type="text" value="<?php echo ($pvo["product"]); ?>" class="totnum" style="width:200px;color:#666;" />
  
</td>
<td class="tb1_td4">
￥<input id="price<?php echo ($i); ?>"  name="price<?php echo ($i); ?>" type="text" value="<?php echo ($pvo["price2"]); ?>" class="totnum" />
</td>
<td class="tb1_td5">
<input id="min<?php echo ($i); ?>" name="" class="min" type="button" value="-" />
<input id="text_box<?php echo ($i); ?>" name="text_box<?php echo ($i); ?>" type="text" value="1" class="num" />
<input id="add<?php echo ($i); ?>" name="" class="add" type="button" value="+" />
</td>
<td class="tb1_td6">
  <input id="zhekou<?php echo ($i); ?>" name="zhekou<?php echo ($i); ?>" type="text" value="<?php echo ($pvo["discount"]); ?>" class="num" />
 折</td>
<td class="tb1_td8">
 ￥<input id="total<?php echo ($i); ?>"  name="total<?php echo ($i); ?>" type="text" value="" class="totnum" />
</td>
<td class="tb1_td7"><a href="#" class="mya">删除</a></td>
</tr>
</table><?php endforeach; endif; else: echo "" ;endif; ?>
  <!---总数---->
  <script>
    $(function () {
        $(".quanxun").click(function () {
            setTotal();
            //alert($(lens[0]).text());
        });
        function setTotal() {
            var len = $(".tot");
            var num = 0;
            for (var i = 0; i < len.length; i++) {
                num = parseInt(num) + parseInt($(len[i]).text());

            }
            //alert(len.length);
            $("#zong<?php echo ($i); ?>").text(parseInt(num));
            $("#shuliang").text(len.length);
        }
        //setTotal();
    })
    </script>
  <table cellpadding="0" cellspacing="0" class="gwc_tb3">
    <tr>
      <td class="tb1_td1"><input id="checkAll" class="allselect" type="checkbox" /></td>
      <td class="tb1_td1">全选</td>
      <td class="tb3_td1"><input id="invert" type="checkbox" />
        反选
        <input id="cancel" type="checkbox" />
        取消 </td>
      <td class="tb3_td2">已选商品
<input id="shuliang"  name="pro_num" type="text" value="0" class="totprice"  />

        </span>
        件</td>
      <td class="tb3_td3">合计(不含运费):<span>￥
        <input id="zong1"  name="total_price" type="text" value="0" class="totprice" />
        </span></td>

    </tr>
  </table>
  </div>


    <li><label>&nbsp;</label><input name="" type="submit" class="btn" value="提交订单"/></li>

    
</form>    
    </div> 
      
    </div> 
    </div>


    
    
    
    
    </div>
</body>

</html>