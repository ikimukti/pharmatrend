<nav class="flex items-center justify-between flex-wrap w-94% mx-auto py-1">
            <div class="">
                <h1 class="text-2xl font-bold px-4 py-2">
                    SKRIPSI
                    <span class="bg-gradient-to-br from-red-500 to-teal-400 bg-clip-text text-transparent">ARIP</span>
                </h1>
            </div>
            <div class="">
                <ul class="flex items-center gap-4">
                    <?php
                        if(!isset($_SESSION["id"])){
                    ?>
                    <li class="px-4 py-2">
                        <a href="dashboard.php" class="text-gray-700 hover:text-gray-950">Home</a>
                    </li>
                    <?php
                        }
                    ?>
                    
                    <li class="px-4 py-2">
                        <a href="about.php" class="text-gray-700 hover:text-gray-950">About</a>
                    </li>
                    <li class="px-4 py-2">
                        <a href="contact.php" class="text-gray-700 hover:text-gray-950">Contact</a>
                    </li>
                    <li class="px-4 py-2">
                        <a href="blog.php" class="text-gray-700 hover:text-gray-950">Blog</a>
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