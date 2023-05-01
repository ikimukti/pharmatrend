<?php
session_start();
if(!isset($_SESSION["id"])){
    header("Location: signin.php");
    
    die();
}
require_once("config.php");
// item data with pagination
$limit = 10;
$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;
$sql = "SELECT * FROM items LIMIT $start, $limit";
$first_page = "items.php?page=1";
$previous_page = $page - 1;
$next_page = $page + 1;
$result = mysqli_query($conn, $sql);
$no = $start + 1;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Items - ARIPSKRIPSI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/output.css">
</head>

<body class="font-inter">
    <header class="bg-white w-full border-b-2 border-gray-200">
        <nav class="flex items-center justify-between flex-wrap w-94% mx-auto py-1">
            <div class="">
                <h1 class="text-2xl font-bold px-4 py-2">
                    SKRIPSI
                    <span class="bg-gradient-to-br from-red-500 to-teal-400 bg-clip-text text-transparent">ARIP</span>
                </h1>
            </div>
            <div class="">
                <ul class="flex items-center gap-4">
                    <li class="px-4 py-2">
                        <a href="#" class="text-gray-700 hover:text-gray-950">Home</a>
                    </li>
                    <li class="px-4 py-2">
                        <a href="#" class="text-gray-700 hover:text-gray-950">About</a>
                    </li>
                    <li class="px-4 py-2">
                        <a href="#" class="text-gray-700 hover:text-gray-950">Contact</a>
                    </li>
                    <li class="px-4 py-2">
                        <a href="#" class="text-gray-700 hover:text-gray-950">Blog</a>
                    </li>
                </ul>
            </div>
            <div class="">
                <!-- <button class="bg-blue-400 text-white px-4 py-2 rounded mx-4 my-2 hover:bg-blue-600">
                    Login
                </button> -->
                <!-- account -->
                <div>
                    <button type="button"
                        class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2"
                        id="options-menu" aria-haspopup="true" aria-expanded="true">
                        Account
                        <!-- Heroicon name: solid/chevron-down -->
                        <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </nav>
    </header>
    <div class="container-fluid mx-auto h-auto">
        <!-- sidebar flex and container -->
        <div class="flex">
            <div class="w-2/12 h-screen border-r-2 border-gray-200 p-2">
                <div class="flex flex-col items-center justify-center mt-4">
                    <h1 class="text-lg"><?php echo $_SESSION["fullname"]; ?></h1>
                    <div class="flex flex-row items-center justify-center gap-2">
                        <h2 class="text-sm text-gray-500 bg-gray-200 px-2 py-1 rounded-full"><?php echo $_SESSION["role"]; ?></h2>
                        <h2 class="text-sm text-gray-500 bg-green-200 px-2 py-1 rounded-full"><?php echo $_SESSION["status"]; ?></h2>
                    </div>
                </div>
                <hr class="my-4">
                <div>
                    <ul class="mt-4">
                        <li class="px-4 py-2">
                            <a href="dashboard.php" class="text-gray-700 hover:text-gray-950">Dashboard</a>
                        </li>
                        <?php
                        if($_SESSION["role"] == "admin"){
                        ?>
                        <li class="px-4 py-2">
                            <a href="manage_user.php" class="text-gray-700 hover:text-gray-950">Manage User</a>
                        </li>
                        <?php
                        }
                        ?>
                        <li class="px-4 py-2">
                            <a href="items.php" class="text-gray-700 hover:text-gray-950">Items</a>
                        </li>
                        <li class="px-4 py-2">
                            <a href="sales.php" class="text-gray-700 hover:text-gray-950">Sales</a>
                        </li>
                        <li class="px-4 py-2">
                            <a href="analytics" class="text-gray-700 hover:text-gray-950">Analytics</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="w-10/12 h-screen p-2">
                <!-- container with breadcrumb -->
                <div class="w-full h-auto border-2 border-gray-200 rounded-md py-4 px-6">
                    <!-- breadcrumb -->
                    <div class="flex items-center gap-2 mb-3">
                        <a href="dashboard.php" class="text-gray-700 hover:text-gray-950">Home</a>
                        <span class="text-gray-700">/</span>
                        <a href="items.php" class="text-gray-700 hover:text-gray-950">Items</a>
                        <span class="text-gray-700">/</span>
                        <!-- page -->
                        <a href="items.php?page=<?php echo $page; ?>" class="text-gray-700 hover:text-gray-950"><?php echo $page; ?></a>
                    </div>
                    <hr>
                    <!-- content -->
                    <div class="flex flex-col gap-4 mt-4">
                        <div class="flex flex-col gap-2">
                            <!-- flex row -->
                            <div class="flex flex-row items-center justify-between">
                                <div class="flex flex-row items-center gap-2">
                                    <h1 class="text-2xl font-bold">Items</h1>
                                    
                                </div>
                                <div class="flex flex-row items-center gap-2">
                                    <a href="add_item.php" 
                                        class="bg-blue-400 text-white px-4 py-2 rounded mx-4 my-2 hover:bg-blue-600">
                                        Add Item
                                    </a>
                                    <input type="text" name="search" id="search"
                                        class="border-2 border-gray-200 rounded-md px-4 py-2 focus:outline-none focus:border-blue-400"
                                        placeholder="Search">
                                    <button type="button"
                                        class="bg-blue-400 text-white px-4 py-2 rounded ml-4 my-2 hover:bg-blue-600">
                                        Search
                                    </button>
                                </div>
                            </div>
                            <!-- table data item -->
                            <div class="w-full h-auto border-2 border-gray-200 rounded-md py-2 px-2">
                                <table class="w-full text-left text-sm">
                                    <thead class="border-b-2 border-gray-200">
                                        <tr>
                                            <th class="px-2 py-2">No</th>
                                            <th class="px-2 py-2">Item Code</th>
                                            <th class="px-2 py-2">Item Name</th>
                                            <th class="px-2 py-2">Price</th>
                                            <th class="px-2 py-2">Stock</th>
                                            <th class="px-2 py-2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            
                                            while($item = mysqli_fetch_assoc($result)){
                                        ?>
                                        <tr class="border-b-2 border-gray-200">
                                            <td class="px-2 py-2"><?php echo $no++; ?></td>
                                            <td class="px-2 py-2"><?php echo $item["code"]; ?></td>
                                            <td class="px-2 py-2"><?php echo $item["name"]; ?></td>
                                            <td class="px-2 py-2"><?php echo $item["price"]; ?></td>
                                            <td class="px-2 py-2"><?php echo $item["stock"]; ?></td>
                                            <td class="px-2 py-2">
                                                <a href="edit_item.php?id=<?php echo $item["id"]; ?>"
                                                    class="bg-blue-400 text-white px-4 py-2 rounded hover:bg-blue-600">
                                                    Edit
                                                </a>
                                                <a href="delete_item.php?id=<?php echo $item["id"]; ?>"
                                                    class="bg-red-400 text-white px-4 py-2 rounded hover:bg-red-600">
                                                    Delete
                                                </a>
                                                <a href="restock_item.php?id=<?php echo $item["id"]; ?>"
                                                    class="bg-green-400 text-white px-4 py-2 rounded hover:bg-green-600">
                                                    Restock
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>
                                <!-- div space between left total and right pagination -->
                                <div class="flex flex-row items-center justify-between mt-2">
                                    <!-- total data -->
                                    <h2 class="text-sm text-gray-500 bg-gray-200 px-2 py-1 rounded-full">Total: 10</h2>
                                    <!-- pagination with number -->
                                    <div class="flex flex-row items-center justify-end gap-2 mt-2 text-sm">
                                        <?php
                                            $sql = "SELECT * FROM items";
                                            $result = mysqli_query($conn, $sql);
                                            $total_data = mysqli_num_rows($result);
                                            $total_page = ceil($total_data / $limit);
                                            if($page > 1){
                                        ?>
                                        <a href="items.php?page=<?php echo $first_page; ?>"
                                            class="bg-gray-200 text-gray-500 px-2 py-1 rounded-md hover:bg-gray-400">
                                            First
                                        </a>
                                        <a href="items.php?page=<?php echo $previous_page; ?>"
                                            class="bg-gray-200 text-gray-500 px-2 py-1 rounded-md hover:bg-gray-400">
                                            Previous
                                        </a>
                                        <?php
                                            }
                                            for($i = 1; $i <= $total_page; $i++){
                                                if($i == $page){
                                                    $active = "bg-blue-400 text-white";
                                                }else{
                                                    $active = "bg-gray-200 text-gray-500 hover:bg-gray-400";
                                                }
                                        ?>
                                        <a href="items.php?page=<?php echo $i; ?>"
                                            class="<?php echo $active; ?> px-2 py-1 rounded-md">
                                            <?php echo $i; ?>
                                        </a>
                                        <?php
                                            }
                                            if($page < $total_page){
                                        ?>
                                        <a href="items.php?page=<?php echo $next_page; ?>"
                                            class="bg-gray-200 text-gray-500 px-2 py-1 rounded-md hover:bg-gray-400">
                                            Next
                                        </a>
                                        <a href="items.php?page=<?php echo $total_page; ?>"
                                            class="bg-gray-200 text-gray-500 px-2 py-1 rounded-md hover:bg-gray-400">
                                            Last
                                        </a>
                                        <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <footer class=" w-full mx-auto text-center py-4 bottom-0 border border-gray-200">
            <p class="text-gray-700">Skripsi Arip &copy; 2023</p>
        </footer>
</body>

</html>