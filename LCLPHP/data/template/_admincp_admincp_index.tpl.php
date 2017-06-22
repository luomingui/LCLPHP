<?php if(!defined('IN_LCL')) exit('Access Denied'); include template('admincp/common/header'); ?><div class="container" id="cpcontainer">
    <script type="text/JavaScript">
        parent.document.title = ' 管理中心 - 首页';
        if (parent.document.getElementById('admincpnav'))
        parent.document.getElementById('admincpnav').innerHTML = '首页';
    </script>
    <div class="itemtitle"><h3> 管理中心</h3></div>
    <table class="tb tb2 fixpadding">
        <tr><th colspan="15" class="partition">系统信息</th></tr>
        <tr><td class="vtop td24 lineheight">程序版本</td><td class="lineheight smallfont">LCL v<?php echo LCL_VERSION;?>
                <a target="_blank" class="lightlink2 smallfont" href="checkBOM.php">检查BOM</a>
            </td></tr>
        <tr><td class="vtop td24 lineheight">UCenter 客户端版本</td><td class="lineheight smallfont">UCenter 1.6.0 Release 20141101</td></tr>
        <tr><td class="vtop td24 lineheight">服务器系统及 PHP</td><td class="lineheight smallfont"><?php echo $serverinfo;?>
                <a target="_blank" class="lightlink2 smallfont" href="tools/tz.php">PHP探针</a>
                <a target="_blank" class="lightlink2 smallfont" href="tools/phpinfo.php">PHP信息</a></td></tr>
        <tr><td class="vtop td24 lineheight">服务器软件</td><td class="lineheight smallfont"><?php echo $serversoft;?></td></tr>
        <tr><td class="vtop td24 lineheight">服务器 MySQL 版本</td><td class="lineheight smallfont"><?php echo $dbversion;?></td></tr>
        <tr><td class="vtop td24 lineheight">上传许可</td><td class="lineheight smallfont"><?php echo $fileupload;?></td></tr>
        <tr><td class="vtop td24 lineheight">当前数据库尺寸</td><td class="lineheight smallfont"><?php echo $dbsize;?></td></tr>

    </table>

</div><?php include template('admincp/common/footer'); ?>