<?php

/**
 * +----------------------------------------------------------------------
 * | LCLPHP [ This is a freeware ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 罗敏贵 <e-mail:minguiluo@163.com> <QQ:271391233>
 * +----------------------------------------------------------------------
 * | 文件功能：LCL启动类
 * +----------------------------------------------------------------------
 */
if (!defined('IN_LCL')) {
    exit('Access Denied');
}

class lcl_application extends lcl_base {

    var $config = array();
    var $var = array();
    var $init_db = true;
    var $init_user = true;
    var $init_setting = true;
    var $init_mobile = true;
    var $init_cron = true;
    var $init_misc = true;
    var $initated = false;
    var $superglobal = array(
        'GLOBALS' => 1,
        '_GET' => 1,
        '_POST' => 1,
        '_REQUEST' => 1,
        '_COOKIE' => 1,
        '_SERVER' => 1,
        '_ENV' => 1,
        '_FILES' => 1,
    );

    static function &instance() {
        static $object;
        if (empty($object)) {
            $object = new self();
        }
        return $object;
    }

    public function __construct() {
        $this->_init_env();
        $this->_init_config();
        $this->_init_input();
        $this->_init_output();
    }

    public function init() {
        if (!$this->initated) {
            $this->_init_db();
            $this->_init_setting();
            $this->_init_user();
            $this->_init_mobile();
            $this->_init_cron();
            $this->_init_misc();
        }
        $this->initated = true;
    }

    private function _init_env() {
        if (PHP_VERSION < '5.3.0') {
            set_magic_quotes_runtime(0);
        }

        define('MAGIC_QUOTES_GPC', function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc());
        define('ICONV_ENABLE', function_exists('iconv'));
        define('MB_ENABLE', function_exists('mb_convert_encoding'));
        define('EXT_OBGZIP', function_exists('ob_gzhandler'));

        define('TIMESTAMP', time());
        $this->timezone_set();
        if (!defined('LCL_CORE_FUNCTION') && !@include(LCL_ROOT . '/function/function_core.php')) {
            exit('function_core.php is missing' . LCL_ROOT . '/function/function_core.php');
        }
        if (function_exists('ini_get')) {
            $memorylimit = @ini_get('memory_limit');
            if ($memorylimit && return_bytes($memorylimit) < 33554432 && function_exists('ini_set')) {
                ini_set('memory_limit', '512M');
            }
        }
        define('IS_ROBOT', checkrobot());

        foreach ($GLOBALS as $key => $value) {
            if (!isset($this->superglobal[$key])) {
                $GLOBALS[$key] = null;
                unset($GLOBALS[$key]);
            }
        }

        global $_G;
        $_G = array(
            'uid' => 0,
            'username' => '',
            'adminid' => 0,
            'formhash' => formhash(),
            'timestamp' => TIMESTAMP,
            'starttime' => microtime(true),
            'clientip' => $this->_get_client_ip(),
            'remoteport' => $_SERVER['REMOTE_PORT'],
            'config' => array(),
            'setting' => array(),
            'cookie' => array(),
            'cache' => array(),
            'lang' => array(),
            'admins' => array(),
            'member' => array(),
            'mobile' => '',
            'mobiletpl' => array('1' => 'mobile', '2' => 'touch', '3' => 'wml', 'yes' => 'touch'),
        );
        $_G['PHP_SELF'] = dhtmlspecialchars($this->_get_script_url());
        $_G['basescript'] = CURSCRIPT;
        $_G['basefilename'] = basename($_G['PHP_SELF']);
        $sitepath = substr($_G['PHP_SELF'], 0, strrpos($_G['PHP_SELF'], '/'));
        if (defined('IN_API')) {
            $sitepath = preg_replace("/\/api\/?.*?$/i", '', $sitepath);
        } elseif (defined('IN_ARCHIVER')) {
            $sitepath = preg_replace("/\/archiver/i", '', $sitepath);
        }
        if (isset($_SERVER['HTTPS']) == 'on') {
            $_G['isHTTPS'] = (@$_SERVER['HTTPS'] && strtolower(@$_SERVER['HTTPS']) != 'off') ? true : false;
        }
        $_G['siteurl'] = dhtmlspecialchars('http' . (@$_G['isHTTPS'] ? 's' : '') . '://' . @$_SERVER['HTTP_HOST'] . $sitepath . '/');

        $url = parse_url($_G['siteurl']);
        $_G['siteroot'] = isset($url['path']) ? $url['path'] : '';
        $_G['siteport'] = empty($_SERVER['SERVER_PORT']) || $_SERVER['SERVER_PORT'] == '80' || $_SERVER['SERVER_PORT'] == '443' ? '' : ':' . $_SERVER['SERVER_PORT'];

        $this->var = & $_G;
    }

    private function _init_input() {
        if (isset($_GET['GLOBALS']) || isset($_POST['GLOBALS']) || isset($_COOKIE['GLOBALS']) || isset($_FILES['GLOBALS'])) {
            system_error('request_tainting');
        }

        if (MAGIC_QUOTES_GPC) {
            $_GET = dstripslashes($_GET);
            $_POST = dstripslashes($_POST);
            $_COOKIE = dstripslashes($_COOKIE);
        }

        $prelength = strlen($this->config['cookie']['cookiepre']);
        foreach ($_COOKIE as $key => $val) {
            if (substr($key, 0, $prelength) == $this->config['cookie']['cookiepre']) {
                $this->var['cookie'][substr($key, $prelength)] = $val;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {
            $_GET = array_merge($_GET, $_POST);
        }

        if (isset($_GET['page'])) {
            $_GET['page'] = rawurlencode($_GET['page']);
        }

        if (!empty($this->var['config']['input']['compatible'])) {
            foreach ($_GET as $k => $v) {
                $this->var['gp_' . $k] = daddslashes($v);
            }
        }
        $this->var['page'] = empty($_GET['page']) ? 1 : max(1, intval($_GET['page']));
    }

    private function _init_output() {
        if ($this->config['security']['attackevasive'] && (!defined('CURSCRIPT') || !in_array($this->var['mod'], array('seccode', 'secqaa', 'swfupload')) && !defined('DISABLEDEFENSE'))) {
            require_once libfile('misc/security', 'include');
        }
        if (!empty($_SERVER['HTTP_ACCEPT_ENCODING']) && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') === false) {
            $this->config['output']['gzip'] = false;
        }
        $allowgzip = $this->config['output']['gzip'] && empty($this->var['inajax']) && $this->var['mod'] != 'attachment' && EXT_OBGZIP;
        setglobal('gzipcompress', $allowgzip);

        if (!ob_start($allowgzip ? 'ob_gzhandler' : null)) {
            ob_start();
        }
        setglobal('charset', $this->config['output']['charset']);
        define('CHARSET', $this->config['output']['charset']);
        if ($this->config['output']['forceheader']) {
            @header('Content-Type: text/html; charset=' . CHARSET);
        }
    }

    private function _init_config() {
        $_config = array();
        @include LCL_ROOT . '/config/config_global.php';
        if (empty($_config)) {
            if (!file_exists(LCL_ROOT . '/data/install.lock')) {
                header('location: install');
                exit;
            } else {
                system_error('config_notfound');
            }
        }
        if (empty($_config['debug']) || !file_exists(libfile("function/debug"))) {
            define('LCL_DEBUG', false);
            error_reporting(0);
        } elseif ($_config['debug'] == 1) {
            define('LCL_DEBUG', true);
            error_reporting(E_ERROR | ~(E_STRICT | E_NOTICE));
        } elseif ($_config['debug'] == 2) {
            define('LCL_DEBUG', true);
            error_reporting(E_ALL | ~(E_STRICT | E_NOTICE));
        } else {
            define('LCL_DEBUG', false);
            error_reporting(0);
        }
        $this->config = & $_config;
        $this->var['config'] = & $_config;

        if (substr($_config['cookie']['cookiepath'], 0, 1) != '/') {
            $this->var['config']['cookie']['cookiepath'] = '/' . $this->var['config']['cookie']['cookiepath'];
        }
        $this->var['config']['cookie']['cookiepre'] = $this->var['config']['cookie']['cookiepre'] . substr(md5($this->var['config']['cookie']['cookiepath'] . '|' . $this->var['config']['cookie']['cookiedomain']), 0, 4) . '_';
    }

    private function _init_db() {
        if ($this->init_db) {
            $driver = function_exists('mysql_connect') ? 'db_driver_mysql' : 'db_driver_mysqli';
            if (getglobal('config/db/slave')) {
                $driver = function_exists('mysql_connect') ? 'db_driver_mysql_slave' : 'db_driver_mysqli_slave';
            }
            DB::init($driver, $this->config['db']);
        }
    }

    private function _init_setting() {
        if ($this->init_setting) {
            $setting = C::t('common_setting')->fetch_all(null);
            setglobal('setting', $setting);
        }
    }

    private function _init_user() {
        if ($this->init_user) {
            $adminid = getglobal('adminid', 'cookie');
            if (!empty($adminid) && $adminid > 0) {
                $this->var['admins'] = C::t('admincp_member')->fetch_by_id($adminid);
                if ($this->var['admins']) {
                    //$this->var['admins'] = $admin;
                    setglobal('uid', 0);
                    setglobal('username', getglobal('username', 'admins'));
                    setglobal('adminid', getglobal('uid', 'admins'));
                }
            }
            $uid = getglobal('uid', 'cookie');
            if (!empty($uid) && $uid > 0) {
                setglobal('uid', getglobal('uid', 'cookie'));
                setglobal('username', getglobal('username', 'cookie'));
                $this->var['member'] = C::t('ztepy')->fetch_by_uid($uid);
            }
        }
    }

    private function _init_mobile() {
        if (!$this->init_mobile) {
            return false;
        }
        if (IS_ROBOT) {
            $nomobile = true;
            $unallowmobile = true;
        }
        $mobile = getgpc('mobile');
        $mobileflag = isset($this->var['mobiletpl'][$mobile]);
        if ($mobile === 'no') {
            dsetcookie('mobile', 'no', 3600);
            $nomobile = true;
        } elseif (@$this->var['cookie']['mobile'] == 'no' && $mobileflag) {
            checkmobile();
            dsetcookie('mobile', '');
        } elseif (@$this->var['cookie']['mobile'] == 'no') {
            $nomobile = true;
        } elseif (!($mobile_ = checkmobile())) {
            $nomobile = true;
        }
        if (!$mobile || $mobile == 'yes') {
            $mobile = isset($mobile_) ? $mobile_ : 2;
        }
        if ($this->var['mobile']) {
            define('IN_MOBILE', isset($this->var['mobiletpl'][$mobile]) ? $mobile : '1');
        }
    }

    private function _init_misc() {
        if ($this->config['security']['urlxssdefend'] && !defined('DISABLEXSSCHECK')) {
            $this->_xss_check();
        }

        if (!$this->init_misc) {
            return false;
        }
        if ($this->init_setting && $this->init_user) {
            if (!isset($this->var['member']['timeoffset']) || $this->var['member']['timeoffset'] == 9999 || $this->var['member']['timeoffset'] === '') {
                $this->var['member']['timeoffset'] = @$this->var['setting']['timeoffset'];
            }
        }

        $timeoffset = $this->init_setting ? $this->var['member']['timeoffset'] : $this->var['setting']['timeoffset'];
        $this->var['timenow'] = array(
            'time' => dgmdate(TIMESTAMP),
            'offset' => $timeoffset >= 0 ? ($timeoffset == 0 ? '' : '+' . $timeoffset) : $timeoffset
        );
        $this->timezone_set($timeoffset);

        $this->var['formhash'] = formhash();
        define('FORMHASH', $this->var['formhash']);

        if (@$this->var['setting']['nocacheheaders']) {
            @header("Expires: -1");
            @header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
            @header("Pragma: no-cache");
        }
        $lastact = TIMESTAMP . "\t" . dhtmlspecialchars(basename($this->var['PHP_SELF'])) . "\t" . dhtmlspecialchars(@$this->var['mod']);
        dsetcookie('lastact', $lastact, 86400);
        setglobal('currenturl_encode', base64_encode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
    }

    private function _init_cron() {
        $ext = empty($this->config['remote']['on']) || empty($this->config['remote']['cron']) || APPTYPEID == 200;
        if ($this->init_cron && $this->init_setting && $ext) {
            if ($this->var['cache']['cronnextrun'] <= TIMESTAMP) {
                lcl_cron::run();
            }
        }
    }

    private function _get_client_ip() {
        $ip = $_SERVER['REMOTE_ADDR'];
        if (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
            foreach ($matches[0] AS $xip) {
                if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                    $ip = $xip;
                    break;
                }
            }
        }
        return $ip;
    }

    private function _xss_check() {

        static $check = array('"', '>', '<', '\'', '(', ')', 'CONTENT-TRANSFER-ENCODING');

        if (isset($_GET['formhash']) && $_GET['formhash'] !== formhash()) {
            system_error('request_tainting');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $temp = $_SERVER['REQUEST_URI'];
        } elseif (empty($_GET['formhash'])) {
            $temp = $_SERVER['REQUEST_URI'] . file_get_contents('php://input');
        } else {
            $temp = '';
        }

        if (!empty($temp)) {
            $temp = strtoupper(urldecode(urldecode($temp)));
            foreach ($check as $str) {
                if (strpos($temp, $str) !== false) {
                    system_error('request_tainting');
                }
            }
        }

        return true;
    }

    private function _get_script_url() {
        if (!isset($this->var['PHP_SELF'])) {
            $scriptName = basename($_SERVER['SCRIPT_FILENAME']);
            if (basename($_SERVER['SCRIPT_NAME']) === $scriptName) {
                $this->var['PHP_SELF'] = $_SERVER['SCRIPT_NAME'];
            } else if (basename($_SERVER['PHP_SELF']) === $scriptName) {
                $this->var['PHP_SELF'] = $_SERVER['PHP_SELF'];
            } else if (isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === $scriptName) {
                $this->var['PHP_SELF'] = $_SERVER['ORIG_SCRIPT_NAME'];
            } else if (($pos = strpos($_SERVER['PHP_SELF'], '/' . $scriptName)) !== false) {
                $this->var['PHP_SELF'] = substr($_SERVER['SCRIPT_NAME'], 0, $pos) . '/' . $scriptName;
            } else if (isset($_SERVER['DOCUMENT_ROOT']) && strpos($_SERVER['SCRIPT_FILENAME'], $_SERVER['DOCUMENT_ROOT']) === 0) {
                $this->var['PHP_SELF'] = str_replace('\\', '/', str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']));
                $this->var['PHP_SELF'][0] != '/' && $this->var['PHP_SELF'] = '/' . $this->var['PHP_SELF'];
            } else {
                system_error('request_tainting');
            }
        }
        return $this->var['PHP_SELF'];
    }

    public function timezone_set($timeoffset = 0) {
        if (function_exists('date_default_timezone_set')) {
            @date_default_timezone_set('Etc/GMT' . ($timeoffset > 0 ? '-' : '+') . (abs($timeoffset)));
        }
    }

}

?>