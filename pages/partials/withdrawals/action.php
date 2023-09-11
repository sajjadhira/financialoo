<?php

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $action = sanitize_text_field($action);
} else {
    $action = '';
}

if ($role == "cashier" && ($action == "approve" || $action == "decline")) {
    global $wpdb;
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $id = sanitize_text_field($id);
    } else {
        $id = null;
    }

    # check if record exists or not


    $table_name = $wpdb->prefix . FINANCIALOO_PREFIX . 'withdrawals';
    $sql = "SELECT * FROM $table_name WHERE id = $id";
    $results = $wpdb->get_results($sql);

    # count rows

    if ($wpdb->num_rows > 0) {
        $id = $results[0]->id;
        # update withdrawal status
        if ($action == "approve") {
            $wpdb->update($table_name, array('status' => 1), array('id' => $id));
        } else {
            $wpdb->update($table_name, array('status' => 2), array('id' => $id));
        }

        // insert data into transactions table
        $transaction_table_name = $wpdb->prefix . FINANCIALOO_PREFIX . 'transactions';
        $transaction_data = array(
            'member_id' => $results[0]->member_id,
            'amount' => $results[0]->amount,
            'type' => 'withdrawal',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s')
        );

        $message = ['type' => 'success', 'color' => 'green', 'message' => 'Withdrawals has been ' . $action . ' successfully.'];
    }

    include FINANCIALOO_PLUGIN_DIR . '/pages/partials/withdrawals/list.php';
}
