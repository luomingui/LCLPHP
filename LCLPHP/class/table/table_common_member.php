<?php 
 
/** 
 * +---------------------------------------------------------------------- 
 * | LCLPHP [ This is a freeware ] 
 * +---------------------------------------------------------------------- 
 * | Copyright (c) 2019 All rights reserved. 
 * +---------------------------------------------------------------------- 
 * | Author: luomingui <e-mail:minguiluo@163.com> <QQ:34178394> 
 * +---------------------------------------------------------------------- 
 * | filefunctio：lcl_common_member 
 * +---------------------------------------------------------------------- 
 */ 
if (!defined('IN_LCL')) { 
    exit('Aecsse Denied'); 
} 
 
class table_common_member extends lcl_table { 
 
    protected $_now; 
 
    public function __construct() { 
        $this->_table = 'common_member'; 
        $this->_pk = 'uid'; 
        $this->_now = time(); 
        parent::__construct(); 
    } 
 
    public function fetch_all() { 
        return DB::fetch_all('select * from %t  order by uid desc', array($this->_table)); 
    } 
 
    public function fetch_all_count() { 
        $list = DB::fetch_all('select * from %t order by uid desc', array($this->_table)); 
        $totail = count($list); 
        return $totail; 
    } 
 
    public function fetch_limit($start, $count) { 
        return DB::fetch_all('select * from %t order by uid desc limit %d,%d', array($this->_table, $start, $count)); 
    } 
 
    public function fetch_by_uid($uid) { 
        $item = DB::fetch_first('select * from %t where uid=%d', array($this->_table, $uid)); 
        return $item; 
    } 
 
 
    public function update_by_uid($uid, $data) { 
        return DB::update($this->_table, $data, 'uid=' . $uid); 
    } 
 
    public function delete_by_uid($uid) { 
        return DB::delete($this->_table, 'uid=' . $uid); 
    } 
 
    public function fetch_all_by_sql($where, $order = '', $start = 0, $limit = 0, $count = 0, $alias = '') { 
        $where = $where && !is_array($where) ? " WHERE $where" : ''; 
        if (is_array($order)) { 
            $order = ''; 
        } 
        if ($count) { 
            return DB::result_first('SELECT count(*) FROM ' . DB::table($this->_table) . '  %i %i %i ' . DB::limit($start, $limit), array($alias, $where, $order)); 
        } 
        return DB::fetch_all('SELECT * FROM ' . DB::table($this->_table) . ' %i %i %i ' . DB::limit($start, $limit), array($alias, $where, $order)); 
    } 
 
} 
 
?> 

