<?php
if (! defined('ABSPATH') ) {
    exit;  // Exit if accessed directly.
}

add_shortcode('sv_prod_price', 'sv_prod_price_shortcode');
function sv_prod_price_shortcode()
{
    global $product;

    $product_id = get_the_ID();
    $product    = wc_get_product($product_id);
    $volume     = get_field('volume');

    if(is_object($product) ) {
        // $weight     = $product->get_weight();
        $attributes    = $product->get_attributes();
        $sale_price    = $product->get_sale_price();
        $regular_price = $product->get_regular_price();
    }
    else {
        $attributes    = null;
        $sale_price    = null;
        $regular_price = null;
    }
    
    $link_setting = get_field('link_setting');
    
    if(isset($attributes['pa_incense'])) {
        $incense_attribute = $attributes['pa_incense'];
        if ($incense_attribute->is_taxonomy()) {
            $values  = wc_get_product_terms($product_id, $incense_attribute->get_name(), array('fields' => 'names'));
            $incense = join(', ', $values);
        } else {
            $incense = $incense_attribute->get_options();
        }
    }
    else {
        $incense = null;
    }

    ob_start();
    ?>

    <div id="prod_detail" class="<?php echo wc_price($sale_price) != '' ? 'on-sale' : '' ?>">
        <p class="price"><span class="tit">판매가</span><span class="description"><?php echo wc_price($regular_price) ?></span></p>
        <p class="sale-price"><span class="tit">할인가</span><span class="description"><?php echo wc_price($sale_price) ?></span></p>
        <?php if ($incense) : ?><p class=""><span class="tit">향</span><span class="description"><?php echo esc_html($incense) ?></span></p><?php 
        endif ?>
        <?php if ($volume) : ?><p class=""><span class="tit">용량</span><span class="description"><?php echo $volume ?></span></p><?php 
        endif ?>
    </div>

    <?php if (!empty($link_setting)) : ?>
        <div id="prod_link">
            <?php foreach ($link_setting as $link) : ?>
                <a href="<?php echo $link['url'] ?>" target="_blank"><?php echo $link['title'] ?></a>
            <?php endforeach ?>
        </div>
    <?php endif ?>

    <?php
    return ob_get_clean();
}

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

add_shortcode('sv_prod_related_posts', 'sv_prod_related_posts_shortcode');
function sv_prod_related_posts_shortcode()
{
    global $n_theme_setup_page_id;  // set on functions.php
    $posts = get_field('related_post');
    ob_start();
    ?>
    <?php if (!empty($posts)) : ?>
        <div class="related-posts">
            <?php 
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
                    $post_excerpt = '포스팅 자세히 보기';
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
            <?php endforeach; ?>
			<?php unset($post_tags); ?>
        </div>
    <?php endif ?>
    <?php
    return ob_get_clean();
}

add_shortcode('sv_prod_related_discussion', 'sv_prod_related_discussion_shortcode');
function sv_prod_related_discussion_shortcode()
{
    $s_discussion_title = get_field('discussion_title');
    $o_x2board = get_field('x2board_id');
    $s_csv_query = get_field('csv_query');
    $o_param = new stdClass();
    $o_param->n_posts_per_page = get_field('list_count');
    if($o_x2board ) {
        $n_board_id = $o_x2board[0]->ID;
    }
    else {
        $n_board_id = 0;
    }
    unset($o_x2board);
    // load x2board API
    include_once X2B_PATH . 'api.php';
    // 묻고 답하기 검색
    $o_param = new stdClass();
    $o_param->a_board_id = array( $n_board_id );
    $o_param->s_query = $s_csv_query;
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
    <?php return ob_get_clean();
}

function custom_script_load_for_product_page()
{
    if (is_product()) { ?>
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
    <?php }
}
add_action('wp_footer', 'custom_script_load_for_product_page');

function custom_script_load_for_shop_page()
{
    if (is_shop()) { ?>
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
    <?php }
}
add_action('wp_footer', 'custom_script_load_for_shop_page');
