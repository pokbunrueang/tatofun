<?php //โครงสร้าง php 

$con=new pdo( 'mysql:host=localhost;dbname=db_pytshop;charset=utf8mb4','','' );
date_default_timezone_get("asia/bangkok");

$date=date("Y-m-d-H:i:s");

$insert='insert into db_pytshop';
$select= 'select*from db_physhop';
$update= 'update db_pytshop';
$delet= 'delet from db_pytshop';

?>