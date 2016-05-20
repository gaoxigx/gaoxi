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
    <li><a href="#">设备管理</a></li>
    <li><a href="#">添加设备</a></li>
    </ul>
    </div>
    
    <div class="formbody">
    <div id="usual1" class="usual"> 
    
    <div class="itab">
        <form name="form1" id="form1" method='post' action="/index.php/Console/Equipment/insert">
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
    <option value="1">管理员</option>
    <option value="2">代言人</option>
    </select>
    </div>
    
    </li>

    <li><label>设备型号<b>*</b></label><input name="xinghao" type="text" class="dfinput" value=""  /></li>

    <li><label>设备编号<b>*</b></label><input name="bianhao" type="text" class="dfinput" value=""  /></li>
    <li><label>设备绑定代言人<b>*</b></label><input name="daiyanren" type="text" class="dfinput" value=""  /></li>
    <li><label>设备号码<b>*</b></label><input name="mobile" type="text" class="dfinput" value=""  />手机号</li>
    <li><label>开机密码<b>*</b></label><input name="kaijipassword" type="text" class="dfinput" value=""  /></li>

    <li><label>登陆微信号<b>*</b></label>
        <input name="weixinhao" type="text" class="dfinput_left" value=""  />
        <label class="pl10">微信密码<b>*</b></label>
        <input name="weixinpass" type="text" class="dfinput_left" value=""  />
    </li>

    <li><label>登陆淘宝账号<b>*</b></label>
        <input name="taobaohao" type="text" class="dfinput_left" value=""  />
        <label class="pl10">淘宝密码<b>*</b></label>
        <input name="taobaopass" type="text" class="dfinput_left" value=""  />
    </li>

    <li><label>登陆QQ账号<b>*</b></label>
        <input name="qqhao" type="text" class="dfinput_left" value=""  />
        <label class="pl10">QQ密码<b>*</b></label>
        <input name="qqpass" type="text" class="dfinput_left" value=""  />
    </li>

    <li><label>微博账号<b>*</b></label>
        <input name="weibohao" type="text" class="dfinput_left" value=""  />
        <label class="pl10">微博密码<b>*</b></label>
        <input name="weibopass" type="text" class="dfinput_left" value=""  />
    </li>


    <li><label>设备负责人<b>*</b></label><input name="fuzeren" type="text" class="dfinput" value=""  /></li>
   
    <li><label>设备使用人<b>*</b></label><input name="shiyongren" type="text" class="dfinput" value=""  /></li>  


    <li><label>所在部门<b>*</b></label><input name="bumen" type="text" class="dfinput" value=""  /></li>    
    <li><label>所在工位<b>*</b></label><input name="gongwei" type="text" class="dfinput" value=""  /></li> 
    <li><label>登记日期<b>*</b></label><input name="addtime" type="text" class="Wdate" value="<?php echo date('Y-m-d',time);?>"  onClick="WdatePicker()" style="width:187px;"/></li>
   

    <li><label>是否审核</label><cite><input type="radio" checked="checked" value="1" name="stauts">是&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" value="0" name="stauts">否</cite></li>
    <li><label>&nbsp;</label><input name="" type="submit" class="btn" value="马上发布"/></li>

    </ul>
</form>    
    </div> 
    
    
   
       
    </div> 
 
 
    
    
    
    
    
    </div>


</body>

</html>