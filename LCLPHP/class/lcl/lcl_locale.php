<?php

if (!defined('IN_LCL')) {
    exit('Access Denied');
}
//-------------Intl Version Begin---------------------
// *如果你打算设更多种语言，可以将可选语言项设置转到后台操作，生成缓存
//-------------
//config default language
$aConfig['languages'] = 'cn';
// Enabled languages
$aConfig['intl'] = array(
    'cn' => array(
        'locale' => 'cn',
        'native' => '简体中文',
        'english' => 'Chinese'
    ),
    'tw' => array(
        'locale' => 'tw',
        'native' => '繁体中文',
        'english' => '**ese'
    ),
    'en' => array(
        'locale' => 'us',
        'native' => 'English',
        'english' => 'English'
    )
);
$aConfig['locale'] = array(
    'cn' => 'cn',
    'zh' => 'zh',
    'en' => 'en'
);
// Enabled languages End
//if( !isset( $_SESSION ) ) session_start();
$_SESSION['locale'] = getcookie('locale');
$locale = @$aConfig['intl'][$_GET['lang']]['locale'];
if (!empty($locale)) {
    $_SESSION['locale'] = $locale;
    dsetcookie('locale', $_SESSION['locale']);
}
if (empty($_SESSION['locale'])) {
    $l = strtolower(substr(getenv('HTTP_ACCEPT_LANGUAGE'), 0, 2));
    if (strtolower(substr(getenv('HTTP_ACCEPT_LANGUAGE'), 0, 5)) == 'zh-cn')
        $l = 'cn';
    $_SESSION['locale'] = !empty($aConfig['locale'][$l]) ? $aConfig['locale'][$l] : $aConfig['languages'];
}
define('LCL_LOCALE', LCL_ROOT . './locale/' . $_SESSION['locale'] . '/');
//define('DISCUZ_TPLPATH', './data/template/'.$_SESSION['locale'].'/');
//-------------Intl Version End---------------------
?>