<?php

/**
 * +----------------------------------------------------------------------
 * | LCLPHP [ This is a freeware ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 罗敏贵 <e-mail:minguiluo@163.com> <QQ:271391233>
 * +----------------------------------------------------------------------
 * | 文件功能：数据库操作
 * +----------------------------------------------------------------------
 */
if (!defined('IN_LCL')) {
    exit('Access Denied');
}
/*
  函数	功能
  DB::table($tablename)	获取正确带前缀的表名，转换数据库句柄，
  DB::delete($tablename, 条件,条数限制)	删除表中的数据
  DB::insert($tablename, 数据(数组),是否返回插入ID,是否是替换式,是否silent)	插入数据操作
  DB::update($tablename, 数据(数组)条件)	更新操作
  DB::fetch(查询后的资源)	从结果集中取关联数组，注意如果结果中的两个或以上的列具有相同字段名，最后一列将优先。
  DB::fetch_first($sql)	取查询的第一条数据fetch
  DB::fetch_all($sql)	查询并fetch
  DB::result_first($sql)	查询结果集的第一个字段值
  DB::query($sql)	普通查询
  DB::num_rows(查询后的资源)	获得记录集总条数
  DB::_execute(命令,参数)	执行mysql类的命令
  DB::limit(n,n)	返回限制字串
  DB::field(字段名, $pid)	返回条件，如果为数组则返回 in 条件
  DB::order(别名, 方法)	排序
  注意：由于 X1.5 里增加了SQL的安全性检测。因此，如果你的SQL语句里包含以下开头的函数 load_file，hex，substring，if，ord，char。 或者包含以下操作 intooutfile，intodumpfile，unionselect，(select')都将被拒绝执行。

  替换参数	功能
  %t	表名，
  %s	字串，如果是数组就序列化
  %f	按 %F 的样式格式化字串
  %d	整数
  %i	不做处理
  %n	若为空即为0，若为数组，就用',' 分割,否则加引号
  函数	功能
  C::t($tablename')->count()	获取表所有行数
  C::t($tablename')->update(键值,$data)	更新键值数据
  C::t($tablename')->delete(键值)	删除键值数据
  C::t($tablename')->truncate()	清空表
  C::t($tablename')->insert($data, $return_insert_id,$replace)	插入数据
  C::t($tablename')->fetch_all($ids)	fetch 数据，可以是单一键值或者多个键值数组
  C::t($tablename')->fetch_all_field()	fetch所有的字段名表
  C::t($tablename')->range($start, $limit, $sort)	fetch值域范围
  C::t($tablename')->optimize()	优化表


 */

class db_driver_mysql {

    var $tablepre;
    var $version = '';
    var $drivertype = 'mysql';
    var $querynum = 0;
    var $slaveid = 0;
    var $curlink;
    var $link = array();
    var $config = array();
    var $sqldebug = array();
    var $map = array();

    function db_mysql($config = array()) {
        if (!empty($config)) {
            $this->set_config($config);
        }
    }

    function set_config($config) {
        $this->config = &$config;
        $this->tablepre = $config['1']['tablepre'];
        if (!empty($this->config['map'])) {
            $this->map = $this->config['map'];
            for ($i = 1; $i <= 100; $i++) {
                if (isset($this->map['forum_thread'])) {
                    $this->map['forum_thread_' . $i] = $this->map['forum_thread'];
                }
                if (isset($this->map['forum_post'])) {
                    $this->map['forum_post_' . $i] = $this->map['forum_post'];
                }
                if (isset($this->map['forum_attachment']) && $i <= 10) {
                    $this->map['forum_attachment_' . ($i - 1)] = $this->map['forum_attachment'];
                }
            }
            if (isset($this->map['common_member'])) {
                $this->map['common_member_archive'] =
                        $this->map['common_member_count'] = $this->map['common_member_count_archive'] =
                        $this->map['common_member_status'] = $this->map['common_member_status_archive'] =
                        $this->map['common_member_profile'] = $this->map['common_member_profile_archive'] =
                        $this->map['common_member_field_forum'] = $this->map['common_member_field_forum_archive'] =
                        $this->map['common_member_field_home'] = $this->map['common_member_field_home_archive'] =
                        $this->map['common_member_validate'] = $this->map['common_member_verify'] =
                        $this->map['common_member_verify_info'] = $this->map['common_member'];
            }
        }
    }

    function connect($serverid = 1) {

        if (empty($this->config) || empty($this->config[$serverid])) {
            $this->halt('config_db_not_found');
        }

        $this->link[$serverid] = $this->_dbconnect(
                $this->config[$serverid]['dbhost'], $this->config[$serverid]['dbuser'], $this->config[$serverid]['dbpw'], $this->config[$serverid]['dbcharset'], $this->config[$serverid]['dbname'], $this->config[$serverid]['pconnect']
        );
        $this->curlink = $this->link[$serverid];
    }

    function _dbconnect($dbhost, $dbuser, $dbpw, $dbcharset, $dbname, $pconnect, $halt = true) {

        if ($pconnect) {
            $link = @mysql_pconnect($dbhost, $dbuser, $dbpw, MYSQL_CLIENT_COMPRESS);
        } else {
            $link = @mysql_connect($dbhost, $dbuser, $dbpw, 1, MYSQL_CLIENT_COMPRESS);
        }
        if (!$link) {
            $halt && $this->halt('notconnect', $this->errno());
        } else {
            $this->curlink = $link;
            if ($this->version() > '4.1') {
                $dbcharset = $dbcharset ? $dbcharset : $this->config[1]['dbcharset'];
                $serverset = $dbcharset ? 'character_set_connection=' . $dbcharset . ', character_set_results=' . $dbcharset . ', character_set_client=binary' : '';
                $serverset .= $this->version() > '5.0.1' ? ((empty($serverset) ? '' : ',') . 'sql_mode=\'\'') : '';
                $serverset && mysql_query("SET $serverset", $link);
            }
            $dbname && @mysql_select_db($dbname, $link);
        }
        return $link;
    }

    function table_name($tablename) {
        if (!empty($this->map) && !empty($this->map[$tablename])) {
            $id = $this->map[$tablename];
            if (!$this->link[$id]) {
                $this->connect($id);
            }
            $this->curlink = $this->link[$id];
        } else {
            $this->curlink = $this->link[1];
        }
        return $this->tablepre . $tablename;
    }

    function select_db($dbname) {
        return mysql_select_db($dbname, $this->curlink);
    }

    function fetch_array($query, $result_type = MYSQL_ASSOC) {
        if ($result_type == 'MYSQL_ASSOC')
            $result_type = MYSQL_ASSOC;
        return mysql_fetch_array($query, $result_type);
    }

    function fetch_first($sql) {
        return $this->fetch_array($this->query($sql));
    }

    function result_first($sql) {
        return $this->result($this->query($sql), 0);
    }

    public function query($sql, $silent = false, $unbuffered = false) {
        if (defined('LCL_DEBUG') && LCL_DEBUG) {
            $starttime = microtime(true);
        }

        if ('UNBUFFERED' === $silent) {
            $silent = false;
            $unbuffered = true;
        } elseif ('SILENT' === $silent) {
            $silent = true;
            $unbuffered = false;
        }

        $func = $unbuffered ? 'mysql_unbuffered_query' : 'mysql_query';

        if (!($query = $func($sql, $this->curlink))) {
            if (in_array($this->errno(), array(2006, 2013)) && substr($silent, 0, 5) != 'RETRY') {
                $this->connect();
                return $this->query($sql, 'RETRY' . $silent);
            }
            if (!$silent) {
                $this->halt($this->error(), $this->errno(), $sql);
            }
        }

        if (defined('LCL_DEBUG') && LCL_DEBUG) {
            $this->sqldebug[] = array($sql, number_format((microtime(true) - $starttime), 6), debug_backtrace(), $this->curlink);
        }

        $this->querynum++;
        return $query;
    }

    function affected_rows() {
        return mysql_affected_rows($this->curlink);
    }

    function error() {
        return (($this->curlink) ? mysql_error($this->curlink) : mysql_error());
    }

    function errno() {
        return intval(($this->curlink) ? mysql_errno($this->curlink) : mysql_errno());
    }

    function result($query, $row = 0) {
        $query = @mysql_result($query, $row);
        return $query;
    }

    function num_rows($query) {
        $query = mysql_num_rows($query);
        return $query;
    }

    function num_fields($query) {
        return mysql_num_fields($query);
    }

    function free_result($query) {
        return mysql_free_result($query);
    }

    function insert_id() {
        return ($id = mysql_insert_id($this->curlink)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
    }

    function fetch_row($query) {
        $query = mysql_fetch_row($query);
        return $query;
    }

    function fetch_fields($query) {
        return mysql_fetch_field($query);
    }

    function version() {
        if (empty($this->version)) {
            $this->version = mysql_get_server_info($this->curlink);
        }
        return $this->version;
    }

    function escape_string($str) {
        return mysql_escape_string($str);
    }

    function close() {
        return mysql_close($this->curlink);
    }

    function halt($message = '', $code = 0, $sql = '') {
        throw new DbException($message, $code, $sql);
    }

}

?>