    <?php if (!empty($message)) { ?>
        <div class="mb-3 bg-<?php echo $message["color"]; ?>-100 border border-<?php echo $message["color"]; ?>-400 text-<?php echo $message["color"]; ?>-700 px-4 py-3 rounded relative w-100" role="alert">
            <strong class="font-bold"><?php echo $message["message"]; ?></strong>
        </div>
    <?php } ?>


    <h2 class="text-xl font-bold mb-4">List of Transactions</h2>

    <!-- add a `add new prompt` button to right -->


    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Transaction ID
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Amount
                    </th>

                    <th scope="col" class="px-6 py-3">
                        Member
                    </th>

                    <th scope="col" class="px-6 py-3">
                        Date
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
                $table_name = $prefix . FINANCIALOO_PREFIX . 'transactions';
                $sql = "SELECT * FROM $table_name";
                $results = $wpdb->get_results($sql);
                foreach ($results as $result) {
                ?>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">

                        <th class="px-6 py-4">
                            <?php
                            echo esc_html($result->transaction_id);
                            ?>
                        </th>
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <?php
                            echo esc_html($result->amount);
                            ?>

                        </td>

                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <?php

                            # get user name from user id

                            $member = get_user_by('id', $result->member_id);

                            echo  esc_html($member->display_name);
                            ?>

                        </td>

                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <?php
                            echo  esc_html(date('d F Y h:i A', strtotime($result->created_at)));
                            ?>
                        </td>

                        <td class="px-6 py-4">


                            <a href="<?php echo admin_url('admin.php?page=' . FINANCIALOO_PREFIX . 'deposits&action=form&id=' . $result->id) ?>" class="text-blue-600 hover:text-blue-900">Edit</a>
                            | <a href="<?php echo admin_url('admin.php?page=' . FINANCIALOO_PREFIX . 'deposits&action=delete&id=' . $result->id) ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure want to delete this <?php echo $title; ?>? Related all data will be deleted and will be undo.');">Delete</a>

                        </td>
                    </tr>
                <?php
                }
                ?>

            </tbody>
        </table>
    </div>