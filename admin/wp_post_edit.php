<?php
if (! defined('ABSPATH') ) {
    exit;  // Exit if accessed directly.
}

/**
 * Let sidebar of post editing UX resizable
 *
 * @return 
 */
function toast_rs_enqueue()
{
    wp_enqueue_script('jquery-ui-resizable');
    wp_enqueue_script('toast_rs_script', get_stylesheet_directory_uri() . '/assets/vendor/resizable-editor-sidebar/script.js', array('jquery-ui-resizable'), null, true);
    wp_enqueue_style('toast_rs_style',  get_stylesheet_directory_uri() . '/assets/vendor/resizable-editor-sidebar/style.css');
}
add_action('admin_enqueue_scripts', 'toast_rs_enqueue', 20);
