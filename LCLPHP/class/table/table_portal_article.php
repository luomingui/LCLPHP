<?php
if (!defined('IN_LCL')) {
    exit('Aecsse Denied');
}

class table_portal_article extends lcl_table {

    protected $_now;

    public function __construct() {
        $this->_table = 'portal_article';
        $this->_pk = 'aid';
        $this->_now = time();
        parent::__construct();
    }

    public function fetch_all() {
        return DB::fetch_all('select * from %t  order by aid desc', array($this->_table));
    }

    public function fetch_all_count() {
        $list = DB::fetch_all('select * from %t order by aid desc', array($this->_table));
        $totail = count($list);
        return $totail;
    }

    public function fetch_limit($start, $count) {
        return DB::fetch_all('select * from %t order by aid desc limit %d,%d', array($this->_table, $start, $count));
    }

    public function fetch_by_aid($aid) {
        $item = DB::fetch_first('select * from %t where aid=%d', array($this->_table, $aid));
        return $item;
    }

    public function update_by_aid($aid, $data) {
        return DB::update($this->_table, $data, 'aid=' . $aid);
    }

    public function delete_by_aid($aid) {
        return DB::delete($this->_table, 'aid=' . $aid);
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

