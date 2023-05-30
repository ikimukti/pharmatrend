<?php
session_start();
if(!isset($_SESSION["id"])){
    header("Location: signin.php");
    
    die();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ARIPSKRIPSI</title>
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
            <div class="w-10/12 h-[calc(100vh-3.5rem)] p-2">
                <!-- container with breadcrumb -->
                <div class="w-full h-auto border-2 border-gray-200 rounded-md py-4 px-6">
                    <!-- breadcrumb -->
                    <div class="flex items-center gap-2 mb-3">
                        <a href="#" class="text-gray-700 hover:text-gray-950"><i class="fas fa-home"></i></a>
                        <span class="text-gray-700">/</span>
                        <a href="#" class="text-gray-700 hover:text-gray-950">Dashboard</a>
                    </div>
                    <hr>
                    <!-- content -->
                    <div class="flex flex-col gap-4 mt-4">
                        <div class="flex flex-col gap-2">
                            <h1 class="text-2xl font-bold">Dashboard</h1>
                            <p class="text-gray-700">Welcome back, <?php echo $_SESSION['fullname']; ?> !, here's what's
                                happening with your store today. this data is updated once a day.</p>
                            <!-- card info flex forecasting -->
                            <div class="w-full h-auto bg-white rounded-md shadow-md p-4">
                                <div class="flex flex-col gap-2">
                                    <h1 class="text-xl font-bold">Sales Forecasting</h1>
                                    <p class="text-gray-700">Based on your store's sales data, here's what we predict
                                        you'll make today.</p>
                                    <!-- card info forecasting flex -->
                                    <div class="flex flex-row gap-4">
                                        <!-- card info -->
                                        <div class="w-1/4 h-auto bg-white rounded-md shadow-md p-4">
                                            <div class="flex flex-col gap-2">
                                                <h1 class="text-xl font-bold">Rp. 1.000.000</h1>
                                                <p class="text-gray-700">Today's Sales</p>
                                            </div>
                                        </div>
                                        <!-- card info -->
                                        <div class="w-1/4 h-auto bg-white rounded-md shadow-md p-4">
                                            <div class="flex flex-col gap-2">
                                                <h1 class="text-xl font-bold">Rp. 1.000.000</h1>
                                                <p class="text-gray-700">Today's Sales</p>
                                            </div>
                                        </div>
                                        <!-- card info -->
                                        <div class="w-1/4 h-auto bg-white rounded-md shadow-md p-4">
                                            <div class="flex flex-col gap-2">
                                                <h1 class="text-xl font-bold">Rp. 1.000.000</h1>
                                                <p class="text-gray-700">Today's Sales</p>
                                            </div>
                                        </div>
                                        <!-- card info -->
                                        <div class="w-1/4 h-auto bg-white rounded-md shadow-md p-4">
                                            <div class="flex flex-col gap-2">
                                                <h1 class="text-xl font-bold">Rp. 1.000.000</h1>
                                                <p class="text-gray-700">Today's Sales</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- button view -->
                                <div class="flex flex-row justify-end mt-4">
                                    <button
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        View
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- fotter -->
        <?php
            include("components/footer.php");
        ?>
</body>

</html>