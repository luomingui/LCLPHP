<?php

$mod = $_GET['mod'];
if ($mod == "view") {
    $aid = $_GET['aid'];
    $article = C::t('portal_article')->fetch_by_aid($aid);
    include simpletemplate('portal/portal_view');
} else {
    $_G['articles'] = Array();
    $articles = C::t('portal_article')->fetch_all();
    foreach ($articles as $row) {
        $row['dateline'] = dgmdate($row['dateline'], 'Y-m-d H:i:s', '9999', getglobal('setting/dateformat') . ' H:i:s');
        $row['catname'] = getcatname($row['catid']);
        $rdata[] = $row;
    }
    $_G['articles'] = $rdata;
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
