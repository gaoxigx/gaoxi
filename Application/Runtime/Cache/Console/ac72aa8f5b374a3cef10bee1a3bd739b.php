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
        <form name="form1" id="form1" method='post' action="/index.php/Console/Personnel/insert">
    <ul> 

    <li><a href="#tab1" class="selected">发布信息</a></li> 
   
    </ul>
    </div> 
    
    <div id="tab1" class="tabson">
    
    <div class="formtext">请正确填写发布信息！</div>
    <ul class="forminfo">
    <li><label>所属分组<b>*</b></label>  
    

    <div class="vocation uew-select">
    <select id="roleid" name="roleid" class="uew-select-value">
    <?php if(is_array($roletype)): $i = 0; $__LIST__ = $roletype;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["rolename"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
    </select>
    </div>
    
    </li>
    <li><label>用户名<b>*</b></label><input name="accounts" type="text" class="dfinput" value=""  /></li>

    <li><label>密码<b>*</b></label><input name="password" type="text" class="dfinput" value=""  /></li>
   
    <!-- <li><label>到期日期<b>*</b></label><input name="endtime" type="text" class="Wdate" value="<?php echo date('Y-m-d',strtotime("+3 month"));?>"  onClick="WdatePicker()" style="width:187px;"/></li> -->
   
   
    <li><label>昵称<b>*</b></label><input name="nickname" type="text" class="dfinput" value=""  /></li>
   
    <li><label>姓名<b>*</b></label><input name="username" type="text" class="dfinput" value=""  /></li>

 <li><label>性别<b>*</b></label><cite><input type="radio" checked="checked" value="男" name="sex">男&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" value="女" name="sex">女</cite></li>
   
    <li><label>手机<b>*</b></label><input name="mobile" type="text" class="dfinput" value=""  /></li>
   

    <li><label>备注<b>*</b></label>
    <textarea class="textinput" rows="" cols="" name="content"></textarea>
    </li>
    <li><label>是否审核</label><cite><input type="radio" checked="checked" value="1" name="status">是&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" value="0" name="status">否</cite></li>
    <li><label>&nbsp;</label><input name="" type="submit" class="btn" value="马上发布"/></li>

    </ul>
</form>    
    </div> 
    
    
   
       
    </div> 
 
 
    
    
    
    
    
    </div>


</body>

</html>