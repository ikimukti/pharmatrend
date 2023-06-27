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
");
function euclideanDistance($clusterPrice, $clusterSold, $price, $sold) {
    $deltaX = $clusterPrice - $price;
    $deltaY = $clusterSold - $sold;
    $distance = sqrt(pow($deltaX, 2) + pow($deltaY, 2));
    return $distance;
}

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
                    <div class="flex items-center gap-2 mb-3 justify-between">
                        <div>
                            <a href="dashboard.php" class="text-gray-700 hover:text-gray-950"><i class="fas fa-home"></i></a>
                            <span class="text-gray-700">/</span>
                            <a href="sales.php" class="text-gray-700 hover:text-gray-950">Analytics</a>
                            <span class="text-gray-700">/</span>
                            <!-- page -->
                            <a href="sales_per_month.php" class="text-blue-400 hover:text-blue-600">All Time</a>
                        </div>
                        <button class="flex flex-row justify-center items-center bg-gray-200 hover:bg-gray-300 rounded-md px-4 py-2 text-gray-700 space-x-2" onclick="window.history.back();">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back</span>
                        </button>
                    </div>
                    <hr>
                    <!-- content -->
                    <div class="flex flex-col gap-4 mt-4">
                        <h1 class="text-2xl font-bold">Analytics</h1>
                        <p class="text-gray-700">Welcome back, <?php echo $_SESSION['fullname']; ?> !, here's what's
                            happening with your store today.</p>
                        <div class="flex flex-col gap-4">
                        <?php
                        $toogleBtnAnalytics = false;
                        $toogleBtnAnalyticsCounter = 0;
                        while ($salesRow = mysqli_fetch_assoc($sales)) {
                            $salesId = $salesRow['id'];
                            $salesName = $salesRow['name'];
                            $salesTotalMonth = $salesRow['total_month'];
                            $monthBtn = date("m") + 24;
                            if($salesTotalMonth <= $monthBtn){
                        ?>
                        <!-- item link to sales per item -->
                        <div href="sales_per_item.php?id=<?php echo $salesId; ?>" class="w-full h-auto bg-white rounded-md shadow-md p-4 flex flex-row gap-4 items-center">
                            <p>
                                This <span class="font-bold"><?php echo $salesName; ?></span> item has been sold for <?php echo $salesTotalMonth; ?> months, click here to see the analytics.
                            </p>
                            <a href="add_sales_per_item.php?id=<?php echo $salesId; ?>&year=<?php echo $year; ?>" class="flex flex-row justify-center items-center bg-yellow-400 hover:bg-yellow-500 rounded-md px-4 py-2 text-white space-x-2">
                                <i class="fas fa-arrow-right"></i>
                                <span>See Item Detail</span>
                            </a>
                        </div>
                        <?php
                            $toogleBtnAnalyticsCounter++;
                            }
                        }
                        if ($toogleBtnAnalyticsCounter == 0) {
                            $toogleBtnAnalytics = true;
                            // 2 tahun lalu
                            $year = date("Y") - 2;
                            // query untuk mendapatkan semua item yang terjual pada 2 tahun lalu rata-rata sold per month
                            $sales2yearago = "SELECT i.id, i.name, AVG(s.sold) AS total_sold, i.price AS price, i.unit AS unit FROM items i JOIN sales s ON i.id = s.id_item WHERE year = '$year' OR year = '$year' + 1 GROUP BY i.id, i.name";
                            $salesResult = mysqli_query($conn, $sales2yearago);
                            // hitung data yang didapatkan
                            $salesCount = mysqli_num_rows($salesResult);
                            $salesAll = array();
                            while ($salesRow = mysqli_fetch_assoc($salesResult)) {
                                $salesId = $salesRow['id'];
                                $salesName = $salesRow['name'];
                                $salesTotalSold = $salesRow['total_sold'];
                                $salesPrice = $salesRow['price'];
                                $salesUnit = $salesRow['unit'];
                                $dataSales = array(
                                    "id" => $salesId,
                                    "name" => $salesName,
                                    "total_sold" => $salesTotalSold,
                                    "total_sold_per_1000" => $salesTotalSold / 1000,
                                    "price" => $salesPrice,
                                    "price_per_1000" => $salesPrice / 1000,
                                    "unit" => $salesUnit
                                );
                                array_push($salesAll, $dataSales);
                            }
                            $randomSales = array();

                            // Mendapatkan jumlah data yang tersedia
                            $totalData = count($salesAll);

                            // Memastikan jumlah data yang tersedia cukup untuk diambil 3
                            if ($totalData >= 3) {
                                // Mengacak urutan data dalam array
                                shuffle($salesAll);

                                // Mengambil 3 data pertama setelah diacak
                                $randomSales = array_slice($salesAll, 0, 3);
                            } else {
                                // Jika jumlah data kurang dari 3, mengembalikan semua data
                                $randomSales = $salesAll;
                            }
                            $beetweenClassVariation = 0;
                            $totalWithinClassVariation = 0;
                            $rasio = 0;
                            $clusterRun = array(
                                "cluster1" => 0,
                                "cluster2" => 0,
                                "cluster3" => 0
                            );
                            $salesCluster = array();
                            $clusterRepeat = false;
                            $clusterIteration = 0;
                            do{
                                $beetweenClassVariation = 0;
                                $totalWithinClassVariation = 0;
                                foreach ($salesAll as $key => $value) {
                                    $salesId = $salesAll[$key]['id'];
                                    $salesName = $salesAll[$key]['name'];
                                    $salesTotalSold = $salesAll[$key]['total_sold'];
                                    $salesTotalSoldPer1000 = $salesAll[$key]['total_sold_per_1000'];
                                    $salesPrice = $salesAll[$key]['price'];
                                    $salesPricePer1000 = $salesAll[$key]['price_per_1000'];
                                    $salesUnit = $salesAll[$key]['unit'];
                                    $m1 = euclideanDistance($randomSales[0]['total_sold_per_1000'], $salesTotalSoldPer1000, $randomSales[0]['price_per_1000'], $salesPricePer1000);
                                    $m2 = euclideanDistance($randomSales[1]['total_sold_per_1000'], $salesTotalSoldPer1000, $randomSales[1]['price_per_1000'], $salesPricePer1000);
                                    $m3 = euclideanDistance($randomSales[2]['total_sold_per_1000'], $salesTotalSoldPer1000, $randomSales[2]['price_per_1000'], $salesPricePer1000);
                                    $m = array($m1, $m2, $m3);
                                    $mMin = min($m);
                                    $mMax = max($m);
                                    $mMinIndex = array_search($mMin, $m);
                                    if($mMin == $m1) {
                                        $clusterRun['cluster1'] += 1;
                                    } else if($mMin == $m2) {
                                        $clusterRun['cluster2'] += 1;
                                    } else if($mMin == $m3) {
                                        $clusterRun['cluster3'] += 1;
                                    }
                                    $mMaxIndex = array_search($mMax, $m);
                                    $nearestCluster = $mMin;
                                    $withinClassVariation = pow($nearestCluster, 2);
                                    $totalWithinClassVariation += $withinClassVariation;
                                    $dataSales = array(
                                        "id" => $salesId,
                                        "name" => $salesName,
                                        "total_sold" => $salesTotalSold,
                                        "total_sold_per_1000" => $salesTotalSold / 1000,
                                        "price" => $salesPrice,
                                        "price_per_1000" => $salesPrice / 1000,
                                        "unit" => $salesUnit,
                                        "m1" => $m1,
                                        "m2" => $m2,
                                        "m3" => $m3,
                                        "mMin" => $mMin,
                                        "mMinIndex" => $mMinIndex,
                                        "mMax" => $mMax,
                                        "mMaxIndex" => $mMaxIndex,
                                        "nearest_cluster" => $nearestCluster,
                                        "within_class_variation" => $withinClassVariation
                                    );
                                    array_push($salesCluster, $dataSales);
                                } 
                                $rasio = $beetweenClassVariation / $totalWithinClassVariation;
                            } while($clusterRepeat == true);
                            echo "<pre>";
                            echo "Total Data : " . $totalData . "<br>";
                            echo "Total Data Cluster 1 : " . $clusterRun['cluster1'] . "<br>";
                            echo "Total Data Cluster 2 : " . $clusterRun['cluster2'] . "<br>";
                            echo "Total Data Cluster 3 : " . $clusterRun['cluster3'] . "<br>";
                            echo "Total Within Class Variation : " . $totalWithinClassVariation . "<br>";
                            echo "Total Beetween Class Variation : " . $beetweenClassVariation . "<br>";
                            echo "Rasio : " . $rasio . "<br>";
                            echo "Cluster Iteration : " . $clusterIteration . "<br>";
                            print_r($salesCluster);
                            echo "</pre>";
                            $clusterRun = array(
                                "cluster1" => 0,
                                "cluster2" => 0,
                                "cluster3" => 0
                            );
                            do {
                                foreach ($salesAll as $key => $value) {
                                    $salesId = $salesAll[$key]['id'];
                                    $salesName = $salesAll[$key]['name'];
                                    $salesTotalSold = $salesAll[$key]['total_sold'];
                                    $salesTotalSoldPer1000 = $salesAll[$key]['total_sold_per_1000'];
                                    $salesPrice = $salesAll[$key]['price'];
                                    $salesPricePer1000 = $salesAll[$key]['price_per_1000'];
                                    $salesUnit = $salesAll[$key]['unit'];
                                    $m1 = euclideanDistance($randomSales[0]['total_sold_per_1000'], $salesTotalSoldPer1000, $randomSales[0]['price_per_1000'], $salesPricePer1000);
                                    $m2 = euclideanDistance($randomSales[1]['total_sold_per_1000'], $salesTotalSoldPer1000, $randomSales[1]['price_per_1000'], $salesPricePer1000);
                                    $m3 = euclideanDistance($randomSales[2]['total_sold_per_1000'], $salesTotalSoldPer1000, $randomSales[2]['price_per_1000'], $salesPricePer1000);
                                    $m = array($m1, $m2, $m3);
                                    $mMin = min($m);
                                    $mMax = max($m);
                                    $mMinIndex = array_search($mMin, $m);
                                    if($mMin == $m1) {
                                        $clusterRun['cluster1'] += 1;
                                    } else if($mMin == $m2) {
                                        $clusterRun['cluster2'] += 1;
                                    } else if($mMin == $m3) {
                                        $clusterRun['cluster3'] += 1;
                                    }
                                    $mMaxIndex = array_search($mMax, $m);
                                    $nearestCluster = $mMin;
                                    $withinClassVariation = pow($nearestCluster, 2);
                                    $totalWithinClassVariation += $withinClassVariation;
                                }
                                $rasio = $beetweenClassVariation / $totalWithinClassVariation;
                            } while($clusterRepeat == true);
                            echo "<pre>";
                            echo "Total Data : " . $totalData . "<br>";
                            echo "Total Data Cluster 1 : " . $clusterRun['cluster1'] . "<br>";
                            echo "Total Data Cluster 2 : " . $clusterRun['cluster2'] . "<br>";
                            echo "Total Data Cluster 3 : " . $clusterRun['cluster3'] . "<br>";
                            echo "Total Within Class Variation : " . $totalWithinClassVariation . "<br>";
                            echo "Total Beetween Class Variation : " . $beetweenClassVariation . "<br>";
                            echo "Rasio : " . $rasio . "<br>";
                            echo "</pre>";
                        }
                        ?>
                        </div>
                        <!-- item auto row 3 wrap -->
                        <div class="flex flex-wrap gap-4 items-center">
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
                                        <?php if($toogleBtnAnalytics){ ?>
                                        <a href="sales.php"
                                            class="bg-blue-500 hover:bg-blue-600 text-white rounded-md py-2 px-4 text-center text-sm">
                                            <i class="fas fa-chart-line"></i>
                                            View Analytics
                                        </a>
                                        <?php } ?>
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
                                        <?php if($toogleBtnAnalytics){ ?>
                                        <a href="sales.php"
                                            class="bg-blue-500 hover:bg-blue-600 text-white rounded-md py-2 px-4 text-center text-sm">
                                            <i class="fas fa-chart-line"></i>
                                            View Analytics
                                        </a>
                                        <?php } ?>
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
                                        <?php if($toogleBtnAnalytics){ ?>
                                        <a href="sales.php"
                                            class="bg-blue-500 hover:bg-blue-600 text-white rounded-md py-2 px-4 text-center text-sm">
                                            <i class="fas fa-chart-line"></i>
                                            View Analytics
                                        </a>
                                        <?php } ?>
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
