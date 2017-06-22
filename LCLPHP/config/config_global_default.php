<?php

$_config = array();

// ----------------------------  CONFIG DB  ----------------------------- //
// ----------------------------  数据库相关设置---------------------------- //

/**
 * 数据库主服务器设置, 支持多组服务器设置, 当设置多组服务器时, 则会根据分布式策略使用某个服务器
 * @example
 * $_config['db']['1']['dbhost'] = 'localhost'; // 服务器地址
 * $_config['db']['1']['dbuser'] = 'root'; // 用户
 * $_config['db']['1']['dbpw'] = 'root';// 密码
 * $_config['db']['1']['dbcharset'] = 'gbk';// 字符集
 * $_config['db']['1']['pconnect'] = '0';// 是否持续连接
 * $_config['db']['1']['dbname'] = 'x1';// 数据库
 * $_config['db']['1']['tablepre'] = 'pre_';// 表名前缀
 *
 * $_config['db']['2']['dbhost'] = 'localhost';
 * ...
 *
 */
$_config['db'][1]['dbhost'] = 'localhost';
$_config['db'][1]['dbuser'] = 'root';
$_config['db'][1]['dbpw'] = '123456';
$_config['db'][1]['dbcharset'] = 'utf8';
$_config['db'][1]['pconnect'] = 0;
$_config['db'][1]['dbname'] = 'lclphp_2018';
$_config['db'][1]['tablepre'] = 'lcl_';

/**
 * 数据库从服务器设置( slave, 只读 ), 支持多组服务器设置, 当设置多组服务器时, 系统根据每次随机使用
 * @example
 * $_config['db']['1']['slave']['1']['dbhost'] = 'localhost';
 * $_config['db']['1']['slave']['1']['dbuser'] = 'root';
 * $_config['db']['1']['slave']['1']['dbpw'] = 'root';
 * $_config['db']['1']['slave']['1']['dbcharset'] = 'gbk';
 * $_config['db']['1']['slave']['1']['pconnect'] = '0';
 * $_config['db']['1']['slave']['1']['dbname'] = 'x1';
 * $_config['db']['1']['slave']['1']['tablepre'] = 'pre_';
 * $_config['db']['1']['slave']['1']['weight'] = '0'; //权重：数据越大权重越高
 *
 * $_config['db']['1']['slave']['2']['dbhost'] = 'localhost';
 * ...
 *
 */
$_config['db']['1']['slave'] = array();

//启用从服务器的开关
$_config['db']['slave'] = false;
/**
 * 数据库 分布部署策略设置
 *
 * @example 将 common_member 部署到第二服务器, common_session 部署在第三服务器, 则设置为
 * $_config['db']['map']['common_member'] = 2;
 * $_config['db']['map']['common_session'] = 3;
 *
 * 对于没有明确声明服务器的表, 则一律默认部署在第一服务器上
 *
 */
$_config['db']['map'] = array();

/**
 * 数据库 公共设置, 此类设置通常对针对每个部署的服务器
 */
$_config['db']['common'] = array();

/**
 *  禁用从数据库的数据表, 表名字之间使用逗号分割
 *
 * @example common_session, common_member 这两个表仅从主服务器读写, 不使用从服务器
 * $_config['db']['common']['slave_except_table'] = 'common_session, common_member';
 *
 */
$_config['db']['common']['slave_except_table'] = '';


/**
 * 内存服务器优化设置
 * 以下设置需要PHP扩展组件支持，其中 memcache 优先于其他设置，
 * 当 memcache 无法启用时，会自动开启另外的两种优化模式
 */
//内存变量前缀, 可更改,避免同服务器中的程序引用错乱
$_config['memory']['prefix'] = 'discuz_';

/* reids设置, 需要PHP扩展组件支持, timeout参数的作用没有查证 */
$_config['memory']['redis']['server'] = '';
$_config['memory']['redis']['port'] = 6379;
$_config['memory']['redis']['pconnect'] = 1;
$_config['memory']['redis']['timeout'] = 0;
$_config['memory']['redis']['requirepass'] = '';
/**
 * 是否使用 Redis::SERIALIZER_IGBINARY选项,需要igbinary支持,windows下测试时请关闭，否则会出>现错误Reading from client: Connection reset by peer
 * 支持以下选项，默认使用PHP的serializer
 * [重要] 该选项已经取代原来的 $_config['memory']['redis']['igbinary'] 选项
 * Redis::SERIALIZER_IGBINARY =2
 * Redis::SERIALIZER_PHP =1
 * Redis::SERIALIZER_NONE =0 //则不使用serialize,即无法保存array
 */
$_config['memory']['redis']['serializer'] = 1;

$_config['memory']['memcache']['server'] = '';   // memcache 服务器地址
$_config['memory']['memcache']['port'] = 11211;   // memcache 服务器端口
$_config['memory']['memcache']['pconnect'] = 1;   // memcache 是否长久连接
$_config['memory']['memcache']['timeout'] = 1;   // memcache 服务器连接超时

$_config['memory']['apc'] = 1;       // 启动对 apc 的支持
$_config['memory']['xcache'] = 1;      // 启动对 xcache 的支持
$_config['memory']['eaccelerator'] = 1;     // 启动对 eaccelerator 的支持
$_config['memory']['wincache'] = 1;      // 启动对 wincache 的支持
// ---------------------------  CONFIG INPUT  --------------------------- //
$_config['template']['admincp'] = 'admincp';
$_config['template']['default'] = 'default';

// 页面输出设置
$_config['output']['charset'] = 'utf-8'; // 页面字符集
$_config['output']['forceheader'] = 1;  // 强制输出页面字符集，用于避免某些环境乱码
$_config['output']['gzip'] = 0;  // 是否采用 Gzip 压缩输出
$_config['output']['tplrefresh'] = 1;  // 模板自动刷新开关 0=关闭, 1=打开
$_config['output']['language'] = 'zh_cn'; // 页面语言 zh_cn/zh_tw
$_config['output']['staticurl'] = 'static/'; // 站点静态文件路径，“/”结尾
$_config['output']['ajaxvalidate'] = 0;  // 是否严格验证 Ajax 页面的真实性 0=关闭，1=打开
$_config['output']['iecompatible'] = 0;  // 页面 IE 兼容模式
// COOKIE 设置
$_config['cookie']['cookiepre'] = 'discuz_';  // COOKIE前缀
$_config['cookie']['cookiedomain'] = '';   // COOKIE作用域
$_config['cookie']['cookiepath'] = '/';   // COOKIE作用路径
// 站点安全设置
$_config['security']['authkey'] = 'asdfasfas'; // 站点加密密钥
$_config['security']['urlxssdefend'] = true;  // 自身 URL XSS 防御
$_config['security']['attackevasive'] = 0;  // CC 攻击防御 1|2|4|8
/* 防御CC攻击
  0表示关闭此功能
  1表示cookie刷新限制
  2表示限制代理访问
  4表示二次请求
  8表示回答问题（第一次访问时需要回答问题）
 */
$_config['security']['querysafe']['status'] = 1;  // 是否开启SQL安全检测，可自动预防SQL注入攻击
$_config['security']['querysafe']['dfunction'] = array('load_file', 'hex', 'substring', 'if', 'ord', 'char');
$_config['security']['querysafe']['daction'] = array('@', 'intooutfile', 'intodumpfile', 'unionselect', '(select', 'unionall', 'uniondistinct');
$_config['security']['querysafe']['dnote'] = array('/*', '*/', '#', '--', '"');
$_config['security']['querysafe']['dlikehex'] = 1;
$_config['security']['querysafe']['afullnote'] = 0;

// $_GET|$_POST的兼容处理，0为关闭，1为开启；开启后即可使用$_G['gp_xx'](xx为变量名，$_GET和$_POST集合的所有变量名)，值为已经addslashes()处理过
$_config['input']['compatible'] = 1;
$_config['debug'] = '';
?>