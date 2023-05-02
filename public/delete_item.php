<?php
session_start();
if(!isset($_SESSION["id"])){
    ob_start();
    header("Location: signin.php");
    
    die();
}
require_once('config.php');
$id = $_GET['id'];
$sql = "DELETE FROM items WHERE id = $id";
if(mysqli_query($conn, $sql)){
    header('location: items.php');
}else{
    echo "Error deleting record: " . mysqli_error($conn);
}
?>