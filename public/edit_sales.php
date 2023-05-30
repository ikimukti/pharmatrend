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
    <title>Add Sales - ARIPSKRIPSI</title>
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
                        <a href="dashboard.php" class="text-gray-700 hover:text-gray-950"><i class="fas fa-home"></i></a>
                        <span class="text-gray-700">/</span>
                        <a href="sales.php" class="text-gray-700 hover:text-gray-950">Sales</a>
                        <span class="text-gray-700">/</span>
                        <a href="edit_sales.php?id=<?php echo $_GET["id"]; ?>" class="text-gray-700 hover:text-gray-950">Edit Sales</a>
                    </div>
                    <hr>
                    <!-- content -->
                    <div class="flex flex-col gap-4 mt-4 mb-16">
                        <div class="flex flex-col gap-2">
                            <!-- flex row -->
                            <div class="flex flex-row items-center justify-between">
                                <div class="flex flex-row items-center gap-2">
                                    <h1 class="text-2xl font-bold">Edit Sales</h1>
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
                                        $sql = "SELECT * FROM items WHERE name = '$name_item'";
                                        $query = mysqli_query($conn, $sql);
                                        if(mysqli_num_rows($query) > 0){
                                            $row = mysqli_fetch_assoc($query);
                                            $id_item = $row["id"];
                                            // check sales exist
                                            $sql = "SELECT * FROM sales WHERE id_item = '$id_item' AND month = '$month' AND year = '$year'";
                                            $query = mysqli_query($conn, $sql);
                                            if(mysqli_num_rows($query) > 0){
                                                // update sales
                                                $sql = "UPDATE sales SET sold = '$sold', updated_at = '$updated_at' WHERE id_item = '$id_item' AND month = '$month' AND year = '$year'";
                                                $query = mysqli_query($conn, $sql); 
                                                echo "<div class='bg-green-200 text-green-700 border-2 border-green-700 rounded-md p-2'>Sales updated successfully</div>";
                                            }else{
                                                echo "<div class='bg-red-200 text-red-700 border-2 border-red-700 rounded-md p-2'>Sales not found</div>";
                                            }
                                        } else{
                                            echo "<div class='bg-red-200 text-red-700 border-2 border-red-700 rounded-md p-2'>Item not found</div>";
                                        }
                                    } else {
                                        // Get data from database
                                        $id = $_GET["id"];
                                        $sql = "SELECT * FROM sales WHERE id = '$id'";
                                        $sales = mysqli_query($conn, $sql);
                                        if(mysqli_num_rows($sales) > 0){
                                            $row = mysqli_fetch_assoc($sales);
                                            $id_item = $row["id_item"];
                                            $sold = $row["sold"];
                                            $month = $row["month"];
                                            $year = $row["year"];
                                            $sql = "SELECT * FROM items WHERE id = '$id_item'";
                                            $items = mysqli_query($conn, $sql);
                                            if(mysqli_num_rows($items) > 0){
                                                $row = mysqli_fetch_assoc($items);
                                                $name_item = $row["name"];
                                                $code = $row["code"];
                                            }
                                        }
                                    }
                                ?>
                                <form action="edit_sales.php?id=<?php echo $_GET["id"]; ?>" method="POST" class="flex flex-col gap-4">
                                    <!-- name auto_complete -->
                                    <div class="flex flex-col gap-2 mt-2 relative">
                                        <label for="name" class="text-sm">Name</label>
                                        <input type="text" name="name" id="name" autocomplete="off" value="<?php echo $name_item; ?>"
                                             readonly class="border-2 border-gray-200 bg-gray-100 rounded-md p-2">
                                    </div>
                                    <div class="flex flex-col gap-2 mt-2">
                                        <label for="code" class="text-sm">Code</label>
                                        <input type="text" name="code" id="code"
                                            class="border-2 border-gray-200 bg-gray-100 rounded-md p-2"
                                            value="<?php echo $code; ?>" readonly>
                                    </div>
                                    <div class="flex flex-col gap-2 mt-2">
                                        <label for="sold" class="text-sm">Sold</label>
                                        <div class="flex flex-row items-center gap-2">
                                            <input type="number" name="sold" id="sold" value="<?php echo $sold; ?>"
                                                class="border-2 border-gray-200 rounded-md p-2 w-full">
                                            <h1 class="text-sm">Pack</h1>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-2 mt-2">
                                        <label for="year" class="text-sm">Year</label>
                                        <div class="flex flex-row items-center gap-2">
                                            <input type="number" name="year" id="year" value="<?php echo $year; ?>"
                                                class="border-2 border-gray-200 rounded-md p-2 w-full" readonly>
                                            <h1 class="text-sm">Year</h1>
                                        </div>
                                    </div>
                                    <!-- month -->
                                    <div class="flex flex-col gap-2 mt-2">
                                        <label for="month" class="text-sm">Month</label>
                                        <div class="flex flex-row items-center gap-2">
                                            <input type="number" name="month" id="month" value="<?php echo $month; ?>"
                                                class="border-2 border-gray-200 rounded-md p-2 w-full" readonly>
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