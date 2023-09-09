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

function financialoo_get_role_by_id($id)
{
    // get role from FINANCIALOO_PREFIX . 'roles' table according to id
    global $wpdb;
    $prefix = $wpdb->prefix;
    $table_name = $prefix . FINANCIALOO_PREFIX . 'roles';
    $sql = "SELECT * FROM $table_name WHERE id = $id";
    $result = $wpdb->get_row($sql);
    if ($result)
        return $result->name;
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
