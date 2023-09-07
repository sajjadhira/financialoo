<?php


if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $action = sanitize_text_field($action);
} else {
    $action = '';
}


if ($action == "form") {

    $statuses = [
        0 => 'Inactive',
        1 => 'Active'
    ];

    $id = null;
    $action_button = 'Save';
    $category_name = '';
    $category_status = '';

    global $wpdb;
    $table_name = $wpdb->prefix . FINANCIALOO_PREFIX . 'deposits_categories';

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $id = sanitize_text_field($id);
        $action_button = 'Update';

        // get data from deposite_categories table

        $sql = "SELECT * FROM $table_name WHERE id = $id";
        $results = $wpdb->get_results($sql);

        # count rows

        if ($wpdb->num_rows > 0) {
            $id = $results[0]->id;
            $action_button = 'Update';
            $category_name = $results[0]->name;
            $category_status = $results[0]->status;
        }
    }

    if (isset($_POST["deposit_category_submit"])) {

        if (!isset($_POST[FINANCIALOO_PREFIX . 'deposit_category_nonce_field']) || !wp_verify_nonce($_POST[FINANCIALOO_PREFIX . 'deposit_category_nonce_field'], FINANCIALOO_PREFIX . 'deposit_category_nonce_action')) {
            $message = ['type' => 'error', 'color' => 'red', 'message' => 'Sorry, your nonce did not verify.'];
        } else {
            if (!isset($_POST["name"]) || !isset($_POST["status"])) {
                $message = ['type' => 'error', 'color' => 'red', 'message' => 'Please fill all fields.'];
            } else {
                $name = sanitize_text_field($_POST["name"]);
                $status = sanitize_text_field($_POST["status"]);

                # now check expense category name already exists or not


                $sql = "SELECT * FROM $table_name WHERE name = '$name' and id != $id";
                $results = $wpdb->get_results($sql);

                # count rows

                if ($wpdb->num_rows > 0) {
                    $message = ['type' => 'error', 'color' => 'red', 'message' => 'Deposit Category already exists.'];
                } else {

                    if (is_null($id)) {

                        // insert into expense_categories table

                        $wpdb->insert(
                            $table_name,
                            array(
                                'name' => $name,
                                'status' => $status,
                                'created_at' => current_time('mysql')
                            )
                        );

                        $message = ['type' => 'success', 'color' => 'green', 'message' => 'Deposit Category added successfully.'];
                    } else {

                        // update into expense_categories table

                        $wpdb->update(
                            $table_name,
                            array(
                                'name' => $name,
                                'status' => $status,
                                'created_at' => current_time('mysql')
                            ),
                            array('id' => $id)
                        );

                        $message = ['type' => 'success', 'color' => 'green', 'message' => 'Deposit Category updated successfully.'];
                    }
                }

                $category_name = $name;
                $category_status = $status;
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
            <label for="name" class="block text-gray-700 font-medium mb-2">Category Name</label>
            <input name="name" id="name" class="w-2/3 px-3 py-2 border rounded-md focus:ring focus:ring-blue-300" placeholder="Category Name" value="<?php echo $category_name; ?>" required>

        </div>

        <div class="mb-4 mt-4">
            <label for="status" class="block text-gray-700 font-medium mb-2">Status</label>
            <select name="status" id="status" class="w-2/3 px-3 py-2 border rounded-md focus:ring focus:ring-blue-300" required>
                <option value="">Select Status</option>
                <?php foreach ($statuses as $key => $value) { ?>
                    <option value="<?php echo $key; ?>" <?php if ($category_status == $key) {
                                                            echo 'selected';
                                                        } ?>><?php echo $value; ?></option>
                <?php } ?>
            </select>

        </div>


        <!-- Add Nonce -->

        <?php wp_nonce_field(FINANCIALOO_PREFIX . 'deposit_category_nonce_action', FINANCIALOO_PREFIX . 'deposit_category_nonce_field'); ?>

        <div class="mb-4 mt-4">
            <button type="submit" name="deposit_category_submit" class="w-2/3 bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">
                <?php echo $action_button; ?>
            </button>
        </div>
    </form>
<?php
}
