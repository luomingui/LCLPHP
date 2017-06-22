<?php

/**
 * +----------------------------------------------------------------------
 * | LCLPHP [ This is a freeware ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 罗敏贵 <e-mail:minguiluo@163.com> <QQ:271391233>
 * +----------------------------------------------------------------------
 * | 文件功能：错误扩展类
 * +----------------------------------------------------------------------
 */
if (!defined('IN_LCL')) {
    exit('Access Denied');
}

class DbException extends Exception {

    public $sql;

    public function __construct($message, $code = 0, $sql = '') {
        $this->sql = $sql;
        parent::__construct($message, $code);
    }

    public function getSql() {
        return $this->sql;
    }

}

?>