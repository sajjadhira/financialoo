<?php

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $action = sanitize_text_field($action);
} else {
    $action = '';
}

if ($action == "delete") {
    global $wpdb;
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $id = sanitize_text_field($id);
    } else {
        $id = null;
    }

    # check if record exists or not

    $table_name = $wpdb->prefix . FINANCIALOO_PREFIX . 'deposits';
    $sql = "SELECT * FROM $table_name WHERE id = $id";
    $results = $wpdb->get_results($sql);

    # count rows

    if ($wpdb->num_rows > 0) {
        $id = $results[0]->id;
        # delete record from expense_categories table
        $wpdb->delete($table_name, array('id' => $id));

        $message = ['type' => 'success', 'color' => 'green', 'message' => 'Deposit record deleted successfully.'];
    }

    include FINANCIALOO_PLUGIN_DIR . '/pages/partials/deposits/list.php';
}
