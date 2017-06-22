<?php

/**
 * +----------------------------------------------------------------------
 * | LCLPHP [ This is a freeware ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 罗敏贵 <e-mail:minguiluo@163.com> <QQ:271391233>
 * +----------------------------------------------------------------------
 * | 文件功能：后台帮助类
 * +----------------------------------------------------------------------
 */
if (!defined('IN_LCL')) {
    exit('Access Denied');
}

class lcl_admincp {

    var $core = null;
    var $script = null;
    var $adminuser = array();
    var $perms = null;
    var $panel = 1;
    var $isfounder = false;
    var $cpaccess = 0;
    var $sessionlife = 1800;
    var $sessionlimit = 0;

    function &instance() {
        static $object;
        if (empty($object)) {
            $object = new lcl_admincp();
        }
        return $object;
    }

    function __construct() {
        ;
    }

    function init() {

        if (empty($this->core) || !is_object($this->core)) {
            exit('No LCL core found');
        }

        $this->adminuser = & $this->core->var['admins'];
        $this->isfounder = $this->checkfounder($this->adminuser);

        $this->check_cpaccess();
        $this->writecplog();
    }

    function checkfounder($user) {
        if (@$user['uid'] == 1 && @$user['username'] == 'admin') {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function check_cpaccess() {
        global $_G;
        $session = array();
        if (!@$this->adminuser['uid']) {
            $this->cpaccess = 0;
        } else {
            if (!$this->isfounder) {
                $session = C::t('admincp_member')->fetch($this->adminuser['uid']);
            }
            if (empty($session)) {
                $this->cpaccess = $this->isfounder ? 1 : -2;
            } elseif ($session && empty($session['uid'])) {
                $this->cpaccess = 1;
            } elseif ($session['dateline'] < $this->sessionlimit) {
                $this->cpaccess = 1;
            } else {
                $this->cpaccess = -1;
            }
        }
        if ($this->cpaccess == 0) {
            require $this->admincpfile('login');
            exit();
        }
    }

    function writecplog() {
        global $_G;
        $extralog = implodearray(array('GET' => $_GET, 'POST' => $_POST), array('formhash', 'submit', 'addsubmit', 'admin_password', 'sid', 'action'));
        writelog('cplog', implode("\t", clearlogstring(array($_G['timestamp'], $_G['username'], $_G['adminid'], $_G['clientip'], getgpc('action'), $extralog))));
    }

    function allow($action, $operation, $do) {

        if ($this->perms === null) {
            $this->load_admin_perms();
        }

        if (isset($this->perms['all'])) {
            return $this->perms['all'];
        }

        if (!empty($_POST) && !array_key_exists('_allowpost', $this->perms) && $action . '_' . $operation != 'misc_custommenu') {
            return false;
        }
        $this->perms['misc_custommenu'] = 1;

        $key = $action;
        if (isset($this->perms[$key])) {
            return $this->perms[$key];
        }
        $key = $action . '_' . $operation;
        if (isset($this->perms[$key])) {
            return $this->perms[$key];
        }
        $key = $action . '_' . $operation . '_' . $do;
        if (isset($this->perms[$key])) {
            return $this->perms[$key];
        }
        return false;
    }

    function load_admin_perms() {

        $this->perms = array();

        if (!$this->isfounder) {
            if ($this->adminuser['cpgroupid']) {
                foreach (C::t('admincp_perm')->fetch_all_by_cpgroupid($this->adminuser['cpgroupid']) as $perm) {
                    if (empty($this->adminuser['customperm'])) {
                        $this->perms[$perm['perm']] = true;
                    } elseif (!in_array($perm['perm'], (array) $this->adminuser['customperm'])) {
                        $this->perms[$perm['perm']] = true;
                    }
                }
            } else {
                $this->perms['all'] = true;
            }
        } else {
            $this->perms['all'] = true;
        }
    }

    function admincpfile($action) {
        $file = './admincp/admincp_' . $action . '.php';
        if (file_exists($file)) {
            return $file;
        } else {
            cpmsg("The file $file does not exist", '', 'error');
        }
    }

    function show_admincp_main() {
        $this->do_request('frame');
    }

    function do_admin_logout() {
        dsetcookie('adminid', '');
        dsetcookie('adminname', '');
    }

    function do_admin_password() {
        $this->admincpfile('password');
    }

    function show_no_access() {
        cpmsg('action_noaccess', '', 'error');
    }

    function do_request($action) {

        global $_G;

        $lang = lang('admincp');
        $title = 'cplog_' . getgpc('action') . (getgpc('operation') ? '_' . getgpc('operation') : '');
        $operation = getgpc('operation');
        $do = getgpc('do');
        $sid = $_G['sid'];
        if ($action == 'frame' || $this->allow($action, $operation, $do)) {
            $file = './admincp/admincp_' . $action . '.php';
            if (file_exists($file)) {
                return $file;
            } else {
                cpmsg("The file $file does not exist", '', 'error');
            }
        } else {
            cpmsg('action_noaccess', '', 'error');
        }
    }

}

