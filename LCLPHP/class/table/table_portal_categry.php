<?php

if (!defined('IN_LCL')) {
    exit('Aecsse Denied');
}

class table_portal_categry extends lcl_table {

    protected $_now;

    public function __construct() {
        $this->_table = 'portal_categry';
        $this->_pk = 'catid';
        $this->_now = time();
        parent::__construct();
    }

    public function fetch_all() {
        return DB::fetch_all('select * from %t  order by catid desc', array($this->_table));
    }

    public function fetch_all_count() {
        $list = DB::fetch_all('select * from %t order by catid desc', array($this->_table));
        $totail = count($list);
        return $totail;
    }

    public function fetch_limit($start, $count) {
        return DB::fetch_all('select * from %t order by catid desc limit %d,%d', array($this->_table, $start, $count));
    }

    public function fetch_by_catid($catid) {
        $item = DB::fetch_first('select * from %t where catid=%d', array($this->_table, $catid));
        return $item;
    }

    public function fetch_by_upid($upid = 0) {
        $item = DB::fetch_all('select * from %t where upid=%d order by displayorder', array($this->_table, $upid));
        return $item;
    }

    public function update_by_catid($catid, $data) {
        return DB::update($this->_table, $data, 'catid=' . $catid);
    }

    public function delete_by_catid($catid) {
        return DB::delete($this->_table, 'catid=' . $catid);
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

    public function range($start = 0, $limit = 0) {
        $data = array();
        $query = DB::query('SELECT * FROM ' . DB::table($this->_table) . ' ORDER BY displayorder,catid' . DB::limit($start, $limit));
        while ($value = DB::fetch($query)) {
            $value['catname'] = dhtmlspecialchars($value['catname']);
            $data[$value['catid']] = $value;
        }
        return $data;
    }

}
?>

