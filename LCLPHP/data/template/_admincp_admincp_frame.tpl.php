<?php if(!defined('IN_LCL')) exit('Access Denied'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>管理中心</title>
        <link rel="stylesheet" href="template/admincp/res/css/admincp.css" type="text/css" media="all" />
        <script src="static/js/common.js" type="text/javascript" type="text/javascript" type="text/javascript"></script>
    </head>
    <body style="margin: 0px;" scroll="no">
        <div id="append_parent"></div><div id="ajaxwaitid"></div>
        <script>
            function changetoVersion(arg1, arg2) {
                if (location.href.indexOf('?lang=') != '-1' || location.href.indexOf('?') == '-1') {
                    arg1 = '?lang=' + arg1;
                    arg2 = '?lang=' + arg2;
                } else {
                    arg1 = '&lang=' + arg1;
                    arg2 = '&lang=' + arg2;
                }
                if (location.href.indexOf(arg1) != '-1') {
                    location.reload();
                } else if (location.href.indexOf(arg2) != '-1') {
                    location.href = location.href.replace(arg2, '')
                            + arg1;
                } else {
                    location.href += arg1;
                }
            }
        </script>
        <table id="frametable" cellpadding="0" cellspacing="0" width="100%" height="100%">
            <tr>
                <td colspan="2" height="90">
                    <div class="mainhd">
                        <a href="admin.php" class="logo">Administrator's Control Panel</a>
                        <div class="uinfo" id="frameuinfo">
                            <p>您好,  <em><?php echo $_G['cookie']['adminname'];?></em>
                                [<a href="admin.php?action=password" target="main">修改密码</a>]
                                [<a href="admin.php?action=logout" target="_top">退出</a>]
                            </p>
                            <p>
                                &nbsp;<a href="javascript:changetoVersion('cn','en')" class="xi2 xw1">中文版</a>
                                &nbsp;<a href="javascript:changetoVersion('en','cn')" class="xi2 xw1">English</a>
                            </p>
                            <p class="btnlink"><a href="index.php" target="_blank">站点首页</a></p>
                        </div>
                        <div class="navbg"></div>
                        <div class="nav">
                            <ul id="topmenu">
                                <?php echo $topmenhtml;?>
                            </ul>
                            <div class="currentloca">
                                <p id="admincpnav"></p>
                            </div>
                            <div class="navbd"></div>
                            <div class="sitemapbtn">
                                <div style="float: left; margin:-7px 10px 0 0">
                                    <form name="search" method="post" autocomplete="off" action="admin.php?action=search" target="main">
                                        <input type="text" name="keywords" value="" class="txt" x-webkit-speech speech />
                                        <input type="hidden" name="searchsubmit" value="yes" class="btn" />
                                        <input type="submit" name="searchsubmit" value="搜索" class="btn" style="margin-top: 5px;vertical-align:middle" />
                                    </form>
                                </div>
                                <span id="add2custom" style="display: none"></span>
                                <a href="###" id="cpmap" onclick="showMap();
                return false;">
                                    <img src="template/admincp/res/img/btn_map.gif" title="管理中心导航(ESC键)" width="46" height="18" />
                                </a>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td valign="top" width="160" class="menutd">
                    <div id="leftmenu" class="menu">
                        <?php echo $leftmenuhtml;?>
                    </div>
                </td>
                <td valign="top" width="100%" class="mask">
                    <iframe src="admin.php?action=index" id="main" name="main" width="100%" height="100%" frameborder="0" scrolling="yes" style="overflow: visible;display:"></iframe>
                </td>
            </tr>
        </table>
        <div id="scrolllink" style="display: none">
            <span onclick="menuScroll(1)"><img src="template/admincp/res/img/scrollu.gif" /></span>
            <span onclick="menuScroll(2)"><img src="template/admincp/res/img/scrolld.gif" /></span>
        </div>
        <div class="copyright">
            <p>Powered by LCL X<?php echo LCL_VERSION;?></p>
            <p>&copy; 2001-2018, ZTE Inc.</p>
        </div>
        <div id="cpmap_menu" class="custom" style="display: none">
            <div class="cmain" id="cmain"></div>
            <div class="cfixbd"></div>
        </div>
        <script src="template/admincp/res/js/lcl_admincp.js" type="text/javascript"></script>
    </body>
</html>

