<?php
session_start();
if(!isset($_SESSION["id"])){
    ob_start();
    header("Location: signin.php");
    
    die();
}
require_once('config.php');
$id = $_GET['id'];
$sql = "SELECT * FROM users WHERE id = $id";
$query = mysqli_query($conn, $sql);
if(mysqli_num_rows($query) > 0){
    $row = mysqli_fetch_assoc($query);
    $fullname_old = $row["fullname"];
    $email_old = $row["email"];
    $phone_old = $row["phone"];
    $address_old = $row["address"];
    $photo_old = $row["photo"];
    $role_old = $row["role"];
    $status_old = $row["status"];
    $created_at_old = $row["created_at"];
    $updated_at_old = $row["updated_at"];
}else{
    echo "Error fetching data: " . mysqli_error($conn);
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - PharmaTrend</title>
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
        <?php
            require("components/navbar.php");
        ?>
    </header>
    <div class="container-fluid mx-auto h-auto">
        <!-- sidebar flex and container -->
        <div class="flex">
            <!-- sidebar -->
            <?php
                require("components/sidebar.php");
            ?>
            <div class="w-10/12 h-[calc(100vh-3.5rem)] p-2">
                <!-- container with breadcrumb -->
                <div class="w-full h-auto border-2 border-gray-200 rounded-md py-4 px-6">
                    <!-- breadcrumb -->
                    <div class="flex items-center gap-2 mb-3 justify-between">
                        <div>
                            <a href="dashboard.php" class="text-gray-700 hover:text-gray-950"><i class="fas fa-home"></i></a>
                            <span class="text-gray-700">/</span>
                            <a href="users.php" class="text-gray-700 hover:text-gray-950">Users</a>
                            <span class="text-gray-700">/</span>
                            <a href="edit_user.php?id=<?php echo $id; ?>" class="text-gray-700 hover:text-gray-950">Edit
                                User</a>
                        </div>
                        <button class="flex flex-row justify-center items-center bg-gray-200 hover:bg-gray-300 rounded-md px-4 py-2 text-gray-700 space-x-2" onclick="window.history.back();">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back</span>
                        </button>
                    </div>
                    <hr>
                    <!-- content -->
                    <div class="flex flex-col gap-4 mt-4 mb-16">
                        <div class="flex flex-col gap-2">
                            <!-- flex row -->
                            <div class="flex flex-row items-center justify-between">
                                <div class="flex flex-row items-center gap-2">
                                    <h1 class="text-2xl font-bold">Edit User</h1>
                                </div>
                            </div>
                            <!-- div form edit user -->
                            <div class="flex flex-col gap-4">
                                <?php
                                    if(isset($_POST["submit"])){
                                        $fullname = $_POST["fullname"];
                                        $email = $_POST["email"];
                                        $phone = $_POST["phone"];
                                        $address = $_POST["address"];
                                        $role = $_POST["role"];
                                        $status = $_POST["status"];
                                        $error = array();
                                        if(empty($fullname) || empty($email) || empty($phone) || empty($address) || empty($role) || empty($status)){
                                            array_push($error, "Please fill all the fields");
                                        }
                                        // Add validation rules here according to your requirements
                                        if(count($error) > 0){
                                            foreach($error as $err){
                                                echo "<div class='bg-red-200 text-red-700 border-2 border-red-700 rounded-md p-2'>$err</div>";
                                            }
                                        }
                                        else{
                                            $created_at = $created_at_old;
                                            $updated_at = date("Y-m-d H:i:s");
                                            // Update data in the database
                                            $sql = "UPDATE `users` SET `fullname`='$fullname', `email`='$email', `phone`='$phone', `address`='$address', `role`='$role', `status`='$status', `created_at`='$created_at', `updated_at`='$updated_at' WHERE id = $id";
                                            if(mysqli_query($conn, $sql)){
                                                // header already sent error fix
                                                ob_start();
                                                if(!headers_sent()){
                                                    header("Location: manage_user.php");
                                                } else{
                                                    echo "<script>window.location.href='manage_user.php';</script>";
                                                }
                                            }
                                            else{
                                                echo "Error updating data: " . mysqli_error($conn);
                                            }
                                        }
                                    }
                                ?>
                                <form action="edit_user.php?id=<?php echo $id; ?>" method="POST"
                                    class="flex flex-col gap-4">
                                    <div class="flex flex-col gap-2 mt-2">
                                        <label for="fullname" class="text-sm">Full Name</label>
                                        <input type="text" name="fullname" id="fullname"
                                            class="border-2 border-gray-200 rounded-md p-2" placeholder="Full name"
                                            value="<?php echo isset($_POST["fullname"]) ? $_POST["fullname"] : $fullname_old; ?>">
                                    </div>
                                    <div class="flex flex-col gap-2 mt-2">
                                        <label for="email" class="text-sm">Email</label>
                                        <input type="email" name="email" id="email"
                                            class="border-2 border-gray-200 rounded-md p-2" placeholder="Email"
                                            value="<?php echo isset($_POST["email"]) ? $_POST["email"] : $email_old; ?>">
                                    </div>
                                    <div class="flex flex-col gap-2 mt-2">
                                        <label for="phone" class="text-sm">Phone</label>
                                        <input type="text" name="phone" id="phone"
                                            class="border-2 border-gray-200 rounded-md p-2" placeholder="Phone"
                                            value="<?php echo isset($_POST["phone"]) ? $_POST["phone"] : $phone_old; ?>">
                                    </div>
                                    <div class="flex flex-col gap-2 mt-2">
                                        <label for="address" class="text-sm">Address</label>
                                        <input type="text" name="address" id="address"
                                            class="border-2 border-gray-200 rounded-md p-2" placeholder="Address"
                                            value="<?php echo isset($_POST["address"]) ? $_POST["address"] : $address_old; ?>">
                                    </div>
                                    <div class="flex flex-col gap-2 mt-2">
                                        <label for="role" class="text-sm">Role</label>
                                        <select name="role" id="role"
                                            class="border-2 border-gray-200 rounded-md p-2">
                                            <option value="admin" <?php if($role_old == 'admin') echo 'selected'; ?>>Admin</option>
                                            <option value="user" <?php if($role_old == 'user') echo 'selected'; ?>>User</option>
                                        </select>
                                    </div>
                                    <div class="flex flex-col gap-2 mt-2">
                                        <label for="status" class="text-sm">Status</label>
                                        <select name="status" id="status"
                                            class="border-2 border-gray-200 rounded-md p-2">
                                            <option value="active" <?php if($status_old == 'active') echo 'selected'; ?>>Active</option>
                                            <option value="inactive" <?php if($status_old == 'inactive') echo 'selected'; ?>>Inactive</option>
                                        </select>
                                    </div>
                                    <!-- input button save and cancel -->
                                    <div class="flex flex-row items-center justify-end gap-2 mt-4">
                                        <button type="submit"
                                            class="bg-blue-400 hover:bg-blue-600 text-white px-4 py-2 rounded-md"
                                            name="submit">Save</button>
                                        <a href="manage_user.php"
                                            class="bg-red-400 hover:bg-red-600 text-white px-4 py-2 rounded-md">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
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
