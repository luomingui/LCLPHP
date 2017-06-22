<?php
$perpage = 10;
$start = ($page - 1) * $perpage;
$wheresql = " 1=1 ";
$mod = $_GET['mod'];
if ($mod == "view") {
    $aid = $_GET['aid'];
    $article = C::t('portal_article')->fetch_by_aid($aid);
    include simpletemplate('portal/portal_view');
} else {
    $_G['datalist'] = Array();
    $count = C::t('portal_article')->fetch_all_by_sql($wheresql, '', 0, 0, 1);
    if ($count) {
        $repairs = array();
        $query = C::t('portal_article')->fetch_all_by_sql($wheresql, $ordersql, $start, $perpage);
        foreach ($query as $row) {
            $row['dateline'] = dgmdate($row['dateline'], 'Y-m-d H:i:s', '9999', getglobal('setting/dateformat') . ' H:i:s');
            $row['catname'] = getcatname($row['catid']);
            $rdata[] = $row;
        }
        $_G['datalist'] = $rdata;
        $multi = multi($count, $perpage, $page, 'admin.php?action=news&perpage=' . $perpage);
    }
    include simpletemplate('portal/portal');
	
	
}

function getcatname($catid) {
    $model = C::t('portal_categry')->fetch_by_catid($catid);
    if ($model) {
        return $model['catname'];
    } else {
        return "保密";
    }
}

?>
