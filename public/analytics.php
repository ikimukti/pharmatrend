<?php
session_start();
if (!isset($_SESSION["id"])) {
    header("Location: signin.php");
    die();
}
require_once("config.php");

$year = date("Y");
$month = date("m");

// Menghitung Month's Revenue
$monthRevenueQuery = "SELECT SUM(s.sold * i.price) AS monthRevenue
                      FROM sales s
                      JOIN items i ON s.id_item = i.id
                      WHERE s.month = $month AND s.year = $year";
$monthRevenueResult = mysqli_query($conn, $monthRevenueQuery);
$monthRevenueRow = mysqli_fetch_assoc($monthRevenueResult);
$monthRevenue = $monthRevenueRow['monthRevenue'];

// Menghitung Last Month's Revenue
$lastMonth = $month - 1;
$lastMonthYear = $year;
if ($lastMonth == 0) {
    $lastMonth = 12;
    $lastMonthYear = $year - 1;
}
$lastMonthRevenueQuery = "SELECT SUM(s.sold * i.price) AS lastMonthRevenue
                          FROM sales s
                          JOIN items i ON s.id_item = i.id
                          WHERE s.month = $lastMonth AND s.year = $lastMonthYear";
$lastMonthRevenueResult = mysqli_query($conn, $lastMonthRevenueQuery);
$lastMonthRevenueRow = mysqli_fetch_assoc($lastMonthRevenueResult);
$lastMonthRevenue = $lastMonthRevenueRow['lastMonthRevenue'];

// Menghitung Year Revenue
$yearRevenueQuery = "SELECT SUM(s.sold * i.price) AS yearRevenue
                     FROM sales s
                     JOIN items i ON s.id_item = i.id
                     WHERE s.year = $year";
$yearRevenueResult = mysqli_query($conn, $yearRevenueQuery);
$yearRevenueRow = mysqli_fetch_assoc($yearRevenueResult);
$yearRevenue = $yearRevenueRow['yearRevenue'];

// Menghitung Last Year Revenue
$lastYear = $year - 1;
$lastYearRevenueQuery = "SELECT SUM(s.sold * i.price) AS lastYearRevenue
                         FROM sales s
                         JOIN items i ON s.id_item = i.id
                         WHERE s.year = $lastYear";
$lastYearRevenueResult = mysqli_query($conn, $lastYearRevenueQuery);
$lastYearRevenueRow = mysqli_fetch_assoc($lastYearRevenueResult);
$lastYearRevenue = $lastYearRevenueRow['lastYearRevenue'];

// Menghitung All Time Revenue
$allTimeRevenueQuery = "SELECT SUM(s.sold * i.price) AS allTimeRevenue
                        FROM sales s
                        JOIN items i ON s.id_item = i.id";
$allTimeRevenueResult = mysqli_query($conn, $allTimeRevenueQuery);
$allTimeRevenueRow = mysqli_fetch_assoc($allTimeRevenueResult);
$allTimeRevenue = $allTimeRevenueRow['allTimeRevenue'];

// Menghitung jumlah item terjual pada bulan ini
$itemSoldQuery = "SELECT SUM(s.sold) AS itemSold
                  FROM sales s
                  WHERE s.month = $month AND s.year = $year";
$itemSoldResult = mysqli_query($conn, $itemSoldQuery);
$itemSoldRow = mysqli_fetch_assoc($itemSoldResult);
$itemSold = $itemSoldRow['itemSold'];

// Menghitung jumlah item terjual pada tahun ini
$itemSoldYearQuery = "SELECT SUM(s.sold) AS itemSoldYear
                      FROM sales s
                      WHERE s.year = $year";
$itemSoldYearResult = mysqli_query($conn, $itemSoldYearQuery);
$itemSoldYearRow = mysqli_fetch_assoc($itemSoldYearResult);
$itemSoldYear = $itemSoldYearRow['itemSoldYear'];

// Menghitung jumlah item terjual sepanjang waktu
$itemSoldAllTimeQuery = "SELECT SUM(s.sold) AS itemSoldAllTime
                         FROM sales s";
$itemSoldAllTimeResult = mysqli_query($conn, $itemSoldAllTimeQuery);
$itemSoldAllTimeRow = mysqli_fetch_assoc($itemSoldAllTimeResult);
$itemSoldAllTime = $itemSoldAllTimeRow['itemSoldAllTime'];

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - ARIPSKRIPSI</title>
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
                        <!-- Kode HTML lainnya -->
                    </div>
                    <hr>
                    <!-- content -->
                    <div class="flex flex-col gap-4 mt-4">
                        <h1 class="text-2xl font-bold">Analytics</h1>
                        <p class="text-gray-700">Welcome back, <?php echo $_SESSION['fullname']; ?> !, here's what's
                            happening with your store today.</p>
                        <!-- item auto row 3 wrap -->
                        <div class="flex flex-wrap gap-4 items-center">
                            <?php
                                // Mendapatkan bulan dan tahun saat ini
                                $currentYear = date('Y');
                                $currentMonth = date('n');

                                // Membuat array untuk menyimpan bulan dan tahun yang akan ditampilkan
                                $years = array();
                                $months = array();

                                // Menambahkan tahun dan bulan saat ini ke dalam array
                                $years[] = $currentYear;
                                $months[] = $currentMonth;

                                // Menambahkan 2 tahun dan bulan yang lalu ke dalam array
                                for ($i = 1; $i <= 2; $i++) {
                                    $previousYear = $currentYear;
                                    $previousMonth = $currentMonth - $i;
                                    if ($previousMonth <= 0) {
                                        $previousYear--;
                                        $previousMonth += 12;
                                    }
                                    $years[] = $previousYear;
                                    $months[] = $previousMonth;
                                }

                                // Mengubah array tahun dan bulan menjadi string format query
                                $dateConditions = array();
                                for ($i = 0; $i < count($years); $i++) {
                                    $year = $years[$i];
                                    $month = str_pad($months[$i], 2, '0', STR_PAD_LEFT); // Format bulan menjadi 2 digit (01, 02, dst.)
                                    $dateConditions[] = "(s.`year` = $year AND s.`month` = $month)";
                                }

                                // Menggabungkan kondisi tanggal menggunakan operator OR
                                $dateCondition = implode(" OR ", $dateConditions);
                                $whereCondition = "WHERE (s.`year` >= $currentYear - 2 AND s.`month` >= $currentMonth) OR (s.`year` >= $currentYear - 1 AND s.`month` < $currentMonth)";

                                // Query untuk mendapatkan penjualan pada bulan-bulan tersebut
                                $query = "SELECT s.`month`, s.`year`, SUM(s.`sold`) AS total_sales,
                                            (SELECT COUNT(*) FROM `items`) AS total_product,
                                            SUM(s.`sold` * i.`price`) AS income
                                            FROM `sales` s
                                            LEFT JOIN `items` i ON s.`id_item` = i.`id`
                                            $whereCondition
                                            GROUP BY s.`year`, s.`month`
                                            ORDER BY s.`year` DESC, FIELD(s.`month`, '12', '11', '10', '9', '8', '7', '6', '5', '4', '3', '2', '1') ASC";


                                $result = $conn->query($query);

                               // ...

// Mengecek apakah ada bulan yang belum memasukkan penjualan untuk semua item
$missingMonths = array();
while ($row = $result->fetch_assoc()) {
    $year = $row['year'];
    $month = $row['month'];
    $totalSales = $row['total_sales'];
    $totalProduct = $row['total_product'];

    // Mengecek apakah ada penjualan untuk semua item pada bulan tersebut
    if ($totalSales < $totalProduct) {
        $missingMonths[] = array('year' => $year, 'month' => $month);
    }
}

// Menampilkan pesan jika ada bulan yang belum memasukkan penjualan untuk semua item
if (!empty($missingMonths)) {
    echo '<div class="w-full h-auto bg-white rounded-md shadow-md p-4">';
    echo 'Terdapat bulan-bulan berikut yang belum memasukkan penjualan untuk semua item:';
    echo '<ul>';
    foreach ($missingMonths as $missingMonth) {
        $year = $missingMonth['year'];
        $month = $missingMonth['month'];
        echo '<li>' . $month . '/' . $year . '</li>';
    }
    echo '</ul>';
    echo '</div>';
}

// Melanjutkan menampilkan data penjualan seperti biasa
// ...

                                ?>

                            <!-- card info flex forecasting -->
                            <div class="w-full h-auto bg-white rounded-md shadow-md p-4">
                                <div class="flex flex-row gap-2 justify-between">
                                    <div class="flex flex-col gap-2">
                                        <h1 class="text-xl font-bold">
                                            <i class="fas fa-chart-line"></i>
                                            <?php echo "Rp. " . number_format($monthRevenue, 2, ',', '.'); ?>
                                            <!-- span percentage pertumbuhan revenue daru bulan sebelumnya -->
                                            <?php
                                                if ($monthRevenue > $lastMonthRevenue) {
                                                    $percentage = ($monthRevenue - $lastMonthRevenue) / $lastMonthRevenue * 100;?>
                                                <span class="text-green-500 text-sm">
                                                    <i class="fas fa-arrow-up"></i>
                                                    <?php echo "+" . number_format($percentage, 2, ',', '.') . "%"; ?>
                                                </span>
                                            <?php
                                                } else if ($monthRevenue < $lastMonthRevenue) {
                                                    $percentage = ($lastMonthRevenue - $monthRevenue) / $lastMonthRevenue * 100; ?>
                                                <span class="text-red-500 text-sm">
                                                    <i class="fas fa-arrow-down"></i>
                                                    <?php echo "-" . number_format($percentage, 2, ',', '.') . "%"; ?>
                                                </span>
                                            <?php } else { ?>
                                                <span class="text-gray-500 text-sm">
                                                    <i class="fas fa-minus"></i>
                                                    0%
                                                </span>
                                            <?php } ?>
                                        </h1>
                                        <h2 class="text-gray-700 text-sm">
                                            <i class="fas fa-calendar"></i>
                                            Rp. <?php echo number_format($lastMonthRevenue, 2, ',', '.'); ?>
                                            last month
                                        </h2>
                                        <h2 class="text-gray-700">
                                            <i class="fas fa-shopping-cart"></i>
                                            <?php echo number_format($itemSold, 0, ',', '.'); ?> items sold this month
                                        </h2>
                                        <p class="text-gray-700">Month's Revenue</p>
                                    </div>
                                    <div class="flex flex-row gap-2 justify-end items-end">
                                        <a href="sales.php"
                                            class="bg-blue-500 hover:bg-blue-600 text-white rounded-md py-2 px-4 text-center text-sm">
                                            <i class="fas fa-shopping-cart"></i>
                                            View Sales
                                        </a>
                                        <a href="sales.php"
                                            class="bg-blue-500 hover:bg-blue-600 text-white rounded-md py-2 px-4 text-center text-sm">
                                            <i class="fas fa-chart-line"></i>
                                            View Analytics
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- card info flex forecasting -->
                            <div class="w-full h-auto bg-white rounded-md shadow-md p-4">
                                <div class="flex flex-col gap-2">
                                    <div class="flex flex-col gap-2">
                                        <h1 class="text-xl font-bold">
                                            <i class="fas fa-chart-line"></i>
                                            <?php echo "Rp. " . number_format($yearRevenue, 2, ',', '.'); ?>
                                            <!-- span percentage pertumbuhan revenue daru tahun sebelumnya -->
                                            <?php
                                                if ($yearRevenue > $lastYearRevenue) {
                                                    $percentage = ($yearRevenue - $lastYearRevenue) / $lastYearRevenue * 100;?>
                                                <span class="text-green-500 text-sm">
                                                    <i class="fas fa-arrow-up"></i>
                                                    <?php echo "+" . number_format($percentage, 2, ',', '.') . "%"; ?>
                                                </span>
                                            <?php
                                                } else if ($yearRevenue < $lastYearRevenue) {
                                                    $percentage = ($yearRevenue - $lastYearRevenue) / $lastYearRevenue * 100; ?>
                                                <span class="text-red-500 text-sm">
                                                    <i class="fas fa-arrow-down"></i>
                                                    <?php echo "" . number_format($percentage, 2, ',', '.') . "%"; ?>
                                                </span>
                                            <?php } else { ?>
                                                <span class="text-gray-500 text-sm">
                                                    <i class="fas fa-minus"></i>
                                                    0%
                                                </span>
                                            <?php } ?>
                                        </h1>
                                        <h2 class="text-gray-700 text-sm">
                                            <i class="fas fa-calendar"></i>
                                            Rp. <?php echo number_format($lastYearRevenue, 2, ',', '.'); ?>
                                            last year
                                        </h2>
                                        <h2 class="text-gray-700">
                                            <i class="fas fa-shopping-cart"></i>
                                            <?php echo number_format($itemSoldYear, 0, ',', '.'); ?> items sold this year
                                        </h2>
                                        <p class="text-gray-700">Year's Revenue</p>
                                    </div>
                                    <div class="flex flex-row gap-2 justify-end items-end">
                                        <a href="sales.php"
                                            class="bg-blue-500 hover:bg-blue-600 text-white rounded-md py-2 px-4 text-center text-sm">
                                            <i class="fas fa-shopping-cart"></i>
                                            View Sales
                                        </a>
                                        <a href="sales.php"
                                            class="bg-blue-500 hover:bg-blue-600 text-white rounded-md py-2 px-4 text-center text-sm">
                                            <i class="fas fa-chart-line"></i>
                                            View Analytics
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- card info flex forecasting -->
                            <div class="w-full h-auto bg-white rounded-md shadow-md p-4">
                                <div class="flex flex-row gap-2 justify-between">
                                    <div class="flex flex-col gap-2">
                                        <h1 class="text-xl font-bold">
                                            <i class="fas fa-chart-line"></i>
                                            <?php echo "Rp. " . number_format($allTimeRevenue, 2, ',', '.'); ?>
                                        </h1>
                                        <h2 class="text-gray-700">
                                            <i class="fas fa-shopping-cart"></i>
                                            <?php echo number_format($itemSoldAllTime, 0, ',', '.'); ?> items sold all time
                                        </h2>
                                        <p class="text-gray-700">All Time Revenue</p>
                                    </div>
                                    <div class="flex flex-row gap-2 justify-end items-end">
                                        <a href="sales.php"
                                            class="bg-blue-500 hover:bg-blue-600 text-white rounded-md py-2 px-4 text-center text-sm">
                                            <i class="fas fa-shopping-cart"></i>
                                            View Sales
                                        </a>
                                        <a href="sales.php"
                                            class="bg-blue-500 hover:bg-blue-600 text-white rounded-md py-2 px-4 text-center text-sm">
                                            <i class="fas fa-chart-line"></i>
                                            View Analytics
                                        </a>
                                    </div>
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
