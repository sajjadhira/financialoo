<?php


# add menu

function financialoo_add_menu()
{
    add_menu_page(
        __(ucfirst(FINANCIALOO_NAME), FINANCIALOO_NAME),
        __(ucfirst(FINANCIALOO_NAME), FINANCIALOO_NAME),
        'manage_options',
        FINANCIALOO_NAME,
        FINANCIALOO_PREFIX . 'menu_page',
        'dashicons-chart-area'
    );

    # add submenu
    # lets make an array of submenus
    $submenus = [
        'rols' => 'Roles',
        'members' => 'Members',
        'deposit_categories' => 'Deposit Categories',
        'deposits' => 'Deposits',
        'expense_categories' => 'Expense Categories',
        'expenses' => 'Expenses',
        'withdrawals' => 'Withdrawals',
        'transactions' => 'Transactions',
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
    }
}

add_action('admin_menu', 'financialoo_add_menu');
