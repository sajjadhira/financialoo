<?php


if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $action = sanitize_text_field($action);
} else {
    $action = '';
}


if ($action == "form") {

    $action_button = 'Make Request';
    $id = null;
    $withdrawal_status = $amount = 0;
    $member_id = wp_get_current_user()->ID;


    global $wpdb;
    $prefix = $wpdb->prefix;
    $table_name = $prefix . FINANCIALOO_PREFIX . 'withdrawals';

    // get data from withdrawals_categories table


    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $id = sanitize_text_field($id);
        $action_button = 'Update Request';

        // get data from withdrawals_categories table

        $sql = "SELECT * FROM $table_name WHERE id = $id";
        $results = $wpdb->get_results($sql);

        # count rows

        if ($wpdb->num_rows > 0) {
            $id = $results[0]->id;
            $action_button = 'Update';
            $amount = $results[0]->amount;
            $withdrawal_status = $results[0]->status;
        }
    }

    if (isset($_POST["withdrawals_submit"])) {

        if (!isset($_POST[FINANCIALOO_PREFIX . 'withdrawals_nonce_field']) || !wp_verify_nonce($_POST[FINANCIALOO_PREFIX . 'withdrawals_nonce_field'], FINANCIALOO_PREFIX . 'withdrawals_nonce_action')) {
            $message = ['type' => 'error', 'color' => 'red', 'message' => 'Sorry, your nonce did not verify.'];
        } else {
            if (!isset($_POST["amount"])) {
                $message = ['type' => 'error', 'color' => 'red', 'message' => 'Please fill all fields.'];
            } else {
                $amount = sanitize_text_field($_POST["amount"]);

                if (is_null($id)) {

                    // insert into withdrawals_categories table

                    $member_insert = $wpdb->insert(
                        $table_name,
                        array(
                            'member_id' => $member_id,
                            'amount' => $amount,
                            'created_at' => current_time('mysql')
                        )
                    );
                } else {


                    if ($withdrawal_status == 0 && $member_id == $results[0]->member_id) {
                        // update withdrawal record

                        $withdrawal_update = $wpdb->update(
                            $table_name,
                            array(
                                'member_id' => $member_id,
                                'amount' => $amount,
                                'created_at' => current_time('mysql')
                            ),
                            array('id' => $id)
                        );


                        if ($withdrawal_update) {
                            $message = ['type' => 'success', 'color' => 'green', 'message' => 'Withdrawal request updated successfully.'];
                        } else {
                            $message = ['type' => 'error', 'color' => 'red', 'message' => 'Error: ' . $wpdb->last_error . ' in query: ' . $wpdb->last_query];
                        }
                    } else {
                        $message = ['type' => 'error', 'color' => 'red', 'message' => 'You can not update this withdrawal request.'];
                    }
                }
            }
        }
    }

?>
    <!-- set message -->

    <?php if (!empty($message)) { ?>
        <div class="bg-<?php echo $message["color"]; ?>-100 border border-<?php echo $message["color"]; ?>-400 text-<?php echo $message["color"]; ?>-700 px-4 py-3 rounded relative w-100" role="alert">
            <strong class="font-bold"><?php echo $message["message"]; ?></strong>
        </div>
    <?php } ?>



    <form method="POST">


        <?php
        // get all users from wp_users table
        ?>
        <div class="mb-4 mt-4">
            <label for="amount" class="block text-gray-700 font-medium mb-2">Amount*</label>
            <input name="amount" id="amount" class="w-2/3 px-3 py-2 border rounded-md focus:ring focus:ring-blue-300" value="<?php echo $amount; ?>" required>
        </div>

        <!-- Add Nonce -->

        <?php wp_nonce_field(FINANCIALOO_PREFIX . 'withdrawals_nonce_action', FINANCIALOO_PREFIX . 'withdrawals_nonce_field'); ?>

        <div class="mb-4 mt-4">
            <button type="submit" name="withdrawals_submit" class="w-2/3 bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">
                <?php echo $action_button; ?>
            </button>
        </div>
    </form>
<?php
}
