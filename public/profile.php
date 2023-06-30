<?php
session_start();
if(!isset($_SESSION["id"])){
    ob_start();
    header("Location: signin.php");
    
    die();
}
require_once("config.php");
// item data with pagination and descending order
$limit = 10;
$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;
$items = mysqli_query($conn, "SELECT * FROM items ORDER BY id DESC LIMIT $start, $limit");
$items_all = mysqli_query($conn, "SELECT * FROM items");
$total = mysqli_num_rows($items_all);
$pages = ceil($total / $limit);
$first_page = 1;
$previous_page = $page - 1;
$next_page = $page + 1;
$no = $start + 1;
// search item
if(isset($_GET  ["search"])){
    $search = $_GET["search"];
    $items = mysqli_query($conn, "SELECT * FROM items WHERE name LIKE '%$search%' OR code LIKE '%$search%' ORDER BY id DESC LIMIT $start, $limit");
    $items_all = mysqli_query($conn, "SELECT * FROM items WHERE name LIKE '%$search%' OR code LIKE '%$search%'");
    $total = mysqli_num_rows($items_all);
    $pages = ceil($total / $limit);
    $previous = $page - 1;
    $next = $page + 1;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - PharmaTrend</title>
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
            include("components/navbar.php");
        ?>
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
                        <?php
                            $id = $_SESSION["id"];
                            $profile = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id'");
                            $row = mysqli_fetch_assoc($profile);
                        ?>
                        <!-- Form Profile -->
                        <div class="flex flex-col gap-4 w-full">
                            <!-- session info error and success -->
                            <?php
                                if(isset($_SESSION["info"])){
                                    $info = $_SESSION["info"];
                                    echo "<div class='bg-red-500 text-white p-2 rounded-md'>$info</div>";
                                    unset($_SESSION["info"]);
                                }
                                if(isset($_SESSION["success"])){
                                    $success = $_SESSION["success"];
                                    echo "<div class='bg-green-500 text-white p-2 rounded-md'>$success</div>";
                                    unset($_SESSION["success"]);
                                }
                                if(isset($_SESSION["error"])){
                                    $error = $_SESSION["error"];
                                    echo "<div class='bg-red-500 text-white p-2 rounded-md'>$error</div>";
                                    unset($_SESSION["error"]);
                                }
                            ?>
                            <form action="edit_profile.php" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4 w-full">
                                <!-- Profile Preview -->
                                <div class="flex flex-col gap-2">
                                    <label for="photo" class="text-gray-700">Photo <span class="text-red-500">*</span></label>
                                    <div class="flex flex-row gap-2">
                                        <?php
                                            // check if photo is empty
                                            if($row["photo"] == ""){
                                                $photo = "img/profile/profile.jpeg";
                                            }else{
                                                $photo = "img/profile/".$row["photo"];
                                            }
                                        ?>
                                        <img src="<?php echo $photo ?>" alt="profile" class="w-32 h-32 rounded-md object-cover">
                                        <div class="flex flex-col gap-2">
                                            <label for="photo" class="text-gray-700">Change Photo <span class="text-red-500">*</span></label>
                                            <input type="file" name="photo" id="photo" class="border-2 border-gray-200 rounded-md p-2">
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label for="fullname" class="text-gray-700">Fullname <span class="text-red-500">*</span></label>
                                    <input type="text" name="fullname" id="fullname" class="border-2 border-gray-200 rounded-md p-2" value="<?php echo $row["fullname"] ?>">
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label for="email" class="text-gray-700">Email <span class="text-red-500">*</span></label>
                                    <input type="email" name="email" id="email" class="border-2 border-gray-200 rounded-md p-2" value="<?php echo $row["email"] ?>">
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label for="password" class="text-gray-700">Password <span class="text-red-500">*</span></label>
                                    <input type="password" name="password" id="password" class="border-2 border-gray-200 rounded-md p-2 bg-gray-200" value="<?php echo $row["password"] ?>" readonly>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label for="phone" class="text-gray-700">Phone <span class="text-red-500">*</span></label>
                                    <input type="text" name="phone" id="phone" class="border-2 border-gray-200 rounded-md p-2" value="<?php echo $row["phone"] ?>">
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label for="address" class="text-gray-700">Address <span class="text-red-500">*</span></label>
                                    <input type="text" name="address" id="address" class="border-2 border-gray-200 rounded-md p-2" value="<?php echo $row["address"] ?>">
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label for="role" class="text-gray-700">Role <span class="text-red-500">*</span></label>
                                    <input type="text" name="role" id="role" class="border-2 border-gray-200 rounded-md p-2 bg-gray-200" value="<?php echo $row["role"] ?>" readonly>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label for="status" class="text-gray-700">Status <span class="text-red-500">*</span></label>
                                    <input type="text" name="status" id="status" class="border-2 border-gray-200 rounded-md p-2 bg-gray-200" value="<?php echo $row["status"] ?>" readonly>
                                </div>
                                <!-- button -->
                                <div class="flex flex-row gap-2">
                                    <button type="submit" name="update" class="bg-blue-500 hover:bg-blue-600 rounded-md px-4 py-2 text-white">Update</button>
                                    <button type="reset" class="bg-red-500 hover:bg-red-600 rounded-md px-4 py-2 text-white">Reset</button>
                                </div>
                            </form>
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