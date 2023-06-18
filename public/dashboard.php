<?php
session_start();
if(!isset($_SESSION["id"])){
    header("Location: signin.php");
    
    die();
}
require_once("config.php");
// Query untuk mengambil data dari tabel sales
$query = "SELECT s.id, s.code, s.sold, s.month, s.year, s.created_at, s.updated_at, s.id_item, s.id_user, i.name
          FROM sales s
          JOIN items i ON s.id_item = i.id";
$result = mysqli_query($conn, $query); // Ganti $conn dengan koneksi database Anda

// Memasukkan data hasil query ke dalam array
$salesData = [];
while ($row = mysqli_fetch_assoc($result)) {
    $salesData[] = $row;
}

// Menyiapkan array untuk menyimpan data penjualan per bulan
$salesByMonth = [];

// Menghitung total penjualan per bulan
foreach ($salesData as $sale) {
    $month = $sale['month'];
    $year = $sale['year'];
    $sold = $sale['sold'];
    
    // Membuat kunci bulan dan tahun dalam format "Bulan Tahun" (contoh: "Januari 2023")
    $monthYearKey = date('F Y', strtotime("$year-$month-01"));
    
    if (!isset($salesByMonth[$monthYearKey])) {
        $salesByMonth[$monthYearKey] = 0;
    }
    
    $salesByMonth[$monthYearKey] += $sold;
}

// Mengurutkan data penjualan per bulan berdasarkan bulan dan tahun secara ascending
ksort($salesByMonth);

// Membuat array labels bulan dan array data penjualan
$labels = array_keys($salesByMonth);
$data = array_values($salesByMonth);

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ARIPSKRIPSI</title>
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
                    <div class="flex items-center gap-2 mb-3">
                        <a href="#" class="text-gray-700 hover:text-gray-950"><i class="fas fa-home"></i></a>
                        <span class="text-gray-700">/</span>
                        <a href="#" class="text-gray-700 hover:text-gray-950">
                            Dashboard
                        </a>
                    </div>
                    <hr>
                    <!-- content -->
                    <div class="flex flex-col gap-4 mt-4">
                        <div class="flex flex-col gap-2">
                            <h1 class="text-2xl font-bold">Dashboard</h1>
                            <p class="text-gray-700">Welcome back, <?php echo $_SESSION['fullname']; ?> !, here's what's
                                happening with your store today. this data is updated once a day.</p>
                            <!-- card info flex forecasting -->
                            <div class="w-full h-auto bg-white rounded-md shadow-md p-4">
                                <div class="flex flex-col gap-2">
                                    <h1 class="text-xl font-bold">Sales Performance</h1>
                                    <p class="text-gray-700">Based on your store's sales data, here's what we predict
                                        you'll make today.</p>
                                    <!-- card info forecasting flex -->
                                    <div class="flex flex-row gap-4">
                                        <canvas id="salesPerformance"></canvas>
                                    </div>
                                </div>
                                <!-- button view -->
                                <div class="flex flex-row justify-end mt-4">
                                    <button
                                        class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded text-sm">
                                        <i class="fas fa-eye"></i>
                                        View Report
                                    </button>
                                </div>
                            </div>
                            <!-- card info flex forecasting -->
                            <div class="w-full h-auto bg-white rounded-md shadow-md p-4">
                                <div class="flex flex-col gap-2">
                                    <h1 class="text-xl font-bold">Item Sales Performance</h1>
                                    <p class="text-gray-700">Based on your store's sales data, here's what we predict
                                        you'll make today.</p>
                                    <!-- card info forecasting flex -->
                                    <div class="flex flex-row gap-4">
                                        <canvas id="itemSalesPerformance"></canvas>
                                    </div>
                                </div>
                                <!-- button view -->
                                <div class="flex flex-row justify-end mt-4">
                                    <button
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        <i class="fas fa-eye"></i>
                                        View Report
                                    </button>
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
        <div>
    </div>
    <script>
        const ctx = document.getElementById('salesPerformance');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Total Penjualan',
                    data: <?php echo json_encode($data); ?>,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        const ctx2 = document.getElementById('itemSalesPerformance');

        function getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i <
                6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }


    </script>
</body>

</html>