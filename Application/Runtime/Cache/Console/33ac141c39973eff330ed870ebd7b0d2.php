<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>无标题文档</title>
        <link href="/Public/Console/css/style.css" rel="stylesheet" type="text/css" />
        <link href="/Public/Console/css/page.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="/Public/Console/js/jquery.js"></script>

        <script type="text/javascript">
            $(document).ready(function () {
                $(".click").click(function () {
                    $(".tip").fadeIn(200);
                });

                $(".tiptop a").click(function () {
                    $(".tip").fadeOut(200);
                });

                $(".sure").click(function () {
                    $(".tip").fadeOut(100);
                });

                $(".cancel").click(function () {
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
                <li><a href="#">微信修改</a></li>
            </ul>
        </div>

        <div class="formbody">
            <div id="usual1" class="usual"> 
                 <form name="form1" id="form1" method='post' action="/index.php/Console/Wecate/edit/id">
                <div class="itab">
                   
                        <ul> 

                            <li><a href="#tab1" class="selected">修改信息</a></li> 

                        </ul>
                </div> 

                <div id="tab1" class="tabson">

                    <div class="formtext">请正确填写个人信息！</div>
                    <ul class="forminfo">

                        <li>
                            <label>微信号<b>*</b></label>                  
                            <?php if($id): ?><input class="dfinput" name="wechat_id" type="text"  placeholder="wechat_id" value="<?php echo ($user["wechat_id"]); ?>"/>
                                <?php else: ?>
                                <input class="dfinput"  name="wechat_id" type="text"  placeholder="wechat_id" /><?php endif; ?>  
                        </li>

                        <li>
                            <label>昵称<b>*</b></label>                  
                            <?php if($id): ?><input class="dfinput" name="nickname" type="text"  placeholder="nickname" value="<?php echo ($user["nickname"]); ?>"/>
                                <?php else: ?>
                                <input class="dfinput"  name="nickname" type="text"  placeholder="nickname" /><?php endif; ?>  
                        </li>

                        <li>
                            <label>姓名<b>*</b></label>                  
                            <?php if($id): ?><input class="dfinput" name="username" type="text"  placeholder="Username" value="<?php echo ($user["username"]); ?>"/>
                                <?php else: ?>
                                <input class="dfinput"  name="username" type="text"  placeholder="Username" /><?php endif; ?>  
                        </li>

                        <li><label>性别<b>*</b></label><cite><input type="radio" checked="checked" value="1" name="sex">男&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" value="2" name="sex">女</cite></li>

                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit"><i class="ace-icon fa fa-check bigger-110"></i>提交</button>  
                            &nbsp; &nbsp; &nbsp;
                            <button class="btn" type="reset"><i class="ace-icon fa fa-undo bigger-110"></i> 取消</button>
                        </div>

                    </ul>
              
                </div> 
                 </form>

            </div>
        </div>
    
        <script type="text/javascript">
            $('.tablelist tbody tr:odd').addClass('odd');
        </script>

    </body>

</html>