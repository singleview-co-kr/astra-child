<?php

add_shortcode('wv_prod_price', 'wv_prod_price_shortcode');
function wv_prod_price_shortcode()
{
    global $product;

    $product_id = get_the_ID();
    $product = wc_get_product($product_id);

    $volume = get_field('volume');
    $weight = $product->get_weight();
    $link_setting = get_field('link_setting');

    $attributes = $product->get_attributes();
    if($attributes['pa_incense']){
        $incense_attribute = $attributes['pa_incense'];
        if ($incense_attribute->is_taxonomy()) {
            $values = wc_get_product_terms($product_id, $incense_attribute->get_name(), array('fields' => 'names'));
            $incense = join(', ', $values);
        } else {
            $incense = $incense_attribute->get_options();
        }
    }

    ob_start();
?>

    <div id="prod_detail" class="<?= wc_price($product->get_sale_price()) != '' ? 'on-sale' : '' ?>">
        <p class="price"><span class="tit">판매가</span><span class="description"><?= wc_price($product->get_regular_price()) ?></span></p>
        <p class="sale-price"><span class="tit">할인가</span><span class="description"><?= wc_price($product->get_sale_price()) ?></span></p>
        <?php if ($incense) : ?><p class=""><span class="tit">향</span><span class="description"><?= esc_html($incense) ?></span></p><?php endif ?>
        <?php if ($volume) : ?><p class=""><span class="tit">용량</span><span class="description"><?= $volume ?></span></p><?php endif ?>
        <?php if ($weight) : ?><p class=""><span class="tit">무게</span><span class="description"><?= $weight ?>kg</span></p><?php endif ?>
    </div>

    <?php if (!empty($link_setting)) : ?>
        <div id="prod_link">
            <?php foreach ($link_setting as $link) : ?>
                <a href="<?= $link['url'] ?>" target="_blank"><?= $link['title'] ?></a>
            <?php endforeach ?>
        </div>
    <?php endif ?>

<?php
    return ob_get_clean();
}

add_shortcode('wv_prod_related_product', 'wv_prod_related_product_shortcode');
function wv_prod_related_product_shortcode()
{
    $products = get_field('recommend_product');

    ob_start();
?>

    <?php if (!empty($products)) : ?>
        <div class="related-product">
            <div class="productSwiper">
                <div class="swiper-wrapper">
                    <?php foreach ($products as $item) : ?>
                        <div class="swiper-slide">
                            <a href="<?php echo get_permalink($item->ID); ?>">
                                <div class="thumbnail">
                                    <?php echo get_the_post_thumbnail($item->ID, 'medium'); ?>
                                </div>
                                <p class="title"><?php echo get_the_title($item->ID); ?></p>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <script>
            const productSwiper = new Swiper('.productSwiper', {
                slidesPerView: 1.4,
                spaceBetween: 30,
                breakpoints: {
                    768: {
                        slidesPerView: 4,
                        spaceBetween: 40,
                    }
                },
            });
        </script>
    <?php endif ?>
<?php
    return ob_get_clean();
}

add_shortcode('wv_prod_related_posts', 'wv_prod_related_posts_shortcode');
function wv_prod_related_posts_shortcode()
{
    $posts = get_field('related_post');

    ob_start();
?>

    <?php if (!empty($posts)) : ?>
        <div class="related-posts">
            <?php
            foreach ($posts as $item) :
                $post_id = $item->ID;
                $post_title = get_the_title($post_id);
                $post_thumbnail = get_the_post_thumbnail_url($post_id, 'medium');
                $post_tags = get_the_tags($post_id);
                $tags_array = array();
                if (!empty($post_tags)) {
                    foreach ($post_tags as $tag) {
                        $tags_array[] = $tag->name; // 태그 이름을 배열에 추가
                    }
                }
                $post_content = apply_filters('the_content', get_post_field('post_content', $post_id));
            ?>
                <div class="item">
                    <div class="thumbnail">
                        <a href="<?php echo get_permalink($post_id); ?>">
                            <?php echo get_the_post_thumbnail($post_id, 'medium'); ?>
                        </a>
                    </div>
                    <div class="text-wrap">
                        <?php if (!empty($tags_array)) : ?>
                            <p class="tag">#<?= implode(' #', $tags_array) ?></p>
                        <?php endif ?>
                        <a href="<?php echo get_permalink($post_id); ?>">
                            <p class="title ellipsis-1"><?php echo get_the_title($post_id); ?></p>
                        </a>
                        <div class="description ellipsis-2"><?= $post_content ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif ?>
    <?php
    return ob_get_clean();
}

function custom_script_load_for_product_page()
{
    if (is_product()) {
    ?>
        <script>
            jQuery(document).ready(function($) {
                var originalHeight = $('#wv_prod_content').get(0).scrollHeight;
                if (originalHeight > 500) {
                    $('#wv_prod_content').addClass('short');
                }

                $('#toggle-button a').click(function(e) {
                    e.preventDefault();
                    var element = $('#wv_prod_content');
                    if (element.hasClass('short')) {
                        element.removeClass('short');
                        $('#toggle-button a').find('svg').css('transform', 'rotate(180deg)');
                    } else {
                        $('#wv_prod_content').addClass('short');
                        $('#toggle-button a').find('svg').css('transform', 'rotate(0deg)');
                    }
                });

                jQuery(".cr-all-reviews-add-review").on("click", function(t) {
                    t.preventDefault();
                    if (0 < jQuery(".cr-all-reviews-shortcode").length) {
                        jQuery(".cr-all-reviews-shortcode").addClass("cr-all-reviews-new-review");
                    }
                    if (0 < jQuery(".cr-reviews-grid").length) {
                        jQuery(".cr-reviews-grid").addClass("cr-reviews-grid-new-review");
                    }
                });
            });
        </script>
<?php
    }
}
add_action('wp_footer', 'custom_script_load_for_product_page');

function custom_script_load_for_shop_page()
{
    if (is_shop()) {
    ?>
        <script>
            jQuery(document).ready(function($) {

                $('.woocommerce-shop .wpfFilterTitle').removeClass('active');

                $('.woocommerce-shop .wpfFilterTitle').click(function(e) {
                    e.preventDefault();
                    if ($(this).hasClass('active')) {
                        $(this).removeClass('active');
                    } else {
                        $(this).addClass('active');
                    }
                });

                /* $('#toggle-button a').click(function(e) {
                    e.preventDefault();
                    var element = $('#wv_prod_content');
                    if (element.hasClass('short')) {
                        element.removeClass('short');
                        $('#toggle-button a').find('svg').css('transform', 'rotate(180deg)');
                    } else {
                        $('#wv_prod_content').addClass('short');
                        $('#toggle-button a').find('svg').css('transform', 'rotate(0deg)');
                    }
                });

                jQuery(".cr-all-reviews-add-review").on("click", function(t) {
                    t.preventDefault();
                    if (0 < jQuery(".cr-all-reviews-shortcode").length) {
                        jQuery(".cr-all-reviews-shortcode").addClass("cr-all-reviews-new-review");
                    }
                    if (0 < jQuery(".cr-reviews-grid").length) {
                        jQuery(".cr-reviews-grid").addClass("cr-reviews-grid-new-review");
                    }
                }); */
            });
        </script>
<?php
    }
}
add_action('wp_footer', 'custom_script_load_for_shop_page');
