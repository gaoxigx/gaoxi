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
    <li><a href="#">修改密码</a></li>
    </ul>
    </div>
    
    <div class="formbody">
    <div id="usual1" class="usual"> 
    
    <div class="itab">
        <form name="form1" id="form1" method='post' action="/index.php/Console/Personnel/passupdate">
    <ul> 

    <li><a href="#tab1" class="selected">修改密码</a></li> 
   
    </ul>
    </div> 
    
    <div id="tab1" class="tabson">
    
    <div class="formtext">请正确填写原密码及更改密码 ！</div>
    <ul class="forminfo">
   
    <li><label>用户名<b>*</b></label><?php echo (session('username')); ?></li>

    <li><label>原密码<b>*</b></label><input name="oldpassword" type="password" class="dfinput" value=""  /></li>
    <li><label>新密码<b>*</b></label><input name="password" type="password" class="dfinput" value=""  /></li>
   

    <li><label>&nbsp;</label><input name="" type="submit" class="btn" value="修改密码"/></li>

    </ul>
</form>    
    </div> 
    </div> 
    
    </div>
</body>

</html>