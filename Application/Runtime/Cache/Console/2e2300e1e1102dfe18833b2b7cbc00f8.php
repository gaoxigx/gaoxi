<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>无标题文档</title>
        <link href="/Public/Console/css/style.css" rel="stylesheet" type="text/css" />
        <script language="JavaScript" src="/Public/Console/js/jquery.js"></script>

        <script type="text/javascript">
            $(function () {
                //导航切换
                $(".menuson li").click(function () {
                    $(".menuson li.active").removeClass("active")
                    $(this).addClass("active");
                });

                $('.title').click(function () {
                    var $ul = $(this).next('ul');
                    $('dd').find('ul').slideUp();
                    if ($ul.is(':visible')) {
                        $(this).next('ul').slideUp();
                    } else {
                        $(this).next('ul').slideDown();
                    }
                });
            })
        </script>


    </head>

    <body style="background:#f0f9fd;">
        <div class="lefttop"><span></span>通讯录</div>

        <dl class="leftmenu">

            <dd><div class="title"><span><img src="/Public/Console/images/leftico04.png" /></span>订单管理</div>
                <ul class="menuson">
                    <li><cite></cite><a href="../Order/Plist.html" target="rightFrame">订单列表</a><i></i></li>
                    <li><cite></cite><a href="../Order/add.html" target="rightFrame">提交订单</a><i></i></li>
                    <li><cite></cite><a href="../Order/add.html" target="rightFrame">购物车</a><i></i></li>

                </ul>
            </dd>  



            <dd><div class="title"><span><img src="/Public/Console/images/leftico03.png" /></span>产品管理</div>
                <ul class="menuson">
                    <li><cite></cite><a href="../Product/Plist.html" target="rightFrame">产品列表</a><i></i></li>
                    <li><cite></cite><a href="../Product/add.html" target="rightFrame">发布产品</a><i></i></li>
                </ul>    
            </dd>  



            <dd>
                <div class="title">
                    <span><img src="/Public/Console/images/leftico02.png" /></span>设备管理
                </div>
                <ul class="menuson">
                    <li><cite></cite><a href="../Equipment/Plist.html" target="rightFrame">设备列表</a><i></i></li>
                    <li><cite></cite><a href="../Equipment/add.html" target="rightFrame">发布设备信息</a><i></i></li>
                    <li><cite></cite><a href="../Wecate/Wecate.html" target="rightFrame">微信号</a><i></i></li>
                </ul>     
            </dd> 



            <dd>
                <div class="title">
                    <span><img src="/Public/Console/images/leftico01.png" /></span>管理员
                </div>
                <ul class="menuson">
                    <li><cite></cite><a href="../Personnel/passadd.html" target="rightFrame">修改密码</a><i></i></li>
                    <li><cite></cite><a href="../Group/rolelist.html" target="rightFrame">组管理</a><i></i></li>
                    <li><cite></cite><a href="../Personnel/PerList" target="rightFrame">管理员管理</a><i></i></li>
                    <li><cite></cite><a href="../Staff/PerList" target="rightFrame">员工管理</a><i></i></li>
                </ul>     
            </dd> 



        </dl>

    </body>
</html>