<?php
session_start();
if(!isset($_SESSION["id"])){
    ob_start();
    header("Location: signin.php");
    die();
}

require_once("config.php");

if(isset($_POST["update"])){
    $id = $_SESSION["id"];
    $oldPassword = $_POST["old_password"];
    $newPassword = $_POST["new_password"];
    $confirmPassword = $_POST["confirm_password"];
    
    // Retrieve current password from the database
    $result = mysqli_query($conn, "SELECT password FROM users WHERE id = '$id'");
    $row = mysqli_fetch_assoc($result);
    $currentPassword = $row["password"];
    
    // Verify old password
    if(password_verify($oldPassword, $currentPassword)){
        // Check if the new passwords match
        if($newPassword === $confirmPassword){
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            // Update the password in the database
            mysqli_query($conn, "UPDATE users SET password = '$hashedPassword' WHERE id = '$id'");
            
            // Redirect to the profile page
            header("Location: profile.php");
            exit();
        } else {
            $error = "New passwords do not match.";
        }
    } else {
        $error = "Invalid old password.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - PharmaTrend</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="font-inter">
    <header class="bg-white w-full border-b-2 border-gray-200">
        <?php include("components/navbar.php"); ?>
    </header>
        <div class="container-fluid mx-auto h-auto">
        <!-- sidebar flex and container -->
        <div class="flex">
            <?php
                include("components/sidebar.php");
            ?>
            <div class="w-10/12 h-[calc(100vh-3.5rem)] p-2 mb-96">
                <!-- container with breadcrumb -->
                <div class="w-full h-auto border-2 border-gray-200 rounded-md py-4 px-6">
                    <!-- breadcrumb -->
                    <div class="flex items-center gap-2 mb-3 justify-between">
                        <div>
                            <a href="dashboard.php" class="text-gray-700 hover:text-gray-950"><i class="fas fa-home"></i></a>
                            <span class="text-gray-700">/</span>
                            <a href="profile.php" class="text-gray-700 hover:text-gray-950">Profile</a>
                            <span class="text-gray-700">/</span>
                            <a href="profile.php" class="text-gray-700 hover:text-gray-950"><?php echo $_SESSION["fullname"] ?></a>
                        </div>
                        <button class="flex flex-row justify-center items-center bg-gray-200 hover:bg-gray-300 rounded-md px-4 py-2 text-gray-700 space-x-2" onclick="window.history.back();">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back</span>
                        </button>
                    </div>
                    <hr>
                    <!-- content -->
                    <div class="flex flex-col gap-4 mt-4 w-full">
                        <div class="flex flex-col gap-4 w-full">
                        <form action="change_password.php" method="POST" class="flex flex-col gap-4 w-full">
                            <!-- Existing form fields -->
                            <!-- Your existing code for the profile form fields -->

                            <!-- New Password -->
                            <div class="flex flex-col gap-2">
                                <label for="old_password" class="text-gray-700">Old Password <span class="text-red-500">*</span></label>
                                <input type="password" name="old_password" id="old_password" class="border-2 border-gray-200 rounded-md p-2">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="new_password" class="text-gray-700">New Password <span class="text-red-500">*</span></label>
                                <input type="password" name="new_password" id="new_password" class="border-2 border-gray-200 rounded-md p-2">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="confirm_password" class="text-gray-700">Confirm Password <span class="text-red-500">*</span></label>
                                <input type="password" name="confirm_password" id="confirm_password" class="border-2 border-gray-200 rounded-md p-2">
                            </div>

                            <!-- Error message -->
                            <?php if(isset($error)): ?>
                            <div class="text-red-500"><?php echo $error; ?></div>
                            <?php endif; ?>

                            <!-- Button -->
                            <div class="flex flex-row gap-2">
                                <button type="submit" name="update" class="bg-blue-500 hover:bg-blue-600 rounded-md px-4 py-2 text-white">Update</button>
                                <button type="reset" class="bg-red-500 hover:bg-red-600 rounded-md px-4 py-2 text-white">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- footer -->
        <?php
            include("components/footer.php");
        ?>
</body>

</html>
