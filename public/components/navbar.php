<nav class="flex items-center justify-between flex-wrap w-94% mx-auto py-1">
    <div class="">
        <a class="text-2xl font-bold px-4 py-2 flex items-center cursor-pointer" href="index.php">
            PHARMA
            <span class="bg-gradient-to-br from-red-500 to-teal-400 bg-clip-text text-transparent">TREND</span>
        </a>
    </div>
    <div class="">
        <?php if(isset($_SESSION["id"])){ ?>
            <!-- account -->
        <div>
            <button type="button"
                class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2"
                id="accountMenuButton" aria-expanded="false" aria-haspopup="true" onclick="toogleAccountMenu()">
                <i class="fas fa-user-circle mr-2 mt-1"></i>
                Account
                <i class="fas fa-chevron-down ml-4 mt-1"></i>
            </button>
        </div>
        <!-- account menu dropdown absolute hidden -->
        <div id="accountMenu"
            class="absolute right-2 mt-2 w-auto h-auto min-w-[300px] rounded-md shadow-md bg-white ring-1 ring-black ring-opacity-5 py-4 z-10 px-4 hidden">
            <!-- account profile -->
            <div class="flex flex-col items-start justify-start">
                <div class="flex flex-row justify-between items-center space-x-2">
                    <div class="flex flex-col items-start space-x-2">
                        <h1 class="text-lg font-bold mt-2 ml-2">
                            <?php echo $_SESSION["fullname"]; ?>
                        </h1>
                        <h2 class="text-sm text-gray-500">
                            <i class="fas fa-circle text-green-500 mr-2"></i><?php echo $_SESSION["status"]; ?>
                        </h2>
                    </div>
                </div>
                <!-- account menu -->
                <ul class="space-y-2 w-full mt-4">
                    <li
                        class="flex flex-row justify-between items-center space-x-2 hover:bg-fuchsia-200 rounded-md p-2 cursor-pointer">
                        <div>
                            <i class="fas fa-user-circle text-fuchsia-500 mr-2"></i>
                            <a href="profile.php" class="text-gray-500 hover:text-gray-900">Profile</a>
                        </div>
                    </li>
                    <hr class="border-gray-200">
                    <li
                        class="flex flex-row justify-between items-center space-x-2 hover:bg-fuchsia-200 rounded-md p-2 cursor-pointer">
                        <div>
                            <i class="fas fa-sign-out-alt text-fuchsia-500 mr-2"></i>
                            <a href="signout.php" class="text-gray-500 hover:text-gray-900">Sign Out</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <?php } ?>
    </div>
    </div>
</nav>