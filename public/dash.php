<?php
session_start();
if(!isset($_SESSION["id"])){
    ob_start();
    header("Location: signin.php");
    
    die();
}
require_once("config.php");

// Query untuk mengambil data penjualan dari tabel sales
$sql = "SELECT sales.month, sales.year, sales.sold, items.name, items.id
        FROM sales
        INNER JOIN items ON sales.id_item = items.id";
$result = $conn->query($sql);

// Array untuk menyimpan data penjualan per bulan dan tahun
$salesNumber = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $monthYear = $row['month'] . ' ' . $row['year'];
        $item = array(
            'iditem' => $row['id'],
            'name' => $row['name'],
            'sales' => $row['sold']
        );

        // Jika data penjualan untuk bulan dan tahun tertentu belum ada, tambahkan ke array salesNumber
        if (!isset($salesNumber[$monthYear])) {
            $salesNumber[$monthYear] = array();
        }

        // Tambahkan data penjualan item ke array salesNumber
        array_push($salesNumber[$monthYear], $item);
    }
}

// Fungsi untuk mengurutkan data penjualan berdasarkan jumlah penjualan
function sortBySales($a, $b) {
    return $b['sales'] - $a['sales'];
}

// Urutkan data penjualan setiap bulan berdasarkan jumlah penjualan
foreach ($salesNumber as &$sales) {
    usort($sales, 'sortBySales');
}

// Ambil 5 item terlaris dari setiap bulan
$itemSalesPerformance = array();
foreach ($salesNumber as $monthYear => $sales) {
    $topItems = array_slice($sales, 0, 5);
    $itemSalesPerformance[$monthYear] = $topItems;
}

// Cetak itemSalesPerformance
echo "<pre>";
print_r($itemSalesPerformance);
echo "</pre>";

// Tutup koneksi ke database
$conn->close();
?>
