<?php
//error_reporting(E_ALL); ini_set("display_errors", 1);
//$url = 'https://bancadoithuong.blog';
$url = 'https://omegaads.live/';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
curl_setopt($ch, CURLOPT_TIMEOUT,10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$output = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo 'HTTP code: ' . $httpcode;
?>
