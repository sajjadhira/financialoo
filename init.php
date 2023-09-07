<?php


# inclusw styles.php file

require_once FINANCIALOO_PLUGIN_DIR . '/styles.php';
require_once FINANCIALOO_PLUGIN_DIR . '/functions.php';

# inti action



# add menu

function financialoo_add_menu()
{

    add_menu_page(
        __(ucfirst(FINANCIALOO_NAME), FINANCIALOO_NAME),
        __(ucfirst(FINANCIALOO_NAME), FINANCIALOO_NAME),
        'manage_options',
        FINANCIALOO_NAME,
        FINANCIALOO_PREFIX . 'home_page',
        'dashicons-chart-area'
    );

    include_page('Home', FINANCIALOO_PREFIX . 'home_page');

    # add submenu
    # lets make an array of submenus
    $submenus = [
        'deposits' => 'Deposits',
        'expenses' => 'Expenses',
        'transactions' => 'Transactions',
        'withdrawals' => 'Withdrawals',
        'members' => 'Members',
        'expense_categories' => 'Expense Categories',
        'deposit_categories' => 'Deposit Categories',
        'rols' => 'Roles',
    ];

    foreach ($submenus as $key => $value) {
        add_submenu_page(
            FINANCIALOO_NAME,
            __($value, FINANCIALOO_NAME),
            __($value, FINANCIALOO_NAME),
            'manage_options',
            FINANCIALOO_PREFIX .  $key,
            FINANCIALOO_PREFIX . $key
        );

        $title = $value;
        $function_name = FINANCIALOO_PREFIX . $key;
        include_page($title, $function_name);
    }
}

add_action('admin_menu', 'financialoo_add_menu');
