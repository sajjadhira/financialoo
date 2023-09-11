<?php

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $action = sanitize_text_field($action);
} else {
    $action = '';
}


$role = finacialoo_get_current_role();

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


            // insert data into transactions table
            $transaction_table_name = $wpdb->prefix . FINANCIALOO_PREFIX . 'transactions';

            //  add negetive sign to amount
            $amount = $results[0]->amount * (-1);
            $transaction_data = array(
                'member_id' => $results[0]->member_id,
                'amount' => $amount,
                'type' => 'withdrawal',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s')
            );

            $wpdb->insert($transaction_table_name, $transaction_data);
            $message = ['type' => 'success', 'color' => 'green', 'message' => 'Withdrawals has been ' . $action . ' successfully.'];
            $wpdb->update($table_name, array('status' => 1), array('id' => $id));
        } else {
            $message = ['type' => 'success', 'color' => 'green', 'message' => 'Withdrawals has been ' . $action . ' successfully.'];
            $wpdb->update($table_name, array('status' => 2), array('id' => $id));
        }
    }

    include FINANCIALOO_PLUGIN_DIR . '/pages/partials/withdrawals/list.php';
}
