<?php
session_start();
if(!isset($_SESSION["id"])){
    header("Location: signin.php");
    
    die();
}
require_once("config.php");
// item data with pagination and descending order
$limit = 10;
$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;
// join table sales and items
$sales = "SELECT s.id, s.code, s.sold, s.month, s.year, s.created_at, i.name, i.price, i.stock FROM sales s INNER JOIN items i ON s.id_item = i.id ORDER BY s.id DESC LIMIT $start, $limit";
$sales_all = "SELECT s.id, s.code, s.sold, s.month, s.year, s.created_at, i.name, i.price, i.stock FROM sales s INNER JOIN items i ON s.id_item = i.id ORDER BY s.id DESC";
$sales_result = mysqli_query($conn, $sales);
$sales_all_result = mysqli_query($conn, $sales_all);
$total = mysqli_num_rows($sales_all_result);
$pages = ceil($total / $limit);
$first_page = 1;
$prev_page = $page - 1;
$next_page = $page + 1;
$last_page = $pages;
$no = $start + 1;
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales - ARIPSKRIPSI</title>
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
                        <a href="dashboard.php" class="text-gray-700 hover:text-gray-950">Home</a>
                        <span class="text-gray-700">/</span>
                        <a href="sales.php" class="text-gray-700 hover:text-gray-950">Sales</a>
                        <span class="text-gray-700">/</span>
                        <a href="sales.php?page<?php echo $page; ?>"
                            class="text-gray-700 hover:text-gray-950"><?php echo $page; ?></a>
                    </div>
                    <hr>
                    <!-- content -->
                    <div class="flex flex-col gap-4 mt-4">
                        <div class="flex flex-col gap-2">
                            <!-- flex row -->
                            <div class="flex flex-row items-center justify-between">
                                <div class="flex flex-row items-center gap-2">
                                    <h1 class="text-2xl font-bold">Sales</h1>
                                </div>
                                <div class="flex flex-row items-center gap-2">
                                    <a href="add_sale.php"
                                        class="bg-blue-400 text-white px-4 py-2 rounded mx-4 my-2 hover:bg-blue-600">
                                        Add Sales
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
                                            <th class="px-2 py-2">Code</th>
                                            <th class="px-2 py-2">Item</th>
                                            <th class="px-2 py-2">Sold</th>
                                            <th class="px-2 py-2">Month</th>
                                            <th class="px-2 py-2">Year</th>
                                            <th class="px-2 py-2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            
                                            while($sales = mysqli_fetch_array($sales_result)){
                                                $bulan = array(
                                                    1 => 'Januari',
                                                    2 => 'Februari',
                                                    3 => 'Maret',
                                                    4 => 'April',
                                                    5 => 'Mei',
                                                    6 => 'Juni',
                                                    7 => 'Juli',
                                                    8 => 'Agustus',
                                                    9 => 'September',
                                                    10 => 'Oktober',
                                                    11 => 'November',
                                                    12 => 'Desember'
                                                );
                                        ?>
                                        <tr class="border-b-2 border-gray-200">
                                            <td class="px-2 py-2"><?php echo $no++; ?></td>
                                            <td class="px-2 py-2"><?php echo $sales["code"]; ?></td>
                                            <td class="px-2 py-2"><?php echo $sales["name"]; ?></td>
                                            <td class="px-2 py-2"><?php echo $sales["sold"]; ?> pack</td>
                                            <td class="px-2 py-2"><?php echo $bulan[$sales["month"]]; ?></td>
                                            <td class="px-2 py-2"><?php echo $sales["year"]; ?></td>
                                            <td class="px-2 py-2">
                                                <a href="edit_sales.php?id=<?php echo $sales["id"]; ?>"
                                                    class="bg-blue-400 text-white px-4 py-2 rounded hover:bg-blue-600 mr-2">
                                                    Edit
                                                </a>
                                                <a href="delete_sales.php?id=<?php echo $sales["id"]; ?>"
                                                    class="bg-red-400 text-white px-4 py-2 rounded hover:bg-red-600 mr-2">
                                                    Delete
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
                                            $sql = "SELECT * FROM sales";
                                            $result = mysqli_query($conn, $sql);
                                            $total_data = mysqli_num_rows($result);
                                            $total_page = ceil($total_data / $limit);
                                            if($page > 1){
                                        ?>
                                        <a href="sales.php?page=<?php echo $first_page; ?>"
                                            class="bg-gray-200 text-gray-500 px-2 py-1 rounded-md hover:bg-gray-400">
                                            First
                                        </a>
                                        <a href="sales.php?page=<?php echo $prev_page; ?>"
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
                                        <a href="sales.php?page=<?php echo $i; ?>"
                                            class="<?php echo $active; ?> px-2 py-1 rounded-md">
                                            <?php echo $i; ?>
                                        </a>
                                        <?php
                                            }
                                            if($page < $total_page){
                                        ?>
                                        <a href="sales.php?page=<?php echo $next_page; ?>"
                                            class="bg-gray-200 text-gray-500 px-2 py-1 rounded-md hover:bg-gray-400">
                                            Next
                                        </a>
                                        <a href="sales.php?page=<?php echo $total_page; ?>"
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
        <?php
            include("components/footer.php");
        ?>
</body>

</html>