<?php

/**
 * +----------------------------------------------------------------------
 * | LCLPHP [ This is a freeware ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 罗敏贵 <e-mail:minguiluo@163.com> <QQ:271391233>
 * +----------------------------------------------------------------------
 * | 文件功能：日志帮助类
 * +----------------------------------------------------------------------
 */
if (!defined('IN_LCL')) {
    exit('Access Denied');
}

class helper_log {

    public static function runlog($file, $message, $halt = 0) {
        global $_G;
        $username = $_G['username'];
        $nowurl = $_SERVER['REQUEST_URI'] ? $_SERVER['REQUEST_URI'] : ($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME']);
        $log = dgmdate($_G['timestamp'], 'Y-m-d H:i:s') . "\t" . $_G['clientip'] . "\t$username\t{$nowurl}\t" . str_replace(array("\r", "\n"), array(' ', ' '), trim($message)) . "\n";
        helper_log::writelog($file, $log);
        if ($halt) {
            exit();
        }
    }

    public static function writecplog() {
        global $_G;
        $extralog = helper_log::implodearray(array('GET' => $_GET, 'POST' => $_POST), array('formhash', 'submit', 'addsubmit', 'admin_password', 'sid', 'action'));
        writelog('cplog', implode("\t", helper_log::clearlogstring(array($_G['timestamp'], $_G['username'], $_G['adminid'], $_G['clientip'], getgpc('action'), $extralog))));
    }

    public static function implodearray($array, $skip = array()) {
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

    public static function clearlogstring($str) {
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

    public static function writelog($file, $log) {
        global $_G;
        $yearmonth = dgmdate(TIMESTAMP, 'Ym', 8);
        $logdir = LCL_ROOT . './data/log/';
        $logfile = $logdir . $yearmonth . '_' . $file . '.php';
        if (@filesize($logfile) > 2048000) {
            $dir = opendir($logdir);
            $length = strlen($file);
            $maxid = $id = 0;
            while ($entry = readdir($dir)) {
                if (strpos($entry, $yearmonth . '_' . $file) !== false) {
                    $id = intval(substr($entry, $length + 8, -4));
                    $id > $maxid && $maxid = $id;
                }
            }
            closedir($dir);

            $logfilebak = $logdir . $yearmonth . '_' . $file . '_' . ($maxid + 1) . '.php';
            @rename($logfile, $logfilebak);
        }
        if ($fp = @fopen($logfile, 'a')) {
            @flock($fp, 2);
            if (!is_array($log)) {
                $log = array($log);
            }
            foreach ($log as $tmp) {
                fwrite($fp, "<?PHP exit;?>\t" . str_replace(array('<?', '?>'), '', $tmp) . "\n");
            }
            fclose($fp);
        }
    }

    public static function useractionlog($uid, $action) {
        $uid = intval($uid);
        if (empty($uid) || empty($action)) {
            return false;
        }
        $action = getuseraction($action);
        C::t('common_member_action_log')->insert(array('uid' => $uid, 'action' => $action, 'dateline' => TIMESTAMP));
        return true;
    }

    public static function getuseraction($var) {
        $value = false;
        $ops = array('tid', 'pid', 'blogid', 'picid', 'doid', 'sid', 'aid', 'uid_cid', 'blogid_cid', 'sid_cid', 'picid_cid', 'aid_cid', 'topicid_cid', 'pmid');
        if (is_numeric($var)) {
            $value = isset($ops[$var]) ? $ops[$var] : false;
        } else {
            $value = array_search($var, $ops);
        }
        return $value;
    }

}

?>