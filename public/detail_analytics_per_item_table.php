<?php
session_start();
if (!isset($_SESSION["id"])) {
    ob_start();
    header("Location: signin.php");

    die();
}
require_once("config.php");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

# 1 tahun saja
$currentYear = date('Y'); // Tahun saat ini

$query = "SELECT * FROM trends_moment WHERE id_item = {$_GET['id']} AND year = $currentYear ORDER BY month ASC";



$result = mysqli_query($conn, $query);


// Query untuk mengambil data item berdasarkan id_item
$itemQuery = "SELECT i.id, i.code, i.name, i.unit, i.price, i.stock, i.created_at, i.updated_at, i.id_user, c.id AS cluster_id, c.cluster, c.cluster_sold, c.cluster_price, c.cluster_sold_per_1000, c.cluster_price_per_1000, c.lowest, c.middle, c.highest, c.category
FROM items AS i
JOIN sales_cluster AS sc ON i.id = sc.id_item
JOIN clustering AS c ON sc.nearest_cluster = c.id
WHERE i.id = {$_GET['id']}";
$itemResult = mysqli_query($conn, $itemQuery);
$item = mysqli_fetch_assoc($itemResult);

// Query untuk mengambil data user penambah item
$userQuery = "SELECT id, fullname, email, phone, address, photo, created_at, updated_at FROM users WHERE id = {$item['id_user']}";
$userResult = mysqli_query($conn, $userQuery);
$user = mysqli_fetch_assoc($userResult);

// Query untuk menghitung total pendapatan dari item ini
$totalRevenueQuery = "SELECT SUM(sold) AS total_revenue FROM sales WHERE id_item = {$_GET['id']}";
$totalRevenueResult = mysqli_query($conn, $totalRevenueQuery);
$totalRevenueRow = mysqli_fetch_assoc($totalRevenueResult);
$totalRevenue = $totalRevenueRow['total_revenue'];

// Query untuk menghitung pendapatan bulan saat ini
$currentMonth = date('m');
$currentYear = date('Y');
$currentMonthRevenueQuery = "SELECT SUM(sold) AS current_month_revenue FROM sales WHERE id_item = {$_GET['id']} AND month = $currentMonth AND year = $currentYear";
$currentMonthRevenueResult = mysqli_query($conn, $currentMonthRevenueQuery);
$currentMonthRevenueRow = mysqli_fetch_assoc($currentMonthRevenueResult);
$currentMonthRevenue = $currentMonthRevenueRow['current_month_revenue'];
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                            <a href="items.php" class="text-gray-700 hover:text-gray-950">Items</a>
                            <span class="text-gray-700">/</span>
                            <!-- page -->
                            <a href="items.php?id=<?php echo $_GET['id']; ?>" class="text-gray-700 hover:text-gray-950"><?php echo $_GET['id']; ?></a>
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
                                    <h1 class="text-2xl font-bold">Items</h1>
                                </div>

                            </div>
                            <!-- information item -->
                            <div class="flex-1 flex flex-row gap-4">
                                <div class="flex-1 flex-col gap-2">
                                    <div>
                                        <i class="fas fa-info-circle"></i>
                                        <span class="font-bold">Item Code:</span>
                                        <span><?php echo $item['code']; ?></span>
                                    </div>
                                    <div>
                                        <!-- box -->
                                        <i class="fas fa-box"></i>
                                        <span class="font-bold">Item Name:</span>
                                        <span><?php echo $item['name']; ?></span>
                                    </div>
                                    <div>
                                        <!-- category -->
                                        <i class="fas fa-tags"></i>
                                        <span class="font-bold">Category:</span>
                                        <span><?php echo $item['category']; ?></span>
                                    </div>
                                    <div>
                                        <!-- unit -->
                                        <i class="fas fa-boxes"></i>
                                        <span class="font-bold">Unit:</span>
                                        <span><?php echo $item['unit']; ?></span>
                                    </div>
                                    <div>
                                        <!-- Price -->
                                        <i class="fas fa-money-bill-wave"></i>
                                        <span class="font-bold">Price:</span>
                                        <span>Rp. <?php echo number_format($item['price'], 2, ',', '.'); ?></span>
                                    </div>
                                </div>
                                <div class="flex-1 flex-col gap-2">
                                    <div>
                                        <i class="fas fa-calendar-day"></i>
                                        <span class="font-bold">Created At:</span>
                                        <span><?php echo date('d M Y', strtotime($item['created_at'])); ?></span>
                                    </div>
                                    <div>
                                        <i class="fas fa-calendar-day"></i>
                                        <span class="font-bold">Updated At:</span>
                                        <span><?php echo date('d M Y', strtotime($item['updated_at'])); ?></span>
                                    </div>
                                    <div>
                                        <!-- user -->
                                        <i class="fas fa-user"></i>
                                        <span class="font-bold">Added By:</span>
                                        <span>
                                            <?php
                                            // First capital per word
                                            echo ucwords($user['fullname']);
                                            ?></span>
                                    </div>
                                    <div>
                                        <!-- Revenue -->
                                        <i class="fas fa-money-bill-wave"></i>
                                        <span class="font-bold">Total Revenue:</span>
                                        <span><?php echo number_format($totalRevenue, 0, ',', '.'); ?> pcs sold (Rp.
                                            <?php echo number_format($totalRevenue * $item['price'], 2, ',', '.'); ?>)
                                        </span>
                                    </div>
                                    <div>
                                        <!-- Revenue -->
                                        <i class="fas fa-money-bill-wave"></i>
                                        <span class="font-bold">Current Month Revenue:</span>
                                        <span><?php echo number_format($currentMonthRevenue, 0, ',', '.'); ?> pcs sold
                                            <?php echo number_format($currentMonthRevenue * $item['price'], 2, ',', '.'); ?>)
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!-- table -->
                            <table class="table-auto w-full">
                                <thead>
                                    <tr>
                                        <th class="border-2 border-gray-200 px-2 py-1 text-sm">No</th>
                                        <th class="border-2 border-gray-200 px-2 py-1 text-sm">Date</th>
                                        <th class="border-2 border-gray-200 px-2 py-1 text-sm">Aktual</th>
                                        <th class="border-2 border-gray-200 px-2 py-1 text-sm">Index Musim</th>
                                        <th class="border-2 border-gray-200 px-2 py-1 text-sm">Trend Moment</th>
                                        <th class="border-2 border-gray-200 px-2 py-1 text-sm">APE</th>
                                        <th class="border-2 border-gray-200 px-2 py-1 text-sm">Accuracy</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    // Inisialisasi array untuk menyimpan label dan data
                                    $labels = [];
                                    $data = [];
                                    $totalAPE = 0;
                                    $totalAccuracy = 0;
                                    $totalMAPE = 0;
                                    $firstDate = '';
                                    $lastDate = '';
                                    $totalIndexMusim = 0;
                                    $totalTrendMoment = 0;
                                    $totalAktual = 0;

                                    $jmdata = 0;
                                    // Loop melalui hasil query dan tambahkan data ke array
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        // Ambil bulan dan tahun dari kolom "month" dan "year"
                                        $month = $row['month'];
                                        $year = $row['year'];
                                        $id_item = $row['id_item'];
                                        $trend = $row['forecast'];
                                        $totalAPE += $row['ape'];
                                        $totalAccuracy += $row['accuracy'];
                                        if ($row['mape'] != 0) {
                                            $totalMAPE = $row['mape'];
                                        }
                                        $totalIndexMusim += $row['indexMusim'];
                                        $totalTrendMoment += $row['forecast'];



                                        // get first date
                                        if ($firstDate == '') {
                                            $lastDate = date('d M Y', strtotime("$year-$month-01"));
                                        }



                                        // Buat label bulan/tahun dalam format yang diinginkan (misalnya "Jan 2023")
                                        $label = date('M Y', strtotime("$year-$month-01"));

                                        // Tambahkan label ke array labels
                                        $labels[] = $label;

                                        $sqlSold = "SELECT * FROM sales WHERE id_item = '$id_item' AND month = '$month' AND year = '$year'";
                                        $resultSold = mysqli_query($conn, $sqlSold);
                                        $rowSold = mysqli_fetch_assoc($resultSold);

                                        if ($rowSold) {
                                            // Data penjualan ditemukan
                                            $sold = $rowSold['sold'];
                                            $firstDate = date('d M Y', strtotime("$year-$month-01"));
                                            $jmdata++;
                                            // get last date
                                            // Lakukan tindakan yang diinginkan dengan data penjualan yang ditemukan
                                        } else {
                                            // Data penjualan tidak ditemukan
                                            // Lakukan tindakan alternatif yang sesuai dalam kasus ini
                                            $sold = 0;
                                        }
                                        $totalAktual += $sold;
                                    ?>
                                        <tr>
                                            <td class="border-2 border-gray-200 px-2 py-1 text-sm"><?php echo $no++; ?></td>
                                            <td class="border-2 border-gray-200 px-2 py-1 text-sm"><?php echo $label; ?></td>
                                            <td class="border-2 border-gray-200 px-2 py-1 text-sm"><?php echo $sold; ?></td>
                                            <td class="border-2 border-gray-200 px-2 py-1 text-sm"><?php echo number_format($row['indexMusim'], 4); ?></td>
                                            <td class="border-2 border-gray-200 px-2 py-1 text-sm"><?php echo number_format($trend, 2); ?></td>
                                            <td class="border-2 border-gray-200 px-2 py-1 text-sm"><?php echo number_format($row['ape'], 2); ?> %</td>
                                            <td class="border-2 border-gray-200 px-2 py-1 text-sm"><?php echo number_format($row['accuracy'], 2); ?> %</td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- explanation of the results of trend moment analysis yielding APE, ACCURACY and MAPE values with average results -->
                        <div class="mt-4">
                            <h2 class="text-xl font-bold">Results:</h2>
                            <p class="mt-2">The results of the trend moment analysis are as follows:</p>
                            <p class="mt-2">
                                After analyzing the trend moment, from <?php echo $lastDate; ?> to <?php echo $firstDate; ?> period using previous sales data, the drug named
                                <span class="text-red-500"><?php echo $item['name']; ?></span> has a trend moment value of
                                yielded the following results: the average APE (Absolute Percentage Error) value is
                                <span class="text-red-500"><?php echo number_format($totalAPE / $jmdata, 2); ?>%</span>,
                                the average accuracy is
                                <span class="text-red-500"><?php echo number_format($totalAccuracy / $jmdata, 2); ?>%</span>,
                                and the average MAPE (Mean Absolute Percentage Error) value is
                                <span class="text-red-500"><?php echo number_format($totalMAPE, 2); ?>%</span>.
                                Therefore, it can be concluded that the trend moment prediction for the drug <?php echo $item['name']; ?> in the
                                <?php echo $lastDate; ?> to <?php echo $firstDate; ?> period has an accuracy level of <?php if ($totalMAPE  < 10) { ?>
                                    <span class="bg-green-500 text-white px-2 py-1 rounded">Very Good</span>
                                <?php } elseif ($totalMAPE  < 20) { ?>
                                    <span class="bg-green-500 text-white px-2 py-1 rounded">Good</span>
                                <?php } elseif ($totalMAPE  < 50) { ?>
                                    <span class="bg-yellow-500 text-white px-2 py-1 rounded">Acceptable</span>
                                <?php } else { ?>
                                    <span class="bg-red-500 text-white px-2 py-1 rounded">Poor</span>
                                    <?php } ?>.
                            </p>
                            <ul class="list-disc pl-6 mt-2">
                                <li> MAPE: <?php echo number_format($totalMAPE, 2); ?> %</li>
                            </ul>
                            <ul class="list-disc pl-6">
                                <li>Average APE: <?php echo number_format($totalAPE / $jmdata, 2); ?> %</li>
                                <li>Average Accuracy: <?php echo number_format($totalAccuracy / $jmdata, 2); ?> %</li>
                            </ul>
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