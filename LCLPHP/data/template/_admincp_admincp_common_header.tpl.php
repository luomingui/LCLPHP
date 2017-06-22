<?php if(!defined('IN_LCL')) exit('Access Denied'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=7">
                <title>管理中心</title>
                <!--
                * +----------------------------------------------------------------------
                * | LCLPHP [ This is a freeware ]
                * +----------------------------------------------------------------------
                * | Copyright (c) 2015 All rights reserved.
                * +----------------------------------------------------------------------
                * | Author: 罗敏贵 <e-mail:minguiluo@163.com> <QQ:271391233>
                * +----------------------------------------------------------------------
                -->
                </head>
                <body>
                    <script type="text/javascript" type="text/javascript">
                        var siteurl="<?php echo $_G['setting']['siteurl'];?>",
                        charset = '<?php echo CHARSET;?>',
                        reporturl = '<?php echo $_G['currenturl_encode'];?>',
                        lcl_uid = '<?php echo $_G['adminid'];?>',
                        cookiepre = '<?php echo $_G['config']['cookie']['cookiepre'];?>',
                        cookiedomain = '<?php echo $_G['config']['cookie']['cookiedomain'];?>',
                        cookiepath = '<?php echo $_G['config']['cookie']['cookiepath'];?>';
                    </script>
                    <link rel="stylesheet" href="<?php echo $_G['siteurl'];?>template/admincp/res/css/admincp.css" type="text/css" media="all" />
                    <script src="<?php echo $_G['siteurl'];?>static/js/jquery.js" type="text/javascript" type="text/javascript" type="text/javascript"></script>
                    <script src="<?php echo $_G['siteurl'];?>static/js/common.js" type="text/javascript" type="text/javascript" type="text/javascript"></script>
                    <script src="<?php echo $_G['siteurl'];?>template/admincp/res/js/admincp.js" type="text/javascript" type="text/javascript" type="text/javascript"></script>

                    <div id="append_parent"></div><div id="ajaxwaitid"></div>


