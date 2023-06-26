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

$month = $_GET['month'];
$year = $_GET['year'];

// Query untuk mengambil data penjualan berdasarkan id_item dan rentang bulan dan tahun kemudian join dengan tabel items dan users
$query = "SELECT sales.id, sales.id_item, sales.sold, sales.month, sales.year, items.id, items.code, items.name, items.unit, items.price, items.stock, items.created_at, items.updated_at, items.id_user, users.id, users.fullname, users.email, users.phone, users.address, users.photo, users.created_at, users.updated_at FROM sales INNER JOIN items ON sales.id_item = items.id INNER JOIN users ON items.id_user = users.id WHERE sales.month = $month AND sales.year = $year ORDER BY sales.year ASC, sales.month ASC";

$result = mysqli_query($conn, $query);

// Inisialisasi array untuk menyimpan label dan data
$labels = [];
$data = [];
$totalSold = 0;
$revenue = 0;
// Loop melalui hasil query dan tambahkan data ke array
while ($row = mysqli_fetch_assoc($result)) {
    // Ambil item yang terjual pada bulan dan tahun tersebut
    $item = $row['name'];

    // Tambahkan item ke array labels jika belum ada
    if (!in_array($item, $labels)) {
        $labels[] = $item;
    }

    // Tambahkan jumlah barang yang terjual ke array data
    $data[] = $row['sold'];
    // Ambil jumlah barang yang terjual dan harga barang
    $sold = $row['sold'];
    $price = $row['price'];

    // Tambahkan jumlah barang yang terjual ke totalSold
    $totalSold += $sold;

    // Hitung pendapatan dari barang yang terjual
    $itemRevenue = $sold * $price;
    $revenue += $itemRevenue;

}

// Mengurutkan data secara descending berdasarkan jumlah terjual
arsort($data);
// Mengambil 10 item tertinggi
$topLabels = array_slice($labels, 0, 10);
$topData = array_slice($data, 0, 10);

// 

// Mengubah format array menjadi string JSON
$labelsJSON = json_encode($topLabels);
$dataJSON = json_encode($topData);

// Menentukan jumlah data per halaman
$itemsPerPage = 10;

// Mendapatkan jumlah total data penjualan
$totalRowsQuery = "SELECT COUNT(*) AS total_rows FROM sales WHERE month = $month AND year = $year";
$totalRowsResult = mysqli_query($conn, $totalRowsQuery);
$totalRowsRow = mysqli_fetch_assoc($totalRowsResult);
$totalRows = $totalRowsRow['total_rows'];
// Mendapatkan jumlah halaman
$totalPages = ceil($totalRows / $itemsPerPage);

// Mendapatkan halaman saat ini dari parameter URL
$currentpage = isset($_GET['page']) ? $_GET['page'] : 1;

// Menghitung offset
$offset = ($currentpage - 1) * $itemsPerPage;
// Query baru dengan tambahan LIMIT dan OFFSET
$query2 = "SELECT sales.id, sales.id_item, sales.sold, sales.month, sales.year, items.id, items.code, items.name, items.unit, items.price, items.stock, items.created_at, items.updated_at, items.id_user, users.id, users.fullname, users.email, users.phone, users.address, users.photo, users.created_at, users.updated_at FROM sales INNER JOIN items ON sales.id_item = items.id INNER JOIN users ON items.id_user = users.id WHERE sales.month = $month AND sales.year = $year ORDER BY sales.year ASC, sales.month ASC LIMIT $itemsPerPage OFFSET $offset";

$firstPage = 1;
$lastPage = $currentpage == $totalPages;
$prevPage = $currentpage - 1;
$nextPage = $currentpage + 1;


$result2 = mysqli_query($conn, $query2);

?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Sales Per Month - ARIPSKRIPSI</title>
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
            <div class="w-10/12 h-[calc(100vh-3.5rem)] p-2 md:p-4 lg:p-6 xl:p-8 overflow-y-auto mb-10">
                <!-- container with breadcrumb -->
                <div class="w-full h-auto border-2 border-gray-200 rounded-md py-4 px-6">
                    <!-- breadcrumb -->
                    <div class="flex items-center gap-2 mb-3 justify-between">
                        <div>
                            <a href="dashboard.php" class="text-gray-700 hover:text-gray-950"><i
                                class="fas fa-home"></i></a>
                            <span class="text-gray-700">/</span>
                            <a href="items.php" class="text-gray-700 hover:text-gray-950">Items</a>
                            <span class="text-gray-700">/</span>
                            <!-- page -->
                            <a href="sales_per_month.php" class="text-gray-700 hover:text-gray-950">Sales Per Month</a>
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
                            <div class="my-8">
                                <h1 class="text-3xl font-bold text-gray-800">Detail Sales Per Month</h1>
                                <p class="mt-2 text-sm text-gray-500">Items that have been sold in a certain month and year.</p>
                            </div>
                            <!-- Information This Month -->
                            <div class="my-8">
                                <h2 class="text-md text-gray-800">Month: <?php echo $month; ?></h2>
                                <h2 class="text-md text-gray-800">Year: <?php echo $year; ?></h2>
                                <h2 class="text-md text-gray-800">Total Items Sold: <?php echo number_format($totalSold, 0, ',', '.'); ?></h2>
                                <h2 class="text-md text-gray-800">Total Revenue: Rp <?php echo number_format($revenue, 2, ',', '.'); ?></h2>
                            </div>
                            <div class="w-full h-auto border-2 border-gray-200 rounded-md py-2 px-2">
                                <canvas id="salesChart"></canvas>
                            </div>
                            <div class="w-full h-auto border-2 border-gray-200 rounded-md py-2 px-2">
                                <table class="w-full mt-4">
                                    <thead>
                                        <tr>
                                            <th class="py-2 px-4 bg-gray-100 border-b">
                                                <i class="fas fa-hashtag"></i>
                                                No.
                                            </th>
                                            <th class="py-2 px-4 bg-gray-100 border-b">
                                                <i class="fas fa-box"></i>
                                                Item
                                            </th>
                                            <th class="py-2 px-4 bg-gray-100 border-b">
                                                <i class="fas fa-shopping-cart"></i>
                                                Sold
                                            </th>
                                            <th class="py-2 px-4 bg-gray-100 border-b">
                                                <i class="fas fa-money-bill-wave"></i>
                                                Price
                                            </th>
                                            <th class="py-2 px-4 bg-gray-100 border-b">
                                                <i class="fas fa-money-bill-wave"></i>
                                                Subtotal
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Reset nomor urut
                                        $no = ($currentpage - 1) * $itemsPerPage + 1;

                                        // Loop melalui hasil query dan tambahkan data ke tabel
                                        while ($row = mysqli_fetch_assoc($result2)) {
                                            $itemName = $row['name'];
                                            $itemSold = $row['sold'];
                                            $itemPrice = $row['price'];
                                            $itemSubtotal = $itemSold * $itemPrice;
                                        ?>
                                            <tr>
                                                <td class="text-sm py-2 px-4 border-b"><?php echo $no; ?></td>
                                                <td class="text-sm py-2 px-4 border-b"><?php echo $itemName; ?></td>
                                                <td class="text-sm py-2 px-4 border-b"><?php echo number_format($itemSold, 0, ',', '.'); ?></td>
                                                <td class="text-sm py-2 px-4 border-b">Rp. <?php echo number_format($itemPrice, 2, ',', '.'); ?></td>
                                                <td class="text-sm py-2 px-4 border-b">Rp. <?php echo number_format($itemSubtotal, 2, ',', '.'); ?></td>
                                            </tr>
                                        <?php
                                            $no++;
                                        }
                                        ?>
                                    </tbody>
                                </table>

                                <!-- Pagination -->
                                <div class="flex items-center justify-between mt-4">
                                    <div class="flex items-center">
                                        <p class="text-sm text-gray-700">Total Data Bulan: <?php echo $totalRows; ?></p>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex flex-row items-center justify-end gap-2 mt-2 text-sm">
                                            <?php if ($totalPages > 1) { ?>
                                                <a href="?month=<?php echo $month; ?>&year=<?php echo $year; ?>&page=<?php echo $firstPage; ?>" class="py-2 px-4 bg-blue-500 text-white hover:bg-blue-600">
                                                        <i class="fas fa-angle-double-left"></i>
                                                </a>
                                                <a href="?month=<?php echo $month; ?>&year=<?php echo $year; ?>&page=<?php echo $prevPage; ?>" class="py-2 px-4 bg-blue-500 text-white hover:bg-blue-600">
                                                        <i class="fas fa-angle-left"></i>
                                                </a>
                                                <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                                                    <?php if ($i == $currentpage) { ?>
                                                        <a href="?month=<?php echo $month; ?>&year=<?php echo $year; ?>&page=<?php echo $i; ?>" class="py-2 px-4 bg-blue-500 text-white hover:bg-blue-600"><?php echo $i; ?></a>
                                                    <?php }  else if ($i == $totalPages - 1) { ?>
                                                        <a href="#" class="py-2 px-4 bg-gray-200 hover:bg-gray-300">...</a>
                                                    <?php } else if($i > $currentpage - 3 && $i < $currentpage + 3) { ?>
                                                        <a href="?month=<?php echo $month; ?>&year=<?php echo $year; ?>&page=<?php echo $i; ?>" class="py-2 px-4 bg-gray-200 hover:bg-gray-300"><?php echo $i; ?></a>
                                                    <?php } ?>
                                            <?php } ?>
                                            <a href="?month=<?php echo $month; ?>&year=<?php echo $year; ?>&page=<?php echo $nextPage; ?>" class="py-2 px-4 bg-blue-500 text-white hover:bg-blue-600">
                                                    <i class="fas fa-angle-right"></i>
                                            </a>
                                            <a href="?month=<?php echo $month; ?>&year=<?php echo $year; ?>&page=<?php echo $lastPage; ?>" class="py-2 px-4 bg-blue-500 text-white hover:bg-blue-600">
                                                    <i class="fas fa-angle-double-right"></i>
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
        </div>
        <!-- footer -->
        <?php
        include("components/footer.php");
        ?>
        <script>
    // Mengambil data JSON dari PHP
    var labels = <?php echo $labelsJSON; ?>;
    var data = <?php echo $dataJSON; ?>;

    // Membuat chart menggunakan Chart.js
    var ctx = document.getElementById('salesChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Terjual',
                data: data,
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)',
                        borderWidth: 1
                    },
                    ticks: {
                        beginAtZero: true,
                        callback: function (value, index, values) {
                            return value + ' pcs';
                        },
                        precision: 0,
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return 'Terjual: ' + context.raw + ' pcs';
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Penjualan Per Item',
                    font: {
                        size: 16,
                        weight: 'bold'
                    }
                }
            }
        }
    });
</script>
    </body>
</html>