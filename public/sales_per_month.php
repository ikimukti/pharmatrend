<?php
session_start();
if (!isset($_SESSION["id"])) {
    ob_start();
    header("Location: signin.php");
    die();
}
require_once("config.php");

// Periksa apakah ada tahun yang dipilih
if (!isset($_GET["year"])) {
    // Redirect ke halaman dengan query string kosong untuk menampilkan semua data
    ob_start();
    header("Location: sales_per_month.php?page=1&year=");
    die();
}

// Ambil tahun dari query string
$selectedYear = $_GET["year"];

// Query untuk mengambil bulan, tahun, total item terjual, total produk, dan total penjualan dari tabel sales dan items
$limit = 12;
$page = isset($_GET["page"]) ? $_GET["page"] : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;

// Buat kondisi WHERE berdasarkan tahun yang dipilih atau tampilkan semua data jika tahun kosong
$whereCondition = $selectedYear ? "WHERE s.`year` = $selectedYear" : "";

$query = "SELECT s.`month`, s.`year`, SUM(s.`sold`) AS total_sales, COUNT(i.`id`) AS total_product,
            SUM(s.`sold` * i.`price`) AS income
            FROM `sales` s
            LEFT JOIN `items` i ON s.`id_item` = i.`id`
            $whereCondition
            GROUP BY s.`year`, s.`month`
            ORDER BY s.`year` DESC, FIELD(s.`month`, '12', '11', '10', '9', '8', '7', '6', '5', '4', '3', '2', '1') ASC
            LIMIT $start, $limit";
$result = $conn->query($query);

$totalItemQuery = "SELECT COUNT(`id`) AS total_item FROM `items`";
$totalItemResult = $conn->query($totalItemQuery);
$totalItem = $totalItemResult->fetch_assoc()['total_item'];

$totalSalesQuery = "SELECT COUNT(*) AS total_sales FROM (SELECT DISTINCT `year`, `month` FROM `sales`) AS subquery";
$totalSalesResult = $conn->query($totalSalesQuery);
$totalSales = $totalSalesResult->fetch_assoc()['total_sales'];

$totalPage = ceil($totalSales / $limit);
$totalData = $totalSales;
$firstPage = 1;
$prevPage = ($page > 1) ? $page - 1 : 1;
$nextPage = ($page < $totalPage) ? $page + 1 : $totalPage;
$lastPage = $totalPage;
$no = $start + 1;
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Per Month - ARIPSKRIPSI</title>
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
                        <a href="dashboard.php" class="text-gray-700 hover:text-gray-950"><i class="fas fa-home"></i></a>
                        <span class="text-gray-700">/</span>
                        <a href="sales.php" class="text-gray-700 hover:text-gray-950">Sales</a>
                        <span class="text-gray-700">/</span>
                        <!-- page -->
                        <a href="sales_per_month.php" class="text-blue-400 hover:text-blue-600">Sales Per Month</a>
                    </div>
                    <hr>
                    <!-- content -->
                    <div class="flex flex-col gap-4 mt-4">
                        <div class="flex flex-col gap-2">
                            <!-- flex row -->
                            <div class="flex flex-row items-center justify-between">
                                <div class="flex flex-row items-center gap-2">
                                    <h1 class="text-2xl font-bold">Sales Per Month</h1>

                                </div>
                                <div class="flex flex-row items-center gap-2">
                                    <form action="sales_per_month.php" method="GET" class="flex flex-row items-center gap-2">
                                        <select name="year" id="year" class="border-2 border-gray-200 rounded-md px-4 py-2 focus:outline-none focus:border-blue-400">
                                            <option value="">All Year</option>
                                            <?php
                                            $currentYear = date("Y");
                                            for ($i = $currentYear; $i >= $currentYear - 4; $i--) {
                                                $selected = isset($_GET['year']) && $_GET['year'] == $i ? "selected" : "";
                                                ?>
                                                <option value="<?= $i ?>" <?= $selected ?>>
                                                    <?= $i ?>
                                                </option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <button type="submit" class="bg-blue-400 text-white px-4 py-2 rounded ml-4 my-2 hover:bg-blue-600">
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
                                                <i class="fas fa-calendar"></i>
                                                Date
                                            </th>
                                            <th class="px-2 py-2">
                                                <i class="fas fa-boxes"></i>
                                                Total Sales
                                            </th>
                                            <th class="px-2 py-2">
                                                <i class="fas fa-boxes"></i>
                                                Total Product Sold
                                            </th>
                                            <th class="px-2 py-2">
                                                <i class="fas fas fa-money-bill-wave"></i>
                                                Income
                                            </th>
                                            <th class="px-2 py-2">
                                                <i class="fas fa-cog"></i>
                                                Action  
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            while($row = mysqli_fetch_assoc($result)) {
                                                $month = $row['month'];
                                                $year = $row['year'];
                                                $totalSales = $row['total_sales'];
                                                $totalProduct = $row['total_product'];
                                                $income = $row['income'];
                                        ?>
                                        <tr class="border-b-2 border-gray-200">
                                            <td class="px-2 py-2">
                                                <?php echo $no++; ?>
                                            </td>
                                            <td class="px-2 py-2">
                                                <a href="detail_sales_per_month.php?month=<?php echo $month; ?>&year=<?php echo $year; ?>"
                                                    class="text-blue-400 hover:text-blue-600">
                                                    <?php 
                                                        // show month and year ubah format bulan angka ke nama
                                                        echo date("F", mktime(0, 0, 0, $month, 10)) . " " . $year;
                                                    ?>
                                                </a>
                                            </td>
                                            <td class="px-2 py-2">
                                                <?php echo number_format($totalSales, 0, '.', ',') ?> Items
                                            </td>
                                            <td class="px-2 py-2">
                                                <?php echo number_format($totalProduct, 0, '.', ',') ?> Items
                                            </td>
                                            <td class="px-2 py-2">
                                                Rp. <?php echo number_format($income, 2, '.', ','); ?>
                                            </td>
                                            <td class="px-2 py-2">
                                                <?php
                                                    // if total product less than total item then show add sales button 
                                                    if ($totalProduct < $totalItem) { ?>
                                                        <a href="add_sales_per_month.php?month=<?php echo $month; ?>&year=<?php echo $year; ?>"
                                                            class="bg-green-400 text-white px-4 py-2 rounded hover:bg-green-600 mr-2">
                                                            <i class="fas fa-plus"></i> Add Sales
                                                        </a>
                                                    <?php
                                                    } else {?>
                                                    <span href="#"
                                                        class=" text-gray-500 px-4 py-2 rounde">
                                                        <i class="fas fa-check"></i> Added
                                                    </span>
                                                    <?php
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>
                                <!-- div space between left total and right pagination -->
                                <div class="flex flex-row items-center justify-between mt-2">
                                    <div>
                                        <p class="text-sm text-gray-700">Total Data Bulan: <?php echo $totalData; ?></p>
                                    </div>
                                    <!-- pagination with number -->
                                    <?php if ($_GET["year"] == ""){?>
                                    <div class="flex flex-row items-center justify-end gap-2 mt-2 text-sm">
                                        <?php if ($totalPage > 1) { ?>
                                            <a href="sales_per_month.php?page=<?php echo $firstPage; ?>&year=<?php echo isset($_GET["year"]) ? $_GET["year"] : ""; ?>" class="bg-gray-200 text-gray-500 px-2 py-1 rounded-md hover:bg-gray-400">
                                                <i class="fas fa-angle-double-left"></i>
                                            </a>
                                            <a href="sales_per_month.php?page=<?php echo $prevPage; ?>&year=<?php echo isset($_GET["year"]) ? $_GET["year"] : ""; ?>" class="bg-gray-200 text-gray-500 px-2 py-1 rounded-md hover:bg-gray-400">
                                                <i class="fas fa-angle-left"></i>
                                            </a>
                                            <?php for ($i = 1; $i <= $totalPage; $i++) {
                                                // keluarkan 2 awal dan 2 akhir dari page saat ini dan buat ...
                                                for ($i = 1; $i <= $totalPage; $i++) {
                                                    if ($i == $page) { ?>
                                                        <a href="sales_per_month.php?page=<?php echo $i; ?>&year=<?php echo isset($_GET["year"]) ? $_GET["year"] : ""; ?>" class="bg-blue-500 text-white px-2 py-1 rounded-md hover:bg-blue-600">
                                                            <?php echo $i; ?>
                                                        </a>
                                                    <?php } else if ($i == $totalPage - 1) { ?>
                                                        <a href="sales_per_month.php?page=<?php echo $i; ?>&year=<?php echo isset($_GET["year"]) ? $_GET["year"] : ""; ?>" class="bg-gray-200 text-gray-500 px-2 py-1 rounded-md hover:bg-gray-400">
                                                            ...
                                                        </a>
                                                    <?php } else if($i > $page - 3 && $i < $page + 3){ ?>
                                                        <a href="sales_per_month.php?page=<?php echo $i; ?>&year=<?php echo isset($_GET["year"]) ? $_GET["year"] : ""; ?>" class="bg-gray-200 text-gray-500 px-2 py-1 rounded-md hover:bg-gray-400">
                                                            <?php echo $i; ?>
                                                        </a>
                                                    <?php } 
                                                }
                                            } ?>
                                            <a href="sales_per_month.php?page=<?php echo $nextPage; ?>&year=<?php echo isset($_GET["year"]) ? $_GET["year"] : ""; ?>" class="bg-gray-200 text-gray-500 px-2 py-1 rounded-md hover:bg-gray-400">
                                                Next
                                            </a>
                                            <a href="sales_per_month.php?page=<?php echo $lastPage; ?>&year=<?php echo isset($_GET["year"]) ? $_GET["year"] : ""; ?>" class="bg-gray-200 text-gray-500 px-2 py-1 rounded-md hover:bg-gray-400">
                                                Last
                                            </a>
                                        <?php } ?>
                                    </div>
                                    <?php } ?>
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