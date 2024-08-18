<?php
if (! defined('ABSPATH') ) {
    exit;  // Exit if accessed directly.
}

add_action('acf/init', 'hfm_acf_init_blocks');
function hfm_acf_init_blocks()
{
    if (function_exists('acf_register_block_type')) {
        acf_register_block_type(
            array(
                'name'            => 'related-products',
                'title'           => '[singleview]연관 상품',
                'description'     => '',
                'render_template' => 'block-templates/relatedProducts.php',
                'category'        => 'widgets',
                'icon'            => 'admin-comments',
                'api_version'     => 2,
                'keywords'        => array('Related Products'),
                'mode'            => 'preview',
                'supports'            => array(
                    'mode' => false,
                ),
            )
        );
        acf_register_block_type(
            array(
                'name'            => 'materials',
                'title'           => '[singleview]준비물',
                'description'     => '',
                'render_template' => 'block-templates/materials.php',
                'category'        => 'widgets',
                'icon'            => 'admin-comments',
                'api_version'     => 2,
                'keywords'        => array('materials'),
                'mode'            => 'preview',
                'supports'            => array(
                    'mode' => false,
                ),
            )
        );
        acf_register_block_type(
            array(
                'name'            => 'procedure',
                'title'           => '[singleview]과정',
                'description'     => '',
                'category'        => 'widgets',
                'icon'            => 'admin-comments',
                'api_version'     => 2,
                'keywords'        => array('procedure'),
                'render_template' => 'block-templates/procedure.php',
                'mode'            => 'preview',
                'supports'            => array(
                    'mode' => false,
                ),
            )
        );
        acf_register_block_type(
            array(
                'name'            => 'discussion',
                'title'           => '[singleview]연관 Q&A',
                'description'     => '',
                'category'        => 'widgets',
                'icon'            => 'admin-comments',
                'api_version'     => 2,
                'keywords'        => array('discussion'),
                'render_template' => 'block-templates/discussion.php',
                'mode'            => 'preview',
                'supports'            => array(
                    'mode' => false,
                ),
            )
        );
    }
    // register_block_type(__DIR__ . '/blocks/procedure_block.json');
}

function custom_qna_shortcode()
{

    $board_id = 4; // 새로 만든 게시판의 ID값으로 수정해주세요.
    $iframe_id = uniqid();

    $url = new KBUrl();
    $_SESSION['kboard_board_id'] = $board_id;

    return '<iframe id="kboard-iframe-' . $iframe_id . '" class="kboard-iframe kboard-iframe-' . $board_id . '" src="' . $url->set('kboard_id', $board_id)->set('iframe_id', $iframe_id)->toString() . '" style="width:100%" scrolling="no" frameborder="0"></iframe>';
}
add_shortcode('custom_qna', 'custom_qna_shortcode');
