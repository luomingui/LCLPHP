<?php

define('IN_LCL', true);

require './function/function_core.php';

echo 'ENCODE<br>';
echo authcode('luomingui','ENCODE');

echo '<br>DECODE<br>';
echo authcode('luomingui');



?>
