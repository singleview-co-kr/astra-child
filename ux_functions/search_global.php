<?php
/**
 * global search of the theme.
 *
 * @author  https://singleview.co.kr/
 * @version 0.0.1
 */
if (! defined('ABSPATH') ) {
    exit;  // Exit if accessed directly.
}

// load x2board API
require_once X2B_PATH . 'api.php';

add_shortcode('global_search', 'global_search_shortcode');
function global_search_shortcode()
{
    ob_start();
    ?>
    <div id="global_search">
        <div class="search-form">
            <div class="form-wrap">
                <input type="text" id="search-input" name="keyword" placeholder="검색어를 입력해주세요.">
                <button type="button" id="search-button" class="search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="21.827" height="21.832" viewBox="0 0 21.827 21.832">
                        <path id="Icon_ionic-ios-search" data-name="Icon ionic-ios-search" d="M26.071,24.745,20,18.618a8.651,8.651,0,1,0-1.313,1.33l6.031,6.088a.934.934,0,0,0,1.319.034A.94.94,0,0,0,26.071,24.745ZM13.2,20.022a6.831,6.831,0,1,1,4.831-2A6.789,6.789,0,0,1,13.2,20.022Z" transform="translate(-4.5 -4.493)"></path>
                    </svg>
                </button>
            </div>
        </div>
        <ul class="nav nav-search mb-3" id="search-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="search-product-tab" data-bs-toggle="pill" data-bs-target="#search-product" type="button" role="tab" aria-controls="search-product" aria-selected="true">상품<span class="cnt">0</span></button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="search-blog-tab" data-bs-toggle="pill" data-bs-target="#search-blog" type="button" role="tab" aria-controls="search-blog" aria-selected="false">블로그<span class="cnt">0</span></button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="search-qna-tab" data-bs-toggle="pill" data-bs-target="#search-qna" type="button" role="tab" aria-controls="search-qna" aria-selected="false">묻고 답하기<span class="cnt">0</span></button>
            </li>
        </ul>
        <div class="tab-content" id="search-tabContent">
            <div class="tab-pane fade show active" id="search-product" role="tabpanel" aria-labelledby="search-product-tab">
                <div class="content">
                    <ul></ul>
                </div>
                <div class="btn-wrap">
                    <button class="load-more hide" data-type="product" data-page="1">더보기<svg xmlns="http://www.w3.org/2000/svg" width="10.243" height="6.325" viewBox="0 0 10.243 6.325">
                            <path id="Icon_material-keyboard-arrow-right" data-name="Icon material-keyboard-arrow-right" d="M12.885,17.665l3.91-3.918-3.91-3.918,1.2-1.2,5.122,5.122-5.122,5.122Z" transform="translate(18.868 -12.885) rotate(90)" fill="#a6a6a6" />
                        </svg></button>
                </div>
            </div>
            <div class="tab-pane fade" id="search-blog" role="tabpanel" aria-labelledby="search-blog-tab">
                <div class="content">
                    <ul></ul>
                </div>
                <div class="btn-wrap">
                    <button class="load-more hide" data-type="blog" data-page="1">더보기<svg xmlns="http://www.w3.org/2000/svg" width="10.243" height="6.325" viewBox="0 0 10.243 6.325">
                            <path id="Icon_material-keyboard-arrow-right" data-name="Icon material-keyboard-arrow-right" d="M12.885,17.665l3.91-3.918-3.91-3.918,1.2-1.2,5.122,5.122-5.122,5.122Z" transform="translate(18.868 -12.885) rotate(90)" fill="#a6a6a6" />
                        </svg></button>
                </div>
            </div>
            <div class="tab-pane fade" id="search-qna" role="tabpanel" aria-labelledby="search-qna-tab">
                <div class="content">
                    <ul></ul>
                </div>
                <div class="btn-wrap">
                    <button class="load-more hide" data-type="qna" data-page="1">더보기<svg xmlns="http://www.w3.org/2000/svg" width="10.243" height="6.325" viewBox="0 0 10.243 6.325">
                            <path id="Icon_material-keyboard-arrow-right" data-name="Icon material-keyboard-arrow-right" d="M12.885,17.665l3.91-3.918-3.91-3.918,1.2-1.2,5.122,5.122-5.122,5.122Z" transform="translate(18.868 -12.885) rotate(90)" fill="#a6a6a6" />
                        </svg></button>
                </div>
            </div>
        </div>
    </div>
    <script>
        jQuery(document).ready(function($) {
            jQuery('#btn_global_search_layer_open a').click(function() {  // Prevent aimless ajax call
                data_fetch(''); 
            });

            $('#search-input').keypress(function(event) {
                if (event.which == 13) {
                    event.preventDefault();
                    $('#search-button').click();
                }
            });

            $('#search-button').on('click', function() {
                var keyword = $('#search-input').val();
                if (keyword == '') {
                    $('#global_search .tab-content .content ul').html('');
                    $('#global_search .nav-item button .cnt').text('0');
                } else {
                    data_fetch(keyword);
                }
            });

            function data_fetch(keyword) {
                $.ajax({
                    url: ajax_var.url,
                    type: 'POST',
                    data: {
                        'action': 'data_fetch',
                        'keyword': keyword
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        $('#search-product-tab .cnt').text(data.product.total);
                        if (data.product.html) {
                            $('#search-product .content ul').html(data.product.html);
                        } else {
                            $('#search-product .content ul').html('');
                        }
                        $('#search-blog-tab .cnt').text(data.blog.total);
                        if (data.blog.html) {
                            $('#search-blog .content ul').html(data.blog.html);
                        } else {
                            $('#search-blog .content ul').html('');
                        }
                        $('#search-qna-tab .cnt').text(data.qna.total);
                        if (data.qna.html) {
                            $('#search-qna .content ul').html(data.qna.html);
                        } else {
                            $('#search-qna .content ul').html('');
                        }

                        if (data.product.total > data.posts_per_page) {
                            $('#search-product .load-more').removeClass('hide');
                        }
                        if (data.blog.total > data.posts_per_page) {
                            $('#search-blog .load-more').removeClass('hide');
                        }
                        if (data.qna.total > data.posts_per_page) {
                            $('#search-qna .load-more').removeClass('hide');
                        }
                    }
                });
            }

            $('.load-more').click(function() {
                var btn = $(this),
                    postType = btn.data('type'), // 상품, 블로그, 묻고 답하기 구분
                    page = btn.data('page'); // 현재 페이지 번호

                $.ajax({
                    url: ajax_var.url,
                    type: 'POST',
                    data: {
                        'action': 'data_fetch',
                        'keyword': $('#search-input').val(),
                        'type': postType,
                        'page': page + 1 // 다음 페이지 번호
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        // 결과에 따라 페이지 내용을 업데이트하고, 페이지 번호를 증가시킵니다.
                        if (data[postType].html) {
                            $('#search-' + postType).find('.content ul').append(data[postType].html);
                            btn.data('page', page + 1); // 페이지 번호 증가
                            btn.removeClass('no-post');
                            btn.removeClass('hide');
                        } else {
                            // 더 이상 불러올 데이터가 없으면 버튼을 숨깁니다.
                            btn.addClass('no-post');
                            btn.addClass('hide');
                        }
                    }
                });
            });
        });
    </script>
    <?php
    return ob_get_clean();
}

add_action('wp_ajax_data_fetch', 'data_fetch');
add_action('wp_ajax_nopriv_data_fetch', 'data_fetch');

function data_fetch()
{
    global $wpdb;
    $keyword = $_POST['keyword'];
    $response = array();

    // $type = $_POST['type'];
    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    $posts_per_page = 9;
    $offset = ($page - 1) * $posts_per_page;

    if ($keyword == '') {
        // 상품 검색
        add_filter('posts_search', 'search_filter_by_title_only', 10, 2);
        $product_args = array(
            'post_type' => 'product',
            'posts_per_page' => $posts_per_page,
            'offset' => $offset,
        );
        // $product_args['offset'] = $offset;
        $products = new WP_Query($product_args);
        remove_filter('posts_search', 'search_filter_by_title_only', 10);

        // 블로그 검색
        add_filter('posts_search', 'search_filter_by_title_only', 10, 2);
        $blog_args = array(
            'post_type' => 'post',
            'posts_per_page' => $posts_per_page,
            'offset' => $offset,
        );
        // $blog_args['offset'] = $offset;
        $blogs = new WP_Query($blog_args);
        remove_filter('posts_search', 'search_filter_by_title_only', 10);
    }

    if ($keyword != '') {
        // 상품 검색
        add_filter('posts_search', 'search_filter_by_title_only', 10, 2);
        $product_args = array(
            'post_type' => 'product',
            'posts_per_page' => $posts_per_page,
            'offset' => $offset,
            's' => $keyword
        );
        // $product_args['offset'] = $offset;
        $products = new WP_Query($product_args);
        remove_filter('posts_search', 'search_filter_by_title_only', 10);

        // 블로그 검색
        add_filter('posts_search', 'search_filter_by_title_only', 10, 2);
        $blog_args = array(
            'post_type' => 'post',
            'posts_per_page' => $posts_per_page,
            'offset' => $offset,
            's' => $keyword
        );
        // $blog_args['offset'] = $offset;
        $blogs = new WP_Query($blog_args);
        remove_filter('posts_search', 'search_filter_by_title_only', 10);
    }

    // 상품 검색
    $response['product'] = array();
    $products_output = '';
    if($products->have_posts() ) {
        while ($products->have_posts()) {
            $products->the_post();
            $product_id = get_the_ID();
            $product_permalink = get_permalink($product_id);
            $product = wc_get_product($product_id);

            if ($product->get_sale_price() > 0) {
                $products_output .= "<li class='on-sale'>";
            } else {
                $products_output .= "<li>";
            }
            $products_output .= "<a href='{$product_permalink}' class='product-link'>";
            $products_output .= "<div class='thumbnail'><img src='" . get_the_post_thumbnail_url($product_id, 'medium') . "'></div>";
            $products_output .= "<div class='text-wrap'>";
            $products_output .= "<p class='title ellipsis-1'>" . get_the_title() . "</p>";
            $products_output .= "<p class='price'>" . number_format(intval($product->get_regular_price())) . "원</p>";
            if ($product->get_sale_price() > 0) {
                $products_output .= "<p class='sale'>" . number_format(intval($product->get_sale_price())) . "원</p>";
            }
            $products_output .= "</div>";
            $products_output .= "</a>";
            $products_output .= "</li>";
        }
        $response['product']['html'] = $products_output;
    }
    $response['product']['total'] = $products->found_posts;

    // 블로그 검색
    $response['blog'] = array();
    $blogs_output = null;
    if ($blogs->have_posts()) {
        while ($blogs->have_posts()) {
            $blogs->the_post();
            $blog_permalink = get_permalink();
            $tags = get_the_tags();

            $blogs_output .= "<li>";
            $blogs_output .= "<a href='{$blog_permalink}' class='blog-link'>";
			$blogs_output .= "<div class='thumbnail'>";
			if( has_post_thumbnail() ) {
				$blogs_output .= "<img src='" . get_the_post_thumbnail_url(get_the_ID(), 'medium') . "'>";
			}
			else {
				$blogs_output .= "<img src='" . get_stylesheet_directory_uri() . "/assets/images/thumb_logo_yuhanclorox.jpg'>";
			}
			$blogs_output .= "</div>";
            $blogs_output .= "<div class='text-wrap'>";
            if ($tags) {
                $blogs_output .= "<p class='tags'>";
                foreach ($tags as $tag) {
                    $blogs_output .= '<span>#' . $tag->name . '</span>';
                }
                $blogs_output .= "</p>";
            }
            $blogs_output .= "<p class='title ellipsis-1'>" . get_the_title() . "</p>";
            $blogs_output .= "<p class='description ellipsis-2'>" . get_the_excerpt() . "</p>";
            $blogs_output .= "</div>";
            $blogs_output .= "</a>";
            $blogs_output .= "</li>";
        }
        $response['blog']['html'] = $blogs_output;
    }
    $response['blog']['total'] = $blogs->found_posts;

    // 묻고 답하기 검색
    $a_qna_rst = X2board\Api\get_quick_search(801, $keyword);

    $response['qna'] = array();
    $qna_output = null;
    foreach ($a_qna_rst as $o_post) {
        $qna_output .= "<li>";
        $qna_output .= "<a href='" . $o_post->permalink . "'>";
        $qna_output .= "<div class='thumbnail'><img src='/wp-content/uploads/2024/04/logo2.jpg'></div>";
        $qna_output .= "<div class='text-wrap'>";
        $qna_output .= "<p class='tags'>" . $o_post->category_title . "</p>";
        $qna_output .= "<p class='title ellipsis-1'>" . $o_post->title . "</p>";
        $qna_output .= "<p class='description ellipsis-2'>" . $o_post->content . "</p>";
        $qna_output .= "</div>";
        $qna_output .= "</a>";
        $qna_output .= "</li>";
    }
    $response['qna']['html'] = $qna_output;
    $response['qna']['total'] = count($a_qna_rst);
    unset($a_qna_rst);
    $response['posts_per_page'] = $posts_per_page;

    wp_reset_postdata();
    echo json_encode($response);
    wp_die();
}

function search_filter_by_title_only($search, $wp_query)
{
    global $wpdb;

    if (empty($search)) {
        return $search; // 검색어가 없다면 바로 반환
    }

    // WP_Query가 검색 모드인지 확인
    if (!empty($wp_query->query_vars['search_terms'])) {
        // 검색어가 있다면, 각 검색어에 대해 제목 검색 조건을 생성
        $search_terms = $wp_query->query_vars['search_terms'];
        $search = '';
        foreach ($search_terms as $term) {
            $search .= "{$wpdb->posts}.post_title LIKE '%" . esc_sql($wpdb->esc_like($term)) . "%' OR ";
        }

        // 마지막 'OR' 제거
        $search = substr($search, 0, -4);
        // WHERE 절에 AND로 검색 조건을 추가
        $search = " AND ({$search}) ";
        if (!is_user_logged_in()) {
            $search .= " AND ({$wpdb->posts}.post_password = '') ";
        }
    }
    return $search;
}

add_shortcode('sv_get_notice', 'get_notice_shortcode');
function get_notice_shortcode()
{
    // 묻고 답하기 검색
    $o_param = new stdClass();
    $o_param->s_date_format = 'Y-m-d';
    $a_notice_rst = X2board\Api\get_notice(801, $o_param);
    unset($o_param);
    ob_start();
    ?>
    <div id="ycx-global-notice-latest">
        <table>
            <tbody>
				<?php if( count( $a_notice_rst ) ): ?>
					<?php foreach( $a_notice_rst as $o_notice ): ?>
					<tr>
						<td class="ycx-global-latest-title">
							<a href="<?php echo $o_notice->permalink ?>">
								<div class="ycx-global-notice-cut-strings">
									<?php echo $o_notice->title?>
								</div>
								<p class="latest-date"><?php echo $o_notice->regdate ?><span class="ycx-global-comments-count">(<?php echo number_format($o_notice->readed_count) ?>)</span></p>
							</a>
						</td>
					</tr>
					<?php endforeach ?>
				<?php else: ?>
					<tr>
						<td class="ycx-global-latest-title">
							<a href="#">
								<div class="ycx-global-notice-cut-strings">
									공지 사항이 없습니다.
								</div>
							</a>
						</td>
					</tr>
				<?php endif ?>
				
            </tbody>
        </table>
    </div>
    <?php
    return ob_get_clean();
}
