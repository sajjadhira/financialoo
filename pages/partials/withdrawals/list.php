    <?php if (!empty($message)) { ?>
        <div class="mb-3 bg-<?php echo $message["color"]; ?>-100 border border-<?php echo $message["color"]; ?>-400 text-<?php echo $message["color"]; ?>-700 px-4 py-3 rounded relative w-100" role="alert">
            <strong class="font-bold"><?php echo $message["message"]; ?></strong>
        </div>
    <?php } ?>


    <h2 class="text-xl font-bold mb-4">List of Withdrawals</h2>

    <!-- add a `add new prompt` button to right -->

    <div class="flex justify-end mb-4">
        <a href="<?php echo admin_url('admin.php?page=' . FINANCIALOO_PREFIX . 'withdrawals&action=form') ?>" class="bg-blue-500 hover:bg-white-700 text-white font-bold py-2 px-4 rounded">Make Withdrawal Request</a>
    </div>

    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Amount
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Role
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
                $prefix = $wpdb->prefix;
                $table_name = $prefix . FINANCIALOO_PREFIX . 'withdrawals';
                $sql = "SELECT * FROM $table_name";
                $results = $wpdb->get_results($sql);
                foreach ($results as $result) {
                ?>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">

                        <th class="px-6 py-4">
                            <?php
                            // get name from member wp_user_id from wp_users table
                            $user = get_user_by('id', $result->wp_user_id);
                            echo esc_html($user->display_name);
                            ?>
                        </th>

                        <th class="px-6 py-4">
                            <?php
                            // get sum of amount from transactions table of member_id
                            $transaction_table_name = $prefix . FINANCIALOO_PREFIX . 'transactions';
                            $transaction_sql = "SELECT SUM(amount) as balance FROM $transaction_table_name WHERE member_id = $result->id";
                            $transaction_results = $wpdb->get_results($transaction_sql);
                            $balance = $transaction_results[0]->balance;
                            $balance = $balance ? $balance : 0;
                            echo esc_html($balance);

                            ?>
                        </th>
                        <th class="px-6 py-4">
                            <?php
                            $role = financialoo_get_role_by_id($result->role_id);
                            echo esc_html($role);
                            ?>
                        </th>

                        <td class="px-6 py-4">


                            <a href="<?php echo admin_url('admin.php?page=' . FINANCIALOO_PREFIX . 'members&action=form&id=' . $result->id) ?>" class="text-blue-600 hover:text-blue-900">Edit</a>
                            | <a href="<?php echo admin_url('admin.php?page=' . FINANCIALOO_PREFIX . 'members&action=delete&id=' . $result->id) ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure want to delete this <?php echo $title; ?>? Related all data will be deleted and will be undo.');">Delete</a>
                            | <a href="<?php echo admin_url('admin.php?page=' . FINANCIALOO_PREFIX . 'transactions&action=list&user=' . $result->wp_user_id) ?>" class="text-blue-600 hover:text-blue-900">Transactions</a>

                        </td>
                    </tr>
                <?php
                }
                ?>

            </tbody>
        </table>
    </div>