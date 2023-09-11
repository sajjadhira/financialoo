<?php

function financialoo_get_submenus($parent_slug)
{
    global $submenu;
    $submenus = $submenu[$parent_slug];
    return $submenus;
}

function include_page($title, $function_name)
{

    $page = FINANCIALOO_PLUGIN_DIR . '/pages/' . $function_name . '.php';
    if (file_exists($page)) {
        require_once $page;
    } else {
        $get_boilerplate_file = FINANCIALOO_PLUGIN_DIR . '/boilerplates/page.html';

        if (file_exists($get_boilerplate_file)) {
            $boilerplate_content = file_get_contents($get_boilerplate_file);
            $find = ['{title}', '{function}'];
            $replace = [$title, $function_name];
            $get_new_page_content = str_replace($find, $replace, $boilerplate_content);
            # crrate file
            $file = fopen($page, 'w');
            fwrite($file, $get_new_page_content);
        }
    }
}

function get_member_info($id)
{
    global $wpdb;
    $prefix = $wpdb->prefix;
    $table_name = $prefix . FINANCIALOO_PREFIX . 'members';
    $sql = "SELECT * FROM $table_name WHERE wp_user_id = $id";
    $result = $wpdb->get_row($sql);
    return $result;
}

function financialoo_get_role_by_id($id)
{
    // get role from FINANCIALOO_PREFIX . 'roles' table according to id
    global $wpdb;
    $prefix = $wpdb->prefix;

    $get_member_info = get_member_info($id);
    $role_id = $get_member_info->role_id;
    $table_name = $prefix . FINANCIALOO_PREFIX . 'roles';
    $sql = "SELECT * FROM $table_name WHERE id = $role_id";
    $result = $wpdb->get_row($sql);
    if ($result)
        return strtolower($result->name);
}

function finacialoo_get_roles()
{
    global $wpdb;
    $prefix = $wpdb->prefix;
    $table_name = $prefix . FINANCIALOO_PREFIX . 'roles';
    $sql = "SELECT * FROM $table_name";
    $results = $wpdb->get_results($sql);
    return $results;
}

function finacialoo_get_current_role()
{
    $current_user = wp_get_current_user()->ID;
    $role = financialoo_get_role_by_id($current_user);
    return $role;
}
