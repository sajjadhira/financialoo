<h2 class="text-xl font-bold mb-4">List of Expense Categories</h2>

<!-- add a `add new prompt` button to right -->

<div class="flex justify-end mb-4">
    <a href="<?php echo admin_url('admin.php?page=' . FINANCIALOO_PREFIX . 'expense_categories&action=form') ?>" class="bg-blue-500 hover:bg-white-700 text-white font-bold py-2 px-4 rounded">Add New</a>
</div>

<div class="relative overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Name
                </th>
                <th scope="col" class="px-6 py-3">
                    Total Expenses
                </th>
                <th scope="col" class="px-6 py-3">
                    Status
                </th>
                <th scope="col" class="px-6 py-3">
                    Actions
                </th>
            </tr>
        </thead>
        <tbody>

            <!-- get data from tables -->

            <?php
            global $wpdb;
            $table_name = $wpdb->prefix . FINANCIALOO_PREFIX . 'expenses_categories';
            $sql = "SELECT * FROM $table_name";
            $results = $wpdb->get_results($sql);
            foreach ($results as $result) {
            ?>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <?php
                        echo $result->name;
                        ?>

                    </th>
                    <td class="px-6 py-4">
                        <?php
                        // count total expenses `amount`  of this category
                        $table_name = $wpdb->prefix . FINANCIALOO_PREFIX . 'expenses';
                        $sql = "SELECT SUM(amount) as total FROM $table_name WHERE category_id = $result->id";
                        $total = $wpdb->get_results($sql);
                        echo $total[0]->total;
                        ?>
                    </td>
                    <td class="px-6 py-4">
                        <?php
                        if ($result->status == 0) {
                            echo '<span class="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">Pending</span>';
                        } else {
                            echo '<span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Done</span>';
                        }
                        ?>
                    </td>
                    <td class="px-6 py-4">


                        <a href="<?php echo admin_url('admin.php?page=' . FINANCIALOO_PREFIX . 'expense_categories&action=form&id=' . $result->id) ?>" class="text-blue-600 hover:text-blue-900">Edit</a>
                        | <a href="<?php echo admin_url('admin.php?page=' . FINANCIALOO_PREFIX . 'expense_categories&action=delete&id=' . $result->id) ?>" class="text-red-600 hover:text-red-900">Delete</a>

                    </td>
                </tr>
            <?php
            }
            ?>

        </tbody>
    </table>
</div>