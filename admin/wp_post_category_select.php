<?php
if (! defined('ABSPATH') ) {
    exit;  // Exit if accessed directly.
}

// edit 화면을 표시할 때 
// 326 - ㄳㄷ ㅁㅈㄹ의 [Topic 카테고리 선택] -> [Post Topic 설정], 1061으로 변경
// 453 - ㄳㄷ ㅁㅈㄹ의 [케이보드 추천 상품 설정]
// 729 - ㄳㄷ ㅁㅈㄹ의 [메인 블로그태그 설정]
function hide_editors_on_page_edit($use_block_editor, $post)
{	
    if ($post) {
		$a_page_to_hide_editor = array();
		$a_page_to_hide_editor[] = get_page_by_path("post-topic-설정", null, "page")->ID;
		
        // if ($post->ID === 326 || $post->ID === 453 || $post->ID === 729) {
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

// 관리자 -> [페이지] 메뉴에서 개별 페이지를 숨김
function hide_settings_page($query)
{
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    global $typenow;
    if ($typenow === "page") {
        // 두 페이지의 ID를 가져옵니다.
        $settings_page_id1 = get_page_by_path("post-topic-설정", null, "page")->ID;
        // $settings_page_id3 = get_page_by_path("메인-블로그태그-설정", null, "page")->ID;

        // 쿼리에서 이 페이지들을 제외합니다.
        // $query->set('post__not_in', array($settings_page_id1, $settings_page_id3));
		$query->set('post__not_in', array($settings_page_id1));
    }
    return;
}

add_action('pre_get_posts', 'hide_settings_page');

// Add the page to admin global menu
function add_site_settings_to_menu()
{
    if(get_page_by_path("post-topic-설정", null, "page")) {
        add_menu_page('Post Topic 설정', 'Post Topic 설정', 'manage_options', 'post.php?post=' . get_page_by_path("post-topic-설정", null, "page")->ID . '&action=edit', '', 'dashicons-admin-tools', 20);
    }
    //if(get_page_by_path("메인-블로그태그-설정", null, "page")) {
    //    add_menu_page('메인 블로그태그 설정', '메인 블로그태그 설정', 'manage_options', 'post.php?post=' . get_page_by_path("메인-블로그태그-설정", null, "page")->ID . '&action=edit', '', 'dashicons-admin-tools', 20);
    //}
}
add_action('admin_menu', 'add_site_settings_to_menu');

// Change the active menu item
function higlight_custom_settings_page($file)
{
    global $pagenow;
    
    // "topic-카테고리-선택" 및 "케이보드-추천-상품-설정" 페이지의 ID를 얻습니다.
    $settings_page_id1 = null;
    // $settings_page_id3 = null;
    if(get_page_by_path("post-topic-설정", null, "page")) {
        $settings_page_id1 = get_page_by_path("post-topic-설정", null, "page")->ID;
    }
    // if(get_page_by_path("메인-블로그태그-설정", null, "page")) {
    //    $settings_page_id3 = get_page_by_path("메인-블로그태그-설정", null, "page")->ID;
    // }
    
    if(isset($_GET["post"]) ) {
        $post = (int)$_GET["post"];
        // if ($pagenow === "post.php" && ($post === $settings_page_id1 || $post === $settings_page_id3)) {
		if ($pagenow === "post.php" && ($post === $settings_page_id1)) {
            $file = "post.php?post=$post&action=edit";
        }
        return $file;
    }
    return $file;
}
add_filter('parent_file', 'higlight_custom_settings_page');
