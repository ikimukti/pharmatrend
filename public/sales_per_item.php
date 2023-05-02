<?php
session_start();
if(!isset($_SESSION["id"])){
    ob_start();
    header("Location: signin.php");
    
    die();
}
require_once("config.php");
// item data with pagination and descending order and sales data acumulation
$limit = 10;
$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;
// sql untuk mengambil i.id, i.code, i.name, i.price, jumlah dari sales yang terjadi pada item tersebut berdasarkan id_item, kemudian hitung jumlah bulan di tahun tersebut yang terjadi pada sales berdasarkan kolom month dan year
$items = mysqli_query($conn, "SELECT i.id, i.code, i.name, i.price, SUM(s.sold) AS total_sales, COUNT(DISTINCT CONCAT(s.month, s.year)) AS total_month FROM items i LEFT JOIN sales s ON i.id = s.id_item GROUP BY i.id ORDER BY i.id DESC LIMIT $start, $limit");
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
    <title>Sales Per Item - ARIPSKRIPSI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/output.css">
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
            <div class="w-10/12 h-screen p-2">
                <!-- container with breadcrumb -->
                <div class="w-full h-auto border-2 border-gray-200 rounded-md py-4 px-6">
                    <!-- breadcrumb -->
                    <div class="flex items-center gap-2 mb-3">
                        <a href="dashboard.php" class="text-gray-700 hover:text-gray-950">Home</a>
                        <span class="text-gray-700">/</span>
                        <a href="sales.php" class="text-gray-700 hover:text-gray-950">Sales</a>
                        <span class="text-gray-700">/</span>
                        <!-- page -->
                        <a href="sales_per_item.php" class="text-blue-400 hover:text-blue-600">Sales Per Item</a>
                    </div>
                    <hr>
                    <!-- content -->
                    <div class="flex flex-col gap-4 mt-4">
                        <div class="flex flex-col gap-2">
                            <!-- flex row -->
                            <div class="flex flex-row items-center justify-between">
                                <div class="flex flex-row items-center gap-2">
                                    <h1 class="text-2xl font-bold">Sales Per Item</h1>
                                    
                                </div>
                                <div class="flex flex-row items-center gap-2">
                                    <!-- <a href="sales_add_item.php"
                                        class="bg-blue-400 text-white px-4 py-2 rounded mx-4 my-2 hover:bg-blue-600">
                                        Add Item
                                    </a> -->
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
                                            <th class="px-2 py-2">Total Sales</th>
                                            <th class="px-2 py-2">Total Month</th>
                                            <!-- <th class="px-2 py-2">Stock</th> -->
                                            <th class="px-2 py-2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            while($item = mysqli_fetch_array($items)){
                                        ?>
                                        <tr class="border-b-2 border-gray-200">
                                            <td class="px-2 py-2"><?php echo $no++; ?></td>
                                            <td class="px-2 py-2"><?php echo $item["code"]; ?></td>
                                            <td class="px-2 py-2"><?php echo $item["name"]; ?></td>
                                            <td class="px-2 py-2">Rp. <?php echo number_format($item["price"]); ?></td>
                                            <!-- jika total sales kosong maka tampilkan 0 -->
                                            <td class="px-2 py-2"><?php echo $item["total_sales"] == null ? 0 : $item["total_sales"]; ?> Pcs</td>
                                            <!-- jika total month kosong maka tampilkan 0 -->
                                            <td class="px-2 py-2"><?php echo $item["total_month"] == null ? 0 : $item["total_month"]; ?> Month</td>
                                            <!-- <td class="px-2 py-2"></td> -->
                                            <td class="px-2 py-2">
                                                <!-- <a href="edit_item.php?id=<?php echo $item["id"]; ?>"
                                                    class="bg-blue-400 text-white px-4 py-2 rounded hover:bg-blue-600 mr-2">
                                                    Edit
                                                </a> -->
                                                <a href="delete_item.php?id=<?php echo $item["id"]; ?>"
                                                    class="bg-red-400 text-white px-4 py-2 rounded hover:bg-red-600 mr-2"
                                                    onclick="return confirm('Are you sure you want to delete this item?');">
                                                    Delete
                                                </a>
                                                <!-- jika total bulan kurang dari bulan yang ada dari 3 tahun lalu maka tampilkan tombol add sales item -->
                                                <?php
                                                    if($item["total_month"] < 36){
                                                ?>
                                                <a href="sales_add_item.php?id=<?php echo $item["id"]; ?>"
                                                    class="bg-green-400 text-white px-4 py-2 rounded hover:bg-green-600 mr-2">
                                                    Add Sales
                                                </a>
                                                <?php
                                                    }
                                                ?>
                                                <!-- alert delete confirm -->
                                                <!-- <a href="restock_item.php?id="
                                                    class="bg-green-400 text-white px-4 py-2 rounded hover:bg-green-600">
                                                    Restock
                                                </a> -->
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
                                        <a href="sales_per_item.php?page=<?php echo $first_page; ?>"
                                            class="bg-gray-200 text-gray-500 px-2 py-1 rounded-md hover:bg-gray-400">
                                            First
                                        </a>
                                        <a href="sales_per_item.php?page=<?php echo $prev_page; ?>"
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
                                        <a href="sales_per_item.php?page=<?php echo $i; ?>"
                                            class="<?php echo $active; ?> px-2 py-1 rounded-md">
                                            <?php echo $i; ?>
                                        </a>
                                        <?php
                                            }
                                            if($page < $total_page){
                                        ?>
                                        <a href="sales_per_item.php?page=<?php echo $next_page; ?>"
                                            class="bg-gray-200 text-gray-500 px-2 py-1 rounded-md hover:bg-gray-400">
                                            Next
                                        </a>
                                        <a href="sales_per_item.php?page=<?php echo $total_page; ?>"
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
        <!-- footer -->
        <?php
            include("components/footer.php");
        ?>
</body>

</html>