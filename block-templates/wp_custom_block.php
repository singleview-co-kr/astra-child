<?php
if (! defined('ABSPATH') ) {
    exit;  // Exit if accessed directly.
}

add_action('acf/init', 'hfm_acf_init_blocks');
function hfm_acf_init_blocks() {
    if (function_exists('acf_register_block_type')) {
        acf_register_block_type(
            array(
                'name'            => 'related-products',
                'title'           => '[singleview]연관 상품',
                'description'     => '',
                'render_template' => 'block-templates/related_products.php',
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
    // register_block_type(__DIR__ . '/block-templates/procedure_block.json');
}
