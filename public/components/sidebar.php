<?php
if(!isset($_SESSION["id"])){
    ob_start();
    header("Location: signin.php");
    
    die();
}
?>
<div class="w-2/12 h-screen border-r-2 border-gray-200">
                <div class="flex flex-col items-center justify-center mt-4  p-2">
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
                            <!-- sub -->
                            <ul class="mt-2">
                                <li class="px-4 py-2">
                                    <a href="items.php" class="text-gray-700 hover:text-gray-950">Items All</a>
                                </li>
                                <li class="px-4 py-2">
                                    <a href="add_item.php" class="text-gray-700 hover:text-gray-950">Add Item</a>
                                </li>
                            </ul>
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
                                <li class="px-4 py-2">
                                    <a href="add_sale.php" class="text-gray-700 hover:text-gray-950">Add Sale</a>
                                </li>
                            </ul>
                        </li>
                        <li class="px-4 py-2 hover:bg-gray-400 cursor-pointer">
                            <a href="analytics,php" class="text-gray-700 hover:text-gray-950">Analytics</a>
                        </li>
                    </ul>
                </div>
            </div>