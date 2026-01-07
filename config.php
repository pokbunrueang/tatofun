<?php
$host = "localhost";
$user = "root"; 
$pass = "";
$db   = "db_tatofun"; // ชื่อ Database ตามใน phpMyAdmin

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("เชื่อมต่อล้มเหลว: " . mysqli_connect_error());
}
?>