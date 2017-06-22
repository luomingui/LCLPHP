<?php  
/** http://blog.csdn.net/u011474028/article/details/54973571
 phar归档PHP
 */  
echo "[".dirname(__FILE__)."]";
if(class_exists('Phar')){
	$phar = new Phar('lclphp.phar', 0, 'lclphp.phar');  
	// 添加project里面的所有文件到yunke.phar归档文件  
	$phar->buildFromDirectory(dirname(__FILE__).'/LCLPHP');  
	//设置执行时的入口文件，第一个用于命令行，第二个用于浏览器访问，这里都设置为index.php  
	$phar->setDefaultStub('index.php', 'index.php');
	echo ' build succeed';
}else{
	exit('no Phar modules');
}
