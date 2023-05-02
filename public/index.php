<?php
session_start();
if(isset($_SESSION["id"])){
    header("Location: dashboard.php");
    
    die();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - ARIPSKRIPSI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/output.css">
</head>

<body class="font-inter bg-gradient-to-tr from-blue-200 via-teal-200 to-yellow-200 h-screen">
    <header class="bg-white fixed top-0 w-full">
        <?php
            include("components/navbar.php");
        ?>
    </header>
    <div class="container-fluid mx-auto h-screen">
        <div class="flex flex-col items-center justify-center h-screen w-full content-center">
            <h1 class="text-5xl font-bold text-gray-900">Selamat Datang di Skripsi Arip</h1>
            <p class="text-2xl text-gray-600">Aplikasi Skripsi Arip</p>
            <a class="bg-blue-400 text-white px-4 py-2 rounded-md mx-4 my-2 hover:bg-blue-600" href="signin.php">
                Sign In
            </a>
        </div>
    </div>
    <?php
            include("components/footer.php");
    ?>
</body>

</html>