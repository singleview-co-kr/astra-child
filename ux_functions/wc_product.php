<?php
if (! defined('ABSPATH') ) {
    exit;  // Exit if accessed directly.
}

// begin - remove add-to-cart button completely
// hide add-to-cart message just after click external market button(which is simulated WC add-to-cart button)
add_filter('wc_add_to_cart_message_html', '__return_false');
// https://www.cloudways.com/blog/how-to-remove-hide-or-disable-add-to-cart-button-in-woocommerce/
add_filter( 'woocommerce_is_purchasable', 'disable_add_to_cart_button' );
function disable_add_to_cart_button( $is_purchasable ) {
    // You can add conditions here to disable the button for specific products
    return false; // return false switch the 'Add to Cart' button to 'View more'
}
// end - remove add-to-cart button completely

add_shortcode('sv_prod_price', 'sv_prod_price_shortcode');
function sv_prod_price_shortcode()
{
    // global $product;
    $product_id = get_the_ID();
    $product    = wc_get_product($product_id);
    if(is_object($product) ) {
        // $weight     = $product->get_weight();  // 상품 데이터 > 배송 > 무게(kg)
        $attributes    = $product->get_attributes();
        $sale_price    = $product->get_sale_price();
        $regular_price = $product->get_regular_price();
    }
    else {
        $attributes    = null;
        $sale_price    = null;
        $regular_price = null;
    }
    unset( $product );

    $link_setting = get_field('link_setting');

    if(isset($attributes['pa_volume'])) {
        $volume_attribute = $attributes['pa_volume'];
        if ($volume_attribute->is_taxonomy()) {
            $a_value  = wc_get_product_terms($product_id, $volume_attribute->get_name(), array('fields' => 'names'));
            $volume = join(', ', $a_value);
            unset( $a_value );
        } else {
            $volume = $volume_attribute->get_options();
        }
    }
    else {
        $volume = null;
    }
    if(isset($attributes['pa_scent'])) {
        $scent_attribute = $attributes['pa_scent'];
        if ($scent_attribute->is_taxonomy()) {
            $a_value  = wc_get_product_terms($product_id, $scent_attribute->get_name(), array('fields' => 'names'));
            $scent = join( ', ', $a_value );
            unset( $a_value );
        } else {
            $scent = $scent_attribute->get_options();
        }
    }
    else {
        $scent = null;
    }
    unset( $attributes );
    ob_start();
    ?>

    <div id="prod_detail" class="<?php echo $sale_price != '' ? 'on-sale' : '' ?>">
    <?php if ($regular_price) : ?><p class="price"><span class="tit">판매가</span><span class="description"><?php echo wc_price($regular_price) ?></span></p><?php endif ?>
        <?php if ($sale_price) : ?><p class="sale-price"><span class="tit">할인가</span><span class="description"><?php echo wc_price($sale_price) ?></span></p><?php endif ?>
        <?php if ($scent) : ?><p class=""><span class="tit">향</span><span class="description"><?php echo esc_html($scent) ?></span></p><?php endif ?>
        <?php if ($volume) : ?><p class=""><span class="tit">용량</span><span class="description"><?php echo $volume ?></span></p><?php endif ?>
    </div>

    <?php if (!empty($link_setting)) :  // simulate WC add to cart button to transfer add-to-cart beacon to GA4 ?>
        <div id="prod_link">
            <form class="cart" action="<?php echo get_permalink(); ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" id="quantity_<?php echo uniqid(); ?>" name="quantity" value="1">
                <?php foreach ($link_setting as $link) : ?>
                    <button type="submit" name="add-to-cart" value="<?php echo $product_id ?>" class="single_add_to_cart_button button alt" style="margin-right: 10px;" onclick="window.open('<?php echo $link['url'] ?>', '_blank');"><?php echo $link['title'] ?></button>
                <?php endforeach ?>
            </form>
        </div>
    <?php endif;
    unset( $link_setting );
    unset( $scent );
    unset( $volume );
    return ob_get_clean();
}

add_shortcode('sv_prod_related_posts', 'sv_prod_related_posts_shortcode');
function sv_prod_related_posts_shortcode()
{
    global $n_theme_setup_page_id;  // set on functions.php
    $posts = get_field('related_post');
    ob_start();
    ?>
    <div class="related-posts">
        <?php if (!empty($posts)) :
            $s_theme_default_thumbnail_url = get_field( 'theme_default_thumbnail', $n_theme_setup_page_id );
            foreach ($posts as $item) :
                $post_id = $item->ID;
                $post_tags = get_the_tags($post_id);
                $tags_array = array();
                if (!empty($post_tags)) {
                    foreach ($post_tags as $tag) {
                        $tags_array[] = $tag->name; // 태그 이름을 배열에 추가
                    }
                }
				unset($post_tags);
                $post_excerpt = apply_filters('the_excerpt', get_post_field('post_excerpt', $post_id));
                if($post_excerpt == '' ) {
                    $post_excerpt = '이 포스팅의 요약이 없습니다';
                } ?>
                <div class="item">
                    <div class="thumbnail">
                        <a href="<?php echo get_permalink($post_id); ?>">
							<?php if( has_post_thumbnail($post_id) ) : ?>
								<?php echo get_the_post_thumbnail($post_id, 'medium'); ?>
							<?php else: ?>
								<img src='<?php echo $s_theme_default_thumbnail_url ?>'>
							<?php endif ?>
                        </a>
                    </div>
                    <div class="text-wrap">
                        <?php if (!empty($tags_array)) : ?>
                            <p class="tag">#<?php echo implode(' #', $tags_array) ?></p>
						<?php else : ?>
							<p class="tag">#태그 없음</p>
                        <?php endif ?>
                        <a href="<?php echo get_permalink($post_id); ?>">
                            <p class="title ellipsis-1"><?php echo get_the_title($post_id); ?></p>
                        </a>
                        <div class="description ellipsis-2"><?php echo $post_excerpt ?></div>
                    </div>
                </div>
            <?php endforeach;
            unset($post_tags);
        else : ?>
            연관된 포스팅이 없습니다.
        <?php endif ?>
    </div>
    <?php return ob_get_clean();
}

add_shortcode('sv_prod_related_discussion', 'sv_prod_related_discussion_shortcode');
function sv_prod_related_discussion_shortcode()
{
    $s_discussion_title = get_field('discussion_title');

    $o_param = new stdClass();
    $o_param->n_posts_per_page = get_field('list_count');
    $o_param->s_query = get_field('csv_query');
    $o_param->a_board_id = array();
    $a_x2board = get_field('x2board_id');
    if($a_x2board ) {
        foreach( $a_x2board as $o_single_board ) {
            $o_param->a_board_id[] = $o_single_board->ID;
        }
    }
    unset($a_x2board);

    // load x2board API
    include_once X2B_PATH . 'api.php';
    // 묻고 답하기 검색
    $a_qna_rst = X2board\Api\get_quick_search($o_param);
    unset( $o_param );
    ob_start();
    ?>
    <p class="sec-label"><?php echo $s_discussion_title != '' ? $s_discussion_title : '이 제품과 연관된 Q&A' ?></p>
    <div id="x2board-qna-list" class="latest-qna-list">
        <?php if(empty($a_qna_rst) ) : ?>
            연관된 논의가 없습니다.
        <?php else : ?>
            <!-- 리스트 시작 -->
            <?php if (is_admin()) : ?>
                <?php foreach( $a_qna_rst as $n_idx => $o_post ): ?>
                    <div class="accordion-item">
                        <h2><span class="q">Q.</span> <?php echo $o_post->title ?></h2>
                        <div><?php echo nl2br($o_post->content) ?><BR><BR>
                            <a href='<?php echo $o_post->permalink ?>'>자세히 보기</a>
                        </div>
                    </div>
                <?php endforeach ?>
            <?php else : ?>
                <div class="accordion accordion-flush" id="accordionQna">
                    <?php foreach( $a_qna_rst as $n_idx => $o_post ): ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-heading<?php echo $n_idx ?>">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?php echo $n_idx ?>" aria-expanded="false" aria-controls="flush-collapse<?php echo $n_idx ?>">
                                    <span class="q">Q.</span> <?php echo $o_post->title ?>
                                </button>
                            </h2>
                            <div id="flush-collapse<?php echo $n_idx ?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?php echo $n_idx ?>" data-bs-parent="#accordionQna">
                                <div class="accordion-body"><?php echo nl2br($o_post->content) ?><BR><BR>
                                <a href='<?php echo $o_post->permalink ?>'>자세히 보기</a></div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            <?php endif ?>
        <?php endif ?>
        <!-- 리스트 끝 -->
    </div>
    <?php
    unset( $a_qna_rst );
    return ob_get_clean();
}

function custom_script_load_for_product_page()
{
    if (is_product()) : // run on WC single product page only ?>
        <script>
            jQuery(document).ready(function($) {
                if ($("#sv_prod_content").length) {  // The element exists
                    var originalHeight = $('#sv_prod_content').get(0).scrollHeight;
                } else {  // The element does not exist
                    var originalHeight = 0;
                }

                if (originalHeight > 500) {
                    $('#sv_prod_content').addClass('short');
                }

                $('#toggle-button a').click(function(e) {  // 상세 페이지 펼치기 버튼
                    e.preventDefault();
                    var element = $('#sv_prod_content');
                    if (element.hasClass('short')) {
                        element.removeClass('short');
                        $('#toggle-button a').find('svg').css('transform', 'rotate(180deg)');
                    } else {
                        $('#sv_prod_content').addClass('short');
                        $('#toggle-button a').find('svg').css('transform', 'rotate(0deg)');
                    }
                });
                
                // Customer Reviews for WooCommerce plugin UX related
                jQuery(".cr-all-reviews-add-review").on("click", function(t) {  // hide existing WC review if add new one
                    t.preventDefault();
                    if (0 < jQuery(".cr-all-reviews-shortcode").length) {
                        jQuery(".cr-all-reviews-shortcode").addClass("cr-all-reviews-new-review");
                    }
                    if (0 < jQuery(".cr-reviews-grid").length) {
                        jQuery(".cr-reviews-grid").addClass("cr-reviews-grid-new-review");
                    }
                });

                jQuery(".cr-review-form-continue").on("click", function(t) {  // refresh the page to show new review if add new one
                    t.preventDefault();
                    window.location.reload();
                });
                // translate message "Sorry, no reviews match your current selections"
                var o_msg_no_reviews = jQuery(".cr-search-no-reviews");
                o_msg_no_reviews.text('첫 후기를 작성해 주세요.');
            });
        </script>
    <?php endif;
}
add_action('wp_footer', 'custom_script_load_for_product_page');

function custom_script_load_for_shop_page()
{
    if (is_shop()) : // run on catalog page only ?>
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
                    var element = $('#sv_prod_content');
                    if (element.hasClass('short')) {
                        element.removeClass('short');
                        $('#toggle-button a').find('svg').css('transform', 'rotate(180deg)');
                    } else {
                        $('#sv_prod_content').addClass('short');
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
    <?php endif;
}
add_action('wp_footer', 'custom_script_load_for_shop_page');

/*
add_shortcode('sv_prod_related_product', 'sv_prod_related_product_shortcode');
function sv_prod_related_product_shortcode()
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
*/