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
