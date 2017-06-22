<?php

/**
 * +----------------------------------------------------------------------
 * | LCLPHP [ This is a freeware ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 罗敏贵 <e-mail:minguiluo@163.com> <QQ:271391233>
 * +----------------------------------------------------------------------
 * | 文件功能：验证码
 * +----------------------------------------------------------------------
 */
define('CURSCRIPT', 'seccode');
error_reporting(0);
$seccodeauth = $_GET['seccodeauth'];
$width = $_GET['width'] ? $_GET['width'] : 100;
$height = $_GET['height'] ? $_GET['height'] : 35;

require_once "./class/class_seccode.php";

$code = new seccode();
$code->code = $seccodeauth;
$code->type = 0; // 0英文图片验证码 1中文图片验证码 2Flash 验证码 3语音验证码 4位图验证码
$code->width = $width;
$code->height = $height;
$code->background = 0;  // 是否随机图片背景
$code->adulterate = 0;  // 是否随机背景图形
$code->ttf = 0;    // 是否随机使用ttf字体
$code->angle = 0;   // 是否随机倾斜度
$code->warping = 0;   // 是否随机扭曲
$code->scatter = 0;   // 是否图片打散
$code->color = 0;   // 是否随机颜色
$code->size = 0;   // 是否随机大小
$code->shadow = 0;   // 是否文字阴影
$code->animator = 0;  // 是否GIF 动画
$code->fontpath = './static/seccode/font/';
$code->datapath = './static/seccode/';
$code->includepath = './class/';



$code->display();

//debug($code);
?>