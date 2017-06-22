<?php

/**
 * +----------------------------------------------------------------------
 * | LCLPHP [ This is a freeware ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 罗敏贵 <e-mail:minguiluo@163.com> <QQ:271391233>
 * +----------------------------------------------------------------------
 * | 文件功能：后台常用函数
 * +----------------------------------------------------------------------
 */
if (!defined('IN_LCL')) {
    exit('Access Denied');
}

function istpldir($dir) {
    return is_dir(LCL_ROOT . './' . $dir) && !in_array(substr($dir, -1, 1), array('/', '\\')) &&
            strpos(realpath(LCL_ROOT . './' . $dir), realpath(LCL_ROOT . './template') . DIRECTORY_SEPARATOR) === 0;
}

function isplugindir($dir) {
    return preg_match("/^[a-z]+[a-z0-9_]*\/$/", $dir);
}

function ispluginkey($key) {
    return preg_match("/^[a-z]+[a-z0-9_]*$/i", $key);
}

function cpheader() {
    echo <<<EOT

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <title>中兴捧月-中兴捧月-中兴通讯</title>
            <meta name="keywords" content="中兴捧月 兴人类俱乐部 中兴通讯 " />
            <meta name="description" content="中兴捧月是ZTE官方唯一的以大赛形式发现人才，识别人才的平台之一。" />
            <meta name="author" content="luomingui" />
            <meta name="copyright" content="2001-2019 ZTE Inc." />
            <meta content="Comsenz Inc." name="Copyright" />
            <link rel="stylesheet" href="template/default/res/css/common.css" type="text/css" media="all" />
            <link rel="stylesheet" href="template/default/res/css/detail.css" type="text/css" media="all" />
            <script src="template/default/res/js/jquery.js" type="text/javascript" type="text/javascript"></script>
    </head>
    <body style="margin: 0px" scroll="no">

EOT;
}

function cpmsg($message, $url = '', $type = '', $values = array(), $extra = '', $halt = TRUE, $cancelurl = '') {
    global $_G;
    $vars = explode(':', $message);
    $values['ADMINSCRIPT'] = ADMINSCRIPT;
    if (count($vars) == 2) {
        $message = lang('plugin/' . $vars[0], $vars[1], $values);
    } else {
        $message = cplang($message, $values);
    }
    switch ($type) {
        case 'download':
        case 'succeed': $classname = 'infotitle2';
            break;
        case 'error': $classname = 'infotitle3';
            break;
        case 'loadingform': case 'loading': $classname = 'infotitle1';
            break;
        default: $classname = 'marginbot normal';
            break;
    }
    $message = "<h4 class=\"$classname\">$message</h4>";
    $message_redirect = cplang('message_redirect');

    echo <<<SEARCH
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>系统提示</title>
<link rel="stylesheet" href="template/admincp/res/css/admincp.css" type="text/css" media="all" />
<script type="text/JavaScript">
    var admincpfilename = 'admin.php', IMGDIR = 'static/image/common', STYLEID = '5', VERHASH = 'ouz', IN_ADMINCP = true, ISFRAME = 1, STATICURL='static/', SITEURL = 'http://localhost/', JSPATH = 'static/js/';
</script>
<script src="template/admincp/res/js/common.js" type="text/javascript" type="text/javascript"></script>
<script src="template/admincp/res/js/admincp.js" type="text/javascript" type="text/javascript"></script>
</head>
<body style="margin: 0px" scroll="no">
    <h3 class=\"$classname\">系统提示</h3>
        <div class="infobox">
        $message
            <p class="marginbot">
                <a href="$url" class="lightlink">$message_redirect</a>
            </p>
SEARCH;

    if (strlen($url) > 0) {
        echo '<script type="text/JavaScript">setTimeout("redirect(\'' . $url . '\');", 3000);</script>';
    }
    echo '</div></body></html>';
    exit();
}

function cplang($name, $replace = array(), $output = false) {
    global $_G;
    $ret = '';
    if (!isset($_G['lang']['admincp'])) {
        lang('admincp');
    }

    if (isset($_G['lang']['admincp'][$name])) {
        $ret = $_G['lang']['admincp'][$name];
    } elseif (isset($_G['lang']['admincp_menu'][$name])) {
        $ret = $_G['lang']['admincp_menu'][$name];
    } elseif (isset($_G['lang']['admincp_msg'][$name])) {
        $ret = $_G['lang']['admincp_msg'][$name];
    }
    $ret = $ret ? $ret : ($replace === false ? '' : $name);
    if ($replace && is_array($replace)) {
        $s = $r = array();
        foreach ($replace as $k => $v) {
            $s[] = '{' . $k . '}';
            $r[] = $v;
        }
        $ret = str_replace($s, $r, $ret);
    }
    $output && print($ret);
    return $ret;
}

function implodearray($array, $skip = array()) {
    $return = '';
    if (is_array($array) && !empty($array)) {
        foreach ($array as $key => $value) {
            if (empty($skip) || !in_array($key, $skip, true)) {
                if (is_array($value)) {
                    $return .= "$key={" . helper_log::implodearray($value, $skip) . "}; ";
                } elseif (!empty($value)) {
                    $return .= "$key=$value; ";
                } else {
                    $return .= '';
                }
            }
        }
    }
    return $return;
}

function clearlogstring($str) {
    if (!empty($str)) {
        if (!is_array($str)) {
            $str = dhtmlspecialchars(trim($str));
            $str = str_replace(array("\t", "\r\n", "\n", "   ", "  "), ' ', $str);
        } else {
            foreach ($str as $key => $val) {
                $str[$key] = helper_log::clearlogstring($val);
            }
        }
    }
    return $str;
}

function showheader($key, $url) {
    list($action, $operation, $do) = explode('_', $url . '___');
    $url = $action . ($operation ? '&operation=' . $operation . ($do ? '&do=' . $do : '') : '');
    $menuname = cplang('header_' . $key) != 'header_' . $key ? cplang('header_' . $key) : $key;
    return '<li><em><a href="' . ADMINSCRIPT . '?action=' . $url . '" id="header_' . $key . '" hidefocus="true" onmouseover="previewheader(\'' . $key . '\')" onmouseout="previewheader()" onclick="toggleMenu(\'' . $key . '\', \'' . $url . '\');doane(event);">' . $menuname . '</a></em></li>';
}

function showmenu($key, $menus, $return = 0) {
    global $_G;
    $body = '';
    if (is_array($menus)) {
        foreach ($menus as $menu) {
            if ($menu[0] && $menu[1]) {
                list($action, $operation, $do) = (strstr($menu[1], '_') ? explode('_', $menu[1]) : array($menu[1], ''));
                $menu[1] = $action . ($operation ? '&operation=' . $operation . ($do ? '&do=' . $do : '') : '');
                $body .= '<li><a href="' . (substr($menu[1], 0, 4) == 'http' ? $menu[1] : ADMINSCRIPT . '?action=' . $menu[1]) . '" hidefocus="true" target="' . ($menu[2] ? $menu[2] : 'main') . '"' . ($menu[3] ? $menu[3] : '') . '><em onclick="menuNewwin(this)" title="' . cplang('nav_newwin') . '"></em>' . cplang($menu[0]) . '</a></li>';
            } elseif ($menu[0] && $menu[2]) {
                if ($menu[2] == 1) {
                    $id = 'M' . substr(md5($menu[0]), 0, 8);
                    $hide = false;
                    if (!empty($_G['cookie']['cpmenu_' . $id])) {
                        $hide = true;
                    }
                    $body .= '<li class="s"><div class="lsub' . ($hide ? '' : ' desc') . '" subid="' . $id . '"><div onclick="lsub(\'' . $id . '\', this.parentNode)">' . $menu[0] . '</div><ol style="display:' . ($hide ? 'none' : '') . '" id="' . $id . '">';
                }
                if ($menu[2] == 2) {
                    $body .= '<li class="sp"></li></ol></div></li>';
                }
            }
        }
    }
    if (!$return) {
        return '<ul id="menu_' . $key . '" style="display: none">' . $body . '</ul>';
    } else {
        return $body;
    }
}

function showtitle($title, $extra = '', $multi = 1) {
    global $_G;
    if (!empty($_G['showsetting_multi'])) {
        return;
    }
    return "\n" . '<tr' . ($extra ? " $extra" : '') . '><th colspan="15" class="partition">' . cplang($title) . '</th></tr>';
}

function cptemplate($file) {
    global $_G;
    return template($_G['config']['template']['admincp'] . '/' . $file);
}

?>