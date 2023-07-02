<?php
session_start();
if(!isset($_SESSION["id"])){
    header("Location: signin.php");
    die();
}
require_once("config.php");
// jika id tidak ditemukan & year lebi dari tahun sekarang dan year lebih dari 2 tahun lalu
$id = $_GET["id"];
$sql = "SELECT * FROM items WHERE id = '$id'";
if(mysqli_num_rows(mysqli_query($conn, $sql)) == 0 || $_GET["year"] > date('Y') || $_GET["year"] < date('Y') - 2){
    header("Location: add_sales_per_item.php");
    die();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Per Item Sales - PharmaTrend</title>
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
                    <div class="flex items-center gap-2 mb-3 justify-between">
                        <div>
                            <a href="dashboard.php" class="text-gray-700 hover:text-gray-950"><i class="fas fa-home"></i></a>
                            <span class="text-gray-700">/</span>
                            <a href="sales.php" class="text-gray-700 hover:text-gray-950">Sales</a>
                            <span class="text-gray-700">/</span>
                            <a href="add_sales_per_item.php?id=<?php echo $_GET["id"]; ?>&year=<?php echo $_GET["year"]; ?>"
                                class="text-gray-700 hover:text-gray-950">Add Sales Per Item</a>
                            <span class="text-gray-700">/</span>
                            <a href="add_sales_per_item.php?id=<?php echo $_GET["id"]; ?>&year=<?php echo $_GET["year"]; ?>"
                                class="text-gray-700 hover:text-gray-950">
                                <?php echo $_GET["id"]; ?>
                            </a>
                            <!-- Create if year found -->
                            <?php
                                if(isset($_GET["year"])){
                            ?>
                            <span class="text-gray-700">/</span>
                            <a href="add_sales_per_item.php?id=<?php echo $_GET["id"]; ?> &year=<?php echo $_GET["year"]; ?>"
                                class="text-gray-700 hover:text-gray-950"><?php echo $_GET["year"]; ?></a>
                            <?php
                                }
                            ?>
                        </div>
                        <button class="flex flex-row justify-center items-center bg-gray-200 hover:bg-gray-300 rounded-md px-4 py-2 text-gray-700 space-x-2" onclick="window.history.back();">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back</span>
                        </button>
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
                                        $monthSalesNotExists = array();
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
                                                $nextMonthThisYear = date('F', mktime(0, 0, 0, date('m') + 1, 1));
                                                for ($month = 1; $month <= 12; $month++) {
                                                    $monthName = date('F', mktime(0, 0, 0, $month, 1));
                                                    // Cek apakah bulan $month dan tahun $year sudah ada di database
                                                    $found = false;
                                                    $checkMark = '';
                                                    // if $resultSales is not empty
                                                    if (mysqli_num_rows($resultSales) > 0) {
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
                                                        // add month to array if not exists
                                                        }
                                                    } else {
                                                        $checkMark = '<i class="fas fa-times text-red-500"></i>';
                                                    }
                                                    if ($year == $_GET["year"] && $checkMark == '<i class="fas fa-times text-red-500"></i>' ) {
                                                        if($monthName != $nextMonthThisYear && $year == $currentYear){
                                                            array_push($monthSalesNotExists, $monthName);
                                                        } else if($year != $currentYear){
                                                            array_push($monthSalesNotExists, $monthName);
                                                        }
                                                    }
                                                    // tampilkan sampai bulan saat ini
                                                    if ($year == $currentYear && $month > $currentMonth) {
                                                        $checkMark = '<i class="fas fa-times text-red-500"></i>';
                                                        echo $checkMark . ' Soon';
                                                        break;
                                                    }
                                                    echo $checkMark . ' ' . $monthName . ' ';
                                                }
                                                ?>
                                            </p>
                                        </div>
                                        <!-- button show -->
                                        <?php
                                            if($year != $_GET["year"] ||  $monthSalesNotExists != null){
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
                                        // Create month name list 1-12
                                        $created_at = date("Y-m-d H:i:s");
                                        $updated_at = date("Y-m-d H:i:s");
                                        if($name_item == "" || $code == "" || $month == "" || $year == ""){
                                            echo "<div class='bg-red-200 text-red-700 border-2 border-red-700 rounded-md p-2'>Please fill all the form</div>";
                                        }
                                        for($i = 1; $i <= 12; $i++){
                                            $monthName = date('F', mktime(0, 0, 0, $i, 1));
                                            $monthList[$i] = $monthName;
                                            // isset sold_Month and sold_Month value != null
                                            if(isset($_POST["sold_".$monthName]) && $_POST["sold_".$monthName] != ""){
                                                $sold = $_POST["sold_".$monthName];
                                                $month = $i;
                                                $year = $_POST["year"];
                                                // check item exist
                                                $sql = "SELECT * FROM items WHERE name = '$name_item'";
                                                $query = mysqli_query($conn, $sql);
                                                if(mysqli_num_rows($query) > 0){
                                                    $row = mysqli_fetch_assoc($query);
                                                    $id_item = $row["id"];
                                                    // check sales exist
                                                    $sql = "SELECT * FROM sales WHERE id_item = '$id_item' AND month = '$month' AND year = '$year'";
                                                    $query = mysqli_query($conn, $sql);
                                                    // generate random code + check if code already exist + with prefix id_sales and date
                                                    $sql = "SELECT * FROM items WHERE code = '$code'";
                                                    $query = mysqli_query($conn, $sql);
                                                    while(mysqli_num_rows($query) > 0){
                                                        $code = "ITM".date("YmdHis");
                                                        $sql = "SELECT * FROM items WHERE crode = '$code'";
                                                        $query = mysqli_query($conn, $sql);
                                                    }
                                                    if(mysqli_num_rows($query) > 0){
                                                        echo "<div class='bg-red-200 text-red-700 border-2 border-red-700 rounded-md p-2'>Sales already exist</div>";
                                                    }else{
                                                        // insert sales
                                                        $sql = "INSERT INTO sales (id_user,code, id_item, sold, month, year, created_at, updated_at) VALUES ('$id_user','$code','$id_item','$sold','$month','$year','$created_at','$updated_at')";
                                                        $query = mysqli_query($conn, $sql);
                                                        if($query){
                                                            echo "<div class='bg-green-200 text-green-700 border-2 border-green-700 rounded-md p-2'>Sales added successfully</div>";
                                                        }else{
                                                            echo "<div class='bg-red-200 text-red-700 border-2 border-red-700 rounded-md p-2'>Failed to add sales</div>";
                                                        }
                                                    }
                                                }else{
                                                    echo "<div class='bg-red-200 text-red-700 border-2 border-red-700 rounded-md p-2'>Item not found</div>";
                                                }
                                            }
                                        }
                                        // header already sent error fix
                                        ob_start();
                                        if(!headers_sent()){
                                            header("Location: add_sales_per_item.php?id=".$_GET["id"]."&year=".$year);
                                        } else{
                                            // buffer 2 seconds earlier to prevent header already sent error
                                            echo "<script>setTimeout(() => { window.location.href = 'add_sales_per_item.php?id=".$_GET["id"]."&year=".$year."'; }, 2000);</script>";
                                        }
                                    } else{
                                        $id = $_GET["id"];
                                        // get item name
                                        $sql = "SELECT * FROM items WHERE id = '$id'";
                                        $query = mysqli_query($conn, $sql);
                                        $row = mysqli_fetch_assoc($query);
                                        $name_item = $row["name"];
                                    }
                                ?>
                                <form action="add_sales_per_item.php?id=<?php echo $_GET["id"]; ?>&year=<?php echo $year; ?>" method="post">
                                    <!-- name auto_complete -->
                                    <div class="flex flex-col gap-2 mt-2 relative">
                                        <label for="name" class="text-sm">Name</label>
                                        <input type="text" name="name" id="name"
                                            class="border-2 border-gray-200 rounded-md p-2" required autocomplete="off" readonly value="<?php echo $name_item; ?>">
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
                                    <!-- Create input form, for sold months $monthSalesNotExists -->
                                    <?php
                                        // if monthSalesNotExists not empty
                                        if(!empty($monthSalesNotExists)){
                                            foreach($monthSalesNotExists as $monthNotExist){
                                    ?>
                                    <div class="flex flex-col gap-2 mt-2">
                                        <!-- label month and get years -->
                                        <label for="sold" class="text-sm">Sold <?php echo $monthNotExist; echo " ".$_GET["year"]; ?></label>
                                        <div class="flex flex-row items-center gap-2">
                                            <input type="number" name="sold_<?php echo $monthNotExist; ?>" id="sold"
                                                class="border-2 border-gray-200 rounded-md p-2 w-full">
                                            <h1 class="text-sm">Pack</h1>
                                        </div>
                                    </div>
                                    <?php
                                            }
                                        }
                                    ?>
                                    <!-- year -->
                                    <div class="flex flex-col gap-2 mt-2">
                                        <label for="year" class="text-sm">Year</label>
                                        <input type="number" name="year" id="year"
                                            class="border-2 border-gray-200 rounded-md p-2" value="<?php echo $_GET["year"]; ?>" readonly>
                                    </div>
                                    <!-- input button save and cancel -->
                                    <?php
                                        // show if if monthSalesNotExists not empty
                                        if(!empty($monthSalesNotExists)){
                                    ?>
                                    <div class="flex flex-row items-center justify-end gap-2 mt-4">
                                        <input type="submit"
                                            class="bg-green-400 hover:bg-green-600 text-white px-4 py-2 rounded-md cursor-pointer"
                                            name="submit" value="Save">
                                            <a href="add_sales_per_item.php?id=<?php echo $id; ?>&year=<?php echo $_GET["year"]; ?>"
                                            class="bg-red-400 hover:bg-red-600 text-white px-4 py-2 rounded-md">Cancel</a>
                                    </div>
                                    <?php
                                    } else{
                                        // berikan teks penjelasan bahwa items sales sudah dimasukkan semua
                                    ?>
                                    <div class="flex flex-col gap-2 mt-2">
                                        <h1 class="text-sm">All sales for <?php echo $_GET["year"]; ?> has been added</h1>
                                    </div>
                                    <?php
                                    }
                                    ?>
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