<?php
if(!isset($_SESSION["id"])){
    ob_start();
    header("Location: signin.php");
    
    die();
}
?>
<div class="w-2/12 h-[calc(100vh-3.5rem)] border-r-2 border-gray-200 overflow-y-auto overflow-hidden no-scrollbar">
    <div class="flex flex-col items-center justify-center mt-4  p-2">
        <h1 class="text-lg"><?php echo $_SESSION["fullname"]; ?></h1>
        <div class="flex flex-row items-center justify-center gap-2">
            <span class="text-sm text-gray-500 bg-gray-200 px-2 py-1 rounded-full">
                <i class="fas fa-user"></i>
                <?php echo $_SESSION["role"]; ?></span>
            <span class="text-sm text-gray-500 bg-green-200 px-2 py-1 rounded-full">
                <i class="fas fa-circle text-green-500 mr-2"></i>
                <?php echo $_SESSION["status"]; ?>
            </span>
        </div>
    </div>
    <hr class="my-4">
    <div class="p-2">
        <!-- sidebar Menu menu list with icons and text dropdown -->
        <ul class="space-y-2">
            <li
                class="flex flex-row justify-between items-center space-x-2 hover:bg-fuchsia-200 rounded-md p-2 cursor-pointer">
                <div>
                    <i class=" text-fuchsia-500 text-lg mr-2 fas fa-tachometer-alt"></i>
                    <a href="dashboard.php" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                </div>
            </li>
            <hr class="border-gray-200">
            <li
                class="flex flex-row justify-between items-center space-x-2 hover:bg-fuchsia-200 rounded-md p-2 cursor-pointer">
                <div class="flex flex-row items-center">
                    <i class="fa-solid fa-user text-fuchsia-500 text-lg mr-2"></i>
                    <a href="profile.php" class="text-gray-700 hover:text-gray-900">Profile</a>
                </div>
            </li>
            <?php
            if($_SESSION["role"] == "admin"){
            ?>
            <li class="flex flex-row justify-between items-center space-x-2 hover:bg-fuchsia-200 rounded-md p-2 cursor-pointer"
                onclick="toggleDropdown('5')">
                <div class="flex flex-row items-center">
                    <i class="fa-solid fa-lock text-fuchsia-500 text-lg mr-2"></i>
                    <p class="text-gray-700 hover:text-gray-900">Manage</p>
                </div>
                <!-- dropdown menu -->
                <div>
                    <i class="fas fa-chevron-down first-letter:text-gray-500 ml-auto rotate-180"
                        id="dropdown-icon-5"></i>
                </div>
            </li>
            <!-- dropdown menu list with icons and text -->
            <ul id="dropdown-menu-5" class="flex-col space-y-2 pl-4 py-2 hidden">
                <li
                    class="flex flex-row justify-between items-center space-x-2 hover:bg-fuchsia-200 rounded-md p-2 cursor-pointer">
                    <div>
                        <i class="text-fuchsia-500 text-lg mr-2 fas fa-users"></i>
                        <a href="users.php" class="text-gray-700 hover:text-gray-900">Users</a>
                    </div>
                </li>
                <li
                    class="flex flex-row justify-between items-center space-x-2 hover:bg-fuchsia-200 rounded-md p-2 cursor-pointer">
                    <div>
                        <i class="text-fuchsia-500 text-lg mr-2 fas fa-user-plus"></i>
                        <a href="add_user.php" class="text-gray-700 hover:text-gray-900">Add User</a>
                    </div>
                </li>
                <li
                    class="flex flex-row justify-between items-center space-x-2 hover:bg-fuchsia-200 rounded-md p-2 cursor-pointer">
                    <div>
                        <i class="text-fuchsia-500 text-lg mr-2 fas fa-user-cog"></i>
                        <a href="roles.php" class="text-gray-700 hover:text-gray-900">Roles</a>
                    </div>
                </li>
            </ul>
            <?php
            }
            ?>
            <hr class="border-gray-200">
            <li class="flex flex-row justify-between items-center space-x-2 hover:bg-fuchsia-200 rounded-md p-2 cursor-pointer"
                onclick="toggleDropdown('2')">
                <div class="flex flex-row items-center">
                    <i class="fa-solid fa-boxes-stacked text-fuchsia-500 text-lg mr-2"></i>
                    <p class="text-gray-700 hover:text-gray-900">Items</p>
                </div>
                <!-- dropdown menu -->
                <div>
                    <i class="fas fa-chevron-down first-letter:text-gray-500 ml-auto rotate-180"
                        id="dropdown-icon-2"></i>
                </div>
            </li>
            <!-- dropdown menu list with icons and text -->
            <ul id="dropdown-menu-2" class="flex-col space-y-2 pl-4 py-2 hidden">
                <li
                    class="flex flex-row justify-between items-center space-x-2 hover:bg-fuchsia-200 rounded-md p-2 cursor-pointer">
                    <div>
                        <i class="text-fuchsia-500 text-lg mr-2 fas fa-boxes"></i>
                        <a href="items.php?page=1&search=" class="text-gray-700 hover:text-gray-900">Items</a>
                    </div>
                </li>
                <li
                    class="flex flex-row justify-between items-center space-x-2 hover:bg-fuchsia-200 rounded-md p-2 cursor-pointer">
                    <div>
                        <i class="text-fuchsia-500 text-lg mr-2 fas fa-plus"></i>
                        <a href="add_item.php" class="text-gray-700 hover:text-gray-900">Add Item</a>
                    </div>
                </li>
            </ul>
            <hr class="border-gray-200">
            <li class="flex flex-row justify-between items-center space-x-2 hover:bg-fuchsia-200 rounded-md p-2 cursor-pointer"
                onclick="toggleDropdown('3')">
                <div class="flex flex-row items-center">
                    <i class="fa-solid fa-shopping-cart text-fuchsia-500 text-lg mr-2"></i>
                    <p class="text-gray-700 hover:text-gray-900">Sales</p>
                </div>
                <!-- dropdown menu -->
                <div>
                    <i class="fas fa-chevron-down first-letter:text-gray-500 ml-auto rotate-180"
                        id="dropdown-icon-3"></i>
                </div>
            </li>
            <!-- dropdown menu list with icons and text -->
            <ul id="dropdown-menu-3" class="flex-col space-y-2 pl-4 py-2 hidden">
                <li
                    class="flex flex-row justify-between items-center space-x-2 hover:bg-fuchsia-200 rounded-md p-2 cursor-pointer">
                    <div>
                        <i class="text-fuchsia-500 text-lg mr-2 fas fa-shopping-cart"></i>
                        <a href="sales.php?page=1&search=" class="text-gray-700 hover:text-gray-900">Sales</a>
                    </div>
                </li>
                <li
                    class="flex flex-row justify-between items-center space-x-2 hover:bg-fuchsia-200 rounded-md p-2 cursor-pointer">
                    <div>
                        <i class="text-fuchsia-500 text-lg mr-2 fas fa-plus"></i>
                        <a href="add_sale.php" class="text-gray-700 hover:text-gray-900">Add Sale</a>
                    </div>
                </li>
                <li
                    class="flex flex-row justify-between items-center space-x-2 hover:bg-fuchsia-200 rounded-md p-2 cursor-pointer">
                    <div>
                        <i class="text-fuchsia-500 text-lg mr-2 fa-solid fa-bag-shopping"></i>
                        <a href="sales_per_item.php?page=1&search=" class="text-gray-700 hover:text-gray-900">Sales
                            Per Item</a>
                    </div>
                </li>
                <li
                    class="flex flex-row justify-between items-center space-x-2 hover:bg-fuchsia-200 rounded-md p-2 cursor-pointer">
                    <div>
                        <i class="text-fuchsia-500 text-lg mr-2 fa-solid fa-calendar"></i>
                        <a href="sales_per_month.php?page=1&year="
                            class="text-gray-700 hover:text-gray-900">Sales Per Month</a>
                    </div>
                </li>
            </ul>
            <hr class="border-gray-200">
            <li class="flex flex-row justify-between items-center space-x-2 hover:bg-fuchsia-200 rounded-md p-2 cursor-pointer"
                onclick="toggleDropdown('4')">
                <div class="flex flex-row items-center">
                    <i class="fa-solid fa-chart-bar text-fuchsia-500 text-lg mr-2"></i>
                    <p class="text-gray-700 hover:text-gray-900">Analytics</p>
                </div>
                <!-- dropdown menu -->
                <div>
                    <i class="fas fa-chevron-down first-letter:text-gray-500 ml-auto rotate-180"
                        id="dropdown-icon-4"></i>
                </div>
            </li>
            <!-- dropdown menu list with icons and text -->
            <ul id="dropdown-menu-4" class="flex-col space-y-2 pl-4 py-2 hidden">
                <li
                    class="flex flex-row justify-between items-center space-x-2 hover:bg-fuchsia-200 rounded-md p-2 cursor-pointer">
                    <div>
                        <i class="text-fuchsia-500 text-lg mr-2 fa-solid fa-chart-bar"></i>
                        <a href="analytics.php" class="text-gray-700 hover:text-gray-900">Analytics All</a>
                    </div>
                </li>
                <li
                    class="flex flex-row justify-between items-center space-x-2 hover:bg-fuchsia-200 rounded-md p-2 cursor-pointer">
                    <div>
                        <i class="text-fuchsia-500 text-lg mr-2 fa-solid fa-chart-bar"></i>
                        <a href="analytics_per_item.php" class="text-gray-700 hover:text-gray-900">Analytics Per
                            Item</a>
                    </div>
                </li>
            </ul>
            <hr class="border-gray-200">
            <li
                class="flex flex-row justify-between items-center space-x-2 hover:bg-fuchsia-200 rounded-md p-2 cursor-pointer">
                <div class="flex flex-row items-center">
                    <i class="fa-solid fa-gear text-fuchsia-500 text-lg mr-2"></i>
                    <a class="text-gray-700 hover:text-gray-900" href="settings.php">Settings</a>
                </div>
            </li>
            <!-- Sign out -->
            <hr class="border-gray-200">
            <li
                class="flex flex-row justify-between items-center space-x-2 hover:bg-fuchsia-200 rounded-md p-2 cursor-pointer">
                <div class="flex flex-row items-center">
                    <i class="fa-solid fa-sign-out text-fuchsia-500 text-lg mr-2"></i>
                    <a href="signout.php" class="text-gray-700 hover:text-gray-900">Sign Out</a>
                </div>
            </li>
        </ul>
    </div>
</div>