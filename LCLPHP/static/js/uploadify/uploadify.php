<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
//初始化参数
$action = empty($_GET['action']) ? 1048576 : $_GET['action'];
$iswatermark = empty($_GET['iswatermark']) ? '' : $_GET['iswatermark'];
$timestamp = empty($_GET['timestamp']) ? 5 : $_GET['timestamp'];
$verifyToken = md5('unique_salt' . $timestamp);

//判断上传状态
if (!empty($_FILES)) {
    //引入上传类
    require_once('../../../class/class_upload.php');

    $up = new upload();
    //设置属性(上传的位置， 大小， 类型， 名是是否要随机生成)
    $up->set("path", "../../../data/attachment/" . $action . "/");
    $up->set("maxsize", 2000000);
    $up->set("allowtype", array("gif", "png", "jpg", "jpeg"));
    // $up -> set("israndname", false);
    //使用对象中的upload方法， 就可以上传文件， 方法需要传一个上传表单的名子 pic, 如果成功返回true, 失败返回false
    if ($up->uploadfile("Filedata")) {
        echo "../../../data/attachment/" . $action . "/" . $up->getFileName();
    } else {
        echo $up->getErrorMsg();
    }
    exit();
}

//删除元素
if ($action == 'del') {
    //$dosql->ExecNoneQuery("DELETE FROM `#@__uploads` WHERE path='$filename'");
    unlink($filename);
    exit();
}
