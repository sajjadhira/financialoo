            <?php
            $header_visibility = get_option(FINANCIALOO_PREFIX . 'header_visibility');

            if ($header_visibility == 'show') {
            ?>
                <header>
                    <nav class="bg-white border-gray-200 px-4 lg:px-6 py-2.5 dark:bg-gray-800">
                        <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl">
                            <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white"><?php echo ucfirst(FINANCIALOO_NAME); ?></span>
                            <div class="hidden justify-between items-center w-full lg:flex lg:w-auto lg:order-1" id="mobile-menu-2">
                                <ul class="flex flex-col mt-4 font-medium lg:flex-row lg:space-x-8 lg:mt-0">

                                    <?php
                                    foreach (financialoo_get_submenus(FINANCIALOO_NAME) as $submenu) {
                                    ?>
                                        <li>
                                            <a href="<?php echo admin_url('admin.php?page=' . $submenu[2]) ?>" class="block py-2 pr-4 pl-3 text-gray-700 border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700"><?php echo $submenu[0] ?></a>
                                        </li>
                                    <?php
                                    }
                                    ?>


                                </ul>
                            </div>
                        </div>
                    </nav>
                </header>
            <?php
            }
