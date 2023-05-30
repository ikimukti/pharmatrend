<?php
session_start();
if (!isset($_SESSION["id"])) {
    ob_start();
    header("Location: signin.php");
    die();
}

require_once('config.php');
$id = $_GET['id'];

// Hapus item dari tabel sales yang memiliki id_item yang sesuai
$sqlDeleteSales = "DELETE FROM sales WHERE id_item = $id";
if (!mysqli_query($conn, $sqlDeleteSales)) {
    echo "Error deleting sales records: " . mysqli_error($conn);
    die();
}

// Hapus item dari tabel items
$sqlDeleteItem = "DELETE FROM items WHERE id = $id";
if (mysqli_query($conn, $sqlDeleteItem)) {
    // Atur pesan sesi dan redirect ke halaman items
    $_SESSION['message'] = 'Item deleted successfully.';
    header('location: items.php');
} else {
    echo "Error deleting record: " . mysqli_error($conn);
    die();
}
?>
