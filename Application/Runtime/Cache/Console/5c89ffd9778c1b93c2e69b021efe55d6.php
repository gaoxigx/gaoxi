<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="/Public/Console/css/style.css" rel="stylesheet" type="text/css" />
<link href="/Public/Console/css/select.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/Public/Console/js/jquery.js"></script>

<script type="text/javascript" src="/Public/Console/js/jquery.js"></script>
<script type="text/javascript" src="/Public/Console/js/jquery.idTabs.min.js"></script>
<script type="text/javascript" src="/Public/Console/js/select-ui.min.js"></script>

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
    <li><a href="#">产品管理</a></li>
    <li><a href="#">添加产品</a></li>
    </ul>
    </div>
    
    <div class="formbody">
    <div id="usual1" class="usual"> 
    
    <div class="itab">
        <form name="form1" id="form1" enctype="multipart/form-data"  method='post'   action="/index.php/Console/Product/update">
    <input type="hidden" name ="id"   value="<?php echo ($data["id"]); ?>">
    <ul> 

    <li><a href="#tab1" class="selected">发布信息</a></li> 
   
    </ul>
    </div> 
    
    <div id="tab1" class="tabson">
    
    <div class="formtext">请正确填写发布信息！</div>
    <ul class="forminfo">
    <li><label>类别<b>*</b></label>  
        <div class="vocation uew-select">
            <select name="protype" class="uew-select-value" autocomplete="off">
            <?php if(is_array($protype)): $i = 0; $__LIST__ = $protype;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($voo["id"]); ?>" <?php if($voo["id"] = $data.protype): ?>selected="selected" <?php else: ?> 未审核<?php endif; ?>><?php echo ($voo["typename"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    <div class="click" style="float: left;padding-left: 20px; width: 80px;padding-top:5px;line">
    <span> <img src="/Public/Console/images/t01.png">新建分类</span></div>
</li>
    <li><label>产品名称<b>*</b></label><input name="product" type="text" class="dfinput" value="<?php echo ($data["product"]); ?>"  /></li>
    <li><label>图片上传<b>*</b></label><input name="pic" type="file" class="dfinput" value=""  /></li>
    <li><label>图片预览<img src="/Uploads/<?php echo ($data["pic1"]); ?>"></li>
    <li><label>成本价格<b>*</b></label><input name="price1" type="text" class="dfinput" value="<?php echo ($data["price1"]); ?>"  /></li>
    <li><label>标准价格<b>*</b></label><input name="price2" type="text" class="dfinput" value="<?php echo ($data["price2"]); ?>"  /></li>
    <li><label>最高价格<b>*</b></label><input name="price3" type="text" class="dfinput" value="<?php echo ($data["price3"]); ?>"  /></li>
    <li><label>最低折扣<b>*</b></label><input name="discount" type="text" class="dfinput" value="<?php echo ($data["id"]); ?>"  /></li>
    <li><label>进货数量<b>*</b></label><input name="inputnum" type="text" class="dfinput" value="<?php echo ($data["discount"]); ?>"  /></li>
    <li><label>剩余库存<b>*</b></label><input name="sortnum" type="text" class="dfinput" value="<?php echo ($data["sortnum"]); ?>"  /></li>
    <li><label>销售数量<b>*</b></label><input name="salenum" type="text" class="dfinput" value="<?php echo ($data["salenum"]); ?>"  /></li>  
    <li><label>采购负责人<b>*</b></label><input name="purchaseper" type="text" class="dfinput" value="<?php echo ($data["purchaseper"]); ?>"  /></li>    
 
   <li><label>是否审核</label><cite><input type="radio" checked="checked" value="1" name="stauts">是&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" value="0" name="stauts">否</cite></li>
    <li><label>&nbsp;</label><input name="" type="submit" class="btn" value="马上发布"/></li>

    </ul>
</form>    
    </div> 
      
    </div> 
    </div>

    <div class="tip">
        <form name="formaddtype" action="/index.php/Console/Product/getaddtype" method="post">
        <div class="tiptop"><span>新建分类</span><a></a></div>
        
        <div class="tipinfo">
            <div class="tipright">
                <p>类别名称</p>
                <cite>如果确定请点击新建按钮 ，否则请点取消。</cite>
                <input name="typename" type="text" class="dfinput" value=""  />
            </div>
        </div>
        <div class="tipbtn">
        <input name="" type="submit"  class="sure" value="新建" />&nbsp;
        <input name="" type="button"  class="cancel" value="取消" />
        </div>
    </form>
    </div>
    
    
    
    
    </div>
</body>

</html>