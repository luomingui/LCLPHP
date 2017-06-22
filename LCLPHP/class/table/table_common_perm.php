<?php

if (!defined('IN_LCL')) {
    exit('Aecsse Denied');
}

class table_common_perm extends lcl_table {

    protected $_now;

    public function __construct() {
        $this->_table = 'common_perm';
        $this->_pk = 'permid';
        $this->_now = time();
        parent::__construct();
    }

    public function fetch_all() {
        return DB::fetch_all('select * from %t  order by ' . $this->_pk . ' desc', array($this->_table));
    }

    public function fetch_all_count() {
        $list = DB::fetch_all('select * from %t order by ' . $this->_pk . ' desc', array($this->_table));
        $totail = count($list);
        return $totail;
    }

    public function fetch_limit($start, $count) {
        return DB::fetch_all('select * from %t order by ' . $this->_pk . ' desc limit %d,%d', array($this->_table, $start, $count));
    }

    public function fetch_by_id($id) {
        $item = DB::fetch_first('select * from %t where ' . $this->_pk . '=%d', array($this->_table, $id));
        return $item;
    }

    public function update_by_id($id, $data) {
        return DB::update($this->_table, $data, '' . $this->_pk . '=' . $id);
    }

    public function delete_by_id($id) {
        return DB::delete($this->_table, '' . $this->_pk . '=' . $id);
    }

    public function insert($data, $return_insert_id = true) {
        parent::insert($data, $return_insert_id, false, false);
    }

}

?>