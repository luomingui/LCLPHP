<?php

if (!defined('IN_LCL')) {
    exit('Access Denied');
}

class table_admincp_group extends lcl_table {

    public function __construct() {

        $this->_table = 'admincp_group';
        $this->_pk = 'cpgroupid';

        parent::__construct();
    }

    public function fetch_all() {
        return DB::fetch_all('select * from %t  order by cpgroupid', array($this->_table));
    }

    public function fetch_by_cpgroupid($cpgroupid) {
        $item = DB::fetch_first('select * from %t where cpgroupid=%d', array($this->_table, $cpgroupid));
        return $item;
    }

    public function fetch_by_cpgroupname($name) {
        return $name ? DB::fetch_first('SELECT * FROM %t WHERE cpgroupname=%s', array($this->_table, $name)) : null;
    }

    public function update_by_cpgroupid($cpgroupid, $data) {
        return DB::update($this->_table, $data, 'cpgroupid=' . $cpgroupid);
    }

    public function delete_by_cpgroupid($cpgroupid) {
        return DB::delete($this->_table, 'cpgroupid=' . $cpgroupid);
    }

}

?>