<?php
/*
�ڶ�ά����м�����Լ���LOGO
*/
include 'phpqrcode.php';    
$value = 'https://open.weixin.qq.com/connect/qrconnect?appid=wxd813c1077086880d&redirect_uri=http%3A%2F%2Fwsq.discuz.qq.com%2F%3Fcmd%3DYz1zaXRlJmE9d3hjYWxsYmFjayZ0eXBlPXFyY29ubmVjdCZzaXRlaWQ9MjY1NDU2Nzg1JnNpdGV1aWQ9MCZxcnJlZmVyZXI9aHR0cCUzQSUyRiUyRmNsdWIuam9iLnp0ZS5uZXQlMkZwbHVnaW4ucGhwJTNGaWQlM0R6dGVob2xkaW5nJTI2bW9kJTNEYWRkJnR0PTE0NjQ3NjY1MzYmc2lnbmF0dXJlPTdmZDAxNmEzZWFmYmY1YTMyMjg0YTk4MTZiODQxNjFjMWFiZGY2NGE%3D&response_type=code&scope=snsapi_login&state=#wechat_redirect'; //��ά������   
$errorCorrectionLevel = 'L';//�ݴ���   
$matrixPointSize = 4;//����ͼƬ��С   
//���ɶ�ά��ͼƬ   
QRcode::png($value, 'qrcode.png', $errorCorrectionLevel, $matrixPointSize, 2);   
$logo = 'logo.png';//׼���õ�logoͼƬ   
$QR = 'qrcode.png';//�Ѿ����ɵ�ԭʼ��ά��ͼ
if ($logo !== FALSE) {   
    $QR = imagecreatefromstring(file_get_contents($QR));   
    $logo = imagecreatefromstring(file_get_contents($logo));   
    $QR_width = imagesx($QR);//��ά��ͼƬ���   
    $QR_height = imagesy($QR);//��ά��ͼƬ�߶�   
    $logo_width = imagesx($logo);//logoͼƬ���   
    $logo_height = imagesy($logo);//logoͼƬ�߶�   
    $logo_qr_width = $QR_width / 5;   
    $scale = $logo_width/$logo_qr_width;   
    $logo_qr_height = $logo_height/$scale;   
    $from_width = ($QR_width - $logo_qr_width) / 2;   
    //�������ͼƬ��������С   
    imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,   
    $logo_qr_height, $logo_width, $logo_height);   
} 
//���ͼƬ   
imagepng($QR, 'helloweixin.png');   
echo '<img src="helloweixin.png">';   


?>