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
<script language="javascript" type="text/javascript" src="/Public/My97DatePicker/WdatePicker.js"></script>



</head>

<body>

    <div class="place">
    <span>位置：</span>
    <ul class="placeul">
    
    <li><a href="#">首页</a></li>
    <li><a href="#">管理员管理</a></li>
    </ul>
    </div>
    
    <div class="formbody">
    <div id="usual1" class="usual"> 
    
    <div class="itab">
        
    <ul> 

    <li><a href="#tab1" class="selected">编辑信息</a></li> 
   
    </ul>
    </div> 
    
    <div id="tab1" class="tabson">
    <form name="form1" id="form1" method='post' action="/index.php/Console/Personnel/update">
    <div class="formtext">请正确填写发布信息！</div>
    <ul class="forminfo">
    <li><label>所属分组<b>*</b></label>  
    

    <div class="vocation uew-select">
    <select id="roleid" name="roleid" class="uew-select-value">
        <?php if(is_array($roletype)): $i = 0; $__LIST__ = $roletype;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($vo['id'] == $data['roleid']): ?>selected="selected"<?php endif; ?>><?php echo ($vo["rolename"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
    
    </select>
    </div>
    
    </li>
    <input type="hidden" name ="id"   value="<?php echo ($data["id"]); ?>">
    <li><label>用户名<b>*</b></label><input name="" type="text" class="dfinput" value="<?php echo ($data["accounts"]); ?>"  /><b>用户不能为修改</b></li>

    <li><label>密码<b>*</b></label><input name="password" type="text" class="dfinput" value=""  /><b>密码字段为空时不做修改</b></li>
   
    <!-- <li><label>到期日期<b>*</b></label><input name="endtime" type="text" class="Wdate" value="<?php echo (date('Y-m-d',$endtime['add_time'])); ?>"  onClick="WdatePicker()" style="width:187px;"/></li> -->
      
    <li><label>昵称<b>*</b></label><input name="nickname" type="text" class="dfinput" value="<?php echo ($data["nickname"]); ?>" /></li>
   
    <li><label>姓名<b>*</b></label><input name="username" type="text" class="dfinput" value="<?php echo ($data["username"]); ?>"  /></li>

    <li><label>性别<b>*</b></label><cite><input type="radio"  <?php if($data["sex"] == '男'): ?>checked="checked"<?php endif; ?> value="男" name="sex">男&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio"  <?php if($data["sex"] == 女): ?>checked="checked"<?php endif; ?> value="女" name="sex">女</cite></li>
   
    <li><label>手机<b>*</b></label><input name="mobile" type="text" class="dfinput" value="<?php echo ($data["mobile"]); ?>"  /></li>
   

    <li><label>备注<b>*</b></label>
    <textarea class="textinput" rows="" cols="" name="content"><?php echo ($data["content"]); ?></textarea>
    </li>
    <li><label>是否审核</label><cite><input type="radio"  <?php if($data["status"] == 1): ?>checked="checked"<?php endif; ?> value="1" name="status">是&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" <?php if($data["status"] == 0): ?>checked="checked"<?php endif; ?> value="0" name="status">否</cite></li>
    <li><label>&nbsp;</label><input name="" type="submit" class="btn" value="马上发布"/></li>

    </ul>
</form>    
    </div> 
    
    
   
       
    </div> 
 
 
    
    
    
    
    
    </div>


</body>

</html>