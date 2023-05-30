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
    <title>Sign In - ARIPSKRIPSI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="font-inter bg-gradient-to-tr from-blue-200 via-teal-200 to-yellow-200 h-screen">
    <header class="bg-white fixed top-0 w-full">
        <?php
            include("components/navbar.php");
        ?>
    </header>
    <div class="container-fluid mx-auto h-screen">
        <div class="flex flex-col items-center justify-center h-screen w-full content-center">
            <!-- Form Sign In -->
            <div class="bg-white h-auto rounded-lg shadow-lg py-4 px-6">
                <div class="flex flex-col items-center justify-center h-full">
                    <h1 class="text-2xl font-bold text-gray-700 mt-6">Sign In</h1>
                    <?php
                        if(isset($_POST["submit"])){
                            $email = $_POST["email"];
                            $password = $_POST["password"];
                            require_once "config.php";
                            $sql = "SELECT * FROM users WHERE email='$email'";
                            $result = mysqli_query($conn, $sql);
                            if(mysqli_num_rows($result) > 0){
                                $row = mysqli_fetch_assoc($result);
                                if(password_verify($password, $row["password"])){
                                    session_start();
                                    $_SESSION["id"] = $row["id"];
                                    $_SESSION["fullname"] = $row["fullname"];
                                    $_SESSION["email"] = $row["email"];
                                    $_SESSION["role"] = $row["role"];
                                    $_SESSION["status"] = $row["status"];
                                    header("Location: dashboard.php");
                                    print_r($_SESSION);
                                    die();
                                }else{
                                    echo "<p class='text-red-500'>Password salah</p>";
                                }
                            }else{
                                echo "<p class='text-red-500'>Email tidak terdaftar</p>";
                            }
                        }
                    ?>
                    <form action="signin.php" class="flex flex-col items-center justify-center" method="POST">
                        <div class="flex flex-col items-start justify-center w-full">
                            <label for="email" class="text-gray-700 mt-4 mb-2">Email</label>
                            <input type="email" name="email" id="email"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-400"
                                placeholder="Email">
                        </div>
                        <div class="flex flex-col items-start justify-center w-full">
                            <label for="password" class="text-gray-700 mt-4 mb-2">Password</label>
                            <input type="password" name="password" id="password"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-400"
                                placeholder="Password">
                        </div>
                        <div class="flex flex-col items-end justify-end w-full">
                            <a href="#" class="text-blue-400 mt-4 mb-2 text-sm">Forgot Password?</a>
                            <div class="flex flex-row items-end justify-center w-full">
                                <a href="signup.php" class="text-gray-700 mt-4 text-xs mb-6 px-4 py-3">Don't have an
                                    account?
                                    Sign Up</a>
                                <input type="submit" value="Sign In" name="submit"
                                    class="bg-blue-400 text-white px-4 py-3 rounded mb-6 hover:bg-blue-600">

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <footer class="w-full mx-auto text-center py-4 fixed bottom-0">
        <p class="text-gray-700">Skripsi Arip &copy; 2023</p>
    </footer>
</body>

</html>