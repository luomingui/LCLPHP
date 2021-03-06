<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE);
@set_time_limit(1000);
@set_magic_quotes_runtime(0);

define('IN_LCL', TRUE);
define('IN_COMSENZ', TRUE);
define('ROOT_PATH', dirname(__FILE__) . '/../');

require ROOT_PATH . './lcl_version.php';
require ROOT_PATH . './install/include/install_var.php';
if (function_exists('mysql_connect')) {
    require ROOT_PATH . './install/include/install_mysql.php';
} else {
    require ROOT_PATH . './install/include/install_mysqli.php';
}
require ROOT_PATH . './install/include/install_function.php';
require ROOT_PATH . './install/include/install_lang.php';

$view_off = getgpc('view_off');

define('VIEW_OFF', $view_off ? TRUE : FALSE);

$allow_method = array('show_license', 'env_check', 'app_reg', 'db_init', 'ext_info', 'install_check', 'tablepre_check');

$step = intval(getgpc('step', 'R')) ? intval(getgpc('step', 'R')) : 0;
$method = getgpc('method');

if (empty($method) || !in_array($method, $allow_method)) {
    $method = isset($allow_method[$step]) ? $allow_method[$step] : '';
}

if (empty($method)) {
    show_msg('method_undefined', $method, 0);
}

if (file_exists($lockfile) && $method != 'ext_info') {
    show_msg('install_locked', '', 0);
} elseif (!class_exists('dbstuff')) {
    show_msg('database_nonexistence', '', 0);
}

timezone_set();

$uchidden = getgpc('uchidden');

if (in_array($method, array('app_reg', 'ext_info'))) {
    $isHTTPS = ($_SERVER['HTTPS'] && strtolower($_SERVER['HTTPS']) != 'off') ? true : false;
    $PHP_SELF = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
    $bbserver = 'http' . ($isHTTPS ? 's' : '') . '://' . preg_replace("/\:\d+/", '', $_SERVER['HTTP_HOST']) . ($_SERVER['SERVER_PORT'] && $_SERVER['SERVER_PORT'] != 80 && $_SERVER['SERVER_PORT'] != 443 ? ':' . $_SERVER['SERVER_PORT'] : '');
    $default_ucapi = $bbserver . '/ucenter';
    $default_appurl = $bbserver . substr($PHP_SELF, 0, strrpos($PHP_SELF, '/') - 8);
}

if ($method == 'show_license') {
    show_license();
} elseif ($method == 'env_check') {

    VIEW_OFF && function_check($func_items);

    env_check($env_items);

    dirfile_check($dirfile_items);

    show_env_result($env_items, $dirfile_items, $func_items, $filesock_items);
} elseif ($method == 'app_reg') {
    @include ROOT_PATH . CONFIG;
    if (getgpc('install_ucenter') == 'yes') {
        header("Location: index.php?step=3&install_ucenter=yes");
        die;
    }
    $submit = true;
    $error_msg = array();
    if (isset($form_app_reg_items) && is_array($form_app_reg_items)) {
        foreach ($form_app_reg_items as $key => $items) {
            $$key = getgpc($key, 'p');
            if (!isset($$key) || !is_array($$key)) {
                $submit = false;
                break;
            }
            foreach ($items as $k => $v) {
                $tmp = $$key;
                $$k = $tmp[$k];
                if (empty($$k) || !preg_match($v['reg'], $$k)) {
                    if (empty($$k) && !$v['required']) {
                        continue;
                    }
                    $submit = false;
                    VIEW_OFF or $error_msg[$key][$k] = 1;
                }
            }
        }
    } else {
        $submit = false;
    }
    if (VIEW_OFF) {

        show_msg('missing_parameter', '', 0);
    } else {

        show_form($form_app_reg_items, $error_msg);
    }
} elseif ($method == 'db_init') {

    $submit = true;

    $default_config = $_config = array();
    $default_configfile = './config/config_global_default.php';

    if (!file_exists(ROOT_PATH . $default_configfile)) {
        exit('config_global_default.php was lost, please reupload this  file.');
    } else {
        include ROOT_PATH . $default_configfile;
        $default_config = $_config;
    }

    if (file_exists(ROOT_PATH . CONFIG)) {
        include ROOT_PATH . CONFIG;
    } else {
        $_config = $default_config;
    }

    $dbhost = $_config['db'][1]['dbhost'];
    $dbname = $_config['db'][1]['dbname'];
    $dbpw = $_config['db'][1]['dbpw'];
    $dbuser = $_config['db'][1]['dbuser'];
    $tablepre = $_config['db'][1]['tablepre'];

    $adminemail = 'admin@admin.com';

    $error_msg = array();
    if (isset($form_db_init_items) && is_array($form_db_init_items)) {
        foreach ($form_db_init_items as $key => $items) {
            $$key = getgpc($key, 'p');
            if (!isset($$key) || !is_array($$key)) {
                $submit = false;
                break;
            }
            foreach ($items as $k => $v) {
                $tmp = $$key;
                $$k = $tmp[$k];
                if (empty($$k) || !preg_match($v['reg'], $$k)) {
                    if (empty($$k) && !$v['required']) {
                        continue;
                    }
                    $submit = false;
                    VIEW_OFF or $error_msg[$key][$k] = 1;
                }
            }
        }
    } else {
        $submit = false;
    }

    if ($submit && !VIEW_OFF && $_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($password != $password2) {
            $error_msg['admininfo']['password2'] = 1;
            $submit = false;
        }
        $forceinstall = isset($_POST['dbinfo']['forceinstall']) ? $_POST['dbinfo']['forceinstall'] : '';
        $dbname_not_exists = true;
        if (!empty($dbhost) && empty($forceinstall)) {
            $dbname_not_exists = check_db($dbhost, $dbuser, $dbpw, $dbname, $tablepre);
            if (!$dbname_not_exists) {
                $form_db_init_items['dbinfo']['forceinstall'] = array('type' => 'checkbox', 'required' => 0, 'reg' => '/^.*+/');
                $error_msg['dbinfo']['forceinstall'] = 1;
                $submit = false;
                $dbname_not_exists = false;
            }
        }
    }

    if ($submit) {

        $step = $step + 1;
        if (empty($dbname)) {
            show_msg('dbname_invalid', $dbname, 0);
        } else {
            $mysqlmode = function_exists("mysql_connect") ? 'mysql' : 'mysqli';
            $link = ($mysqlmode == 'mysql') ? @mysql_connect($dbhost, $dbuser, $dbpw) : new mysqli($dbhost, $dbuser, $dbpw);
            if (!$link) {
                $errno = ($mysqlmode == 'mysql') ? mysql_errno($link) : $link->errno;
                $error = ($mysqlmode == 'mysql') ? mysql_error($link) : $link->error;
                if ($errno == 1045) {
                    show_msg('database_errno_1045', $error, 0);
                } elseif ($errno == 2003) {
                    show_msg('database_errno_2003', $error, 0);
                } else {
                    show_msg('database_connect_error', $error, 0);
                }
            }
            $mysql_version = ($mysqlmode == 'mysql') ? mysql_get_server_info() : $link->server_info;
            if ($mysql_version > '4.1') {
                if ($mysqlmode == 'mysql') {
                    mysql_query("CREATE DATABASE IF NOT EXISTS `$dbname` DEFAULT CHARACTER SET " . DBCHARSET, $link);
                } else {
                    $link->query("CREATE DATABASE IF NOT EXISTS `$dbname` DEFAULT CHARACTER SET " . DBCHARSET);
                }
            } else {
                if ($mysqlmode == 'mysql') {
                    mysql_query("CREATE DATABASE IF NOT EXISTS `$dbname`", $link);
                } else {
                    $link->query("CREATE DATABASE IF NOT EXISTS `$dbname`");
                }
            }

            if (($mysqlmode == 'mysql') ? mysql_errno($link) : $link->errno) {
                show_msg('database_errno_1044', ($mysqlmode == 'mysql') ? mysql_error($link) : $link->error, 0);
            }
            if ($mysqlmode == 'mysql') {
                mysql_close($link);
            } else {
                $link->close();
            }
        }

        if (strpos($tablepre, '.') !== false || intval($tablepre{0})) {
            show_msg('tablepre_invalid', $tablepre, 0);
        }

        if ($username && $email && $password) {
            if (strlen($username) > 15 || preg_match("/^$|^c:\\con\\con$|　|[,\"\s\t\<\>&]|^Guest/is", $username)) {
                show_msg('admin_username_invalid', $username, 0);
            } elseif (!strstr($email, '@') || $email != stripslashes($email) || $email != dhtmlspecialchars($email)) {
                show_msg('admin_email_invalid', $email, 0);
            }
        } else {
            show_msg('admininfo_invalid', '', 0);
        }


        $uid = $adminuser['uid'];
        $authkey = substr(md5($_SERVER['SERVER_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . $dbhost . $dbuser . $dbpw . $dbname . $username . $password . $pconnect . substr($timestamp, 0, 6)), 8, 6) . random(10);
        $_config['db'][1]['dbhost'] = $dbhost;
        $_config['db'][1]['dbname'] = $dbname;
        $_config['db'][1]['dbpw'] = $dbpw;
        $_config['db'][1]['dbuser'] = $dbuser;
        $_config['db'][1]['tablepre'] = $tablepre;
        $_config['admincp']['founder'] = (string) $uid;
        $_config['security']['authkey'] = $authkey;
        $_config['cookie']['cookiepre'] = random(4) . '_';
        $_config['memory']['prefix'] = random(6) . '_';

        save_config_file(ROOT_PATH . CONFIG, $_config, $default_config);

        $db = new dbstuff;

        $db->connect($dbhost, $dbuser, $dbpw, $dbname, DBCHARSET);

        if (!VIEW_OFF) {
            show_header();
            show_install();
        }
        $sql = file_get_contents($sqlfile);
        $sql = str_replace("\r\n", "\n", $sql);

        runquery($sql);
        runquery($extrasql);

        $sql = file_get_contents(ROOT_PATH . './install/data/install_data.sql');
        $sql = str_replace("\r\n", "\n", $sql);
        runquery($sql);

        $onlineip = $_SERVER['REMOTE_ADDR'];
        $timestamp = time();
        $backupdir = substr(md5($_SERVER['SERVER_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . substr($timestamp, 0, 4)), 8, 6);
        $ret = false;
        if (is_dir(ROOT_PATH . 'data/backup')) {
            $ret = @rename(ROOT_PATH . 'data/backup', ROOT_PATH . 'data/backup_' . $backupdir);
        }
        if (!$ret) {
            @mkdir(ROOT_PATH . 'data/backup_' . $backupdir, 0777);
        }
        if (is_dir(ROOT_PATH . 'data/backup_' . $backupdir)) {
            $db->query("REPLACE INTO {$tablepre}common_setting (skey, svalue) VALUES ('backupdir', '$backupdir')");
        }

        $db->query("REPLACE INTO {$tablepre}common_setting (skey, svalue) VALUES ('adminemail', '$email')");

        install_extra_setting();

        $db->query("REPLACE INTO {$tablepre}common_setting (skey, svalue) VALUES ('backupdir', '" . $backupdir . "')");

        $db->query("REPLACE INTO {$tablepre}admincp_member (uid, username, password, allowadminsubject, allowadminuser, allowadminscore) VALUES ('$uid', '$username', '$password', '1', '1', '1');");

        install_data($username, $uid);

        $testdata = $portalstatus = 1;
        $groupstatus = $homestatus = 0;

        if ($testdata) {
            install_testdata($username, $uid);
        }

        $yearmonth = date('Ym_', time());
        loginit($yearmonth . 'ratelog');
        loginit($yearmonth . 'illegallog');
        loginit($yearmonth . 'modslog');
        loginit($yearmonth . 'cplog');
        loginit($yearmonth . 'errorlog');
        loginit($yearmonth . 'banlog');

        dir_clear(ROOT_PATH . './data/template');
        dir_clear(ROOT_PATH . './data/cache');

        foreach ($serialize_sql_setting as $k => $v) {
            $v = addslashes(serialize($v));
            $db->query("REPLACE INTO {$tablepre}common_setting VALUES ('$k', '$v')");
        }

        touch($lockfile);
        VIEW_OFF && show_msg('initdbresult_succ');

        if (!VIEW_OFF) {
            echo '<script type="text/javascript">function setlaststep() {document.getElementById("laststep").disabled=false;window.location=\'index.php?method=ext_info\';}</script><script type="text/javascript">setTimeout(function(){window.location=\'index.php?method=ext_info\'}, 30000);</script><iframe src="../misc.php?mod=initsys" style="display:none;" onload="setlaststep()"></iframe>' . "\r\n";
            show_footer();
        }
    }
    if (VIEW_OFF) {

        show_msg('missing_parameter', '', 0);
    } else {
        show_form($form_db_init_items, $error_msg);
    }
} elseif ($method == 'ext_info') {
    @touch($lockfile);
    if (VIEW_OFF) {
        show_msg('ext_info_succ');
    } else {
        show_header();
        echo '</div><div class="main" style="margin-top: -123px;padding-left:30px"><span id="platformIntro"></span>';
        echo '<img src="./images/success.jpg"  style="width:634px;height:411px;"/><br/>';
        echo '<p align="right"><a href="' . $default_appurl . '">' . $lang['install_finish'] . '</a></p><br />';
        echo '</div>';
        show_footer();
    }
} elseif ($method == 'install_check') {

    if (file_exists($lockfile)) {
        show_msg('installstate_succ');
    } else {
        show_msg('lock_file_not_touch', $lockfile, 0);
    }
} elseif ($method == 'tablepre_check') {

    $dbinfo = getgpc('dbinfo');
    extract($dbinfo);
    if (check_db($dbhost, $dbuser, $dbpw, $dbname, $tablepre)) {
        show_msg('tablepre_not_exists', 0);
    } else {
        show_msg('tablepre_exists', $tablepre, 0);
    }
}