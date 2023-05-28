<?php
session_start();
if(!isset($_SESSION["id"])){
    header("Location: signin.php");
    die();
}
require_once("config.php");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Per Item Sales - ARIPSKRIPSI</title>
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
            <!-- sidebar -->
            <?php
                include("components/sidebar.php");
            ?>
            <div class="w-10/12 h-[calc(100vh-3.5rem)] p-2">
                <!-- container with breadcrumb -->
                <div class="w-full h-auto border-2 border-gray-200 rounded-md py-4 px-6">
                    <!-- breadcrumb -->
                    <div class="flex items-center gap-2 mb-3">
                        <a href="dashboard.php" class="text-gray-700 hover:text-gray-950">Home</a>
                        <span class="text-gray-700">/</span>
                        <a href="sales.php" class="text-gray-700 hover:text-gray-950">Sales</a>
                        <span class="text-gray-700">/</span>
                        <a href="add_sales_per_item.php?id=<?php echo $_GET["id"]; ?>"
                            class="text-gray-700 hover:text-gray-950">Add Sales Per Item</a>
                    </div>
                    <hr>
                    <!-- content -->
                    <div class="flex flex-col gap-4 mt-4 mb-16">
                        <div class="flex flex-col gap-2">
                            <!-- flex row -->
                            <div class="flex flex-row items-center justify-between">
                                <div class="flex flex-row items-center gap-2">
                                    <h1 class="text-2xl font-bold">Add Sales Per Item</h1>
                                </div>
                            </div>
                            <!-- infrormation list year on sales item -->
                            <div class="flex flex-col gap-2">
                                <div class="flex flex-row items-center gap-2">
                                    <h1 class="text-xl font-bold">Information</h1>
                                </div>
                                <!-- Remove top/bottom padding when first/last child -->
                                <ul role="list" class="p-4 divide-y divide-slate-200">
                                    <?php
                                        $currentYear = date('Y'); // Mendapatkan tahun saat ini
                                        $currentMonth = date('n'); // Mendapatkan bulan saat ini

                                        // Get data from database by id
                                        $id = $_GET["id"];
                                        $sql = "SELECT * FROM sales WHERE id_item = '$id'";
                                        $resultSales = mysqli_query($conn, $sql);
                                        // print_r($resultSales);
                                        // Loop untuk tiga tahun ke belakang
                                        for ($i = 0; $i < 3; $i++) {
                                            $year = $currentYear - $i;
                                            // Count sales month per year
                                            $salesMonth = 0;
                                            if ($year == $currentYear) {
                                                $salesMonth = 12 - $currentMonth + 1;
                                            } else {
                                                $salesMonth = 12;
                                            }
                                    ?>
                                    <li class="flex py-4 first:pt-0 last:pb-0">
                                        <i class="fas fa-info-circle text-slate-500"></i>
                                        <div class="ml-3 overflow-hidden">
                                            <p class="text-sm font-medium text-slate-900">Year</p>
                                            <p class="text-sm text-slate-500 truncate"><?php echo $year; ?></p>
                                        </div>
                                        <div class="ml-3 overflow-hidden">
                                            <p class="text-sm font-medium text-slate-900">Sales Added Month</p>
                                            <p class="text-sm text-slate-500 truncate">
                                                <?php
                                                // Loop untuk bulan
                                                for ($month = 1; $month <= 12; $month++) {
                                                    $monthName = date('F', mktime(0, 0, 0, $month, 1));
                                                    // Cek apakah bulan $month dan tahun $year sudah ada di database
                                                    $found = false;
                                                    $checkMark = '';
                                                    foreach ($resultSales as $row) {
                                                        if ($row['month'] == $month && $row['year'] == $year) {
                                                            $found = true;
                                                            $checkMark = '<i class="fas fa-check text-green-500"></i>';
                                                            $salesMonth++;
                                                            break;
                                                        }
                                                        // Jika tidak ada, maka akan ada tanda silang
                                                        else {
                                                            $checkMark = '<i class="fas fa-times text-red-500"></i>';
                                                        }
                                                    }
                                                    // tampilkan sampai bulan saat ini
                                                    if ($year == $currentYear && $month > $currentMonth) {
                                                        echo $checkMark . ' Soon';
                                                        break;
                                                    }
                                                    echo $checkMark . ' ' . $monthName . ' ';
                                                }
                                                ?>
                                            </p>
                                        </div>
                                        <!-- button show if salesMonth != 0 -->
                                        <?php
                                            if($salesMonth != 0){
                                        ?>
                                        <div class="ml-3 justify-self-end mt-4">
                                            <a href="add_sales_per_item.php?id=<?php echo $_GET["id"]; ?>&year=<?php echo $year; ?>"
                                                class="text-xs bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md">Add
                                                Sales</a>
                                        </div>
                                        <?php
                                            }
                                        ?>
                                    </li>
                                    <?php
                                        }
                                    ?>
                                </ul>
                            </div>
                            <!-- div form add item -->
                            <div class="flex flex-col gap-4">
                                <?php
                                    if(isset($_POST["submit"])){
                                        $id_user = $_SESSION["id"];
                                        $name_item = $_POST["name"];
                                        $code = $_POST["code"];
                                        $sold = $_POST["sold"];
                                        $month = $_POST["month"];
                                        $year = $_POST["year"];
                                        $created_at = date("Y-m-d H:i:s");
                                        $updated_at = date("Y-m-d H:i:s");
                                        if($name_item == "" || $code == "" || $sold == "" || $month == "" || $year == ""){
                                            echo "<div class='bg-red-200 text-red-700 border-2 border-red-700 rounded-md p-2'>Please fill all the form</div>";
                                        }
                                        // check item exist
                                        
                                        $sql = "SELECT * FROM items WHERE name = '$name_item'";
                                        $query = mysqli_query($conn, $sql);
                                        if(mysqli_num_rows($query) > 0){
                                            $row = mysqli_fetch_assoc($query);
                                            $id_item = $row["id"];
                                            // check sales exist
                                            $sql = "SELECT * FROM sales WHERE id_item = '$id_item' AND month = '$month' AND year = '$year'";
                                            $query = mysqli_query($conn, $sql);
                                            if(mysqli_num_rows($query) > 0){
                                                echo "<div class='bg-red-200 text-red-700 border-2 border-red-700 rounded-md p-2'>Sales already exist</div>";
                                            }else{
                                                // insert sales
                                                $sql = "INSERT INTO sales (id_user,code, id_item, sold, month, year, created_at, updated_at) VALUES ('$id_user','$code','$id_item','$sold','$month','$year','$created_at','$updated_at')";
                                                // save to database and check if success or not and redirect to items.php
                                                if(mysqli_query($conn, $sql)){
                                                    // header already sent error fix
                                                    ob_start();
                                                    if(!headers_sent()){
                                                        header("Location: sales.php");
                                                        ob_end_flush();
                                                        die();
                                                    }else{
                                                        echo "<script>window.location.href='sales.php';</script>";
                                                        die();
                                                    }
                                                }else{
                                                    echo "<div class='bg-red-200 text-red-700 border-2 border-red-700 rounded-md p-2'>Failed to add sales</div>";
                                                }
                                            }
                                        } else{
                                            echo "<div class='bg-red-200 text-red-700 border-2 border-red-700 rounded-md p-2'>Item not found</div>";
                                        }
                                    }
                                ?>
                                <form action="add_sale.php" method="post">
                                    <!-- name auto_complete -->
                                    <div class="flex flex-col gap-2 mt-2 relative"
                                        onclick="event.stopImmediatePropagation()">
                                        <label for="name" class="text-sm">Name</label>
                                        <input type="text" name="name" onkeyup="onKeyUpName(event)" id="name"
                                            class="border-2 border-gray-200 rounded-md p-2">
                                        <div class="w-full max-h-60 bg-gray-100 rounded-md p-2 hidden overflow-y-auto"
                                            id="dropdown_name">
                                        </div>
                                    </div>
                                    <!-- code -->
                                    <?php
                                        // generate random code + check if code already exist + with prefix id_sales and date
                                        $code = "SALE".date("YmdHis");
                                        require_once("config.php");
                                        $sql = "SELECT * FROM items WHERE code = '$code'";
                                        $query = mysqli_query($conn, $sql);
                                        while(mysqli_num_rows($query) > 0){
                                            $code = "ITM".date("YmdHis");
                                            $sql = "SELECT * FROM items WHERE crode = '$code'";
                                            $query = mysqli_query($conn, $sql);
                                        }

                                    ?>
                                    <div class="flex flex-col gap-2 mt-2">
                                        <label for="code" class="text-sm">Code</label>
                                        <input type="text" name="code" id="code"
                                            class="border-2 border-gray-200 bg-gray-100 rounded-md p-2"
                                            value="<?php echo $code; ?>" readonly>
                                    </div>
                                    <div class="flex flex-col gap-2 mt-2">
                                        <label for="sold" class="text-sm">Sold</label>
                                        <div class="flex flex-row items-center gap-2">
                                            <input type="number" name="sold" id="sold"
                                                class="border-2 border-gray-200 rounded-md p-2 w-full">
                                            <h1 class="text-sm">Pack</h1>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-2 mt-2">
                                        <label for="year" class="text-sm">Year</label>
                                        <div class="flex flex-row items-center gap-2">
                                            <select name="year" id="year"
                                                class="border-2 border-gray-200 rounded-md p-2 w-full">
                                                <?php
                                                    $year = date("Y");
                                                    for($i = 0; $i < 3; $i++){
                                                        echo "<option value='$year'>$year</option>";
                                                        $year--;
                                                    }
                                                    ?>
                                            </select>
                                            <h1 class="text-sm">Year</h1>
                                        </div>
                                    </div>
                                    <!-- month -->
                                    <div class="flex flex-col gap-2 mt-2">
                                        <label for="month" class="text-sm">Month</label>
                                        <div class="flex flex-row items-center gap-2">
                                            <select name="month" id="month"
                                                class="border-2 border-gray-200 rounded-md p-2 w-full">
                                                <option value="1">January</option>
                                                <option value="2">February</option>
                                                <option value="3">March</option>
                                                <option value="4">April</option>
                                                <option value="5">Mei</option>
                                                <option value="6">June</option>
                                                <option value="7">July</option>
                                                <option value="8">August</option>
                                                <option value="9">Septemper</option>
                                                <option value="10">October</option>
                                                <option value="11">November</option>
                                                <option value="12">Desember</option>
                                            </select>
                                            <h1 class="text-sm">Month</h1>
                                        </div>
                                    </div>
                                    <!-- input button save and cancel -->
                                    <div class="flex flex-row items-center justify-end gap-2 mt-4">
                                        <input type="submit"
                                            class="bg-green-400 hover:bg-green-600 text-white px-4 py-2 rounded-md cursor-pointer"
                                            name="submit" value="Save">
                                        <a href="items.php"
                                            class="bg-red-400 hover:bg-red-600 text-white px-4 py-2 rounded-md">Cancel</a>
                                    </div>
                                </form>
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
        <?php
            require_once("config.php");
            $sql = "SELECT name, id FROM items";
            $query = mysqli_query($conn, $sql);
            $nameData = array();
        ?>
        <script>
        let nameData = [
            // php while nameData > {name: "name", code: "id"}
            <?php
                    while($row = mysqli_fetch_array($query)){
                ?>, {
                name: "<?php echo $row['name']?>",
                id: "<?php echo $row['id']?>"
            }
            <?php
                    }?>
        ]

        function onKeyUpName(e) {
            let keyword = e.target.value;
            let dropdownEl = document.querySelector("#dropdown_name");
            dropdownEl.classList.remove("hidden");
            let filteredName = nameData.filter((d) => d.name.toLowerCase().includes(keyword.toLowerCase()));
            renderOptions(filteredName);
        }

        document.addEventListener("DOMContentLoaded", () => {
            renderOptions(nameData);
        })

        function renderOptions(options) {
            let dropdownEl = document.querySelector("#dropdown_name");
            let newHtml = ``;
            options.forEach((d) => {
                newHtml += `<div class="px-4 py-3 bg-white rounded-md shadow-md border-b mb-2 border-gray-100 text-sm hover:bg-gray-100 cursor-pointer" onclick="selectOptionName('${d.name}')">
                    ${d.name}
                    </div>`;
            });
            // if options is empty
            if (options.length == 0) {
                newHtml = `<div class="px-4 py-3 bg-white rounded-md shadow-md border-b mb-2 border-gray-100 text-sm hover:bg-gray-100 cursor-pointer">
                    No data found
                    </div>`;
            }
            dropdownEl.innerHTML = newHtml;
        }

        function selectOptionName(e) {
            let inputEl = document.querySelector("#name");
            inputEl.value = e;
            hideDropdown();

        }

        document.addEventListener("click", function() {
            hideDropdown();
        })

        function hideDropdown(e) {
            let dropdownEl = document.querySelector("#dropdown_name");
            dropdownEl.classList.add("hidden");
        }
        </script>
</body>

</html>