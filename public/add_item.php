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
    <title>Add Item - ARIPSKRIPSI</title>
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
                    <ul class="">
                        <li class="px-4 py-2 hover:bg-gray-400">
                            <a href="dashboard.php" class="text-gray-700 hover:text-gray-950">Dashboard</a>
                        </li>
                        <?php
                        if($_SESSION["role"] == "admin"){
                        ?>
                        <li class="px-4 py-2 hover:bg-gray-400">
                            <a href="manage_user.php" class="text-gray-700 hover:text-gray-950">Manage User</a>
                        </li>
                        <?php
                        }
                        ?>
                        <li class="px-4 py-2 hover:bg-gray-400 cursor-pointer">
                            <a href="items.php" class="text-gray-700 hover:text-gray-950">Items</a>
                        </li>
                        <li class="px-4 py-2 hover:bg-gray-400 cursor-pointer">
                            <a href="sales.php" class="text-gray-700 hover:text-gray-950">Sales</a>
                            <!-- sub -->
                            <ul class="mt-2">
                                <li class="px-4 py-2">
                                    <a href="sales.php" class="text-gray-700 hover:text-gray-950">Sales All</a>
                                </li>
                                <li class="px-4 py-2">
                                    <a href="sales_per_item.php" class="text-gray-700 hover:text-gray-950">Sales Per
                                        Item</a>
                                </li>
                            </ul>
                        </li>
                        <li class="px-4 py-2 hover:bg-gray-400 cursor-pointer">
                            <a href="analytics,php" class="text-gray-700 hover:text-gray-950">Analytics</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="w-10/12 h-screen p-2">
                <!-- container with breadcrumb -->
                <div class="w-full h-auto border-2 border-gray-200 rounded-md py-4 px-6">
                    <!-- breadcrumb -->
                    <div class="flex items-center gap-2 mb-3">
                        <a href="#" class="text-gray-700 hover:text-gray-950">Home</a>
                        <span class="text-gray-700">/</span>
                        <a href="#" class="text-gray-700 hover:text-gray-950">Items</a>
                        <span class="text-gray-700">/</span>
                        <h1 class="text-gray-700">Add Item</h1>
                    </div>
                    <hr>
                    <!-- content -->
                    <div class="flex flex-col gap-4 mt-4 mb-16">
                        <div class="flex flex-col gap-2">
                            <!-- flex row -->
                            <div class="flex flex-row items-center justify-between">
                                <div class="flex flex-row items-center gap-2">
                                    <h1 class="text-2xl font-bold">Items</h1>
                                </div>
                            </div>
                            <!-- div form add item -->
                            <div class="flex flex-col gap-4">
                                <?php
                                    if(isset($_POST["submit"])){
                                        $name = $_POST["name"];
                                        $code = $_POST["code"];
                                        $price = $_POST["price"];
                                        $stock = $_POST["stock"];
                                        $error = array();
                                        if(empty($name) || empty($code) || empty($price) || empty($stock)){
                                            array_push($error, "Please fill all the fields");
                                        }
                                        // code between 3-20 characters and name between 3-50 characters
                                        if(strlen($code) < 3 || strlen($code) > 20 || strlen($name) < 3 || strlen($name) > 50){
                                            array_push($error, "Code must be between 3-20 characters and name must be between 3-50 characters");
                                        }
                                        if(!is_numeric($price) || !is_numeric($stock)){
                                            array_push($error, "Price and stock must be numeric");
                                        }
                                        if(count($error) > 0){
                                            foreach($error as $err){
                                                echo "<div class='bg-red-200 text-red-700 border-2 border-red-700 rounded-md p-2'>$err</div>";
                                            }
                                        }
                                        else{
                                            require_once("config.php");
                                            $created_at = date("Y-m-d H:i:s");
                                            $updated_at = date("Y-m-d H:i:s");
                                            $id_user = $_SESSION["id"];
                                            $sql = "SELECT * FROM items WHERE code = '$code' OR name = '$name'";
                                            $query = mysqli_query($conn, $sql);
                                            if(mysqli_num_rows($query) > 0){
                                                echo "<div class='bg-red-200 text-red-700 border-2 border-red-700 rounded-md p-2'>Code or name already exist</div>";
                                            }else{
                                                $sql = "INSERT INTO items (name, code, price, stock, created_at, updated_at, id_user) VALUES ('$name', '$code', '$price', '$stock', '$created_at', '$updated_at', '$id_user')";
                                                if(mysqli_query($conn, $sql)){
                                                    echo "<div class='bg-green-200 text-green-700 border-2 border-green-700 rounded-md p-2'>Item added successfully</div>";
                                                }else{
                                                    echo "<div class='bg-red-200 text-red-700 border-2 border-red-700 rounded-md p-2'>Failed to add item</div>";
                                                    header("Location: items.php");
                                                }
                                            }
                                        }
                                    }
                                ?>
                                <form action="add_item.php" method="post">
                                    <div class="flex flex-col gap-2 mt-2">
                                        <label for="name" class="text-sm">Name</label>
                                        <input type="text" name="name" id="name" class="border-2 border-gray-200 rounded-md p-2">
                                    </div>
                                    <!-- code -->
                                    <div class="flex flex-col gap-2 mt-2">
                                        <label for="code" class="text-sm">Code</label>
                                        <input type="text" name="code" id="code" class="border-2 border-gray-200 rounded-md p-2">
                                    </div>
                                    <div class="flex flex-col gap-2 mt-2">
                                        <label for="price" class="text-sm">Price</label>
                                        <div class="flex flex-row items-center gap-2">
                                            <h1 class="text-sm">Rp.</h1>
                                            <input type="number" name="price" id="price" class="border-2 border-gray-200 rounded-md p-2 w-full">
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-2 mt-2">
                                        <label for="stock" class="text-sm">Stock</label>
                                        <input type="number" name="stock" id="stock" class="border-2 border-gray-200 rounded-md p-2">
                                    </div>
                                    <!-- input button save and cancel -->
                                    <div class="flex flex-row items-center justify-end gap-2 mt-4">
                                        <input type="submit" class="bg-green-400 hover:bg-green-600 text-white px-4 py-2 rounded-md" name="submit" value="Save">
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
</body>

</html>