<?php  
/** 
 phar文件的提取还原：
**/  
$phar = new Phar('phpmd.phar');  
$phar->extractTo('phar_lclphp'); //提取一份原项目文件  