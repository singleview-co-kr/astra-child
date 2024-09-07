<?php
if (! defined('ABSPATH') ) {
    exit;  // Exit if accessed directly.
}

// append customize menu onto theme setup
function prefix_customize_register( $wp_customize ) {
    // https://stackoverflow.com/questions/76419032/how-to-add-page-selection-field-to-wordpress-theme-customizer
    // https://stackoverflow.com/questions/47803067/customize-php-in-wordpress

    $wp_customize->add_section( 'extra_setup' , array(
        'title'    => __( 'Extra setup', 'textdomain' ),
        'priority' => 30,
    ) );

    // Add setting and control.
    $wp_customize->add_setting( 'extra_setup_page', array(
        'default'           => '',
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',  // retrieval by get_theme_mod( 'extra_setup_page' )
        'sanitize_callback' => 'absint'
    ) );

    $wp_customize->add_control( 'extra_setup_page', array(
        'label'   => __( 'Select Extra Setup Page', 'textdomain' ),
        'section' => 'extra_setup',
        'type'    => 'dropdown-pages'
    ) );
}
add_action( 'customize_register', 'prefix_customize_register' );

// get extra setup page id from theme custom value
// $n_theme_setup_page_id has been set on functions.php
$o_theme_setup_post_info = get_post( $n_theme_setup_page_id );
$s_special_admin_page_title = $o_theme_setup_post_info->post_title;
$s_special_admin_page_slug = urldecode( $o_theme_setup_post_info->post_name );
unset( $o_theme_setup_post_info );

// 관리자 -> [페이지] 메뉴에서 개별 페이지를 숨김
function hide_settings_page($query)
{
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    global $typenow;
    global $s_special_admin_page_slug;
    if ($typenow === "page") {
        $n_settings_page_id = get_page_by_path($s_special_admin_page_slug, null, "page")->ID;
        // 쿼리에서 이 페이지들을 제외합니다.
		$query->set('post__not_in', array($n_settings_page_id));
    }
    return;
}
add_action('pre_get_posts', 'hide_settings_page');

// Add the theme setup page to admin global menu
function add_theme_setup_page_to_menu()
{
    global $s_special_admin_page_title;
    global $s_special_admin_page_slug;
    if(get_page_by_path($s_special_admin_page_slug, null, "page")) {
        add_menu_page($s_special_admin_page_title, $s_special_admin_page_title, 'manage_options', 'post.php?post=' . get_page_by_path($s_special_admin_page_slug, null, "page")->ID . '&action=edit', '', 'dashicons-admin-tools', 20);
    }
}
add_action('admin_menu', 'add_theme_setup_page_to_menu');

// Change the active menu item
function higlight_theme_setup_page($file)
{
    global $pagenow;
    global $s_special_admin_page_slug;
    
    $n_settings_page_id = null;
    if(get_page_by_path($s_special_admin_page_slug, null, "page")) {
        $n_settings_page_id = get_page_by_path($s_special_admin_page_slug, null, "page")->ID;
    }
    
    if(isset($_GET["post"]) ) {
        $post = (int)$_GET["post"];
		if ($pagenow === "post.php" && ($post === $n_settings_page_id)) {
            $file = "post.php?post=$post&action=edit";
        }
        return $file;
    }
    return $file;
}
add_filter('parent_file', 'higlight_theme_setup_page');
