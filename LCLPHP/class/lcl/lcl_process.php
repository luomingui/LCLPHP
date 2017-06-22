<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: discuz_process.php 28412 2012-02-29 06:14:48Z cnteacher $
 */
if (!defined('IN_LCL')) {
    exit('Access Denied');
}

class lcl_process {

    public static function islocked($process, $ttl = 0) {
        $ttl = $ttl < 1 ? 600 : intval($ttl);
        return lcl_process::_status('get', $process) || lcl_process::_find($process, $ttl);
    }

    public static function unlock($process) {
        lcl_process::_status('rm', $process);
        lcl_process::_cmd('rm', $process);
    }

    private static function _status($action, $process) {
        static $plist = array();
        switch ($action) {
            case 'set' : $plist[$process] = true;
                break;
            case 'get' : return !empty($plist[$process]);
                break;
            case 'rm' : $plist[$process] = null;
                break;
            case 'clear' : $plist = array();
                break;
        }
        return true;
    }

    private static function _find($name, $ttl) {

        if (!lcl_process::_cmd('get', $name)) {
            lcl_process::_cmd('set', $name, $ttl);
            $ret = false;
        } else {
            $ret = true;
        }
        lcl_process::_status('set', $name);
        return $ret;
    }

    private static function _cmd($cmd, $name, $ttl = 0) {
        static $allowmem;
        if ($allowmem === null) {
            $mc = memory('check');
            $allowmem = $mc == 'memcache' || $mc == 'redis';
        }
        if ($allowmem) {
            return lcl_process::_process_cmd_memory($cmd, $name, $ttl);
        } else {
            return lcl_process::_process_cmd_db($cmd, $name, $ttl);
        }
    }

    private static function _process_cmd_memory($cmd, $name, $ttl = 0) {
        $ret = '';
        switch ($cmd) {
            case 'set' :
                $ret = memory('set', 'process_lock_' . $name, time(), $ttl);
                break;
            case 'get' :
                $ret = memory('get', 'process_lock_' . $name);
                break;
            case 'rm' :
                $ret = memory('rm', 'process_lock_' . $name);
        }
        return $ret;
    }

    private static function _process_cmd_db($cmd, $name, $ttl = 0) {
        $ret = '';
        switch ($cmd) {
            case 'set':
                $ret = C::t('common_process')->insert(array('processid' => $name, 'expiry' => time() + $ttl), FALSE, true);
                break;
            case 'get':
                $ret = C::t('common_process')->fetch($name);
                if (empty($ret) || $ret['expiry'] < time()) {
                    $ret = false;
                } else {
                    $ret = true;
                }
                break;
            case 'rm':
                $ret = C::t('common_process')->delete_process($name, time());
                break;
        }
        return $ret;
    }

}

?>