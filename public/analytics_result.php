<?php
session_start();
if (!isset($_SESSION["id"])) {
    ob_start();
    header("Location: signin.php");

    die();
}
if (!isset($_GET["search"])) {
    ob_start();
    header("Location: analytics_result.php?page=1&search=");
    die();
}
require_once("config.php");
// item data with pagination and descending order
$limit = 50;
$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;
$currentDate = date("Y-m-d");
$currentYear = date("Y");
$currentMonth = date("m");

$items = mysqli_query($conn, "SELECT i.code, i.name, i.unit, i.price, i.id AS id_item, s.id AS id_sales, s.nearest_cluster AS cluster, c.category AS category,
tm.ape AS ape, tm.mape AS mape, tm.forecast AS forecast, tm.accuracy AS accuracy, sa.sold AS sales_real
FROM items AS i
JOIN sales_cluster AS s ON i.id = s.id_item
JOIN sales AS sa ON sa.id_item = i.id
JOIN clustering AS c ON s.nearest_cluster = c.id
LEFT JOIN trends_moment AS tm ON tm.id_item = i.id AND tm.month = $currentMonth AND tm.year = $currentYear
ORDER BY category DESC, cluster ASC, i.name ASC
LIMIT $start, $limit");

$items_all = mysqli_query($conn, "SELECT i.code, i.name, i.unit, i.price, i.id AS id_item, s.id AS id_sales, s.nearest_cluster AS cluster, c.category AS category,
tm.ape AS ape, tm.mape AS mape, tm.forecast AS forecast, tm.accuracy AS accuracy, tm.sales_real AS sales_real
FROM items AS i
JOIN sales_cluster AS s ON i.id = s.id_item
JOIN clustering AS c ON s.nearest_cluster = c.id
JOIN trends_moment AS tm ON tm.id_item = i.id AND tm.month = $currentMonth AND tm.year = $currentYear
ORDER BY category DESC, cluster ASC, i.name ASC");

$totalMAPE = 0;
$accuracyLevel = "";

while ($item = mysqli_fetch_array($items_all)) {
    $totalMAPE += $item["mape"];
}
$totalMAPE = $totalMAPE / mysqli_num_rows($items_all);
if ($totalMAPE  < 10) {
    $accuracyLevel = '<span class="bg-green-500 text-white px-2 py-1 rounded">Very Good</span>';
} else if ($totalMAPE  < 20) {
    $accuracyLevel = '<span class="bg-green-400 text-white px-2 py-1 rounded">Good</span>';
} else if ($totalMAPE  < 30) {
    $accuracyLevel = '<span class="bg-yellow-400 text-white px-2 py-1 rounded">Normal</span>';
} else if ($totalMAPE  < 50) {
    $accuracyLevel = '<span class="bg-yellow-500 text-white px-2 py-1 rounded">Bad</span>';
} else {
    $accuracyLevel = '<span class="bg-red-500 text-white px-2 py-1 rounded">Very Bad</span>';
}

$total = mysqli_num_rows($items_all);
$pages = ceil($total / $limit);
$first_page = 1;
$prev_page = $page - 1;
$next_page = $page + 1;
$no = $start + 1;

// search item
if (isset($_GET["search"])) {
    $search = $_GET["search"];
    $currentDate = date("Y-m-d");
    $currentYear = date("Y");
    $currentMonth = date("m");

    $items = mysqli_query($conn, "SELECT i.code, i.name, i.unit, i.price, i.id AS id_item, s.id AS id_sales, s.nearest_cluster AS cluster, c.category AS category,
    tm.ape AS ape, tm.mape AS mape, tm.forecast AS forecast, tm.accuracy AS accuracy, tm.sales_real AS sales_real
    FROM items AS i
    JOIN sales_cluster AS s ON i.id = s.id_item
    JOIN clustering AS c ON s.nearest_cluster = c.id
    LEFT JOIN trends_moment AS tm ON tm.id_item = i.id AND tm.month = $currentMonth AND tm.year = $currentYear
    WHERE i.name LIKE '%$search%' OR i.code LIKE '%$search%'
    ORDER BY category DESC, cluster ASC, i.name ASC
    LIMIT $start, $limit");

    $items_all = mysqli_query($conn, "SELECT i.code, i.name, i.unit, i.price, i.id AS id_item, s.id AS id_sales, s.nearest_cluster AS cluster, c.category AS category
    FROM items AS i
    JOIN sales_cluster AS s ON i.id = s.id_item
    JOIN clustering AS c ON s.nearest_cluster = c.id
    WHERE i.name LIKE '%$search%' OR i.code LIKE '%$search%'
    ORDER BY category DESC, cluster ASC, i.name ASC");

    $total = mysqli_num_rows($items_all);
    $pages = ceil($total / $limit);
    $previous = $page - 1;
    $next = $page + 1;
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Per Item - PharmaTrend</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                            <a href="analytics_result.php" class="text-gray-700 hover:text-gray-950">Analytics</a>
                            <span class="text-gray-700">/</span>
                            <!-- page -->
                            <a href="detail_analytics_result.php?page=<?php echo $page; ?>" class="text-gray-700 hover:text-gray-950"><?php echo $page; ?></a>
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
                                    <h1 class="text-2xl font-bold">Analytics Result <?php echo date("F Y"); ?></h1>

                                </div>
                                <div class="flex flex-row items-center gap-2">
                                    <form action="analytics_result.php" method="GET" class="flex flex-row items-center gap-2">
                                        <input type="text" name="search" id="search" class="border-2 border-gray-200 rounded-md px-4 py-2 focus:outline-none focus:border-blue-400" placeholder="Search" autocomplete="off" value="<?php echo isset($_GET["search"]) ? $_GET["search"] : ""; ?>">
                                        <button type="submit" class="bg-blue-400 text-white px-4 py-2 rounded ml-4 my-2 hover:bg-blue-600">
                                            <i class="fas fa-search"></i>
                                            Search
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <!-- table data item -->
                            <?php
                            // show message 
                            if (isset($_SESSION["message"])) {
                            ?>
                                <div class="flex flex-row items-center justify-between bg-green-400 text-white px-4 py-2 rounded-md">
                                    <p><?php echo $_SESSION["message"]; ?></p>
                                    <button type="button" class="focus:outline-none" onclick="this.parentElement.style.display='none'">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            <?php
                                unset($_SESSION["message"]);
                            }
                            ?>
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
                                                Name
                                            </th>
                                            <th class="px-2 py-2">
                                                <i class="fas fa-box-open"></i>
                                                Category
                                            </th>
                                            <!-- Sales Real -->
                                            <th class="px-2 py-2">
                                                <i class="fas fa-chart-line"></i>
                                                Sales Real
                                            </th>
                                            <!-- Forecast -->
                                            <th class="px-2 py-2">
                                                <i class="fas fa-chart-line"></i>
                                                Forecast
                                            </th>
                                            <!-- APE -->
                                            <th class="px-2 py-2">
                                                <i class="fas fa-chart-line"></i>
                                                APE
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($item = mysqli_fetch_array($items)) {
                                        ?>
                                            <tr class="border-b-2 border-gray-200">
                                                <td class="px-2 py-2"><?php echo $no++; ?></td>
                                                <td class="px-2 py-2">
                                                    <a href="detail_analytics_result.php?id=<?php echo $item["id_item"]; ?>" class="text-blue-400 hover:text-blue-600">
                                                        <?php echo $item["code"]; ?>
                                                    </a>
                                                </td>
                                                <td class="px-2 py-2"><?php echo $item["name"]; ?></td>
                                                <td class="px-2 py-2"><?php echo $item["category"]; ?></td>
                                                <!-- Sales Real -->
                                                <td class="px-2 py-2">
                                                    <?php
                                                    if ($item["sales_real"] == NULL) {
                                                        echo "-";
                                                    } else {
                                                        echo number_format($item["sales_real"], 0, ",", ".");
                                                    }
                                                    ?>
                                                </td>
                                                <!-- Forecast -->
                                                <td class="px-2 py-2">
                                                    <?php
                                                    if ($item["forecast"] == NULL) {
                                                        echo "-";
                                                    } else {
                                                        echo number_format($item["forecast"], 2, ",", ".");
                                                    }
                                                    ?>
                                                </td>
                                                <!-- APE -->
                                                <td class="px-2 py-2">
                                                    <?php
                                                    if ($item["ape"] == NULL) {
                                                        echo "-";
                                                    } else {
                                                        echo number_format($item["ape"], 2, ",", ".") . "%";
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
                                        if ($page > 1) {
                                        ?>
                                            <a href="analytics_result.php?page=<?php echo $first_page; ?>&search=<?php echo $search; ?>" class="bg-gray-200 text-gray-500 px-2 py-1 rounded-md hover:bg-gray-400">
                                                <i class="fas fa-angle-double-left"></i>
                                            </a>
                                            <a href="analytics_result.php?page=<?php echo $prev_page; ?>&search=<?php echo $search; ?>" class="bg-gray-200 text-gray-500 px-2 py-1 rounded-md hover:bg-gray-400">
                                                <i class="fas fa-angle-left"></i>
                                            </a>
                                            <?php
                                        }
                                        // keluakan 2 awal dan 2 akhir dari page saat ini dan buat ...
                                        for ($i = 1; $i <= $total_page; $i++) {
                                            if ($i == $page) {
                                                $active = "bg-blue-400 text-white";
                                            } else {
                                                $active = "bg-gray-200 text-gray-500 hover:bg-gray-400";
                                            }
                                            if ($i > $page - 3 && $i < $page + 3) {
                                            ?>
                                                <a href="analytics_result.php?page=<?php echo $i; ?>&search=<?php echo $search; ?>" class="<?php echo $active; ?> px-2 py-1 rounded-md">
                                                    <?php echo $i; ?>
                                                </a>
                                            <?php
                                            } else if ($i == $page - 3 || $i == $page + 3) {
                                            ?>
                                                <span class="px-2 py-1 rounded-md">...</span>
                                            <?php
                                            }
                                        }
                                        if ($page < $total_page) {
                                            ?>
                                            <a href="analytics_result.php?page=<?php echo $next_page; ?>&search=<?php echo $search; ?>" class="bg-gray-200 text-gray-500 px-2 py-1 rounded-md hover:bg-gray-400">
                                                <i class="fas fa-angle-right"></i>
                                            </a>
                                            <a href="analytics_result.php?page=<?php echo $total_page; ?>&search=<?php echo $search; ?>" class="bg-gray-200 text-gray-500 px-2 py-1 rounded-md hover:bg-gray-400">
                                                <i class="fas fa-angle-double-right"></i>
                                            </a>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <h2 class="text-xl font-bold">Results:</h2>
                                    <p class="mt-2">The results of the trend moment analysis are as follows:</p>
                                    <ul class="list-disc pl-6 mt-2 mb-2">
                                        <li> MAPE: <?php echo number_format($totalMAPE, 2); ?> %</li>
                                        <li> Accuracy: <?php echo number_format(100 - $totalMAPE, 2); ?> %</li>
                                        <li> Accuracy Level: <?php echo $accuracyLevel; ?></li>
                                    </ul>
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