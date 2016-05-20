<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="/Public/Console/css/style.css" rel="stylesheet" type="text/css" />
<link href="/Public/Console/css/select.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/Public/Console/js/jquery.js"></script>
<script type="text/javascript" src="/Public/Console/js/jquery.idTabs.min.js"></script>
<script type="text/javascript" src="/Public/Console/js/select-ui.min.js"></script>
<script type="text/javascript" src="/Public/editor/kindeditor.js"></script>

<script type="text/javascript">
    KE.show({
        id : 'content7',
        cssPath : '/Public/Console/css/select.css'
    });
  </script>
    
<script type="text/javascript">
$(document).ready(function(e) {
    $(".select1").uedSelect({
        width : 345           
    });
    $(".select2").uedSelect({
        width : 167  
    });
    $(".select3").uedSelect({
        width : 100
    });
});
</script>

</head>

<body>

    <div class="place">
    <span>位置：</span>
    <ul class="placeul">
    <li><a href="#">首页</a></li>
    <li><a href="#">组管理</a></li>
    </ul>
    </div>
    
    <div class="formbody">
    
    
    <div id="usual1" class="usual"> 
    
    <div class="itab">
    <ul> 
    <li><a href="#tab1" class="selected">修改组信息</a></li> 
    <li><a href="#tab2">组成员列表</a></li> 
    </ul>
    </div> 
    
    <div id="tab1" class="tabson">
    
    <div class="formtext">Hi，请填写组的信息！</div>
    
    <ul class="forminfo">
    <form name="form1" id="form1" method='post' action="/index.php/Console/Group/update">
    <input type="hidden" name ="id"   value="<?php echo ($vo["id"]); ?>">
    <li><label>组名称<b>*</b></label><input id="rolename" name="rolename" type="text" class="dfinput" value="<?php echo ($vo["rolename"]); ?>"  /></li>
   
    <li><label>备注信息</label>
    <textarea class="textinput" rows="" cols="" name="depiction"><?php echo ($vo["depiction"]); ?></textarea>
    
    </li>
    <li><label>是否审核</label><cite><input type="radio"<?php if($vo["status"] == 1): ?>checked="checked"<?php endif; ?> value="1" name="status">是&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" <?php if($vo["status"] == 0): ?>checked="checked"<?php endif; ?> value="0" name="status">否</cite></li>
     <li><label>设置许可</label></li>


    <li><label>&nbsp;</label><input name="" type="submit" class="btn" value="修改发布"/></li>
</form>
    </ul>
    
    </div> 
    
    
     <div id="tab2" class="tabson">
    
    
    <ul class="seachform">
    <form name="form2" id="form2" method='post' action="/index.php/Console/Group/roleList">
    <li><label>组名</label><input name="rolename" type="text" class="scinput" /></li>
    </li>
    <li><label>&nbsp;</label><input name="" type="submit" class="scbtn" value="查询"/>
    </li>
    
    </ul>
    
    
    <table class="tablelist">
        <thead>
        <tr>
        <th><input name="" type="checkbox" value="" checked="checked"/></th>
        <th>编号<i class="sort"><img src="/Public/Console/images/px.gif" /></i></th>
        <th>组名称</th>
        <th>发布时间</th>
        <th>是否审核</th>
        <th>操作</th>
        </tr>
        </thead>
        <tbody>
    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
        <td><input name="" type="checkbox" value="" /></td>
        <td><?php echo ($vo["id"]); ?></td>
        <td><?php echo ($vo["rolename"]); ?></td>
        <td><?php echo (date('Y-m-d',$vo["addtime"])); ?></td>
        <td><?php if($vo["status"] == 1): ?>已审核 <?php else: ?> 未审核<?php endif; ?></td>
        <td><a href="/index.php/Console/Group/roleupdate/id/<?php echo ($vo["id"]); ?>" class="tablelink">修改</a><a href="/index.php/Console/Group/delete/id/<?php echo ($vo["id"]); ?>" class="tablelink"> 删除</a></td>
        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
       
    
        </tbody>
    </table>
    
   
  
    
    </div>  
       
       
    </div> 
 
    <script type="text/javascript"> 
      $("#usual1 ul").idTabs(); 
    </script>
    
    <script type="text/javascript">
    $('.tablelist tbody tr:odd').addClass('odd');
    </script>
    
    
    
    
    
    </div>


</body>

</html>