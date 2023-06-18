<?php
session_start();
if(!isset($_SESSION["id"])){
    ob_start();
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="font-inter">
    <header class="bg-white w-full border-b-2 border-gray-200">
        <?php
            require("components/navbar.php");
        ?>
    </header>
    <div class="container-fluid mx-auto h-auto">
        <!-- sidebar flex and container -->
        <div class="flex">
            <!-- sidebar -->
            <?php
                require("components/sidebar.php");
            ?>
            <div class="w-10/12 h-[calc(100vh-3.5rem)] p-2">
                <!-- container with breadcrumb -->
                <div class="w-full h-auto border-2 border-gray-200 rounded-md py-4 px-6">
                    <!-- breadcrumb -->
                    <div class="flex items-center gap-2 mb-3">
                        <a href="dashboard.php" class="text-gray-700 hover:text-gray-950"><i class="fas fa-home"></i></a>
                        <span class="text-gray-700">/</span>
                        <a href="items.php" class="text-gray-700 hover:text-gray-950">Items</a>
                        <span class="text-gray-700">/</span>
                        <a href="add_item.php" class="text-gray-700 hover:text-gray-950">Add Item</a>
                    </div>
                    <hr>
                    <!-- content -->
                    <div class="flex flex-col gap-4 mt-4 mb-16">
                        <div class="flex flex-col gap-2">
                            <!-- flex row -->
                            <div class="flex flex-row items-center justify-between">
                                <div class="flex flex-row items-center gap-2">
                                    <h1 class="text-2xl font-bold">Add Item</h1>
                                </div>
                            </div>
                            <!-- div form add item -->
                            <div class="flex flex-col gap-4">
                                <?php
                                    if(isset($_POST["submit"])){
                                        $name = $_POST["name"];
                                        $code = $_POST["code"];
                                        $price = $_POST["price"];
                                        // $stock = $_POST["stock"];
                                        $stock = 0;
                                        $error = array();
                                        if(empty($name) || empty($code) || empty($price)){
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
                                                // save to database and check if success or not and redirect to items.php
                                                if(mysqli_query($conn, $sql)){
                                                    // header already sent error fix
                                                    ob_start();
                                                    if(!headers_sent()){
                                                        header("Location: items.php");
                                                    } else{
                                                        echo "<script>window.location.href='items.php';</script>";
                                                    }
                                                }else{
                                                    echo "<div class='bg-red-200 text-red-700 border-2 border-red-700 rounded-md p-2'>Failed to add item</div>";
                                                }
                                            }
                                        }
                                    }
                                ?>
                                <form action="add_item.php" method="post">
                                    <div class="flex flex-col gap-2 mt-2">
                                        <label for="name" class="text-sm">Name</label>
                                        <input type="text" name="name" id="name"
                                            class="border-2 border-gray-200 rounded-md p-2">
                                    </div>
                                    <!-- code -->
                                    <?php
                                        // generate random code + with prefix id_sales and date
                                        $code = "ITM".date("YmdHis");
                                    ?>
                                    <div class="flex flex-col gap-2 mt-2">
                                        <label for="code" class="text-sm">Code</label>
                                        <input type="text" name="code" id="code"
                                            class="border-2 border-gray-200 rounded-md p-2" value="<?php echo $code; ?>"
                                            readonly>
                                    </div>
                                    <!-- price -->
                                    <div class="flex flex-col gap-2 mt-2">
                                        <label for="price" class="text-sm">Price</label>
                                        <div class="flex flex-row items-center gap-2">
                                            <h1 class="text-sm">Rp.</h1>
                                            <input type="number" name="price" id="price"
                                                class="border-2 border-gray-200 rounded-md p-2 w-full">
                                        </div>
                                    </div>
                                    <!-- unit -->
                                    <div class="flex flex-col gap-2 mt-2">
                                        <label for="unit" class="text-sm">Unit</label>
                                        <select name="unit" id="unit" class="border-2 border-gray-200 rounded-md p-2">
                                            <option value="pcs">Pcs</option>
                                            <option value="box">Box</option>
                                            <option value="kg">Kg</option>
                                            <option value="gram">Gram</option>
                                            <option value="liter">Liter</option>
                                            <option value="ml">Ml</option>
                                            <option value="pack">Pack</option>
                                            <option value="set">Set</option>
                                            <option value="dozen">Dozen</option>
                                            <option value="bottle">Bottle</option>
                                            <option value="can">Can</option>
                                            <option value="roll">Roll</option>
                                            <option value="tube">Tube</option>
                                            <option value="bag">Bag</option>
                                            <option value="sack">Sack</option>
                                            <option value="bunch">Bunch</option>
                                            <option value="piece">Piece</option>
                                            <option value="pair">Pair</option>
                                            <option value="unit">Unit</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <!-- stock -->
                                    <!-- <div class="flex flex-col gap-2 mt-2">
                                        <label for="stock" class="text-sm">Stock</label>
                                        <input type="number" name="stock" id="stock" class="border-2 border-gray-200 rounded-md p-2">
                                    </div> -->
                                    <!-- input button save and cancel -->
                                    <div class="flex flex-row items-center justify-end gap-2 mt-4">
                                        <input type="submit"
                                            class="bg-green-400 hover:bg-green-600 text-white px-4 py-2 rounded-md"
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
</body>

</html>