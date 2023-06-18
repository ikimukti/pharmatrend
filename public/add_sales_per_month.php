<?php
// Mendapatkan nilai month dan year dari URL
$month = $_GET['month'];
$year = $_GET['year'];

session_start();
if (!isset($_SESSION["id"])) {
    header("Location: signin.php");
    die();
}
require_once("config.php");


// Membuat query untuk mengambil semua item
$sqlItems = "SELECT * FROM items";
$resultItems = $conn->query($sqlItems);

// Membuat query untuk mengambil data penjualan berdasarkan bulan dan tahun
$sqlSales = "SELECT sales.id, sales.code, sales.sold, sales.month, sales.year, sales.created_at, sales.updated_at, sales.id_item, sales.id_user, items.name 
        FROM sales
        INNER JOIN items ON sales.id_item = items.id
        WHERE sales.month = $month AND sales.year = $year";
$resultSales = $conn->query($sqlSales);

// Membuat array untuk menyimpan item yang tidak ada dalam penjualan
$missingItems = [];

// Memeriksa item yang tidak ada dalam penjualan
if ($resultItems->num_rows > 0) {
    while ($rowItems = $resultItems->fetch_assoc()) {
        $itemExists = false;
        if ($resultSales->num_rows > 0) {
            while ($rowSales = $resultSales->fetch_assoc()) {
                if ($rowItems['id'] == $rowSales['id_item']) {
                    $itemExists = true;
                    break;
                }
            }
            // Mengulang kembali ke awal hasil penjualan
            $resultSales->data_seek(0);
        }
        if (!$itemExists) {
            $missingItems[] = $rowItems;
        }
    }
}
// generate random code + check if code already exist + with prefix id_sales and date
$code = "SALE" . date("YmdHis");
require_once("config.php");

// Check if the generated code already exists in the database
$sql = "SELECT * FROM sales WHERE code = '$code'";
$query = mysqli_query($conn, $sql);

while (mysqli_num_rows($query) > 0) {
    $code = "SALE" . date("YmdHis");
    $sql = "SELECT * FROM sales WHERE code = '$code'";
    $query = mysqli_query($conn, $sql);
}

// Handle the form submission
if (isset($_POST['submit'])) {
    // Retrieve the submitted form data
    foreach ($missingItems as $item) {
        $idItem = $item['id'];
        $sold = $_POST['sold_' . $idItem];
        $month = $_GET['month'];
        $year = $_GET['year'];
        $id_user = $_SESSION['id'];

        if ($sold > 0) {
            // Create the INSERT query
            $sql = "INSERT INTO sales (code, sold, month, year, id_item, id_user) VALUES ('$code', '$sold', '$month', '$year', '$idItem', '$id_user')";

            // Execute the INSERT query
            $result = $conn->query($sql);

            // Check if the query was executed successfully
            if ($result) {
                // Redirect to the read sales page
                header("Location: sales_per_month.php");
                die();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Per Month Sales - ARIPSKRIPSI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
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
            <!-- sidebar -->
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
                            <a href="sales.php" class="text-gray-700 hover:text-gray-950">Sales</a>
                            <span class="text-gray-700">/</span>
                            <a
                                href="add_sales_per_item.php?month=<?php echo $_GET["month"]; ?>&year=<?php echo $_GET["year"]; ?>"
                                class="text-gray-700 hover:text-gray-950">Add Sales Per Month</a>
                            <span class="text-gray-700">/</span>
                            <a
                                href="add_sales_per_item.php?month=<?php echo $_GET["month"]; ?>&year=<?php echo $_GET["year"]; ?>"
                                class="text-gray-700 hover:text-gray-950">
                                <?php echo $_GET["year"]; ?> / <?php echo $_GET["month"]; ?>
                            </a>
                        </div>
                        <button onclick="window.history.back()"
                            class="bg-blue-500 hover:bg-blue-600 text-white rounded-md py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-300">
                            <i class="fas fa-arrow-left"></i>
                            Back
                        </button>
                    </div>
                    <hr>
                    <!-- content -->
                    <div class="flex flex-col gap-4 mt-4 mb-16">
                        <?php if (count($missingItems) > 0) { ?>
                        <form
                            action="add_sales_per_month.php?month=<?php echo $_GET["month"]; ?>&year=<?php echo $_GET["year"]; ?>"
                            method="POST">
                            <?php foreach ($missingItems as $item) { ?>
                            <div class="flex flex-col gap-2 mt-2 relative">
                                <label for="sold_<?php echo $item["id"]; ?>"
                                    class="text-sm"><?php echo $item["name"]; ?></label>
                                <input type="number" name="sold_<?php echo $item["id"]; ?>"
                                    id="sold_<?php echo $item["id"]; ?>"
                                    class="w-full border-2 border-gray-200 rounded-md py-2 px-4 focus:outline-none focus:border-gray-300"
                                    placeholder="Jumlah barang terjual" value="" required>
                            </div>
                            <?php } ?>
                            <div class="flex flex-col gap-2 mt-2 relative">
                                <button type="submit" name="submit"
                                    class="bg-blue-500 hover:bg-blue-600 text-white rounded-md py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-300">
                                    <i class="fas fa-plus"></i>
                                    Add Sales
                                </button>
                            </div>
                        </form>
                        <?php } else { ?>
                        <p>Data penjualan tidak ditemukan.</p>
                        <?php } ?>
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
