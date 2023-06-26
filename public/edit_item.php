<?php
session_start();
if(!isset($_SESSION["id"])){
    ob_start();
    header("Location: signin.php");
    
    die();
}
require_once('config.php');
$id = $_GET['id'];
$sql = "SELECT * FROM items WHERE id = $id";
$query = mysqli_query($conn, $sql);
if(mysqli_num_rows($query) > 0){
    $row = mysqli_fetch_assoc($query);
    $name_old = $row["name"];
    $code_old = $row["code"];
    $price_old = $row["price"];
    $stock_old = $row["stock"];
    $created_at_old = $row["created_at"];
    $updated_at_old = $row["updated_at"];
    $id_user = $row["id_user"];
}else{
    echo "Error fetching data: " . mysqli_error($conn);
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item- ARIPSKRIPSI</title>
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
                    <div class="flex items-center gap-2 mb-3 justify-between">
                        <div>
                            <a href="dashboard.php" class="text-gray-700 hover:text-gray-950"><i class="fas fa-home"></i></a>
                            <span class="text-gray-700">/</span>
                            <a href="items.php" class="text-gray-700 hover:text-gray-950">Items</a>
                            <span class="text-gray-700">/</span>
                            <a href="edit_item.php?id=<?php echo $id; ?>" class="text-gray-700 hover:text-gray-950">Edit
                                Item</a>
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
                                    <h1 class="text-2xl font-bold">Edit Item</h1>
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
                                            $created_at = $created_at_old;
                                            $updated_at = date("Y-m-d H:i:s");
                                            $id_user = $_SESSION["id"];
                                            // update data to database
                                            $sql = "UPDATE `items` SET `name`='$name',`code`='$code',`price`='$price',`stock`='$stock',`created_at`='$created_at',`updated_at`='$updated_at',`id_user`='$id_user' WHERE id = $id";
                                            if(mysqli_query($conn, $sql)){
                                                // header already sent error fix
                                                ob_start();
                                                if(!headers_sent()){
                                                    header("Location: items.php");
                                                } else{
                                                    echo "<script>window.location.href='items.php';</script>";
                                                }
                                            }
                                            else{
                                                echo "Error updating data: " . mysqli_error($conn);
                                            }
                                        }
                                    }
                                ?>
                                <form action="edit_item.php?id=<?php echo $id; ?>" method="POST"
                                    class="flex flex-col gap-4">
                                    <div class="flex flex-col gap-2 mt-2">
                                        <label for="name" class="text-sm">Name</label>
                                        <input type="text" name="name" id="name"
                                            class="border-2 border-gray-200 rounded-md p-2" placeholder="Item name"
                                            value="<?php echo isset($_POST["name"]) ? $_POST["name"] : $name_old; ?>">
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
                                            readonly placeholder="Item code"
                                            value="<?php echo isset($_POST["code"]) ? $_POST["code"] : $code_old; ?>">
                                    </div>
                                    <div class="flex flex-col gap-2 mt-2">
                                        <label for="price" class="text-sm">Price</label>
                                        <div class="flex flex-row items-center gap-2">
                                            <h1 class="text-sm">Rp.</h1>
                                            <input type="number" name="price" id="price"
                                                class="border-2 border-gray-200 rounded-md p-2 w-full"
                                                placeholder="Item price"
                                                value="<?php echo isset($_POST["price"]) ? $_POST["price"] : $price_old; ?>">
                                        </div>
                                    </div>
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