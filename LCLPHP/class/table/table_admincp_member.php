<?php

if (!defined('IN_LCL')) {
    exit('Aecsse Denied');
}

class table_admincp_member extends lcl_table {

    public function __construct() {
        $this->_table = 'admincp_member';
        $this->_pk = 'uid';
        parent::__construct();
    }

    public function update_cpgroupid_by_cpgroupid($val, $data) {
        if (!is_array($data)) {
            return null;
        }
        return DB::update('admincp_member', $data, DB::field('cpgroupid', $val));
    }

    public function count_by_uid($uid) {
        return DB::result_first("SELECT count(*) FROM %t WHERE uid=%d", array($this->_table, $uid));
    }

    public function fetch_all_uid_by_gid_perm($gid, $perm) {
        return DB::fetch_all("SELECT uid FROM %t am LEFT JOIN %t ap ON am.cpgroupid=ap.cpgroupid WHERE am.cpgroupid=%d OR ap.perm=%s", array($this->_table, 'admincp_perm', $gid, $perm));
    }

    public function fetch_perm_by_uid_perm($uid, $perm) {
        return DB::result_first("SELECT ap.perm FROM %t am LEFT JOIN %t ap ON ap.cpgroupid=am.cpgroupid WHERE am.uid=%d AND ap.perm=%s", array($this->_table, 'admincp_perm', $uid, $perm));
    }

    public function fetch_all() {
        return DB::fetch_all('select * from %t order by uid desc', array($this->_table));
    }

    public function fetch_all_count() {
        $list = DB::fetch_all('select * from %t order by uid desc', array($this->_table));
        $totail = count($list);
        return $totail;
    }

    public function fetch_limit($start, $count) {
        return DB::fetch_all('select * from %t order by uid desc limit %d,%d', array($this->_table, $start, $count));
    }

    public function fetch_by_id($id) {
        return DB::fetch_first('select * from %t where uid=%d', array($this->_table, $id));
    }

    public function fetch_by_uid($uid) {
        return DB::fetch_first('select * from %t where uid=%d', array($this->_table, $uid));
    }

    public function fetch_by_login($username, $password) {
        return DB::fetch_first('select * from %t where username=%s and password=%s', array($this->_table, $username, $password));
    }

    public function fetch_by_uids($uids) {
        return DB::fetch_all("SELECT * FROM " . DB::table($this->_table) . " where uid in(" . $uids . ") ");
    }

    public function update_by_uid($uid, $data) {
        return DB::update($this->_table, $data, 'uid=' . $uid);
    }

    public function delete_by_id($id) {
        return DB::delete($this->_table, 'id=' . $id);
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