<?php if(!defined('IN_LCL')) exit('Access Denied'); ?>
<!DOCTYPE html>
<html class="no-js" lang="zh-CN">
<head>
    <!-- Meta, title, CSS, favicons, etc. -->
<meta charset="utf-8">
<!-- 删除默认的苹果工具栏和菜单栏 ： 即启用 WebApp 全屏模式  -->
<meta name="apple-mobile-web-app-capable" content="yes" />
<!-- 在web app应用下状态条（屏幕顶部条）的颜色；默认值为default（白色），可以定为black（黑色）和black-translucent（灰色半透明）若值为“black-translucent”将会占据页面px位置，浮在页面上方（会覆盖页面20px高度–iphone4和itouch4的Retina屏幕为40px）。 -->
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<!-- 忽略数字自动识别为电话号码 ,忽略识别邮箱 -->
<meta name="format-detection" content="telephone=no, email=no" />
<!-- 启用360浏览器的极速模式(webkit) -->
<meta name="renderer" content="webkit">
<!-- 避免IE使用兼容模式 -->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- uc强制竖屏 -->
<meta name="screen-orientation" content="portrait" />
<!-- QQ强制竖屏 -->
<meta name="x5-orientation" content="portrait">
<!-- UC强制全屏 -->
<meta name="full-screen" content="true" />
<!-- QQ强制全屏 -->
<meta name="x5-fullscreen" content="true" />
<!-- 360强制全屏 -->
<meta name="360-fullscreen" content="true" />
    <title><?php if(!empty($nav_title) ) { ?><?php echo $nav_title;?>-<?php } ?><?php echo $_G['setting']['bbname'];?>-<?php echo $_G['setting']['sitename'];?></title>
    <title>永新科技-江西网站建设,公众号开发,微信营销,APP开发,软件定制开发等</title>
    <meta name="keywords" content="江西永新, 永新科技, 微信, Android, 移动开发, APP开发, 电商,解决方案">
    <meta name="description" content="永新科技创始于2017年。我们致力于为客户提供专业完整的解决方案，包括网站建设、微信二次开发、APP开发、电子商务、软件定制开发等。">
    <meta name="Copyright" content="0796.net">
    <meta name="author" content="江西永新科技有限责任公司">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="<?php echo $_G['siteurl'];?>template/default/res/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo $_G['siteurl'];?>template/default/res/css/bootstrap-theme.css">
    <link rel="stylesheet" href="<?php echo $_G['siteurl'];?>template/default/res/css/loaders.css">
    <link rel="stylesheet" href="<?php echo $_G['siteurl'];?>template/default/res/css/main.css">
    <link rel="icon" href="/favicon.ico">

    <style type="text/css">
        /*html5*/
        article, aside, dialog, footer, header, section, footer, nav, figure, menu {
            display: block;
        }
    </style>
    <!--[if lte IE 6]>
    <link rel="stylesheet" type="text/css" href="<?php echo $_G['siteurl'];?>template/default/res/css/bootstrap-ie6.css">
    <link href="<?php echo $_G['siteurl'];?>template/default/res/css/non-responsive.css" rel="stylesheet" media="screen">
    <script src="<?php echo $_G['siteurl'];?>template/default/res/js/bootstrap-ie.js" type="text/javascript"></script>
    <![endif]-->
    <!--[if lte IE 7]>
    <link rel="stylesheet" type="text/css" href="<?php echo $_G['siteurl'];?>template/default/res/css/ie.css">
    <![endif]-->
    <!--[if gte IE 7]>
        <script src='<?php echo $_G['siteurl'];?>template/default/res/js/jquery-1.9.0.js'></script>
        <script src='<?php echo $_G['siteurl'];?>template/default/res/js/html5media.js'></script>
        <script src='<?php echo $_G['siteurl'];?>template/default/res/js/video.js'></script>
        <script src='http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js'></script>
        <![endif]-->
    <!--[if lt IE 9]>
    <script src="<?php echo $_G['siteurl'];?>template/default/res/js/html5shiv.js" type="text/javascript"></script>
    <script src="<?php echo $_G['siteurl'];?>template/default/res/js/respond.min.js" type="text/javascript"></script>
    <style type="text/css">
        .video{
            left:0\0;    /*ie8*/
+left:0;        /*ie7*/
            behavior:url(<?php echo $_G['siteurl'];?>template/default/res/css/backgroundsize.min.htc);
        }
    </style>
    <![endif]-->
  <script type="text/javascript">
        var _hmt = _hmt || [];
        var submenu_style = 0;
        var siteurl = "<?php echo $_G['setting']['siteurl'];?>",
        charset = '<?php echo CHARSET;?>',
        reporturl = '<?php echo $_G['currenturl_encode'];?>',
        lcl_uid = '<?php echo $_G['uid'];?>',
        cookiepre = '<?php echo $_G['config']['cookie']['cookiepre'];?>',
        cookiedomain = '<?php echo $_G['config']['cookie']['cookiedomain'];?>',
        cookiepath = '<?php echo $_G['config']['cookie']['cookiepath'];?>';

    </script>
</head>
<body style="background-color: #f2f2f2;">
 <div id="content" style="display:none;">
   <div class="poster">
            <div class="video-bg">
                <video autoplay loop muted id="video" class="video"></video>
            </div>
            <div class="video-mask"></div>
            <div class="navbar navbar-fixed-top navbar-transparent">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                                aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="/">
                            <img class="img-responsive navbar-logo"
                                 src="<?php echo $_G['siteurl'];?>template/default/res/images/logo-light.png" /><span></span>
                        </a>
                    </div>
                    <div id="navbar" class="collapse navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li class="active"><a href="index.php">首页</a></li>
                            <li><a href="index.php#scheme">方案</a></li>
<!--<li><a href="#product">产品</a></li>-->
                            <li><a href="index.php#services">服务</a></li>
                            <li><a href="index.php#case">案例</a></li>
 <li><a href="portal.php">新闻</a></li>
                            <li><a href="index.php#contact">联系</a></li>
                        </ul>
                    </div><!--/.nav-collapse -->
                </div>
                <div class="navbar-lines">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
            <div class="masthead container">
                <p class="title">
                    我们不是机构，我们是 <span id="geekzoo" class="highlight">您的伙伴</span>
                </p>
                <p class="subtitle" id="brief">
                    江西永新科技有限责任公司创始于2017年。至今，我们已经参与完成近百个网站端与移动端专案。我们为客户提供完整的解决方案，包括从视觉识别、用户界面、信息架构、逻辑流程、用户体验、视觉效果，到前后端开发、编程框架，我们相信『激情』、『创造力』与『坚持』是我们持续取得成功的关键。
                </p>
                <p class="buttons">
                    <a href="#contact" class="button">联系我们</a>
                    <a href="#case" class="button">查看案例</a>
                </p>
            </div>
            <div id="arrow" class="arrow">
                <img src="<?php echo $_G['siteurl'];?>template/default/res/images/arrow-down.png" class="img-responsive" />
            </div>
        </div>