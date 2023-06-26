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

// Hapus user dari tabel users
$sqlDeleteUser = "DELETE FROM users WHERE id = $id";
if (mysqli_query($conn, $sqlDeleteUser)) {
    // Atur pesan sesi dan redirect ke halaman users
    $_SESSION['message'] = 'User deleted successfully.';
    header('location: manage_users.php');
} else {
    echo "Error deleting record: " . mysqli_error($conn);
    die();
}
?>
