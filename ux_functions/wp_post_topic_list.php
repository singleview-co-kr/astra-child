<?php
if (! defined('ABSPATH') ) {
    exit;  // Exit if accessed directly.
}

function sv_get_post_topic_shortcode() {
    if (!wp_script_is('sv-category-js', 'enqueued')) {
        wp_enqueue_script('sv-category-js', get_stylesheet_directory_uri() . '/assets/js/post_topic.js', array(), null, true);
    }
    global $n_theme_setup_page_id;  // set on functions.php
    ob_start();
    for( $n_layer = 1; $n_layer < 5; $n_layer++ ) {
        $s_acf_var_name = 'topic_list_layer_' . $n_layer;
        $a_topic_list = get_field( $s_acf_var_name, $n_theme_setup_page_id );
        if ($a_topic_list) : ?>
            <div class="fw-blog-category blog-category-<?php echo $n_layer ?>">
                <div class="category-tabs">
                    <div class="category-tabs-wrap">
                        <?php foreach ($a_topic_list as $n_topic_category_id) :
                            // 각 부모 카테고리에 대한 정보를 가져옵니다.
                            $o_topic_category_info = get_category($n_topic_category_id);
// var_dump($o_topic_category_info);                            
                            if ($o_topic_category_info) : ?>
                                <button class="tab" data-categoryid="<?php echo $n_topic_category_id ?>"><?php echo $o_topic_category_info->name ?></button>
                            <?php endif;
                        endforeach;
                        unset($o_topic_category_info);
                        ?>
                    </div>
                </div>
                <div class="category-content">
                    <div class="blogCategorySwiper"></div>
                    <div class="loading"><lottie-player src="https://lottie.host/d9e36223-6d1e-4b68-82e1-29bd9e817b9f/FjXzm2rXWa.json" background="##ffffff" speed="1" style="width: 120px; height: 120px" loop autoplay direction="1" mode="normal"></lottie-player></div>
                </div>
            </div>
        <?php endif;
        unset($a_topic_list);
    }
    return ob_get_clean();
}
add_shortcode('sv_get_post_topic', 'sv_get_post_topic_shortcode');

function load_subcategories() {
    $parent_cat_id = $_POST['parent_cat_id'];
    $args = array(
        'child_of' => $parent_cat_id,
        'hide_empty' => false,
    );
    $subcategories = get_categories($args);

    echo '<ul class="swiper-wrapper">';

    foreach ($subcategories as $category) {
        $category_link = get_category_link($category->term_id);
        $thumbnail = get_field('post_topic_thumbnail', 'category_' . $category->term_id);
        echo '<li class="swiper-slide">';
        echo '<div class="subcategory">';
        if (!empty($thumbnail)) {
            echo '<div class="thumbnail"><a href="' . esc_url($category_link) . '"><img src="' . esc_url($thumbnail['sizes']['large']) . '" alt="' . esc_attr($category->name) . '"></a></div>';
        }
        echo '<div class="text-wrap">';
        echo '<h3><a href="' . esc_url($category_link) . '">' . esc_html($category->name) . '</a></h3>';
        echo '</div>';
        echo '</div>';
        echo '</li>';
    }
    echo '</ul>';
    wp_die();
}
add_action('wp_ajax_load_subcategories', 'load_subcategories');
add_action('wp_ajax_nopriv_load_subcategories', 'load_subcategories');
