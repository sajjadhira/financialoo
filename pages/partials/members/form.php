<?php


if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $action = sanitize_text_field($action);
} else {
    $action = '';
}


if ($action == "form") {

    $action_button = 'Save';
    $id = $role_id = $member_id =  null;

    global $wpdb;
    $prefix = $wpdb->prefix;
    $table_name = $prefix . FINANCIALOO_PREFIX . 'members';

    // get data from members_categories table


    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $id = sanitize_text_field($id);
        $action_button = 'Update';

        // get data from members_categories table

        $sql = "SELECT * FROM $table_name WHERE id = $id";
        $results = $wpdb->get_results($sql);

        # count rows

        if ($wpdb->num_rows > 0) {
            $id = $results[0]->id;
            $action_button = 'Update';
            $member_id = $results[0]->wp_user_id;
            $role_id = $results[0]->role_id;
        }
    }

    if (isset($_POST["members_submit"])) {

        if (!isset($_POST[FINANCIALOO_PREFIX . 'members_nonce_field']) || !wp_verify_nonce($_POST[FINANCIALOO_PREFIX . 'members_nonce_field'], FINANCIALOO_PREFIX . 'members_nonce_action')) {
            $message = ['type' => 'error', 'color' => 'red', 'message' => 'Sorry, your nonce did not verify.'];
        } else {
            if (!isset($_POST["member_id"]) || !isset($_POST["role_id"])) {
                $message = ['type' => 'error', 'color' => 'red', 'message' => 'Please fill all fields.'];
            } else {
                $member_id = sanitize_text_field($_POST["member_id"]);
                $role_id = sanitize_text_field($_POST["role_id"]);

                if (is_null($id)) {

                    // insert into members_categories table

                    // check if already exists

                    $member_find_sql = "SELECT * FROM $table_name WHERE wp_user_id = $member_id";
                    $member_results = $wpdb->get_results($member_find_sql);

                    if ($wpdb->num_rows > 0) {
                        $message = ['type' => 'error', 'color' => 'red', 'message' => 'Member already exists.'];
                    } else {
                        $member_inserted = $wpdb->insert(
                            $table_name,
                            array(
                                'wp_user_id' => $member_id,
                                'role_id' => $role_id,
                                'created_at' => current_time('mysql')
                            )
                        );

                        if ($member_inserted) {
                            $message = ['type' => 'success', 'color' => 'green', 'message' => 'Member information added successfully.'];
                        } else {
                            $message = ['type' => 'error', 'color' => 'red', 'message' => 'Error: ' . $wpdb->last_error . ' in query: ' . $wpdb->last_query];
                        }
                    }
                } else {

                    // update into members_categories table

                    $member_update = $wpdb->update(
                        $table_name,
                        array(
                            'wp_user_id' => $member_id,
                            'role_id' => $role_id,
                            'created_at' => current_time('mysql')
                        ),
                        array('id' => $id)
                    );

                    if ($member_update) {
                        $message = ['type' => 'success', 'color' => 'green', 'message' => 'Member information updated successfully.'];
                    } else {
                        $message = ['type' => 'error', 'color' => 'red', 'message' => 'Error: ' . $wpdb->last_error . ' in query: ' . $wpdb->last_query];
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

        $users = get_users();
        $roles = finacialoo_get_roles();
        ?>
        <div class="mb-4 mt-4">
            <label for="member_id" class="block text-gray-700 font-medium mb-2">Select Member*</label>
            <select name="member_id" id="member_id" class="w-2/3 px-3 py-2 border rounded-md focus:ring focus:ring-blue-300" required>
                <option value="">Select Member</option>
                <?php foreach ($users as $key => $member) { ?>
                    <option value="<?php echo $member->ID; ?>" <?php if ($member->ID == $member_id) {
                                                                    echo 'selected';
                                                                } ?>><?php echo $member->display_name; ?></option>
                <?php } ?>
            </select>

        </div>
        <div class="mb-4 mt-4">
            <label for="role_id" class="block text-gray-700 font-medium mb-2">Select Role*</label>
            <select name="role_id" id="role_id" class="w-2/3 px-3 py-2 border rounded-md focus:ring focus:ring-blue-300" required>
                <option value="">Select Role</option>
                <?php foreach ($roles as $key => $role) { ?>
                    <option value="<?php echo $role->id; ?>" <?php if ($role->id == $role_id) {
                                                                    echo 'selected';
                                                                } ?>><?php echo $role->name; ?></option>
                <?php } ?>
            </select>

        </div>

        <!-- Add Nonce -->

        <?php wp_nonce_field(FINANCIALOO_PREFIX . 'members_nonce_action', FINANCIALOO_PREFIX . 'members_nonce_field'); ?>

        <div class="mb-4 mt-4">
            <button type="submit" name="members_submit" class="w-2/3 bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">
                <?php echo $action_button; ?>
            </button>
        </div>
    </form>
<?php
}
