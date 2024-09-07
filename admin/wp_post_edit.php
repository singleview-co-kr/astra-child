<?php
if (! defined('ABSPATH') ) {
    exit;  // Exit if accessed directly.
}

// 테마 설정 페이지라면, post edit 화면을 표시할 때, 블록에디터를 숨김
function hide_editors_on_page_edit($use_block_editor, $post)
{
    global $s_special_admin_page_slug;
    if ($post) {
		$a_page_to_hide_editor = array();
		$a_page_to_hide_editor[] = get_page_by_path($s_special_admin_page_slug, null, "page")->ID;
		if ( in_array($post->ID, $a_page_to_hide_editor) ) {
			// hide elementor and gutenberg block editor
            echo '<style>
                #elementor-switch-mode{
                    display: none;    
                }
                body.elementor-editor-active #elementor-switch-mode-button{
					visibility: hidden;
                }
                body.elementor-editor-active #elementor-switch-mode-button:hover{
					visibility: hidden;
                }
				/* hide gutenberg block editor */
				#postdivrich{
                    display: none;    
                }
            </style>';
			unset( $a_page_to_hide_editor );
            return false;
        }
		unset( $a_page_to_hide_editor );
    }
    return $use_block_editor;
}
add_filter('use_block_editor_for_post', 'hide_editors_on_page_edit', 10, 2);

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
