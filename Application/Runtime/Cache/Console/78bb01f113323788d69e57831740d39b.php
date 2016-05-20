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
  // alert($(this).attr('data'));
  // $(".picpic").src($(this).attr('data'));
  $(".picpic").attr('src',$(this).attr('data')); 
  $(".tip0").fadeIn(200);
  });
  
  $(".tiptop a").click(function(){
  $(".tip0").fadeOut(200);
});

  $(".tip0").click(function(){
  $(".tip0").fadeOut(100);
});

  $(".cancel").click(function(){
  $(".tip0").fadeOut(100);
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
    <li><a href="#">产品列表</a></li>
    </ul>
    </div>
    
    <div class="rightinfo">
    
    <div class="tools">
     
        <ul class="toolbar1">
        <!-- <li><span><img src="/Public/Console/images/t05.png" /></span>设置</li> -->
        </ul>

    <ul class="seachform">
    <form action="/index.php/Console/Product/Plist" method="post" id="form2" name="form2">
    <li><label>类别<b></b></label>  
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
        <th>类别</th>
        <th>图片</th>
        <th>产品名称</th>
        <th>成本价格</th>
        <th>标准价格</th>
        <th>最高价格</th>
        <th>最低折扣</th>
        <th>进货数量</th>
        <th>剩余库存</th>
        <th>销售数量</th>
        <th>采购负责人</th>
        <th>添加日期</th>
        <th>操作</th>
        </tr>
        </thead>
        <tbody>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>

       <td><input name="" type="checkbox" value="" /></td>
        <td><?php echo ($vo["id"]); ?></td>
        <td><?php echo (Getprotypename($vo["protype"])); ?></td>
        <td ><div class="click"  style="float: left;" data="/Uploads/<?php echo ($vo["pic"]); ?>">
    <span> <img src="/Uploads/<?php echo ($vo["pic1"]); ?>" height=100></span></div>

        </td>
        <td><?php echo ($vo["product"]); ?></td>
        <td><?php echo ($vo["price1"]); ?></td>
        <td><?php echo ($vo["price2"]); ?></td>
        <td><?php echo ($vo["price3"]); ?></td>
        <td><?php echo ($vo["discount"]); ?></td>
        <td><?php echo ($vo["inputnum"]); ?></td>
        <td><?php echo ($vo["sortnum"]); ?></td>
        <td><?php echo ($vo["salenum"]); ?></td>
        <td><?php echo ($vo["purchaseper"]); ?></td>
        <td><?php echo (date('Y-m-d',$vo["addtime"])); ?></td>
        <td><a href="/index.php/Console/Product/edit/id/<?php echo ($vo["id"]); ?>" class="tablelink">修改</a><a href="/index.php/Console/Product/delete/id/<?php echo ($vo["id"]); ?>" class="tablelink"> 删除</a></td>
        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        
        </tbody>
    </table>
    
   
   <div id="page" class="pagination">
　　<?php echo ($page); ?>
</div>

    
    <script type="text/javascript">
	$('.tablelist tbody tr:odd').addClass('odd');
	</script>

    <div class="tip0" style="">
        <div class="tiptop"><span>图片预览</span><a></a></div>
           <img src="" class="picpic" width=100% >

    </div>
    
</body>

</html>