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
    <title>Sign Up - PharmaTrend</title>
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
            <!-- Form Sign In -->
            <div class="bg-white h-auto rounded-lg shadow-lg py-4 px-6">
                <div class="flex flex-col items-center justify-center h-full">
                    <h1 class="text-2xl font-bold text-gray-700 mt-6">Sign Up</h1>
                    <?php
                        if(isset($_POST["sumbit"])){
                            $email = $_POST["email"];
                            $fullname = $_POST["fullname"];
                            $password = $_POST["password"];
                            $password2 = $_POST["password2"];
                            $role = "user";
                            $status = "active";
                            $created_at = date("Y-m-d H:i:s");
                            $updated_at = date("Y-m-d H:i:s");

                            $password_hash = password_hash($password, PASSWORD_DEFAULT);


                            $error = array();
                            if(empty($email) || empty($fullname) || empty($password) || empty($password2)){
                                array_push($error, "Please fill all the form");
                            }
                            if($password != $password2){
                                array_push($error, "Password not match");
                            }
                            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                                array_push($error, "Email not valid");
                            }
                            if(strlen($password) < 8){
                                array_push($error, "Password must be at least 8 characters");
                            }
                            if(count($error) > 0){
                                foreach($error as $err){
                                    echo '<p class="text-red-600 text-sm text-left">'.$err.'</p>';
                                }
                            } else{
                                require_once "config.php";
                                $sql = "SELECT * FROM users WHERE email = '$email'";
                                $result = mysqli_query($conn, $sql);
                                if(mysqli_num_rows($result) > 0){
                                    echo '<p class="text-red-600 text-sm text-left">Email already exist</p>';
                                } else{
                                    $sql = "INSERT INTO users (email, fullname, password, role, status, created_at, updated_at) VALUES ('$email', '$fullname', '$password_hash', '$role', '$status', '$created_at', '$updated_at')";
                                    if(mysqli_query($conn, $sql)){
                                        echo '<p class="text-green-600 text-sm text-left">Sign up success</p>';
                                    } else{
                                        echo '<p class="text-red-600 text-sm text-left">Sign up failed</p>';
                                        // stop 5 seconds and redirect to sign in page
                                        header("refresh:5;url=signin.php");
                                    }
                                }
                            }

                        }
                    ?>
                    <form action="signup.php" class="flex flex-col items-center justify-center" method="post">
                        <div class="flex flex-col items-start justify-center w-full">
                            <label for="email" class="text-gray-700 mt-4 mb-2">Email</label>
                            <input type="email" name="email" id="email"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-400"
                                placeholder="Email">
                        </div>
                        <div class="flex flex-col items-start justify-center w-full">
                            <label for="fullname" class="text-gray-700 mt-4 mb-2">Full Name</label>
                            <input type="text" name="fullname" id="fullname"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-400"
                                placeholder="fullname">
                        </div>
                        <div class="flex flex-col items-start justify-center w-full">
                            <label for="password" class="text-gray-700 mt-4 mb-2">Password</label>
                            <input type="password" name="password" id="password"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-400"
                                placeholder="Password">
                        </div>
                        <div class="flex flex-col items-start justify-center w-full">
                            <label for="password2" class="text-gray-700 mt-4 mb-2">Confirm Password</label>
                            <input type="password" name="password2" id="password2"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-400"
                                placeholder="Password">
                        </div>
                        <div class="flex flex-col items-end justify-end w-full">
                            <div class="flex flex-row items-end justify-center w-full mt-4">
                                <a href="signin.php" class="text-gray-700 mt-4 text-xs mb-6 px-4 py-3"> Already
                                    have an account? Sign In</a>
                                <button class="bg-blue-400 text-white px-4 py-3 rounded mb-6 hover:bg-blue-600" type="submit" name="submit">
                                    <i class="fas fa-sign-in-alt mr-2"></i>
                                    Sign Up
                                </button>
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