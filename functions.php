<?php

/**
 * Astra Child Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astra Child
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define('CHILD_THEME_ASTRA_CHILD_VERSION', '1.0.0');

/**
 * Enqueue styles
 */
function child_enqueue_styles()
{

    wp_enqueue_style('bootstrap-css', get_stylesheet_directory_uri() . '/assets/vendor/bootstrap/bootstrap.min.css');
    wp_enqueue_style('astra-child-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), CHILD_THEME_ASTRA_CHILD_VERSION, 'all');
    wp_enqueue_style('astra-child-theme-sub-css', get_stylesheet_directory_uri() . '/assets/css/substyle.css', array('astra-theme-css'), CHILD_THEME_ASTRA_CHILD_VERSION, 'all');

    wp_enqueue_script('jquery');

    wp_enqueue_style('font-awesome-style', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css');
    wp_enqueue_style('swiper-style', 'https://fastly.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css');

    wp_enqueue_script('index-js', get_stylesheet_directory_uri() . '/assets/js/index.js', array(), CHILD_THEME_ASTRA_CHILD_VERSION, true);
    wp_enqueue_script('swiper-js', 'https://fastly.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js');
    wp_enqueue_script('dotlottie-js', 'https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js', array(), CHILD_THEME_ASTRA_CHILD_VERSION, true);
    wp_enqueue_script('bootstrap-js', get_stylesheet_directory_uri() . '/assets/vendor/bootstrap/bootstrap.bundle.min.js');
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

/* add_action('wp_loaded', 'custom_redirect_all_access_including_admin');
function custom_redirect_all_access_including_admin()
{
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
}
 */

/* admin bar 숨김 */
add_action('init', 'remove_admin_bar');
function remove_admin_bar()
{
    if ((!current_user_can('administrator') && !is_admin()) || wp_is_mobile()) {
        show_admin_bar(false);
    }
}

/* 디버깅 */
function vd($anything)
{
    echo '<pre>';
    var_dump($anything);
    echo '</pre>';
    die();
}

function pr($anything)
{
    /* if (current_user_can('administrator')) {
        echo '<pre>';
        print_r($anything);
        echo '</pre>';
    } */

    echo '<pre>';
    print_r($anything);
    echo '</pre>';
    // die();
}

include "main_functions.php";
include "productFuntions.php";
include "postTopicList.php";
// include "postSettings.php";
include "admin/wpPostSelectCategory.php";
include "wpCustomBlocks.php";
include "totalSearch.php";


function custom_products_per_page($query)
{
    if (!is_admin() && is_post_type_archive('product') && $query->is_main_query()) { // 상품 아카이브 페이지에만 적용
        $query->set('posts_per_page', 8);
    }
    return $query;
}
add_filter('pre_get_posts', 'custom_products_per_page');


function toast_enqueue_jquery_ui(){
	wp_enqueue_script( 'jquery-ui-resizable');
}
add_action('admin_enqueue_scripts', 'toast_enqueue_jquery_ui');

function toast_resizable_sidebar(){ ?>
	<style>
		.interface-interface-skeleton__sidebar .interface-complementary-area{width:100%;}
		.edit-post-layout:not(.is-sidebar-opened) .interface-interface-skeleton__sidebar{display:none;}
		.is-sidebar-opened .interface-interface-skeleton__sidebar{width:350px;}

		/*UI Styles*/
		.ui-dialog .ui-resizable-n {
			height: 2px;
			top: 0;
		}
		.ui-dialog .ui-resizable-e {
			width: 2px;
			right: 0;
		}
		.ui-dialog .ui-resizable-s {
			height: 2px;
			bottom: 0;
		}
		.ui-dialog .ui-resizable-w {
			width: 2px;
			left: 0;
		}
		.ui-dialog .ui-resizable-se,
		.ui-dialog .ui-resizable-sw,
		.ui-dialog .ui-resizable-ne,
		.ui-dialog .ui-resizable-nw {
			width: 7px;
			height: 7px;
		}
		.ui-dialog .ui-resizable-se {
			right: 0;
			bottom: 0;
		}
		.ui-dialog .ui-resizable-sw {
			left: 0;
			bottom: 0;
		}
		.ui-dialog .ui-resizable-ne {
			right: 0;
			top: 0;
		}
		.ui-dialog .ui-resizable-nw {
			left: 0;
			top: 0;
		}
		.ui-draggable .ui-dialog-titlebar {
			cursor: move;
		}
		.ui-draggable-handle {
			-ms-touch-action: none;
			touch-action: none;
		}
		.ui-resizable {
			position: relative;
		}
		.ui-resizable-handle {
			position: absolute;
			font-size: 0.1px;
			display: block;
			-ms-touch-action: none;
			touch-action: none;
		}
		.ui-resizable-disabled .ui-resizable-handle,
		.ui-resizable-autohide .ui-resizable-handle {
			display: none;
		}
		.ui-resizable-n {
			cursor: n-resize;
			height: 7px;
			width: 100%;
			top: -5px;
			left: 0;
		}
		.ui-resizable-s {
			cursor: s-resize;
			height: 7px;
			width: 100%;
			bottom: -5px;
			left: 0;
		}
		.ui-resizable-e {
			cursor: e-resize;
			width: 7px;
			right: -5px;
			top: 0;
			height: 100%;
		}
		.ui-resizable-w {
			cursor: w-resize;
			width: 7px;
			left: -5px;
			top: 0;
			height: 100%;
		}
		.ui-resizable-se {
			cursor: se-resize;
			width: 12px;
			height: 12px;
			right: 1px;
			bottom: 1px;
		}
		.ui-resizable-sw {
			cursor: sw-resize;
			width: 9px;
			height: 9px;
			left: -5px;
			bottom: -5px;
		}
		.ui-resizable-nw {
			cursor: nw-resize;
			width: 9px;
			height: 9px;
			left: -5px;
			top: -5px;
		}
		.ui-resizable-ne {
			cursor: ne-resize;
			width: 9px;
			height: 9px;
			right: -5px;
			top: -5px;
		}
	</style>

	<script>
		jQuery(window).ready(function(){
    		setTimeout(function(){
        		jQuery('.interface-interface-skeleton__sidebar').width(localStorage.getItem('toast_sidebar_width'))
        		jQuery('.interface-interface-skeleton__sidebar').resizable({
            		handles: 'w',
            		resize: function(event, ui) {
                		jQuery(this).css({'left': 0});
                		localStorage.setItem('toast_sidebar_width', jQuery(this).width());
           				}
        		});
    		}, 500)
		});
	</script>
<?php }
add_action('admin_head', 'toast_resizable_sidebar');