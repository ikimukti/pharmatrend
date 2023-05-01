<?php
session_start();
if(!isset($_SESSION["id"])){
    header("Location: signin.php");
    
    die();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Sales - ARIPSKRIPSI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/output.css">
</head>

<body class="font-inter">
    <header class="bg-white w-full border-b-2 border-gray-200">
        <nav class="flex items-center justify-between flex-wrap w-94% mx-auto py-1">
            <div class="">
                <h1 class="text-2xl font-bold px-4 py-2">
                    SKRIPSI
                    <span class="bg-gradient-to-br from-red-500 to-teal-400 bg-clip-text text-transparent">ARIP</span>
                </h1>
            </div>
            <div class="">
                <ul class="flex items-center gap-4">
                    <li class="px-4 py-2">
                        <a href="#" class="text-gray-700 hover:text-gray-950">Home</a>
                    </li>
                    <li class="px-4 py-2">
                        <a href="#" class="text-gray-700 hover:text-gray-950">About</a>
                    </li>
                    <li class="px-4 py-2">
                        <a href="#" class="text-gray-700 hover:text-gray-950">Contact</a>
                    </li>
                    <li class="px-4 py-2">
                        <a href="#" class="text-gray-700 hover:text-gray-950">Blog</a>
                    </li>
                </ul>
            </div>
            <div class="">
                <!-- <button class="bg-blue-400 text-white px-4 py-2 rounded mx-4 my-2 hover:bg-blue-600">
                    Login
                </button> -->
                <!-- account -->
                <div>
                    <button type="button"
                        class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2"
                        id="options-menu" aria-haspopup="true" aria-expanded="true">
                        Account
                        <!-- Heroicon name: solid/chevron-down -->
                        <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </nav>
    </header>
    <div class="container-fluid mx-auto h-auto">
        <!-- sidebar flex and container -->
        <div class="flex">
            <div class="w-2/12 h-screen border-r-2 border-gray-200 p-2">
                <div class="flex flex-col items-center justify-center mt-4">
                    <h1 class="text-lg"><?php echo $_SESSION["fullname"]; ?></h1>
                    <div class="flex flex-row items-center justify-center gap-2">
                        <h2 class="text-sm text-gray-500 bg-gray-200 px-2 py-1 rounded-full"><?php echo $_SESSION["role"]; ?></h2>
                        <h2 class="text-sm text-gray-500 bg-green-200 px-2 py-1 rounded-full"><?php echo $_SESSION["status"]; ?></h2>
                    </div>
                </div>
                <hr class="my-4">
                <div>
                    <ul class="mt-4">
                        <li class="px-4 py-2">
                            <a href="dashboard.php" class="text-gray-700 hover:text-gray-950">Dashboard</a>
                        </li>
                        <?php
                        if($_SESSION["role"] == "admin"){
                        ?>
                        <li class="px-4 py-2">
                            <a href="manage_user.php" class="text-gray-700 hover:text-gray-950">Manage User</a>
                        </li>
                        <?php
                        }
                        ?>
                        <li class="px-4 py-2">
                            <a href="items.php" class="text-gray-700 hover:text-gray-950">Items</a>
                        </li>
                        <li class="px-4 py-2">
                            <a href="sales.php" class="text-gray-700 hover:text-gray-950">Sales</a>
                        </li>
                        <li class="px-4 py-2">
                            <a href="analytics" class="text-gray-700 hover:text-gray-950">Analytics</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="w-10/12 h-screen p-2">
                <!-- container with breadcrumb -->
                <div class="w-full h-auto border-2 border-gray-200 rounded-md py-4 px-6">
                    <!-- breadcrumb -->
                    <div class="flex items-center gap-2 mb-3">
                        <a href="dashboard.php" class="text-gray-700 hover:text-gray-950">Home</a>
                        <span class="text-gray-700">/</span>
                        <a href="sales.php" class="text-gray-700 hover:text-gray-950">Sales</a>
                        <span class="text-gray-700">/</span>
                        <a href="add_sales.php" class="text-gray-700 hover:text-gray-950">Add Sales</a>
                    </div>
                    <hr>
                    <!-- content -->
                    <div class="flex flex-col gap-4 mt-4 mb-16">
                        <div class="flex flex-col gap-2">
                            <!-- flex row -->
                            <div class="flex flex-row items-center justify-between">
                                <div class="flex flex-row items-center gap-2">
                                    <h1 class="text-2xl font-bold">Sales</h1>
                                </div>
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
                                        require_once("config.php");
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
                                                $query = mysqli_query($conn, $sql);
                                                if($query){
                                                    echo "<div class='bg-green-200 text-green-700 border-2 border-green-700 rounded-md p-2'>Sales added successfully</div>";
                                                }else{
                                                    echo "<div class='bg-red-200 text-red-700 border-2 border-red-700 rounded-md p-2'>Failed to add sales</div>";
                                                }
                                            }
                                        }
                                    }
                                ?>
                                <form action="add_sales.php" method="post">
                                    <!-- name auto_complete -->
                                    <div class="flex flex-col gap-2 mt-2 relative" onclick="event.stopImmediatePropagation()">
                                        <label for="name" class="text-sm">Name</label>
                                        <input type="text" name="name" onkeyup="onKeyUpName(event)" id="name" class="border-2 border-gray-200 rounded-md p-2">
                                        <div class="w-full max-h-60 bg-gray-100 rounded-md p-2 hidden overflow-y-auto" id="dropdown_name">
                                        </div>
                                    </div>
                                    <!-- code -->
                                    <?php
                                        // generate random code + check if code already exist + with prefix id_sales and date
                                        $code = "ITM".date("YmdHis");
                                        require_once("config.php");
                                        $sql = "SELECT * FROM items WHERE code = '$code'";
                                        $query = mysqli_query($conn, $sql);
                                        while(mysqli_num_rows($query) > 0){
                                            $code = "ITM".date("YmdHis");
                                            $sql = "SELECT * FROM items WHERE code = '$code'";
                                            $query = mysqli_query($conn, $sql);
                                        }

                                    ?>
                                    <div class="flex flex-col gap-2 mt-2">
                                        <label for="code" class="text-sm">Code</label>
                                        <input type="text" name="code" id="code" class="border-2 border-gray-200 bg-gray-100 rounded-md p-2" value="<?php echo $code; ?>" readonly>
                                    </div>
                                    <div class="flex flex-col gap-2 mt-2">
                                        <label for="sold" class="text-sm">Sold</label>
                                        <div class="flex flex-row items-center gap-2">
                                            <input type="number" name="sold" id="sold" class="border-2 border-gray-200 rounded-md p-2 w-full">
                                            <h1 class="text-sm">Pack</h1>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-2 mt-2">
                                        <label for="year" class="text-sm">Year</label>
                                        <div class="flex flex-row items-center gap-2">
                                            <select name="year" id="year" class="border-2 border-gray-200 rounded-md p-2 w-full">
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
                                            <select name="month" id="month" class="border-2 border-gray-200 rounded-md p-2 w-full">
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
                                        <input type="submit" class="bg-green-400 hover:bg-green-600 text-white px-4 py-2 rounded-md cursor-pointer" name="submit" value="Save">
                                        <a href="items.php" class="bg-red-400 hover:bg-red-600 text-white px-4 py-2 rounded-md">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <footer class=" w-full mx-auto text-center py-4 bottom-0 border border-gray-200">
            <p class="text-gray-700">Skripsi Arip &copy; 2023</p>
        </footer>
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
                ?>
                ,{
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

            function renderOptions(options){
                let dropdownEl = document.querySelector("#dropdown_name");
                let newHtml = ``;
                options.forEach((d) => {
                    newHtml += `<div class="px-4 py-3 bg-white rounded-md shadow-md border-b mb-2 border-gray-100 text-sm hover:bg-gray-100 cursor-pointer" onclick="selectOptionName('${d.name}')">
                    ${d.name}
                    </div>`;
                });
                // if options is empty
                if(options.length == 0){
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