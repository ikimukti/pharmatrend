<?php
session_start();
if(!isset($_SESSION["id"])){
    ob_start();
    header("Location: signin.php");
    
    die();
}
require_once('config.php');
$id = $_GET['id'];
$sql = "DELETE FROM sales WHERE id = $id";
if(mysqli_query($conn, $sql)){
    // set session message and redirect to sales page
    $_SESSION['message'] = 'Sales deleted successfully.';
    header('location: sales.php');
}else{
    echo "Error deleting record: " . mysqli_error($conn);
}
?>