<?php
function financialoo_styles()
{
    // adding styles tailwindcss CDN
    wp_enqueue_style('ai-article-press-admin-styles', "//cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css");
}

add_action('admin_enqueue_scripts', "financialoo_styles");
