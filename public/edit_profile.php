<?php
session_start();
if (!isset($_SESSION["id"])) {
    ob_start();
    header("Location: signin.php");
    die();
}
require_once("config.php");

if (isset($_POST["update"])) {
    // Mendapatkan data dari form
    $id = $_SESSION["id"];
    $fullname = $_POST["fullname"];
    $email = $_POST["email"];
    if ($email != $_SESSION["email"]) {
        $email_check = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email' OR id != '$id'");
        if (mysqli_num_rows($email_check) > 0) {
            $_SESSION["error"] = "Email sudah digunakan";
            header("Location: edit_profile.php");
            die();
        }
    }
    
    $phone = $_POST["phone"];
    $address = $_POST["address"];

    // Perbarui data profil
    $query = "UPDATE users SET fullname = '$fullname', email = '$email', phone = '$phone', address = '$address' WHERE id = '$id'";
    mysqli_query($conn, $query);

    // Perbarui foto profil jika ada file yang diunggah
    if (!empty($_FILES["photo"]["name"])) {
        $photo = $_FILES["photo"]["name"];
        $photo_tmp = $_FILES["photo"]["tmp_name"];
        $photo_ext = pathinfo($photo, PATHINFO_EXTENSION);
        $photo_filename = uniqid() . "." . $photo_ext;
        move_uploaded_file($photo_tmp, "img/profile/" . $photo_filename);

        // Perbarui nama foto profil di database
        $query = "UPDATE users SET photo = '$photo_filename' WHERE id = '$id'";
        mysqli_query($conn, $query);
        $_SESSION["photo"] = $photo_filename;

        // delete foto lama
        if ($row["photo"] != "default.png") {
            unlink("img/profile/" . $row["photo"]);
        }
    }
    $_SESSION["fullname"] = $fullname;
    $_SESSION["email"] = $email;
    $_SESSION["phone"] = $phone;
    $_SESSION["address"] = $address;
    

    // Redirect ke halaman profil
    header("Location: profile.php");
    $_SESSION["success"] = "Profil berhasil diperbarui";
    die();
}

// Mendapatkan data profil pengguna
$id = $_SESSION["id"];
$profile = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id'");
$row = mysqli_fetch_assoc($profile);
?>

<!-- Bagian HTML form dan tampilan profil -->
<!-- ... (Kode HTML Anda di sini) ... -->
