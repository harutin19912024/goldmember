<?php 
$data = file_get_contents('https://happygifts.ru/catalog_new/zonty/?PAGEN_1=2&');
$data = explode('\n', $data);
print_r($data);die;

?>