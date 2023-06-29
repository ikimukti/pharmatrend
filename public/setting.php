<?php
session_start();
if (!isset($_SESSION["id"])) {
    ob_start();
    header("Location: signin.php");
    die();
}

require_once('config.php');

$id = $_GET['id'];

if (isset($_POST["submit"])) {
    $app_name = $_POST["app_name"];
    $company_name = $_POST["company_name"];
    $address = $_POST["address"];
    $phone_number = $_POST["phone_number"];
    $email = $_POST["email"];
    $website_url = $_POST["website_url"];
    $social_media = $_POST["facebook_url"]; // Ambil nilai dari form Facebook URL

    // Update data di database
    $sql = "UPDATE settings SET app_name = '$app_name', company_name = '$company_name', address = '$address', phone_number = '$phone_number', email = '$email', website_url = '$website_url', social_media = '$social_media' WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        echo "Data updated successfully!";
    } else {
        echo "Error updating data: " . mysqli_error($conn);
    }
}

// Fetch data dari database
$sql = "SELECT * FROM settings WHERE id = $id";
$query = mysqli_query($conn, $sql);

if (mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_assoc($query);
    $app_name_old = $row["app_name"];
    $company_name_old = $row["company_name"];
    $address_old = $row["address"];
    $phone_number_old = $row["phone_number"];
    $email_old = $row["email"];
    $website_url_old = $row["website_url"];
    $social_media_old = $row["social_media"];
} else {
    echo "Error fetching data: " . mysqli_error($conn);
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setting - PharmaTrend</title>
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
                            <a href="items.php" class="text-gray-700 hover:text-gray-950">Setting</a>
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
                                    <h1 class="text-2xl font-bold">Setting</h1>
                                </div>
                            </div>
                            <form action="setting.php?id=<?php echo $id; ?>" method="POST">
                                <div class="flex flex-col gap-2 mt-2">
                                    <label for="app_name" class="text-sm">App Name:</label>
                                    <input type="text" name="app_name" id="app_name" class="border-2 border-gray-200 rounded-md p-2" value="<?php echo isset($_POST["app_name"]) ? $_POST["app_name"] : "" ?>">
                                </div>
                                <div class="flex flex-col gap-2 mt-2">
                                    <label for="company_name" class="text-sm">Company Name:</label>
                                    <input type="text" name="company_name" id="company_name" class="border-2 border-gray-200 rounded-md p-2" value="<?php echo isset($_POST["company_name"]) ? $_POST["company_name"] : "" ?>">
                                </div>
                                <div class="flex flex-col gap-2 mt-2">
                                    <label for="address" class="text-sm">Address:</label>
                                    <input type="text" name="address" id="address" class="border-2 border-gray-200 rounded-md p-2" value="<?php echo isset($_POST["address"]) ? $_POST["address"] : "" ?>">
                                </div>
                                <div class="flex flex-col gap-2 mt-2">
                                    <label for="phone_number" class="text-sm">Phone Number:</label>
                                    <input type="text" name="phone_number" id="phone_number" class="border-2 border-gray-200 rounded-md p-2" value="<?php echo isset($_POST["phone_number"]) ? $_POST["phone_number"] : "" ?>">
                                </div>
                                <div class="flex flex-col gap-2 mt-2">
                                    <label for="email" class="text-sm">Email:</label>
                                    <input type="text" name="email" id="email" class="border-2 border-gray-200 rounded-md p-2" value="<?php echo isset($_POST["email"]) ? $_POST["email"] : "" ?>">
                                </div>
                                <div class="flex flex-col gap-2 mt-2">
                                    <label for="website_url" class="text-sm">Website URL:</label>
                                    <input type="text" name="website_url" id="website_url" class="border-2 border-gray-200 rounded-md p-2" value="<?php echo isset($_POST["website_url"]) ? $_POST["website_url"] : "" ?>">
                                </div>
                                <div class="flex flex-col gap-2 mt-2">
                                    <label for="facebook_url" class="text-sm">Facebook URL:</label>
                                    <input type="text" name="facebook_url" id="facebook_url" class="border-2 border-gray-200 rounded-md p-2" value="<?php echo isset($_POST["facebook_url"]) ? $_POST["facebook_url"] : "" ?>">
                                </div>
                                <!-- button -->
                                <div class="flex flex-row gap-2 mt-4">
                                    <button type="submit" name="submit" class="bg-blue-500 hover:bg-blue-600 rounded-md px-4 py-2 text-white">
                                        <i class="fas fa-save"></i>
                                        <span>Save</span>
                                    </button>
                                    <button type="reset" name="reset" class="bg-red-500 hover:bg-red-600 rounded-md px-4 py-2 text-white">
                                        <i class="fas fa-undo"></i>
                                        <span>Reset</span>
                                    </button>
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