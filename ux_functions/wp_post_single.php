<?php
if (! defined('ABSPATH') ) {
    exit;  // Exit if accessed directly.
}

// https://themeim.com/how-to-post-view-count-wordpress-without-plugin/
function get_wp_post_views()
{
    $n_post_id = get_the_ID();
    $s_count_key = 'post_views_count';
    $s_view_count = get_post_meta($n_post_id, $s_count_key, true);
    $n_view_count = intval($s_view_count);
    if($n_view_count == 0) {
        delete_post_meta($n_post_id, $s_count_key);
        add_post_meta($n_post_id, $s_count_key, '0');
        $s_view_count = '0';
    }
    $s_view = $n_view_count > 1 ? 'Views' : 'View';
    return $s_view_count.' '.$s_view;
} 

function set_wp_post_views()
{
    $n_post_id = get_the_ID();
    $s_count_key = 'post_views_count';
    $s_view_count = get_post_meta($n_post_id, $s_count_key, true);
    if($s_view_count=='') {
        $s_view_count = 0;
        delete_post_meta($n_post_id, $s_count_key);
        add_post_meta($n_post_id, $s_count_key, '0');
    }
    else {
        $s_view_count++;
        update_post_meta($n_post_id, $s_count_key, $s_view_count);
    } 
}
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
add_shortcode('post-views', 'get_wp_post_views');


// function tt3child_register_acf_blocks()
// {
//     /**
//      * We register our block's with WordPress's handy
//      * register_block_type();
//      *
//      * @link https://developer.wordpress.org/reference/functions/register_block_type/
//      */
//     register_block_type(__DIR__ . '/blocks/promotion');
// }
// // Here we call our tt3child_register_acf_block() function on init.
// add_action('init', 'tt3child_register_acf_blocks');
