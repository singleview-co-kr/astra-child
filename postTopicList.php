<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;  // Exit if accessed directly.
}

function fw_get_blog_category_shortcode()
{
    if (!wp_script_is('fw-category-js', 'enqueued')) {
        wp_enqueue_script('fw-category-js', get_stylesheet_directory_uri() . '/assets/js/category.js', array(), null, true);
    }

    $parent_cats = get_field('category', 326);
    $parent_cats_2 = get_field('category_2', 326);
    ob_start();
    if ($parent_cats) :

?>
        <div class="fw-blog-category blog-category-1">
            <div class="category-tabs">
                <div class="category-tabs-wrap">

                    <?php foreach ($parent_cats as $parent_cat) :
                        // 각 부모 카테고리에 대한 정보를 가져옵니다.
                        $parent_cat_info = get_category($parent_cat);
                        if ($parent_cat_info) :
                    ?>
                            <button class="tab" data-categoryid="<?= $parent_cat ?>"><?= $parent_cat_info->name ?></button>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </div>
            </div>
            <div class="category-content">
                <div class="blogCategorySwiper">
                </div>
                <div class="loading"><lottie-player src="https://lottie.host/d9e36223-6d1e-4b68-82e1-29bd9e817b9f/FjXzm2rXWa.json" background="##ffffff" speed="1" style="width: 120px; height: 120px" loop autoplay direction="1" mode="normal"></lottie-player></div>
            </div>
        </div>
    <?php
    endif;
    if ($parent_cats_2) :

    ?>
        <div class="fw-blog-category blog-category-2">
            <div class="category-tabs">
                <div class="category-tabs-wrap">
                    <?php foreach ($parent_cats_2 as $parent_cat) :
                        // 각 부모 카테고리에 대한 정보를 가져옵니다.
                        $parent_cat_info = get_category($parent_cat);
                        if ($parent_cat_info) :
                    ?>
                            <button class="tab" data-categoryid="<?= $parent_cat ?>"><?= $parent_cat_info->name ?></button>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </div>
            </div>
            <div class="category-content">
                <div class="blogCategorySwiper">
                </div>
                <div class="loading"><lottie-player src="https://lottie.host/d9e36223-6d1e-4b68-82e1-29bd9e817b9f/FjXzm2rXWa.json" background="##ffffff" speed="1" style="width: 120px; height: 120px" loop autoplay direction="1" mode="normal"></lottie-player></div>
            </div>
        </div>
<?php
    endif;
    return ob_get_clean();
}
add_shortcode('fw_get_blog_category', 'fw_get_blog_category_shortcode');

function load_subcategories()
{
    $parent_cat_id = $_POST['parent_cat_id'];

    $args = array(
        'child_of' => $parent_cat_id,
        'hide_empty' => false,
    );
    $subcategories = get_categories($args);

    echo '<ul class="swiper-wrapper">';

    foreach ($subcategories as $category) {
        $category_link = get_category_link($category->term_id);
        $thumbnail = get_field('category_thumbnail', 'category_' . $category->term_id);
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
