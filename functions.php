<?php
/**
 * Astra Child Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astra Child
 * @since 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;  // Exit if accessed directly.
}

/**
 * Define Constants
 */
define('YCX_ASTRA_CHILD_VERSION', '1.0.0');


// add_action('wp','custom_error_pages');
// function custom_error_pages()
// {
//     global $wp_query;
 
//     if(isset($_REQUEST['status']) && $_REQUEST['status'] == 403)
//     {
//         $wp_query->is_404 = FALSE;
//         $wp_query->is_page = TRUE;
//         $wp_query->is_singular = TRUE;
//         $wp_query->is_single = FALSE;
//         $wp_query->is_home = FALSE;
//         $wp_query->is_archive = FALSE;
//         $wp_query->is_category = FALSE;
//         add_filter('wp_title','custom_error_title',65000,2);
//         add_filter('body_class','custom_error_class');
//         status_header(403);
//         get_template_part('403');
//         exit;
//     }

//     if(isset($_REQUEST['status']) && $_REQUEST['status'] == 401)
//     {
//         $wp_query->is_404 = FALSE;
//         $wp_query->is_page = TRUE;
//         $wp_query->is_singular = TRUE;
//         $wp_query->is_single = FALSE;
//         $wp_query->is_home = FALSE;
//         $wp_query->is_archive = FALSE;
//         $wp_query->is_category = FALSE;
//         add_filter('wp_title','custom_error_title',65000,2);
//         add_filter('body_class','custom_error_class');
//         status_header(401);
//         get_template_part('401');
//         exit;
//     }
// }
 
// function custom_error_title($title='',$sep='')
// {
//     if(isset($_REQUEST['status']) && $_REQUEST['status'] == 403)
//         return "Forbidden ".$sep." ".get_bloginfo('name');
 
//     if(isset($_REQUEST['status']) && $_REQUEST['status'] == 401)
//         return "Unauthorized ".$sep." ".get_bloginfo('name');
// }
 
// function custom_error_class($classes)
// {
//     if(isset($_REQUEST['status']) && $_REQUEST['status'] == 403)
//     {
//         $classes[]="error403";
//         return $classes;
//     }
 
//     if(isset($_REQUEST['status']) && $_REQUEST['status'] == 401)
//     {
//         $classes[]="error401";
//         return $classes;
//     }
// }

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
    wp_enqueue_script('swiper-js', 'https://fastly.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js');
    wp_enqueue_script('dotlottie-js', 'https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js', array(), YCX_ASTRA_CHILD_VERSION, true);
    wp_enqueue_script('bootstrap-js', get_stylesheet_directory_uri() . '/assets/vendor/bootstrap/bootstrap.bundle.min.js');

    // 불필요한 자원 호출일 가능성 높음
    wp_enqueue_script('index-js', get_stylesheet_directory_uri() . '/assets/js/index.js', array(), YCX_ASTRA_CHILD_VERSION, true);
}

add_action('wp_enqueue_scripts', 'child_enqueue_styles', 15);

function wpdocs_theme_name_scripts()
{
    wp_enqueue_script('ajax-js', get_stylesheet_directory_uri() . '/assets/js/ajaxfile.js', array(), '1.0.0', true);
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


include "main_functions.php";
include "productFuntions.php";
include "postTopicList.php";
include "admin/wpPostSelectCategory.php";
include "wpCustomBlocks.php";
include "search.global.php";
// include "postSettings.php";  // 불필요한 자원 호출일 가능성 높음

function custom_products_per_page($query) {
    if (!is_admin() && is_post_type_archive('product') && $query->is_main_query()) { // 상품 아카이브 페이지에만 적용
        $query->set('posts_per_page', 8);
    }
    return $query;
}
add_filter('pre_get_posts', 'custom_products_per_page');

/**
 * Let sidebar of post editing UX resizable
 *
 * @return 
 */
function toast_rs_enqueue(){
    wp_enqueue_script( 'jquery-ui-resizable');
    wp_enqueue_script( 'toast_rs_script', get_stylesheet_directory_uri() . '/assets/vendor/resizable-editor-sidebar/script.js', array('jquery-ui-resizable'), null, true);
    wp_enqueue_style( 'toast_rs_style',  get_stylesheet_directory_uri() . '/assets/vendor/resizable-editor-sidebar/style.css');
}
add_action('admin_enqueue_scripts', 'toast_rs_enqueue', 20);
