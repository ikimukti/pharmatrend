<?php
$hostname = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "skripsi-arip";
$conn = mysqli_connect($hostname, $db_username, $db_password, $db_name);
if(!$conn){
    die("Connection failed: ".mysqli_connect_error());
}
?>