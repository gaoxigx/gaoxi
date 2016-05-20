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
    <li><a href="#">设备管理</a></li>
    <li><a href="#">设备列表</a></li>  
    </ul>
    </div>
    
    <div class="rightinfo">
    
    <div class="tools">
     
        <ul class="toolbar1">
        <!-- <li><span><img src="/Public/Console/images/t05.png" /></span>设置</li> -->
        </ul>
    <ul class="seachform">
    <form action="/index.php/Console/Equipment/Plist" method="post" id="form2" name="form2">
    <li><label>型号</label><input type="text" class="scinpute" name="xinghao"></li>
    <li><label>编号</label><input type="text" class="scinpute" name="bianhao"></li>
    <li><label>代言人</label><input type="text" class="scinpute" name="daiyanren"></li>
    <li><label>手机</label><input type="text" class="scinpute" name="mobile"></li>
    <li><label>负责人</label><input type="text" class="scinpute" name="fuzeren"></li>
    <li><label>使用人</label><input type="text" class="scinpute" name="shiyongren"></li>

    <li><label>&nbsp;</label><input type="submit" value="查询" class="scbtn" name="">
    </li>
    
    </form></ul>
    </div>
    
    
    <table class="tablelist">
    	<thead>
    	<tr>
        <th><input name="" type="checkbox" value="" checked="checked"/></th>
        <th>ID<i class="sort"><img src="/Public/Console/images/px.gif" /></i></th>
        <th>型号</th>
        <th>编号</th>
        <th>代言人</th>
        <th>手机号</th>
        <th>开机密码</th>
        <th>微信</th>
        <th>淘宝</th>
        <th>QQ</th>
        <th>微博</th>
        <th>负责人</th>
        <th>使用人</th>
        <th>所在部门</th>
        <th>所在工们</th>
        <th>设备维护</th>
        <th>添加日期</th>
        <th>操作</th>
        </tr>
        </thead>
        <tbody>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>

       <td><input name="" type="checkbox" value="" /></td>
        <td><?php echo ($vo["id"]); ?></td>
        <td><?php echo ($vo["xinghao"]); ?></td>
        <td><?php echo ($vo["bianhao"]); ?></td>
        <td><?php echo ($vo["daiyanren"]); ?></td>
        <td><?php echo ($vo["mobile"]); ?></td>
        <td><?php echo ($vo["kaijipassword"]); ?></td>
        <td><?php echo ($vo["weixinhao"]); ?></td>
        <td><?php echo ($vo["taobaohao"]); ?></td>
        <td><?php echo ($vo["qqhao"]); ?></td>
        <td><?php echo ($vo["weibohao"]); ?></td>
        <td><?php echo ($vo["fuzeren"]); ?></td>
        <td><?php echo ($vo["shiyongren"]); ?></td>
        <td><?php echo ($vo["bumen"]); ?></td>
        <td><?php echo ($vo["gongwei"]); ?></td>
        <td><?php echo ($vo["weihu"]); ?></td>
        <td><?php echo (date('Y-m-d',$vo["addtime"])); ?></td>
        <td><a href="/index.php/Console/Equipment/edit/id/<?php echo ($vo["id"]); ?>" class="tablelink">修改</a><a href="/index.php/Console/Equipment/delete/id/<?php echo ($vo["id"]); ?>" class="tablelink"> 删除</a></td>
        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        
        </tbody>
    </table>
    
   
   <div id="page" class="pagination">
　　<?php echo ($page); ?>
</div>
    
    <div class="tip">
    	<div class="tiptop"><span>提示信息</span><a></a></div>
        
      <div class="tipinfo">
        <span><img src="/Public/Console/images/ticon.png" /></span>
        <div class="tipright">
        <p>是否确认对信息的修改 ？</p>
        <cite>如果是请点击确定按钮 ，否则请点取消。</cite>
        </div>
        </div>
        
        <div class="tipbtn">
        <input name="" type="button"  class="sure" value="确定" />&nbsp;
        <input name="" type="button"  class="cancel" value="取消" />
        </div>
    
    </div>
    
    
    
    
    </div>
    
    <script type="text/javascript">
	$('.tablelist tbody tr:odd').addClass('odd');
	</script>

</body>

</html>