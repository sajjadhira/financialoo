<?php


if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $action = sanitize_text_field($action);
} else {
    $action = '';
}


if ($action == "form") {

    $action_button = 'Save';
    $id = $category_id = $amount = $member_id = null;

    global $wpdb;
    $prefix = $wpdb->prefix;
    $table_name = $prefix . FINANCIALOO_PREFIX . 'deposits';
    $category_table = $prefix . FINANCIALOO_PREFIX . 'deposits_categories';

    // get members list from members table

    $members_table = $prefix . FINANCIALOO_PREFIX . 'members';
    $sql = "SELECT * FROM $members_table";
    $members = $wpdb->get_results($sql);

    // get data from deposit_categories table

    $sql = "SELECT * FROM $category_table";
    $categories = $wpdb->get_results($sql);


    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $id = sanitize_text_field($id);
        $action_button = 'Update';

        // get data from deposit_categories table

        $sql = "SELECT * FROM $table_name WHERE id = $id";
        $results = $wpdb->get_results($sql);

        # count rows

        if ($wpdb->num_rows > 0) {
            $id = $results[0]->id;
            $action_button = 'Update';
            $category_id = $results[0]->category_id;
            $amount = $results[0]->amount;
            $member_id = $results[0]->member_id;
        }
    }

    if (isset($_POST["deposit_submit"])) {

        if (!isset($_POST[FINANCIALOO_PREFIX . 'deposits_nonce_field']) || !wp_verify_nonce($_POST[FINANCIALOO_PREFIX . 'deposits_nonce_field'], FINANCIALOO_PREFIX . 'deposits_nonce_action')) {
            $message = ['type' => 'error', 'color' => 'red', 'message' => 'Sorry, your nonce did not verify.'];
        } else {
            if (!isset($_POST["amount"]) || !isset($_POST["category_id"]) || !isset($_POST["member_id"])) {
                $message = ['type' => 'error', 'color' => 'red', 'message' => 'Please fill all fields.'];
            } else {
                $amount = sanitize_text_field($_POST["amount"]);
                $category_id = sanitize_text_field($_POST["category_id"]);
                $member_id = sanitize_text_field($_POST["member_id"]);

                # get current user id

                $user_id = get_current_user_id();


                if (is_null($id)) {

                    // insert into deposit_categories table


                    $inserted = $wpdb->insert(
                        $table_name,
                        array(
                            'amount' => $amount,
                            'category_id' => $category_id,
                            'wp_user_id' => $user_id,
                            'member_id' => $member_id,
                            'created_at' => current_time('mysql')
                        )
                    );

                    # check if  inserted

                    if ($inserted) {
                        $message = ['type' => 'success', 'color' => 'green', 'message' => 'Deposit added successfully.'];
                    } else {
                        # print wordpress insert error
                        $message = ['type' => 'error', 'color' => 'red', 'message' => $wpdb->last_error];
                    }
                } else {

                    // update into deposit_categories table

                    $wpdb->update(
                        $table_name,
                        array(
                            'amount' => $amount,
                            'category_id' => $category_id,
                            'wp_user_id' => $user_id,
                            'member_id' => $member_id,
                            'created_at' => current_time('mysql')
                        ),
                        array('id' => $id)
                    );

                    $message = ['type' => 'success', 'color' => 'green', 'message' => 'Deposit updated successfully.'];
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

        <div class="mb-4 mt-4">
            <label for="amount" class="block text-gray-700 font-medium mb-2">Amount*</label>
            <input name="amount" id="amount" type="number" class="w-2/3 px-3 py-2 border rounded-md focus:ring focus:ring-blue-300" placeholder="Amount" value="<?php echo $amount; ?>" required>

        </div>

        <div class="mb-4 mt-4">
            <label for="category_id" class="block text-gray-700 font-medium mb-2">Category*</label>
            <select name="category_id" id="category_id" class="w-2/3 px-3 py-2 border rounded-md focus:ring focus:ring-blue-300" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $key => $category) { ?>
                    <option value="<?php echo $category->id; ?>" <?php if ($category->id == $category_id) {
                                                                        echo 'selected';
                                                                    } ?>><?php echo $category->name; ?></option>
                <?php } ?>
            </select>

        </div>

        <div class="mb-4 mt-4">
            <label for="member_id" class="block text-gray-700 font-medium mb-2">Select Member*</label>
            <select name="member_id" id="member_id" class="w-2/3 px-3 py-2 border rounded-md focus:ring focus:ring-blue-300" required>
                <option value="">Select Member</option>
                <?php foreach ($members as $key => $member) { ?>
                    <option value="<?php echo $member->id; ?>" <?php if ($member->id == $member_id) {
                                                                    echo 'selected';
                                                                } ?>><?php
                                                                        // get member name from user table using wp_user_id
                                                                        $user_info = get_userdata($member->wp_user_id);
                                                                        echo $user_info->user_login;
                                                                        ?></option>
                <?php } ?>
            </select>

        </div>

        <!-- Add Nonce -->

        <?php wp_nonce_field(FINANCIALOO_PREFIX . 'deposits_nonce_action', FINANCIALOO_PREFIX . 'deposits_nonce_field'); ?>

        <div class="mb-4 mt-4">
            <button type="submit" name="deposit_submit" class="w-2/3 bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">
                <?php echo $action_button; ?>
            </button>
        </div>
    </form>
<?php
}
