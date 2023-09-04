<?php
/*
Plugin Name: Financialoo
Plugin URI: https://pluginoo.com/financialoo
Description: Financialoo is a financial management tool that helps you to manage your organization finances.
Version: 1.0.0
Author: Pluginoo
Author URI: https://pluginoo.com
License: GPLv2 or later
Text Domain: financialoo
*/

// verfy first define some constants


define('FINANCIALOO_VERSION', '1.0.0');
define('FINANCIALOO_PLUGIN', __FILE__);
define('FINANCIALOO_PLUGIN_BASENAME', plugin_basename(FINANCIALOO_PLUGIN));
define('FINANCIALOO_NAME', basename(FINANCIALOO_PLUGIN_BASENAME, '.php'));
define('FINANCIALOO_PREFIX', FINANCIALOO_NAME . '_');
define('FINANCIALOO_PLUGIN_NAME', trim(dirname(FINANCIALOO_PLUGIN_BASENAME), '/'));
define('FINANCIALOO_PLUGIN_DIR', untrailingslashit(dirname(FINANCIALOO_PLUGIN)));


// first add text domain

function financialoo_load_textdomain()
{
    load_plugin_textdomain('financialoo', false, dirname(plugin_basename(__FILE__)) . '/languages');
}

add_action('plugins_loaded', 'financialoo_load_textdomain');

// activation and deactivation hooks

function financialoo_activation_calllback()
{
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();
    $prefix = $wpdb->prefix;
    # roles table
    $table_name = $prefix . FINANCIALOO_PREFIX . 'roles';
    $sql = "CREATE TABLE `$table_name` (
        `id` bigint NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `status` int DEFAULT 0,
        `created_at` datetime NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    # members table
    $table_name = $prefix . FINANCIALOO_PREFIX . 'members';
    $sql = "CREATE TABLE `$table_name` (
        `id` bigint NOT NULL AUTO_INCREMENT,
        `wp_user_id` bigint NOT NULL,
        `role_id` bigint NOT NULL,
        `status` int DEFAULT 0,
        `created_at` datetime NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    # deposite_categories table
    $table_name = $prefix . FINANCIALOO_PREFIX . 'deposite_categories';
    $sql = "CREATE TABLE `$table_name` (
        `id` bigint NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `status` int DEFAULT 0,
        `created_at` datetime NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    # deposits table
    $table_name = $prefix . FINANCIALOO_PREFIX . 'deposits';
    $sql = "CREATE TABLE `$table_name` (
        `id` bigint NOT NULL AUTO_INCREMENT,
        `member_id` bigint NOT NULL,
        `wp_user_id` bigint NOT NULL,
        `amount` decimal(10,2) NOT NULL,
        `status` int DEFAULT 0,
        `created_at` datetime NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    # withdrawals table
    $table_name = $prefix . FINANCIALOO_PREFIX . 'withdrawals';
    $sql = "CREATE TABLE `$table_name` (
        `id` bigint NOT NULL AUTO_INCREMENT,
        `member_id` bigint NOT NULL,
        `wp_user_id` bigint NOT NULL,
        `amount` decimal(10,2) NOT NULL,
        `status` int DEFAULT 0,
        `created_at` datetime NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    # transactions table
    $table_name = $prefix . FINANCIALOO_PREFIX . 'transactions';
    $sql = "CREATE TABLE `$table_name` (
        `id` bigint NOT NULL AUTO_INCREMENT,
        `member_id` bigint NOT NULL,
        `wp_user_id` bigint NOT NULL,
        `amount` decimal(10,2) NOT NULL,
        `type` varchar(255) NOT NULL,
        `status` int DEFAULT 0,
        `created_at` datetime NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    # expenses categories table

    $table_name = $prefix . FINANCIALOO_PREFIX . 'expenses_categories';
    $sql = "CREATE TABLE `$table_name` (
        `id` bigint NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `status` int DEFAULT 0,
        `created_at` datetime NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    # expenses table

    $table_name = $prefix . FINANCIALOO_PREFIX . 'expenses';
    $sql = "CREATE TABLE `$table_name` (
        `id` bigint NOT NULL AUTO_INCREMENT,
        `amount` decimal(10,2) NOT NULL,
        `category_id` bigint NOT NULL,
        `description` text NOT NULL,
        `attachment_id` varchar(255) NULL,
        `wp_user_id` bigint NOT NULL,
        `status` int DEFAULT 0,
        `created_at` datetime NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

register_activation_hook(__FILE__, 'financialoo_activation_calllback');

function financialoo_deactivation_calllback()
{
    global $wpdb;
    $prefix = $wpdb->prefix;
    $tables = [
        'roles',
        'members',
        'deposite_categories',
        'deposits',
        'withdrawals',
        'transactions',
        'expenses_categories',
        'expenses',
    ];

    foreach ($tables as $table) {
        $table_name = $prefix . FINANCIALOO_PREFIX . $table;
        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);
    }
}

register_deactivation_hook(__FILE__, 'financialoo_deactivation_calllback');
