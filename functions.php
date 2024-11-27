<?php
/**
 * Astra Child Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astra Child
 * @since   1.0.0
 */
if (! defined('ABSPATH') ) {
    exit;  // Exit if accessed directly.
}

/**
 * Define Constants
 */
define('YCX_ASTRA_CHILD_VERSION', '1.0.0');

/* add_action('wp_loaded', 'custom_redirect_all_access_including_admin');
function custom_redirect_all_access_including_admin() {
    $allowed_ips = array('211.202.91.71'); // 허용할 IP 주소를 여기에 입력하세요.
    $redirect_page_path = '/404/'; // 리디렉션할 페이지의 경로를 여기에 입력하세요.
    $redirect_page_url = home_url() . $redirect_page_path; // 전체 URL 생성

    // 현재 페이지가 리디렉션 대상 페이지인지 확인
    if ($_SERVER['REQUEST_URI'] == $redirect_page_path) {
        return; // 현재 페이지가 리디렉션 대상 페이지이면 리디렉션 수행하지 않음
    }

    // 현재 접근 IP가 허용 목록에 없으면 리디렉션
    if (!in_array($_SERVER['REMOTE_ADDR'], $allowed_ips)) {
        wp_redirect($redirect_page_url);
        exit;
    }
}*/

// 모양 -> 사용자 정의 -> Extra setup
// get [템플릿 변수 설정] page id and set globally
$n_theme_setup_page_id = get_theme_mod( 'extra_setup_page' );

/**
 * Enqueue styles
 */
function child_enqueue_styles()
{
    wp_enqueue_style('bootstrap', get_stylesheet_directory_uri() . '/assets/vendor/bootstrap/bootstrap.min.css');
    wp_enqueue_style('astra-child-theme', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), YCX_ASTRA_CHILD_VERSION, 'all');
    wp_enqueue_style('astra-child-theme-sub', get_stylesheet_directory_uri() . '/assets/css/substyle.css', array('astra-theme-css'), YCX_ASTRA_CHILD_VERSION, 'all');
    wp_enqueue_style('astra-child-theme-global-search-notice', get_stylesheet_directory_uri() . '/assets/css/global_search_notice.css', array('astra-theme-css'), YCX_ASTRA_CHILD_VERSION, 'all');
    wp_enqueue_style('font-awesome-style', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css');
    wp_enqueue_style('swiper-style', 'https://fastly.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css');

    wp_enqueue_script('jquery');
    wp_enqueue_script('swiper', 'https://fastly.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js');
    wp_enqueue_script('dotlottie', 'https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js', array(), YCX_ASTRA_CHILD_VERSION, true);
    wp_enqueue_script('bootstrap', get_stylesheet_directory_uri() . '/assets/vendor/bootstrap/bootstrap.bundle.min.js');
    wp_enqueue_script('global_search', get_stylesheet_directory_uri() . '/assets/js/global_search.js', array(), YCX_ASTRA_CHILD_VERSION, true);
}

add_action('wp_enqueue_scripts', 'child_enqueue_styles', 15);

function wpdocs_theme_name_scripts()
{
    wp_enqueue_script('ajax-js', get_stylesheet_directory_uri() . '/assets/js/ajaxfile.js', array(), YCX_ASTRA_CHILD_VERSION, true);
    wp_localize_script(
        'ajax-js',
        'ajax_var',
        array(
            'url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ajax-nonce')
        )
    );
}
add_action('wp_enqueue_scripts', 'wpdocs_theme_name_scripts');

/* admin bar 숨김 */
add_action('init', 'remove_admin_bar');
function remove_admin_bar()
{
    if ((!current_user_can('administrator') && !is_admin()) || wp_is_mobile()) {
        show_admin_bar(false);
    }
}

require "admin/admin_menu.php";
require "admin/wp_post_edit.php";
require "block-templates/wp_custom_block.php";
require "cache/CacheFileDisk.class.php";
require "ux_functions/main_screen.php";
require "ux_functions/search_global.php";
require "ux_functions/wc_product.php";
require "ux_functions/wp_post_topic_list.php";
require "ux_functions/wp_post_single.php";

function custom_products_per_page($query)
{
    if (!is_admin() && is_post_type_archive('product') && $query->is_main_query()) { // 상품 아카이브 페이지에만 적용
        $query->set('posts_per_page', 8);
    }
    return $query;
}
add_filter('pre_get_posts', 'custom_products_per_page');
