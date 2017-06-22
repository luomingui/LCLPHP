<?php

$_config = array();

// ----------------------------  CONFIG DB  ----------------------------- //
$_config['db']['1']['dbhost'] = 'localhost';
$_config['db']['1']['dbuser'] = 'root';
$_config['db']['1']['dbpw'] = '123456';
$_config['db']['1']['dbcharset'] = 'utf8';
$_config['db']['1']['pconnect'] = '0';
$_config['db']['1']['dbname'] = 'lclphp';
$_config['db']['1']['tablepre'] = 'lcl_';
$_config['db']['slave'] = '';
$_config['db']['common']['slave_except_table'] = '';

// --------------------------  CONFIG MEMORY  --------------------------- //
$_config['memory']['prefix'] = 'H8ssEA_';
$_config['memory']['redis']['server'] = '';
$_config['memory']['redis']['port'] = 6379;
$_config['memory']['redis']['pconnect'] = 1;
$_config['memory']['redis']['timeout'] = '0';
$_config['memory']['redis']['requirepass'] = '';
$_config['memory']['redis']['serializer'] = 1;
$_config['memory']['memcache']['server'] = '';
$_config['memory']['memcache']['port'] = 11211;
$_config['memory']['memcache']['pconnect'] = 1;
$_config['memory']['memcache']['timeout'] = 1;
$_config['memory']['apc'] = 1;
$_config['memory']['xcache'] = 1;
$_config['memory']['eaccelerator'] = 1;
$_config['memory']['wincache'] = 1;
// --------------------------  CONFIG COOKIE  --------------------------- //
$_config['cookie']['cookiepre'] = 'lcl_2017_';
$_config['cookie']['cookiedomain'] = '';
$_config['cookie']['cookiepath'] = '/';

// --------------------------  CONFIG OUTPUT  --------------------------- //
$_config['output']['charset'] = 'utf-8';
$_config['output']['forceheader'] = 1;
$_config['output']['gzip'] = '0';
$_config['output']['tplrefresh'] = 1;
$_config['output']['language'] = 'cn';
$_config['output']['staticurl'] = 'static/';
$_config['output']['ajaxvalidate'] = '0';
$_config['output']['iecompatible'] = '0';

// -------------------------  CONFIG SECURITY  -------------------------- //
$_config['security']['authkey'] = 'lcl0639'; // 站点加密密钥
$_config['security']['urlxssdefend'] = true;  // 自身 URL XSS 防御
$_config['security']['attackevasive'] = 0;  // CC 攻击防御 1|2|4|8

$_config['security']['querysafe']['status'] = 1;  // 是否开启SQL安全检测，可自动预防SQL注入攻击
$_config['security']['querysafe']['dfunction'] = array('load_file', 'hex', 'substring', 'if', 'ord', 'char');
$_config['security']['querysafe']['daction'] = array('@', 'intooutfile', 'intodumpfile', 'unionselect', '(select', 'unionall', 'uniondistinct');
$_config['security']['querysafe']['dnote'] = array('/*', '*/', '#', '--', '"');
$_config['security']['querysafe']['dlikehex'] = 1;
$_config['security']['querysafe']['afullnote'] = 0;

// ---------------------------  CONFIG INPUT  --------------------------- //
$_config['template']['admincp'] = 'admincp';
$_config['template']['default'] = 'default';

// ---------------------------  CONFIG INPUT  --------------------------- //
$_config['input']['compatible'] = 1;
$_config['cache']['type'] = 'sql'; //file-文件缓存,sql-数据库缓存
$_config['debug'] = '1';
// -------------------  THE END  -------------------- //
?>