<?php

/**
 * +----------------------------------------------------------------------
 * | LCLPHP [ This is a freeware ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 罗敏贵 <e-mail:minguiluo@163.com> <QQ:271391233>
 * +----------------------------------------------------------------------
 * | 文件功能：自动加载类
 * +----------------------------------------------------------------------
 */
define('IN_LCL', true);
define('LCL_ROOT', substr(dirname(__FILE__), 0, -5));
define('LCL_CORE_DEBUG', false);
define('LCL_TABLE_EXTENDABLE', false);

set_exception_handler(array('core', 'handleException'));

if (LCL_CORE_DEBUG) {
    set_error_handler(array('core', 'handleError'));
    register_shutdown_function(array('core', 'handleShutdown'));
}
if (function_exists('spl_autoload_register')) {
    spl_autoload_register(array('core', 'autoload'));
} else {

    function __autoload($class) {
        return core::autoload($class);
    }

}

C::creatapp();

class core {

    private static $_tables;
    private static $_imports;
    private static $_app;
    private static $_memory;

    public static function app() {
        return self::$_app;
    }

    public static function creatapp() {
        if (!is_object(self::$_app)) {
            self::$_app = lcl_application::instance();
        }
        return self::$_app;
    }

    public static function import($name, $folder = '', $force = true) {
        $key = $folder . $name;
        if (!isset(self::$_imports[$key])) {
            $path = LCL_ROOT . '/' . $folder;
            if (strpos($name, '/') !== false) {
                $pre = basename(dirname($name));
                $filename = dirname($name) . '/' . $pre . '_' . basename($name) . '.php';
            } else {
                $filename = $name . '.php';
            }
            if (is_file($path . '/' . $filename)) {
                include $path . '/' . $filename;
                self::$_imports[$key] = true;
                return true;
            } elseif (!$force) {
                return false;
            } else {
                throw new Exception('Oops! System file lost: ' . $filename);
            }
        }
        return true;
    }

    public static function autoload($class) {
        $class = strtolower($class);
        if (strpos($class, '_') !== false) {
            list($folder) = explode('_', $class);
            $file = 'class/' . $folder . '/' . substr($class, strlen($folder) + 1);
        } else {
            $file = 'class/' . $class;
        }
        try {
            self::import($file);
            return true;
        } catch (Exception $exc) {
            $trace = $exc->getTrace();
            foreach ($trace as $log) {
                if (empty($log['class']) && $log['function'] == 'class_exists') {
                    return false;
                }
            }
            lcl_error::exception_error($exc);
        }
    }

    public static function t($name) {
        return self::_make_obj($name, 'table', LCL_TABLE_EXTENDABLE);
    }

    protected static function _make_obj($name, $type, $extendable = false, $p = array()) {
        $pluginid = null;
        if ($name[0] === '#') {
            list(, $pluginid, $name) = explode('#', $name);
        }
        $cname = $type . '_' . $name;
        if (!isset(self::$_tables[$cname])) {
            if (!class_exists($cname, false)) {
                self::import(($pluginid ? 'plugin/' . $pluginid : 'class') . '/' . $type . '/' . $name);
            }
            if ($extendable) {
                self::$_tables[$cname] = new discuz_container();
                switch (count($p)) {
                    case 0: self::$_tables[$cname]->obj = new $cname();
                        break;
                    case 1: self::$_tables[$cname]->obj = new $cname($p[1]);
                        break;
                    case 2: self::$_tables[$cname]->obj = new $cname($p[1], $p[2]);
                        break;
                    case 3: self::$_tables[$cname]->obj = new $cname($p[1], $p[2], $p[3]);
                        break;
                    case 4: self::$_tables[$cname]->obj = new $cname($p[1], $p[2], $p[3], $p[4]);
                        break;
                    case 5: self::$_tables[$cname]->obj = new $cname($p[1], $p[2], $p[3], $p[4], $p[5]);
                        break;
                    default: $ref = new ReflectionClass($cname);
                        self::$_tables[$cname]->obj = $ref->newInstanceArgs($p);
                        unset($ref);
                        break;
                }
            } else {
                self::$_tables[$cname] = new $cname();
            }
        }
        return self::$_tables[$cname];
    }

    public static function memory() {
        if (!self::$_memory) {
            self::$_memory = new lcl_memory();
            self::$_memory->init(self::app()->config['memory']);
        }
        return self::$_memory;
    }

    public static function handleException($exception) {
        lcl_error::exception_error($exception);
    }

    public static function handleError($errno, $errstr, $errfile, $errline) {
        if ($errno & LCL_CORE_DEBUG) {
            lcl_error::system_error($errstr, false, true, false);
        }
    }

    public static function handleShutdown() {
        if (($error = error_get_last()) && $error['type'] & LCL_CORE_DEBUG) {
            lcl_error::system_error($error['message'], false, true, false);
        }
    }

}

class C extends core {

}

class DB extends lcl_database {

}

/* * begin 多语言包切换* */
require_once './class/lcl/lcl_locale.php';
/* * end 多语言包切换* */
?>