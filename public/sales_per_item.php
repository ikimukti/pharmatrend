<?php
session_start();
if(!isset($_SESSION["id"])){
    ob_start();
    header("Location: signin.php");
    
    die();
}
require_once("config.php");
if(!isset($_GET["search"])){
    ob_start();
    header("Location: sales_per_item.php?page=".$_GET["page"]."&search=");
    die();
}
// item data with pagination and descending order and sales data acumulation
$limit = 10;
$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;
// sql untuk mengambil i.id, i.code, i.name, i.price, jumlah dari sales yang terjadi pada item tersebut berdasarkan id_item, kemudian hitung jumlah bulan di tahun tersebut yang terjadi pada sales berdasarkan kolom month dan total_month_last_year = jumlah month year pada sales 2 tahun terakhir
$sales = mysqli_query($conn, "SELECT i.id, i.code, i.name, i.price, i.stock, i.unit, s.id_item,
       SUM(s.sold) AS total_sales, 
       COUNT(DISTINCT CONCAT(s.month, s.year)) AS total_month, 
       COALESCE(t.total_month_last_year, 0) AS total_month_last_year
FROM items i 
LEFT JOIN sales s ON i.id = s.id_item 
LEFT JOIN (
   SELECT s1.id_item, COUNT(DISTINCT CONCAT(s1.month, s1.year)) AS total_month_last_year
   FROM sales s1
   WHERE (s1.year >= YEAR(CURDATE()) - 3 AND s1.year <= YEAR(CURDATE()) - 1) -- Tahun ke-3, ke-2, dan ke-1 sejak saat ini
      OR (s1.year = YEAR(CURDATE()) AND s1.month <= MONTH(CURDATE())) -- Bulan-bulan di tahun ini hingga bulan saat ini
   GROUP BY s1.id_item
   HAVING COUNT(DISTINCT CONCAT(s1.month, s1.year)) >= 12
) AS t ON i.id = t.id_item
GROUP BY i.id 
ORDER BY total_sales DESC 
LIMIT $start, $limit;
");
$items_all = mysqli_query($conn, "SELECT * FROM items");
$total = mysqli_num_rows($items_all);
$pages = ceil($total / $limit);
$first_page = 1;
$prev_page = $page - 1;
$next_page = $page + 1;
$no = $start + 1;
// search item
if(isset($_GET["search"])){
    $search = $_GET["search"];
    $sales = mysqli_query($conn, "SELECT i.id, i.code, i.name, i.price, i.stock, i.unit, s.id_item,
       SUM(s.sold) AS total_sales, 
       COUNT(DISTINCT CONCAT(s.month, s.year)) AS total_month, 
       COALESCE(t.total_month_last_year, 0) AS total_month_last_year
FROM items i
LEFT JOIN sales s ON i.id = s.id_item
LEFT JOIN (
   SELECT s1.id_item, COUNT(DISTINCT CONCAT(s1.month, s1.year)) AS total_month_last_year
   FROM sales s1
   WHERE (s1.year >= YEAR(CURDATE()) - 3 AND s1.year <= YEAR(CURDATE()) - 1) -- Tahun ke-3, ke-2, dan ke-1 sejak saat ini
      OR (s1.year = YEAR(CURDATE()) AND s1.month <= MONTH(CURDATE())) -- Bulan-bulan di tahun ini hingga bulan saat ini
   GROUP BY s1.id_item
   HAVING COUNT(DISTINCT CONCAT(s1.month, s1.year)) >= 12
) AS t ON i.id = t.id_item
WHERE i.name LIKE '%$search%' OR i.code LIKE '%$search%'
GROUP BY i.id
ORDER BY total_sales DESC
LIMIT $start, $limit
");
    $items_all = mysqli_query($conn, "SELECT * FROM items WHERE name LIKE '%$search%' OR code LIKE '%$search%'");
    $total = mysqli_num_rows($items_all);
    $pages = ceil($total / $limit);
    $first_page = 1;
    $previous_page = $page - 1;
    $next_page = $page + 1;
    $no = $start + 1;
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
                    <div class="flex items-center gap-2 mb-3 justify-between">
                        <div>
                            <a href="dashboard.php" class="text-gray-700 hover:text-gray-950"><i class="fas fa-home"></i></a>
                            <span class="text-gray-700">/</span>
                            <a href="sales.php" class="text-gray-700 hover:text-gray-950">Sales</a>
                            <span class="text-gray-700">/</span>
                            <!-- page -->
                            <a href="sales_per_item.php" class="text-blue-400 hover:text-blue-600">Sales Per Item</a>
                        </div>
                        <button class="flex flex-row justify-center items-center bg-gray-200 hover:bg-gray-300 rounded-md px-4 py-2 text-gray-700 space-x-2" onclick="window.history.back();">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back</span>
                        </button>
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
                                    <form action="sales_per_item.php" method="GET" class="flex flex-row items-center gap-2">
                                        <input type="text" name="search" id="search"
                                        class="border-2 border-gray-200 rounded-md px-4 py-2 focus:outline-none focus:border-blue-400"
                                        placeholder="Search" autocomplete="off" value="<?php echo isset($_GET["search"]) ? $_GET["search"] : ""; ?>">
                                        <button type="submit"
                                            class="bg-blue-400 text-white px-4 py-2 rounded ml-4 my-2 hover:bg-blue-600">
                                            <i class="fas fa-search"></i>
                                            Search
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <!-- table data item -->
                            <div class="w-full h-auto border-2 border-gray-200 rounded-md py-2 px-2">
                                <table class="w-full text-left text-sm">
                                    <thead class="border-b-2 border-gray-200">
                                        <tr>
                                            <th class="px-2 py-2">
                                                <i class="fas fa-hashtag"></i>
                                                No
                                            </th>
                                            <th class="px-2 py-2">
                                                <i class="fas fa-barcode"></i>    
                                                Code
                                            </th>
                                            <th class="px-2 py-2">
                                                <i class="fas fa-box"></i>
                                                Item Name</th>
                                            <th class="px-2 py-2">
                                                <i class="fas fa-money-bill-wave"></i>
                                                Price</th>
                                            <th class="px-2 py-2">
                                                <i class="fas fa-boxes"></i>    
                                                Total Sales</th>
                                            <th class="px-2 py-2">
                                                <i class="fas fa-calendar"></i>  
                                                Total Month
                                            </th>
                                            <!-- total_month_last_year -->
                                            <th class="px-2 py-2">
                                                <i class="fas fa-calendar"></i>  
                                                Total 2 Years Ago</th>
                                            <!-- <th class="px-2 py-2">Stock</th> -->
                                            <th class="px-2 py-2">
                                                <i class="fas fa-cog"></i>
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            // $items output dari query di atas
                                            while($sale = mysqli_fetch_assoc($sales)){
                                        ?>
                                        <tr class="border-b-2 border-gray-200">
                                            <td class="px-2 py-2">
                                                <?php echo $no++; ?>
                                            </td>
                                            <!-- keluarkan 5 karakter saja -->
                                            <td class="px-2 py-2">
                                                <?php echo substr($sale["code"], 0, 5); ?>
                                            </td>
                                            <td class="px-2 py-2">
                                                <a href="detail_sales_per_item.php?id=<?php echo $sale["id_item"]; ?>"
                                                    class="text-blue-400 hover:text-blue-600">
                                                    <?php echo $sale["name"]; ?>
                                                </a>
                                            </td>
                                            <td class="px-2 py-2">
                                                Rp. <?php echo number_format($sale["price"]); ?>
                                            </td>
                                            <!-- jika total sales kosong maka tampilkan 0 -->
                                            <td class="px-2 py-2">
                                                <?php
                                                    $total_sales = $sale["total_sales"] == null ? 0 : $sale["total_sales"];
                                                    echo number_format($total_sales, 0, '.', ',')." ".$sale["unit"];
                                                ?></td>
                                            <!-- jika total month kosong maka tampilkan 0 -->
                                            <td class="px-2 py-2">
                                                <?php $total_month = $sale["total_month"] == null ? 0 : $sale["total_month"]; 
                                                    echo number_format($total_month, 0, '.', ',')." Month";
                                                ?></td>
                                            <!-- total_month_last_year -->
                                            <td class="px-2 py-2">
                                                <?php echo $sale["total_month_last_year"] == null ? 0 : $sale["total_month_last_year"]; ?>
                                                Month</td>
                                            </td>
                                            <!-- <td class="px-2 py-2"></td> -->
                                            <td class="px-2 py-2">
                                                <!-- <a href="edit_item.php?id=<?php echo $sale["id"]; ?>"
                                                    class="bg-blue-400 text-white px-4 py-2 rounded hover:bg-blue-600 mr-2">
                                                    Edit
                                                </a> -->
                                                <!-- jika total bulan kurang dari bulan yang ada dari 3 tahun lalu maka tampilkan tombol add sales item -->
                                                <?php
                                                    // $monthBtn hitung bulan 2 tahun lalu
                                                    $monthBtn = date("m") + 24;
                                                    if($sale["total_month"] <= $monthBtn){
                                                        // Tahun sekarang
                                                        $year = date("Y");
                                                ?>
                                                <a href="add_sales_per_item.php?id=<?php echo $sale["id"]; ?>&year=<?php echo $year; ?>"
                                                    class="bg-green-400 text-white px-4 py-2 rounded hover:bg-green-600 mr-2">
                                                    <i class="fas fa-plus"></i> Add Sales
                                                </a>
                                                <?php
                                                    } else {
                                                        // Sales sudah di tambahkan
                                                ?>
                                                <span href="#"
                                                    class=" text-gray-500 px-4 py-2 rounde">
                                                    <i class="fas fa-check"></i> Added
                                                </span>
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
                                    <?php
                                        $sql = "SELECT * FROM items WHERE name LIKE '%$search%' OR code LIKE '%$search%' OR price LIKE '%$search%' OR stock LIKE '%$search%' ORDER BY id DESC";
                                            $result = mysqli_query($conn, $sql);
                                            $total_data = mysqli_num_rows($result);
                                            $total_page = ceil($total_data / $limit);
                                    ?>
                                    <p class="text-sm text-gray-500 bg-gray-200 px-2 py-1 rounded-full">
                                        <i class="fas fa-list"></i>
                                        Total: <?php echo $total_data; ?> Items
                                    </p>
                                    <!-- pagination with number -->
                                    <div class="flex flex-row items-center justify-end gap-2 mt-2 text-sm">
                                        <?php
                                            
                                            if($page > 1){
                                        ?>
                                        <a href="?page=<?php echo $first_page; ?>&search=<?php echo $_GET["search"]; ?>"
                                            class="bg-gray-200 text-gray-500 px-2 py-1 rounded-md hover:bg-gray-400">
                                            <i class="fas fa-angle-double-left"></i>
                                        </a>
                                        <a href="?page=<?php echo $prev_page; ?>&search=<?php echo $_GET["search"]; ?>"
                                            class="bg-gray-200 text-gray-500 px-2 py-1 rounded-md hover:bg-gray-400">
                                            <i class="fas fa-angle-left"></i>
                                        </a>
                                        <?php
                                            }
                                            // keluakan 2 awal dan 2 akhir dari page saat ini dan buat ...
                                            for($i = 1; $i <= $total_page; $i++){
                                                if($i == $page){
                                                    $active = "bg-blue-400 text-white";
                                                }else{
                                                    $active = "bg-gray-200 text-gray-500 hover:bg-gray-400";
                                                }
                                                if($i > $page - 3 && $i < $page + 3){
                                        ?>
                                        <a href="?page=<?php echo $i; ?>&search=<?php echo $_GET["search"]; ?>"
                                            class="<?php echo $active; ?> px-2 py-1 rounded-md">
                                            <?php echo $i; ?>
                                        </a>
                                        <?php
                                                } else if ($i == $page - 3 || $i == $page + 3){
                                        ?>
                                        <span class="px-2 py-1 rounded-md">...</span>
                                        <?php
                                                }
                                            }
                                            if($page < $total_page){
                                        ?>
                                        <a href="?page=<?php echo $next_page; ?>&search=<?php echo $_GET["search"]; ?>"
                                            class="bg-gray-200 text-gray-500 px-2 py-1 rounded-md hover:bg-gray-400">
                                            <i class="fas fa-angle-right"></i>
                                        </a>
                                        <a href="?page=<?php echo $total_page; ?>&search=<?php echo $_GET["search"]; ?>"
                                            class="bg-gray-200 text-gray-500 px-2 py-1 rounded-md hover:bg-gray-400">
                                            <i class="fas fa-angle-double-right"></i>
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