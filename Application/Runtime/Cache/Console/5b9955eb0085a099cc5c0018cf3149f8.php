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
    <li><a href="#">微信号</a></li>
    </ul>
    </div>
    
    <div class="rightinfo">
    
    <div class="tools">
     
        <ul class="toolbar1">
        <!-- <li><span><img src="/Public/Console/images/t05.png" /></span>设置</li> -->
        </ul>
    <ul class="seachform">
        <form action="/index.php/Console/Equipment/Wecate" method="post" id="form2" name="form2">            
            <li><label>搜索</label><input type="text" class="scinpute" name="username" value=""></li>
            <li><label>&nbsp;</label><input type="submit" value="查询" class="scbtn" name=""></li>
        </form>
    </ul>
    </div>
    
    
    <table class="tablelist">
    	<thead>
    	<tr>
        <th><input name="" type="checkbox" value="" checked="checked"/></th>
        <th>ID<i class="sort"><img src="/Public/Console/images/px.gif" /></i></th>
        <th>微信号</th>
        <th>名称</th>
        <th>昵称</th>
        <th>性别</th>
        <th>创建时间</th>
        <th>备用</th>
        <th>操作</th>
        </tr>
        </thead>
        <tbody>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                <td><input name="" type="checkbox" value="" /></td>
                 <td><?php echo ($vo["id"]); ?></td>
                 <td><?php echo ($vo["wechat_id"]); ?></td>
                 <td><?php echo ($vo["username"]); ?></td>
                 <td><?php echo ($vo["nickname"]); ?></td>
                 <td><?php if($vo["gender"] == 1): ?>男<?php elseif($vo["gender"] == 2): ?>女<?php else: ?>未知<?php endif; ?></td>
                 <td><?php echo (date("Y-m-d H:i:s",$vo["create_time"])); ?></td>               
                 <td><?php echo ($vo["backup"]); ?></td>
                 <td><a href="/index.php/Console/Equipment/wecate_edit/id/<?php echo ($vo["id"]); ?>" class="tablelink">修改</a><a href="/index.php/Console/Equipment/wecate_delete/id/<?php echo ($vo["id"]); ?>" class="tablelink"> 删除</a></td>
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