<?php

/**
 * +----------------------------------------------------------------------
 * | LCLPHP [ This is a freeware ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 罗敏贵 <e-mail:minguiluo@163.com> <QQ:271391233>
 * +----------------------------------------------------------------------
 * | 文件功能：全局常用函数
 * +----------------------------------------------------------------------
 */
if (!defined('IN_LCL')) {
    exit('Access Denied');
}

define('LCL_CORE_FUNCTION', true);

/**
 * 系统错误处理
 * @param <type> $message 错误信息
 * @param <type> $show 是否显示信息
 * @param <type> $save 是否存入日志
 * @param <type> $halt 是否中断访问
 */
function system_error($message, $show = true, $save = true, $halt = true) {
    lcl_error::system_error($message, $show, $save, $halt);
}

/**
 * 设置全局 $_G 中的变量
 * @global <array> $_G
 * @param <string> $key 键
 * @param <string> $value 值
 * @param <mix> $group 组(准备废弃,尽量不用)
 * @return true
 *
 * @example
 * setglobal('test', 1); // $_G['test'] = 1;
 * setglobal('config/test/abc') = 2; //$_G['config']['test']['abc'] = 2;
 *
 */
function setglobal($key, $value, $group = null) {
    global $_G;
    $key = explode('/', $group === null ? $key : $group . '/' . $key);
    $p = &$_G;
    foreach ($key as $k) {
        if (!isset($p[$k]) || !is_array($p[$k])) {
            $p[$k] = array();
        }
        $p = &$p[$k];
    }
    $p = $value;
    return true;
}

/**
 * 获取全局变量 $_G 当中的某个数值
 * @global  $_G
 * @param <type> $key
 * @param <type> $group 计划废弃的参数,不建议使用
 * @return <mix>
 *
 * $v = getglobal('test'); // $v = $_G['test']
 * $v = getglobal('test/hello/ok');  // $v = $_G['test']['hello']['ok']
 */
function getglobal($key, $group = null) {
    global $_G;
    $key = explode('/', $group === null ? $key : $group . '/' . $key);
    $v = &$_G;
    foreach ($key as $k) {
        if (!isset($v[$k])) {
            return null;
        }
        $v = &$v[$k];
    }
    return $v;
}

/**
 * 取出 get, post, cookie 当中的某个变量
 *
 * @param string $k  key 值
 * @param string $type 类型
 * @return mix
 */
function getgpc($k, $type = 'GP') {
    $type = strtoupper($type);
    switch ($type) {
        case 'G': $var = &$_GET;
            break;
        case 'P': $var = &$_POST;
            break;
        case 'C': $var = &$_COOKIE;
            break;
        default:
            if (isset($_GET[$k])) {
                $var = &$_GET;
            } else {
                $var = &$_POST;
            }
            break;
    }

    return isset($var[$k]) ? $var[$k] : NULL;
}

/**
 * 加载语言
 * 语言文件统一为 $lang = array();
 * @param $file - 语言文件，可包含路径如 forum/xxx home/xxx
 * @param $langvar - 语言文字索引
 * @param $vars - 变量替换数组
 * @return 语言文字
 */
function lang($file, $langvar = null, $vars = array(), $default = null) {
    global $_G;
    $fileinput = $file;

    list($path, $file) = (strstr($file, '/') ? explode('/', $file) : array($file, ''));

    if (!$file) {
        $file = $path;
        $path = '';
    }
    if (strpos($file, ':') !== false) {
        $path = 'plugin';
        list($file) = explode(':', $file);
    }

    if ($path != 'plugin') {
        $key = $path == '' ? $file : $path . '_' . $file;
        if (!isset($_G['lang'][$key])) {
            include LCL_LOCALE . ($path == '' ? '' : $path . '/') . 'lang_' . $file . '.php';
            $_G['lang'][$key] = $lang;
        }
        if (defined('IN_MOBILE') && !defined('TPL_DEFAULT')) {
            include LCL_LOCALE . './mobile/lang_template.php';
            $_G['lang'][$key] = array_merge($_G['lang'][$key], $lang);
        }
        $returnvalue = &$_G['lang'];
    } else {
        if (empty($_G['config']['plugindeveloper'])) {
            loadcache('pluginlanguage_script');
        } elseif (!isset($_G['cache']['pluginlanguage_script'][$file]) && preg_match("/^[a-z]+[a-z0-9_]*$/i", $file)) {
            if (@include(LCL_LOCALE . './data/plugindata/' . $file . '.lang.php')) {
                $_G['cache']['pluginlanguage_script'][$file] = $scriptlang[$file];
            } else {
                loadcache('pluginlanguage_script');
            }
        }
        $returnvalue = & $_G['cache']['pluginlanguage_script'];
        $key = &$file;
    }
    $return = $langvar !== null ? (isset($returnvalue[$key][$langvar]) ? $returnvalue[$key][$langvar] : null) : $returnvalue[$key];
    $return = $return === null ? ($default !== null ? $default : $langvar) : $return;
    $searchs = $replaces = array();
    if ($vars && is_array($vars)) {
        foreach ($vars as $k => $v) {
            $searchs[] = '{' . $k . '}';
            $replaces[] = $v;
        }
    }
    if (is_string($return) && strpos($return, '{_G/') !== false) {
        preg_match_all('/\{_G\/(.+?)\}/', $return, $gvar);
        foreach ($gvar[0] as $k => $v) {
            $searchs[] = $v;
            $replaces[] = getglobal($gvar[1][$k]);
        }
    }
    $return = str_replace($searchs, $replaces, $return);
    return $return;
}

/**
 * 检查模板源文件是否更新
 * 当编译文件不存时强制重新编译
 * 当 tplrefresh = 1 时检查文件
 * 当 tplrefresh > 1 时，则根据 tplrefresh 取余，无余时则检查更新
 *
 */
function checktplrefresh($maintpl, $subtpl, $timecompare, $templateid, $cachefile, $tpldir, $file) {
    static $tplrefresh, $timestamp, $targettplname;
    if ($tplrefresh === null) {
        $tplrefresh = getglobal('config/output/tplrefresh');
        $timestamp = getglobal('timestamp');
    }
    //echo $maintpl;
    if (1 || empty($timecompare) || $tplrefresh == 1 || ($tplrefresh > 1 && !($timestamp % $tplrefresh))) {
        if (empty($timecompare) || @filemtime(LCL_ROOT . $subtpl) > $timecompare) {
            require_once LCL_ROOT . '/class/class_template.php';
            $template = new template();
            $template->parse_template($maintpl, $templateid, $tpldir, $file, $cachefile);
            if ($targettplname === null) {
                $targettplname = getglobal('style/tplfile');
                if (!empty($targettplname)) {
                    include_once libfile('function/block');
                    $targettplname = strtr($targettplname, ':', '_');
                    update_template_block($targettplname, getglobal('style/tpldirectory'), $template->blocks);
                }
                $targettplname = true;
            }
            return TRUE;
        }
    }
    return FALSE;
}

/**
 * 解析模板
 * @return 返回域名
 * 1、模板文件
 * 2、模板类型，如：diy模板和普通模板，diy模板位于data/diy,普通模板位于 template/default/下
 * 3、生成的tpl模板文件的存放位置
 * 4、是否返回模板文件的路径，0-返回编译后的php文件
 * 5、原始的模板文件
 */
function template($file, $templateid = 0, $tpldir = '', $gettplfile = 0, $primaltpl = '') {
    global $_G;

    $oldfile = $file;
    if (strpos($file, ':') !== false) {
        $clonefile = '';
        list($templateid, $file, $clonefile) = explode(':', $file);
        $oldfile = $file;
        $file = empty($clonefile) ? $file : $file . '_' . $clonefile;
        if ($templateid == 'diy') {

        } else {
            $tpldir = './plugin/' . $templateid . '/template';
        }
    }

    $file .=!empty($_G['inajax']) && ($file == 'common/header' || $file == 'common/footer') ? '_ajax' : '';
    $tpldir = $tpldir ? $tpldir : (defined('TPLDIR') ? TPLDIR : '');
    $templateid = $templateid ? $templateid : (defined('TEMPLATEID') ? TEMPLATEID : '');
    $filebak = $file;

    if (!$tpldir) {
        $tpldir = './template';
    }
    $tplfile = $tpldir . '/' . $file . '.htm';

    $file == 'common/header' && defined('CURMODULE') && CURMODULE && $file = 'common/header_' . $_G['basescript'] . '_' . CURMODULE;

    $cachefile = './data/template/' . (defined('STYLEID') ? STYLEID . '_' : '_') . $templateid . '_' . str_replace('/', '_', $file) . '.tpl.php';
    if ($templateid != 1 && !file_exists(LCL_ROOT . $tplfile) && !file_exists(substr(LCL_ROOT . $tplfile, 0, -4) . '.php') && !file_exists(LCL_ROOT . ($tplfile = $tpldir . $filebak . '.htm'))) {
        $tplfile = './template/' . $filebak . '.htm';
    }

    if ($gettplfile) {
        return $tplfile;
    }
    checktplrefresh($tplfile, $tplfile, @filemtime(LCL_ROOT . $cachefile), $templateid, $cachefile, $tpldir, $file);
    return LCL_ROOT . $cachefile;
}

function simpletemplate($file) {
    global $_G;
    $tplfile = $file;
    if (defined('IN_MOBILE')) {
        $tplfile = $_G['config']['template']['default'] . '/touch/' . $file;
    } else {
        $tplfile = $_G['config']['template']['default'] . '/' . $file;
    }
    return template($tplfile);
}

function hookscriptoutput($tplfile) {
    global $_G;
    if (!empty($_G['hookscriptoutput'])) {
        return;
    }
    hookscript('global', 'global');
    if (defined('CURMODULE')) {
        $param = array('template' => $tplfile, 'message' => $_G['hookscriptmessage'], 'values' => $_G['hookscriptvalues']);
        hookscript(CURMODULE, $_G['basescript'], 'outputfuncs', $param);
    }
    $_G['hookscriptoutput'] = true;
}

/**
 * 返回库文件的全路径
 *
 * @param string $libname 库文件分类及名称
 * @param string $folder 模块目录'module','include','class'
 * @return string
 *
 * @example require LCL_ROOT.'./source/function/function_cache.php'
 * @example 我们可以利用此函数简写为：require libfile('function/cache');
 *
 */
function libfile($libname, $folder = '') {
    $libpath = '/' . $folder;
    if (strstr($libname, '/')) {
        list($pre, $name) = explode('/', $libname);
        $path = "{$libpath}/{$pre}/{$pre}_{$name}";
    } else {
        $path = "{$libpath}/{$libname}";
    }
    $outpath = realpath(LCL_ROOT . $path . '.php');
    if (!file_exists($outpath)) {
        echo 'libfile action_noaccess  ' . $libname;
    }
    return preg_match('/^[\w\d\/_]+$/i', $path) ? $outpath : false;
}

/**
 * 对字符串或者输入进行 addslashes 操作
 * @param <mix> $string
 * @param <int> $force
 * @return <mix>
 */
function daddslashes($string, $force = 1) {
    if (is_array($string)) {
        $keys = array_keys($string);
        foreach ($keys as $key) {
            $val = $string[$key];
            unset($string[$key]);
            $string[addslashes($key)] = daddslashes($val, $force);
        }
    } else {
        $string = addslashes($string);
    }
    return $string;
}

/**
 * HTML转义字符
 * @param $string - 字符串
 * @return 返回转义好的字符串
 */
function dhtmlspecialchars($string, $flags = null) {
    if (is_array($string)) {
        foreach ($string as $key => $val) {
            $string[$key] = dhtmlspecialchars($val, $flags);
        }
    } else {
        if ($flags === null) {
            $string = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string);
            if (strpos($string, '&amp;#') !== false) {
                $string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $string);
            }
        } else {
            if (PHP_VERSION < '5.4.0') {
                $string = htmlspecialchars($string, $flags);
            } else {
                if (strtolower(CHARSET) == 'utf-8') {
                    $charset = 'UTF-8';
                } else {
                    $charset = 'ISO-8859-1';
                }
                $string = htmlspecialchars($string, $flags, $charset);
            }
        }
    }
    return $string;
}

//连接字符
function dimplode($array) {
    if (!empty($array)) {
        $array = array_map('addslashes', $array);
        return "'" . implode("','", is_array($array) ? $array : array($array)) . "'";
    } else {
        return 0;
    }
}

function checkrobot($useragent = '') {
    static $kw_spiders = array('bot', 'crawl', 'spider', 'slurp', 'sohu-search', 'lycos', 'robozilla');
    static $kw_browsers = array('msie', 'netscape', 'opera', 'konqueror', 'mozilla');

    $useragent = strtolower(empty($useragent) ? $_SERVER['HTTP_USER_AGENT'] : $useragent);
    if (strpos($useragent, 'http://') === false && dstrpos($useragent, $kw_browsers))
        return false;
    if (dstrpos($useragent, $kw_spiders))
        return true;
    return false;
}

/**
 * 检查是否是以手机浏览器进入(IN_MOBILE)
 */
function checkmobile() {
    global $_G;
    $mobile = array();
    static $touchbrowser_list = array('iphone', 'android', 'phone', 'mobile', 'wap', 'netfront', 'java', 'opera mobi', 'opera mini',
 'ucweb', 'windows ce', 'symbian', 'series', 'webos', 'sony', 'blackberry', 'dopod', 'nokia', 'samsung',
 'palmsource', 'xda', 'pieplus', 'meizu', 'midp', 'cldc', 'motorola', 'foma', 'docomo', 'up.browser',
 'up.link', 'blazer', 'helio', 'hosin', 'huawei', 'novarra', 'coolpad', 'webos', 'techfaith', 'palmsource',
 'alcatel', 'amoi', 'ktouch', 'nexian', 'ericsson', 'philips', 'sagem', 'wellcom', 'bunjalloo', 'maui', 'smartphone',
 'iemobile', 'spice', 'bird', 'zte-', 'longcos', 'pantech', 'gionee', 'portalmmm', 'jig browser', 'hiptop',
 'benq', 'haier', '^lct', '320x320', '240x320', '176x220', 'windows phone');
    static $wmlbrowser_list = array('cect', 'compal', 'ctl', 'lg', 'nec', 'tcl', 'alcatel', 'ericsson', 'bird', 'daxian', 'dbtel', 'eastcom',
 'pantech', 'dopod', 'philips', 'haier', 'konka', 'kejian', 'lenovo', 'benq', 'mot', 'soutec', 'nokia', 'sagem', 'sgh',
 'sed', 'capitel', 'panasonic', 'sonyericsson', 'sharp', 'amoi', 'panda', 'zte');

    static $pad_list = array('ipad');

    $useragent = strtolower($_SERVER['HTTP_USER_AGENT']);

    if (dstrpos($useragent, $pad_list)) {
        return false;
    }
    if (($v = dstrpos($useragent, $touchbrowser_list, true))) {
        $_G['mobile'] = $v;
        return '2';
    }
    if (($v = dstrpos($useragent, $wmlbrowser_list))) {
        $_G['mobile'] = $v;
        return '3'; //wml版
    }
    $brower = array('mozilla', 'chrome', 'safari', 'opera', 'm3gate', 'winwap', 'openwave', 'myop');
    if (dstrpos($useragent, $brower))
        return false;

    $_G['mobile'] = 'unknown';
    if (isset($_G['mobiletpl'][$_GET['mobile']])) {
        return true;
    } else {
        return false;
    }
}

/**
 * 手机模式下替换所有链接为mobile=yes形式
 * @param $file - 正则匹配到的文件字符串
 * @param $file - 要被替换的字符串
 * @$replace 替换后字符串
 */
function mobilereplace($file, $replace) {
    global $_G;
    if (strpos($replace, 'mobile=') === false) {
        if (strpos($replace, '?') === false) {
            $replace = 'href="' . $file . $replace . '?mobile=yes"';
        } else {
            $replace = 'href="' . $file . $replace . '&mobile=yes"';
        }
        return $replace;
    } else {
        return 'href="' . $file . $replace . '"';
    }
}

/**
 * 同 php header函数, 针对 location 跳转做了特殊处理
 * @param <type> $string
 * @param <type> $replace
 * @param <type> $http_response_code
 */
function dheader($string, $replace = true, $http_response_code = 0) {
    $islocation = substr(strtolower(trim($string)), 0, 8) == 'location';
    if (defined('IN_MOBILE') && strpos($string, 'mobile') === false && $islocation) {
        if (strpos($string, '?') === false) {
            $string = $string . '?mobile=' . IN_MOBILE;
        } else {
            if (strpos($string, '#') === false) {
                $string = $string . '&mobile=' . IN_MOBILE;
            } else {
                $str_arr = explode('#', $string);
                $str_arr[0] = $str_arr[0] . '&mobile=' . IN_MOBILE;
                $string = implode('#', $str_arr);
            }
        }
    }
    $string = str_replace(array("\r", "\n"), array('', ''), $string);
    if (empty($http_response_code) || PHP_VERSION < '4.3') {
        @header($string, $replace);
    } else {
        @header($string, $replace, $http_response_code);
    }
    if ($islocation) {
        exit();
    }
}

/**
 * 字符串方式实现 preg_match("/(s1|s2|s3)/", $string, $match)
 * @param string $string 源字符串
 * @param array $arr 要查找的字符串 如array('s1', 's2', 's3')
 * @param bool $returnvalue 是否返回找到的值
 * @return bool
 */
function dstrpos($string, $arr, $returnvalue = false) {
    if (empty($string))
        return false;
    foreach ((array) $arr as $v) {
        if (strpos($string, $v) !== false) {
            $return = $returnvalue ? $v : true;
            return $return;
        }
    }
    return false;
}

/**
 * 产生随机码
 * @param $length - 要多长
 * @param $numberic - 数字还是字符串
 * @return 返回字符串
 */
function random($length, $numeric = 0) {
    $seed = base_convert(md5(microtime() . $_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
    $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
    if ($numeric) {
        $hash = '';
    } else {
        $hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
        $length--;
    }
    $max = strlen($seed) - 1;
    for ($i = 0; $i < $length; $i++) {
        $hash .= $seed{mt_rand(0, $max)};
    }
    return $hash;
}

/**
 * 设置cookie
 * @param $var - 变量名
 * @param $value - 变量值
 * @param $life - 生命期
 * @param $prefix - 前缀
 */
function dsetcookie($var, $value = '', $life = 0, $prefix = 1, $httponly = false) {

    global $_G;

    $config = $_G['config']['cookie'];

    $_G['cookie'][$var] = $value;
    $var = ($prefix ? $config['cookiepre'] : '') . $var;
    $_COOKIE[$var] = $value;

    if ($value == '' || $life < 0) {
        $value = '';
        $life = -1;
    }

    if (defined('IN_MOBILE')) {
        $httponly = false;
    }

    $life = $life > 0 ? getglobal('timestamp') + $life : ($life < 0 ? getglobal('timestamp') - 31536000 : 0);
    $path = $httponly && PHP_VERSION < '5.2.0' ? $config['cookiepath'] . '; HttpOnly' : $config['cookiepath'];

    $secure = $_SERVER['SERVER_PORT'] == 443 ? 1 : 0;
    if (PHP_VERSION < '5.2.0') {
        setcookie($var, $value, $life, $path, $config['cookiedomain'], $secure);
    } else {
        setcookie($var, $value, $life, $path, $config['cookiedomain'], $secure, $httponly);
    }
}

/**
 * 获取cookie
 */
function getcookie($key) {
    global $_G;
    return isset($_G['cookie'][$key]) ? $_G['cookie'][$key] : '';
}

/**
 * 内存读写接口函数
 *
 * @param 命令 $cmd (set|get|rm|check)
 * @param 键值 $key
 * @param 数据 $value
 * @param 有效期 $ttl
 * @return mix
 *
 * @example set : 写入内存 $ret = memory('set', 'test', 'ok')
 * @example get : 读取内存 $data = memory('get', 'test')
 * @example rm : 删除内存  $ret = memory('rm', 'test')
 * @example check : 检查内存功能是否可用 $allow = memory('check')
 */
function memory($cmd, $key = '', $value = '', $ttl = 0, $prefix = '') {
    if ($cmd == 'check') {
        return C::memory()->enable ? C::memory()->type : '';
    } elseif (C::memory()->enable && in_array($cmd, array('set', 'get', 'rm', 'inc', 'dec'))) {
        if (defined('LCL_DEBUG') && LCL_DEBUG) {
            if (is_array($key)) {
                foreach ($key as $k) {
                    C::memory()->debug[$cmd][] = ($cmd == 'get' || $cmd == 'rm' ? $value : '') . $prefix . $k;
                }
            } else {
                C::memory()->debug[$cmd][] = ($cmd == 'get' || $cmd == 'rm' ? $value : '') . $prefix . $key;
            }
        }
        switch ($cmd) {
            case 'set': return C::memory()->set($key, $value, $ttl, $prefix);
                break;
            case 'get': return C::memory()->get($key, $value);
                break;
            case 'rm': return C::memory()->rm($key, $value);
                break;
            case 'inc': return C::memory()->inc($key, $value ? $value : 1);
                break;
            case 'dec': return C::memory()->dec($key, $value ? $value : -1);
                break;
        }
    }
    return null;
}

/**
 * 对字符串进行加密和解密
 * @param <string> $string
 * @param <string> $operation  DECODE 解密 | ENCODE  加密
 * @param <string> $key 当为空的时候,取全局密钥
 * @param <int> $expiry 有效期,单位秒
 * @return <string>
 */
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
    $ckey_length = 4;
    $key = md5($key != '' ? $key : getglobal('authkey'));
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

    $cryptkey = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);

    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);

    $result = '';
    $box = range(0, 255);

    $rndkey = array();
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }

    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }

    if ($operation == 'DECODE') {
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc . str_replace('=', '', base64_encode($result));
    }
}

/**
 * 获取文件扩展名
 */
function fileext($filename) {
    return addslashes(strtolower(substr(strrchr($filename, '.'), 1, 10)));
}

/**
 * 检查邮箱是否有效
 * @param $email 要检查的邮箱
 * @param 返回结果
 */
function isemail($email) {
    return strlen($email) > 6 && preg_match("/^([A-Za-z0-9\-_.+]+)@([A-Za-z0-9\-]+[.][A-Za-z0-9\-.]+)$/", $email);
}

/**
 * 判断一个字符串是否在另一个字符串中存在
 *
 * @param string 原始字串 $string
 * @param string 查找 $find
 * @return boolean
 */
function strexists($string, $find) {
    return !(strpos($string, $find) === FALSE);
}

/**
 * 针对uft-8进行特殊处理的strlen
 * @param string $str
 * @return int
 */
function dstrlen($str) {
    if (strtolower(CHARSET) != 'utf-8') {
        return strlen($str);
    }
    $count = 0;
    for ($i = 0; $i < strlen($str); $i++) {
        $value = ord($str[$i]);
        if ($value > 127) {
            $count++;
            if ($value >= 192 && $value <= 223)
                $i++;
            elseif ($value >= 224 && $value <= 239)
                $i = $i + 2;
            elseif ($value >= 240 && $value <= 247)
                $i = $i + 3;
        }
        $count++;
    }
    return $count;
}

/**
 * 根据中文裁减字符串
 * @param $string - 字符串
 * @param $length - 长度
 * @param $doc - 缩略后缀
 * @return 返回带省略号被裁减好的字符串
 */
function cutstr($string, $length, $dot = ' ...') {
    if (strlen($string) <= $length) {
        return $string;
    }

    $pre = chr(1);
    $end = chr(1);
    $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end), $string);

    $strcut = '';
    if (strtolower(CHARSET) == 'utf-8') {

        $n = $tn = $noc = 0;
        while ($n < strlen($string)) {

            $t = ord($string[$n]);
            if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                $tn = 1;
                $n++;
                $noc++;
            } elseif (194 <= $t && $t <= 223) {
                $tn = 2;
                $n += 2;
                $noc += 2;
            } elseif (224 <= $t && $t <= 239) {
                $tn = 3;
                $n += 3;
                $noc += 2;
            } elseif (240 <= $t && $t <= 247) {
                $tn = 4;
                $n += 4;
                $noc += 2;
            } elseif (248 <= $t && $t <= 251) {
                $tn = 5;
                $n += 5;
                $noc += 2;
            } elseif ($t == 252 || $t == 253) {
                $tn = 6;
                $n += 6;
                $noc += 2;
            } else {
                $n++;
            }

            if ($noc >= $length) {
                break;
            }
        }
        if ($noc > $length) {
            $n -= $tn;
        }

        $strcut = substr($string, 0, $n);
    } else {
        $_length = $length - 1;
        for ($i = 0; $i < $length; $i++) {
            if (ord($string[$i]) <= 127) {
                $strcut .= $string[$i];
            } else if ($i < $_length) {
                $strcut .= $string[$i] . $string[++$i];
            }
        }
    }

    $strcut = str_replace(array($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);

    $pos = strrpos($strcut, chr(1));
    if ($pos !== false) {
        $strcut = substr($strcut, 0, $pos);
    }
    return $strcut . $dot;
}

function debug($var = null, $vardump = false) {
    echo '<pre>';
    $vardump = empty($var) ? true : $vardump;
    if ($vardump) {
        var_dump($var);
    } else {
        print_r($var);
    }
    echo '</pre>';
    exit();
}

function debuginfo() {
    global $_G;
    if (getglobal('setting/debug')) {
        $db = & DB::object();
        $_G['debuginfo'] = array(
            'time' => number_format((microtime(true) - $_G['starttime']), 6),
            'queries' => $db->querynum,
            'memory' => ucwords(C::memory()->type)
        );
        if ($db->slaveid) {
            $_G['debuginfo']['queries'] = 'Total ' . $db->querynum . ', Slave ' . $db->slavequery;
        }
        return TRUE;
    } else {
        return FALSE;
    }
}

function output() {
    global $_G;

    if (defined('LCL_DEBUG') && LCL_DEBUG && @include(libfile("function/debug"))) {
        function_exists('debugmessage') && debugmessage();
    }
}

function dintval($int, $allowarray = false) {
    $ret = intval($int);
    if ($int == $ret || !$allowarray && is_array($int))
        return $ret;
    if ($allowarray && is_array($int)) {
        foreach ($int as &$v) {
            $v = dintval($v, true);
        }
        return $int;
    } elseif ($int <= 0xffffffff) {
        $l = strlen($int);
        $m = substr($int, 0, 1) == '-' ? 1 : 0;
        if (($l - $m) === strspn($int, '0987654321', $m)) {
            return $int;
        }
    }
    return $ret;
}

function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val{strlen($val) - 1});
    switch ($last) {
        case 'g': $val *= 1024;
        case 'm': $val *= 1024;
        case 'k': $val *= 1024;
    }
    return $val;
}

/**
 * 格式化时间
 * @param $timestamp - 时间戳
 * @param $format - dt=日期时间 d=日期 t=时间 u=个性化 其他=自定义
 * @param $timeoffset - 时区
 * @return string
 */
function dgmdate($timestamp, $format = 'dt', $timeoffset = '9999', $uformat = '') {
    global $_G;
    $format == 'u' && !$_G['setting']['dateconvert'] && $format = 'dt';
    static $dformat, $tformat, $dtformat, $offset, $lang;
    if ($dformat === null) {
        $dformat = getglobal('setting/dateformat');
        $tformat = getglobal('setting/timeformat');
        $dtformat = $dformat . ' ' . $tformat;
        $offset = getglobal('member/timeoffset');
        $sysoffset = getglobal('setting/timeoffset');
        $offset = $offset == 9999 ? ($sysoffset ? $sysoffset : 0) : $offset;
        $lang = lang('admincp', 'date');
    }
    $timeoffset = $timeoffset == 9999 ? $offset : $timeoffset;
    $timestamp += $timeoffset * 3600;
    $format = empty($format) || $format == 'dt' ? $dtformat : ($format == 'd' ? $dformat : ($format == 't' ? $tformat : $format));
    if ($format == 'u') {
        $todaytimestamp = TIMESTAMP - (TIMESTAMP + $timeoffset * 3600) % 86400 + $timeoffset * 3600;
        $s = gmdate(!$uformat ? $dtformat : $uformat, $timestamp);
        $time = TIMESTAMP + $timeoffset * 3600 - $timestamp;
        if ($timestamp >= $todaytimestamp) {
            if ($time > 3600) {
                $return = intval($time / 3600) . '&nbsp;' . $lang['hour'] . $lang['before'];
            } elseif ($time > 1800) {
                $return = $lang['half'] . $lang['hour'] . $lang['before'];
            } elseif ($time > 60) {
                $return = intval($time / 60) . '&nbsp;' . $lang['min'] . $lang['before'];
            } elseif ($time > 0) {
                $return = $time . '&nbsp;' . $lang['sec'] . $lang['before'];
            } elseif ($time == 0) {
                $return = $lang['now'];
            } else {
                $return = $s;
            }
            if ($time >= 0 && !defined('IN_MOBILE')) {
                $return = '<span title="' . $s . '">' . $return . '</span>';
            }
        } elseif (($days = intval(($todaytimestamp - $timestamp) / 86400)) >= 0 && $days < 7) {
            if ($days == 0) {
                $return = $lang['yday'] . '&nbsp;' . gmdate($tformat, $timestamp);
            } elseif ($days == 1) {
                $return = $lang['byday'] . '&nbsp;' . gmdate($tformat, $timestamp);
            } else {
                $return = ($days + 1) . '&nbsp;' . $lang['day'] . $lang['before'];
            }
            if (!defined('IN_MOBILE')) {
                $return = '<span title="' . $s . '">' . $return . '</span>';
            }
        } else {
            $return = $s;
        }
        return $return;
    } else {
        return gmdate($format, $timestamp);
    }
}

/**
  得到时间戳
 */
function dmktime($date) {
    if (strpos($date, '-')) {
        $time = explode('-', $date);
        return mktime(0, 0, 0, $time[1], $time[2], $time[0]);
    }
    return 0;
}

function formhash($specialadd = '') {
    global $_G;
    $hashadd = defined('IN_ADMINCP') ? 'Only For Admin Control Panel' : '';
    return substr(md5(substr($_G['timestamp'], 0, -7) . $_G['username'] . $_G['uid'] . @$_G['authkey'] . $hashadd . $specialadd), 8, 8);
}

/**
 * 检查是否正确提交了表单
 * @param $var 需要检查的变量
 * @param $allowget 是否允许GET方式
 * @param $seccodecheck 验证码检测是否开启
 * @return 返回是否正确提交了表单
 */
function submitcheck($var, $allowget = 0, $seccodecheck = 0, $secqaacheck = 0) {
    if (!getgpc($var)) {
        return FALSE;
    } else {
        return helper_form::submitcheck($var, $allowget, $seccodecheck, $secqaacheck);
    }
}

/**
 * 分页
 * @param $num - 总数
 * @param $perpage - 每页数
 * @param $curpage - 当前页
 * @param $mpurl - 跳转的路径
 * @param $maxpages - 允许显示的最大页数
 * @param $page - 最多显示多少页码
 * @param $autogoto - 最后一页，自动跳转
 * @param $simple - 是否简洁模式（简洁模式不显示上一页、下一页和页码跳转）
 * @return 返回分页代码
 */
function multi($num, $perpage, $curpage, $mpurl, $maxpages = 0, $page = 10, $autogoto = FALSE, $simple = FALSE, $jsfunc = FALSE) {
    return $num > $perpage ? helper_page::multi($num, $perpage, $curpage, $mpurl, $maxpages, $page, $autogoto, $simple, $jsfunc) : '';
}

/**
 * 只有上一页下一页的分页（无需知道数据总数）
 * @param $num - 本次所取数据条数
 * @param $perpage - 每页数
 * @param $curpage - 当前页
 * @param $mpurl - 跳转的路径
 * @return 返回分页代码
 */
function simplepage($num, $perpage, $curpage, $mpurl) {
    return helper_page::simplepage($num, $perpage, $curpage, $mpurl);
}

function sendeamil($to, $subject, $mailcontent) {
//******************** 配置信息 ********************************
    $mailcontent .=$mailcontent . <<<EOT
这封信是由 中兴捧月 发送的。<br />
您收到这封邮件，是由于在中兴捧月使用了这个邮箱地址。
如果您并没有访问过 中兴捧月，或没有进行上述操作，请忽 略这封邮件。您不需要退订或进行其他进一步的操作。<br />
EOT;
//************************ 配置信息 ****************************
    $smtp = new smtp();
    $state = $smtp->send($to, $subject, $mailcontent);
    return $state;
}

function showmessage($message, $url = '', $type = '') {
    require_once libfile('function/message');
    return dshowmessage($message, $url = '', $type = '');
}

/**
 * 退出程序 同 exit 的区别, 对输出数据会进行 重新加工和处理
 * 通常情况下,我们建议使用本函数终止程序, 除非有特别需求
 * @param <type> $message
 */
function dexit($message = '') {
    echo $message;
    output();
    exit();
}

function dunserialize($data) {
    if (($ret = unserialize($data)) === false) {
        $ret = unserialize(stripslashes($data));
    }
    return $ret;
}

//取缓存数据
function loadcache($cachenames, $force = false) {
    global $_G;
    static $loadedcache = array();
    $cachenames = is_array($cachenames) ? $cachenames : array($cachenames);
    $caches = array();
    foreach ($cachenames as $k) {
        if (!isset($loadedcache[$k]) || $force) {
            $caches[] = $k;
            $loadedcache[$k] = true;
        }
    }

    if (!empty($caches)) {
        $cachedata = C::t('common_syscache')->fetch_all($caches);
        foreach ($cachedata as $cname => $data) {
            if ($cname == 'setting') {
                $_G['setting'] = $data;
            } elseif ($cname == 'usergroup_' . $_G['groupid']) {
                $_G['cache'][$cname] = $_G['group'] = $data;
            } elseif ($cname == 'style_default') {
                $_G['cache'][$cname] = $_G['style'] = $data;
            } elseif ($cname == 'grouplevels') {
                $_G['grouplevels'] = $data;
            } else {
                $_G['cache'][$cname] = $data;
            }
        }
    }
    return true;
}

//写入缓存
function save_syscache($cachename, $data) {
    savecache($cachename, $data);
}

function savecache($cachename, $data) {
    C::t('common_syscache')->insert($cachename, $data);
}

function runlog($file, $message, $halt = 0) {
    helper_log::runlog($file, $message, $halt);
}

function writelog($file, $log) {
    helper_log::writelog($file, $log);
}

function useractionlog($uid, $action) {
    return helper_log::useractionlog($uid, $action);
}

function getuseraction($var) {
    return helper_log::getuseraction($var);
}

function dir_writeable($dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0777);
    }
    if (is_dir($dir)) {
        if ($fp = @fopen("$dir/test.txt", 'w')) {
            @fclose($fp);
            @unlink("$dir/test.txt");
            $writeable = 1;
        } else {
            $writeable = 0;
        }
    }
    return $writeable;
}

/**
 * 字节格式化单位
 * @param $filesize - 大小(字节)
 * @return 返回格式化后的文本
 */
function sizecount($size) {
    if ($size >= 1073741824) {
        $size = round($size / 1073741824 * 100) / 100 . ' GB';
    } elseif ($size >= 1048576) {
        $size = round($size / 1048576 * 100) / 100 . ' MB';
    } elseif ($size >= 1024) {
        $size = round($size / 1024 * 100) / 100 . ' KB';
    } else {
        $size = intval($size) . ' Bytes';
    }
    return $size;
}

/*
 * 处理搜索关键字
 */

function stripsearchkey($string) {
    $string = trim($string);
    $string = str_replace('*', '%', addcslashes($string, '%_'));
    $string = str_replace('_', '\_', $string);
    return $string;
}

/*
 * 递归创建目录
 */

function dmkdir($dir, $mode = 0777, $makeindex = TRUE) {
    if (!is_dir($dir)) {
        dmkdir(dirname($dir), $mode, $makeindex);
        @mkdir($dir, $mode);
        if (!empty($makeindex)) {
            @touch($dir . '/index.html');
            @chmod($dir . '/index.html', 0777);
        }
    }
    return true;
}

/**
 * 远程FTP使用
 */
function ftpcmd($cmd, $arg1 = '') {
    static $ftp;
    $ftpon = getglobal('setting/ftp/on');
    if (!$ftpon) {
        return $cmd == 'error' ? -101 : 0;
    } elseif ($ftp == null) {
        require_once libfile('class/ftp');
        $ftp = & lcl_ftp::instance();
    }
    if (!$ftp->enabled) {
        return $ftp->error();
    } elseif ($ftp->enabled && !$ftp->connectid) {
        $ftp->connect();
    }
    switch ($cmd) {
        case 'upload' : return $ftp->upload(getglobal('setting/attachdir') . '/' . $arg1, $arg1);
            break;
        case 'delete' : return $ftp->ftp_delete($arg1);
            break;
        case 'close' : return $ftp->ftp_close();
            break;
        case 'error' : return $ftp->error();
            break;
        case 'object' : return $ftp;
            break;
        default : return false;
    }
}

/**
 * 编码转换
 * @param <string> $str 要转码的字符
 * @param <string> $in_charset 输入字符集
 * @param <string> $out_charset 输出字符集(默认当前)
 * @param <boolean> $ForceTable 强制使用码表(默认不强制)
 *
 */
function diconv($str, $in_charset, $out_charset = CHARSET, $ForceTable = FALSE) {
    global $_G;

    $in_charset = strtoupper($in_charset);
    $out_charset = strtoupper($out_charset);

    if (empty($str) || $in_charset == $out_charset) {
        return $str;
    }

    $out = '';

    if (!$ForceTable) {
        if (function_exists('iconv')) {
            $out = iconv($in_charset, $out_charset . '//IGNORE', $str);
        } elseif (function_exists('mb_convert_encoding')) {
            $out = mb_convert_encoding($str, $out_charset, $in_charset);
        }
    }

    if ($out == '') {
        require_once libfile('class/chinese');
        $chinese = new Chinese($in_charset, $out_charset, true);
        $out = $chinese->Convert($str);
    }

    return $out;
}

/**
 * ip允许访问
 * @param $ip 要检查的ip地址
 * @param - $accesslist 允许访问的ip地址
 * @param 返回结果
 */
function ipaccess($ip, $accesslist) {
    return preg_match("/^(" . str_replace(array("\r\n", ' '), array('|', ''), preg_quote($accesslist, '/')) . ")/", $ip);
}

/**
 * ip限制访问
 * @param $ip 要检查的ip地址
 * @param - $accesslist 允许访问的ip地址
 * @param 返回结果
 */
function ipbanned($onlineip) {
    global $_G;

    if ($_G['setting']['ipaccess'] && !ipaccess($onlineip, $_G['setting']['ipaccess'])) {
        return TRUE;
    }

    loadcache('ipbanned');
    if (empty($_G['cache']['ipbanned'])) {
        return FALSE;
    } else {
        if ($_G['cache']['ipbanned']['expiration'] < TIMESTAMP) {
            require_once libfile('function/cache');
            updatecache('ipbanned');
        }
        return preg_match("/^(" . $_G['cache']['ipbanned']['regexp'] . ")$/", $onlineip);
    }
}

/**
 * PHP 兼容性函数
 */
if (!function_exists('file_put_contents')) {
    if (!defined('FILE_APPEND'))
        define('FILE_APPEND', 8);

    function file_put_contents($filename, $data, $flag = 0) {
        $return = false;
        if ($fp = @fopen($filename, $flag != FILE_APPEND ? 'w' : 'a')) {
            if ($flag == LOCK_EX)
                @flock($fp, LOCK_EX);
            $return = fwrite($fp, is_array($data) ? implode('', $data) : $data);
            fclose($fp);
        }
        return $return;
    }

}
?>