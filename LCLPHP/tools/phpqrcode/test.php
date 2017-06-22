<?php
/*
在二维码的中间加上自己的LOGO
*/
include 'phpqrcode.php';    
$value = 'https://open.weixin.qq.com/connect/qrconnect?appid=wxd813c1077086880d&redirect_uri=http%3A%2F%2Fwsq.discuz.qq.com%2F%3Fcmd%3DYz1zaXRlJmE9d3hjYWxsYmFjayZ0eXBlPXFyY29ubmVjdCZzaXRlaWQ9MjY1NDU2Nzg1JnNpdGV1aWQ9MCZxcnJlZmVyZXI9aHR0cCUzQSUyRiUyRmNsdWIuam9iLnp0ZS5uZXQlMkZwbHVnaW4ucGhwJTNGaWQlM0R6dGVob2xkaW5nJTI2bW9kJTNEYWRkJnR0PTE0NjQ3NjY1MzYmc2lnbmF0dXJlPTdmZDAxNmEzZWFmYmY1YTMyMjg0YTk4MTZiODQxNjFjMWFiZGY2NGE%3D&response_type=code&scope=snsapi_login&state=#wechat_redirect'; //二维码内容   
$errorCorrectionLevel = 'L';//容错级别   
$matrixPointSize = 4;//生成图片大小   
//生成二维码图片   
QRcode::png($value, 'qrcode.png', $errorCorrectionLevel, $matrixPointSize, 2);   
$logo = 'logo.png';//准备好的logo图片   
$QR = 'qrcode.png';//已经生成的原始二维码图
if ($logo !== FALSE) {   
    $QR = imagecreatefromstring(file_get_contents($QR));   
    $logo = imagecreatefromstring(file_get_contents($logo));   
    $QR_width = imagesx($QR);//二维码图片宽度   
    $QR_height = imagesy($QR);//二维码图片高度   
    $logo_width = imagesx($logo);//logo图片宽度   
    $logo_height = imagesy($logo);//logo图片高度   
    $logo_qr_width = $QR_width / 5;   
    $scale = $logo_width/$logo_qr_width;   
    $logo_qr_height = $logo_height/$scale;   
    $from_width = ($QR_width - $logo_qr_width) / 2;   
    //重新组合图片并调整大小   
    imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,   
    $logo_qr_height, $logo_width, $logo_height);   
} 
//输出图片   
imagepng($QR, 'helloweixin.png');   
echo '<img src="helloweixin.png">';   


?>