<?php


if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $action = sanitize_text_field($action);
} else {
    $action = '';
}


if ($action == "form") {

    $action_button = 'Save';
    $id = $category_id = $amount = $description = null;
    // $default_date = current time
    $default_date = date('Y-m-d\TH:i:s', time());

    global $wpdb;
    $prefix = $wpdb->prefix;
    $table_name = $prefix . FINANCIALOO_PREFIX . 'expenses';
    $category_table = $prefix . FINANCIALOO_PREFIX . 'expenses_categories';

    // get data from expense_categories table

    $sql = "SELECT * FROM $category_table";
    $categories = $wpdb->get_results($sql);


    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $id = sanitize_text_field($id);
        $action_button = 'Update';

        // get data from expense_categories table

        $sql = "SELECT * FROM $table_name WHERE id = $id";
        $results = $wpdb->get_results($sql);

        # count rows

        if ($wpdb->num_rows > 0) {
            $id = $results[0]->id;
            $action_button = 'Update';
            $category_id = $results[0]->category_id;
            $amount = $results[0]->amount;
            $description = $results[0]->description;
            $default_date = date('Y-m-d\TH:i:s', strtotime($results[0]->created_at));
        }
    }

    if (isset($_POST["expense_submit"])) {

        if (!isset($_POST[FINANCIALOO_PREFIX . 'expenses_nonce_field']) || !wp_verify_nonce($_POST[FINANCIALOO_PREFIX . 'expenses_nonce_field'], FINANCIALOO_PREFIX . 'expenses_nonce_action')) {
            $message = ['type' => 'error', 'color' => 'red', 'message' => 'Sorry, your nonce did not verify.'];
        } else {
            if (!isset($_POST["amount"]) || !isset($_POST["category_id"]) || !isset($_POST["date"])) {
                $message = ['type' => 'error', 'color' => 'red', 'message' => 'Please fill all fields.'];
            } else {
                $amount = sanitize_text_field($_POST["amount"]);
                $category_id = sanitize_text_field($_POST["category_id"]);
                $description = sanitize_textarea_field($_POST["description"]);
                $date = sanitize_text_field($_POST["date"]);

                // convert date to mysql format
                $date = date('Y-m-d H:i:s', strtotime($date));
                // $default_date format 
                $default_date = date('Y-m-d\TH:i:s', strtotime($date));

                # get current user id

                $user_id = get_current_user_id();


                if (is_null($id)) {

                    // insert into expense_categories table


                    $wpdb->insert(
                        $table_name,
                        array(
                            'amount' => $amount,
                            'category_id' => $category_id,
                            'description' => $description,
                            'wp_user_id' => $user_id,
                            'created_at' => $date
                        )
                    );

                    $message = ['type' => 'success', 'color' => 'green', 'message' => 'Expense added successfully.'];
                } else {

                    // update into expense_categories table

                    $wpdb->update(
                        $table_name,
                        array(
                            'amount' => $amount,
                            'category_id' => $category_id,
                            'description' => $description,
                            'wp_user_id' => $user_id,
                            'created_at' => $date
                        ),
                        array('id' => $id)
                    );

                    $message = ['type' => 'success', 'color' => 'green', 'message' => 'Expense updated successfully.'];
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
            <label for="description" class="block text-gray-700 font-medium mb-2">Description</label>
            <textarea name="description" id="description" class="w-2/3 px-3 py-2 border rounded-md focus:ring focus:ring-blue-300" placeholder="Input Description"><?php echo $description; ?></textarea>

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
            <label for="date" class="block text-gray-700 font-medium mb-2">Date*</label>
            <!-- input for datetime and default will be $default_date -->
            <input name="date" id="date" type="datetime-local" class="w-2/3 px-3 py-2 border rounded-md focus:ring focus:ring-blue-300" placeholder="Date" value="<?php echo $default_date; ?>" required>

        </div>


        <!-- Add Nonce -->

        <?php wp_nonce_field(FINANCIALOO_PREFIX . 'expenses_nonce_action', FINANCIALOO_PREFIX . 'expenses_nonce_field'); ?>

        <div class="mb-4 mt-4">
            <button type="submit" name="expense_submit" class="w-2/3 bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">
                <?php echo $action_button; ?>
            </button>
        </div>
    </form>
<?php
}
