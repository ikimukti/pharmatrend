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
$twoYearsAgo = $currentYear - 3; // 3 tahun sebelumnya

// Query untuk mengambil data penjualan berdasarkan id_item dan rentang tahun kemudian join dengan tabel items dan users
$query = "SELECT sales.id, sales.id_item, sales.sold, sales.month, sales.year, items.id, items.code, items.name, items.unit, items.price, items.stock, items.created_at, items.updated_at, items.id_user, users.id, users.fullname, users.email, users.phone, users.address, users.photo, users.created_at, users.updated_at FROM sales INNER JOIN items ON sales.id_item = items.id INNER JOIN users ON items.id_user = users.id WHERE sales.id_item = {$_GET['id']} AND sales.year BETWEEN $twoYearsAgo AND $currentYear ORDER BY sales.year ASC, sales.month ASC";

$result = mysqli_query($conn, $query);

// Inisialisasi array untuk menyimpan label dan data
$labels = [];
$data = [];

// Loop melalui hasil query dan tambahkan data ke array
while ($row = mysqli_fetch_assoc($result)) {
    // Ambil bulan dan tahun dari kolom "month" dan "year"
    $month = $row['month'];
    $year = $row['year'];

    // Buat label bulan/tahun dalam format yang diinginkan (misalnya "Jan 2023")
    $label = date('M Y', strtotime("$year-$month-01"));

    // Tambahkan label ke array labels
    $labels[] = $label;

    // Tambahkan jumlah barang yang terjual ke array data
    $data[] = [
        'value' => $row['sold'],
        'price' => $row['price'],
        'revenue' => $row['sold'] * $row['price']
    ];
}
// Mengubah struktur data untuk hanya memuat nilai 'value'
$values = array_map(function ($item) {
    return $item['value'];
}, $data);

// Mengubah format array menjadi string JSON
$valuesJSON = json_encode($values);
// max value dari data $valuesJSON
$maxValue = max($values);

// Mengubah struktur data untuk hanya memuat nilai 'revenue'
$revenues = array_map(function ($item) {
    return $item['revenue'];
}, $data);

// Mengubah format array menjadi string JSON
$revenuesJSON = json_encode($revenues);

// Query untuk mengambil data item berdasarkan id_item
$itemQuery = "SELECT id, code, name, unit, price, stock, created_at, updated_at, id_user FROM items WHERE id = {$_GET['id']}";
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
    <title>Items - PharmaTrend</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                            <a href="dashboard.php" class="text-gray-700 hover:text-gray-950"><i
                                class="fas fa-home"></i></a>
                            <span class="text-gray-700">/</span>
                            <a href="items.php" class="text-gray-700 hover:text-gray-950">Items</a>
                            <span class="text-gray-700">/</span>
                            <!-- page -->
                            <a href="items.php?id=<?php echo $_GET['id']; ?>"
                                class="text-gray-700 hover:text-gray-950"><?php echo $_GET['id']; ?></a>
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
                            <div class="flex flex-col gap-2">
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
                            <div class="w-full h-auto border-2 border-gray-200 rounded-md py-2 px-2">
                                <canvas id="salesChart"></canvas>
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
            var ctx = document.getElementById('salesChart').getContext('2d');
            var salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($labels); ?>,
                    datasets: [{
                        label: 'Penjualan',
                        data: <?php echo $valuesJSON; ?>,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.4,
                        borderWidth: 1,
                        parsing: {
                            xAxisKey: 'date',
                            // get value from data object
                            yAxisKey: 'value'
                        }
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
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
                                suggestedMax: <?php echo $maxValue; ?>,
                            },
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    var data = context.dataset.data[context.dataIndex];
                                    var price = <?php echo $item['price']; ?>;
                                    var revenue = data * price;
                                    revenue = revenue.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
                                    return 'Terjual: ' + data + ' pcs' + ' (' + revenue + ')';
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Penjualan Per Bulan',
                            font: {
                                size: 16,
                                weight: 'bold'
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    }
                }
            });
        </script>
</body>

</html>
