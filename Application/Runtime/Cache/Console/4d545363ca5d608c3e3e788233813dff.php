<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>欢迎登录后台管理系统</title>
<link href="/Public/Console/css/style.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="/Public/Console/js/jquery.js"></script>
<script src="/Public/Console/js/cloud.js" type="text/javascript"></script>

<script language="javascript">
	$(function(){
    $('.loginbox').css({'position':'absolute','left':($(window).width()-692)/2});
	$(window).resize(function(){  
    $('.loginbox').css({'position':'absolute','left':($(window).width()-692)/2});
    })  
});  
</script> 

</head>

<body style="background-color:#1c77ac; background-image:url(images/light.png); background-repeat:no-repeat; background-position:center top; overflow:hidden;">



    <div id="mainBody">
      <div id="cloud1" class="cloud"></div>
      <div id="cloud2" class="cloud"></div>
    </div>  


<div class="logintop">    
    <span>欢迎登录后台管理界面平台</span>    
    <ul>
    <li><a href="#">回首页</a></li>
    <li><a href="#">帮助</a></li>
    <li><a href="#">关于</a></li>
    </ul>    
    </div>
    
    <div class="loginbody">
    
    <span class="systemlogo"></span> 
       
    <div class="loginbox">
    <form id="login" method='post' action="/index.php/Console/Login/dologin">
    <ul>
    <li><input id="user_name" name="username" type="text" class="loginuser" value="" onclick="JavaScript:this.value=''"/></li>
    <li><input id="password" name="password" type="password" class="loginpwd" value="" onclick="JavaScript:this.value=''"/></li>
    <li>
        <input id="loginbtn" name="but" type="button" class="loginbtn" value="登录"    /><label><input name="" type="checkbox" value="" checked="checked" />记住密码</label><label><a href="#">忘记密码？</a></label></li>
    </ul>
    </form>
    
    </div>
    
    </div>
    
    
    
    <div class="loginbm">版权所有  2016  &copy NICO·中国</div>
	
     <script type="text/javascript">
    $(document).ready(function(){
    
        //数字验证 
        // $("#getcode_num").click(function(){
        //     $(this).attr("src", 'verify.html?' + Math.random());
        // });
        
        $("#loginbtn").click(function(){

            var user_name = $("#user_name").val();
            var password = $("#password").val();
            // var code_num = $("#code_num").val();
            if (user_name == '') {
                alert('用户名不能为空');
                $("#user_name").focus();
                return false;
            }
            
            if (password == '') {
                alert("密码不能为空！");
                $("#password").focus();
                return false;
            }
            $("#login").attr("action", "dologin").submit();

            // if (code_num == '') {
            //     alert("验证码不能为空！");
            //     $("#code_num").focus();
            //     return false;
            // }
            // else {
            //     $.post("chk_code.html?code=code_num'", {
            //         code: code_num
            //     }, function(msg){
            //         if (msg) {
            //             $("#login").attr("action", "dologin").submit();
            //         }
            //         else {
            //             alert("验证码错误！");
            //             return false;
            //         }
            //     });
            // }
        });
    });
    
    /*回车事件*/
    function EnterPress(e){ //传入 event 
        var e = e || window.event;
        if (e.keyCode == 13) {
            $("#login").attr("action", "dologin").submit();
        }
    }
</script>
</body>

</html>