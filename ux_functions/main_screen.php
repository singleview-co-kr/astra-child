<?php
if (! defined('ABSPATH') ) {
    exit;  // Exit if accessed directly.
}

if ( ! function_exists('get_field') ) {
    show_alert_message( 'Advanced Custom Fields PRO is required' );
    return;
}

function main_slide_shortcode()
{
    global $n_theme_setup_page_id;  // set on functions.php
    $a_main_slide_info = get_field( 'main_slide_info', $n_theme_setup_page_id );
    ob_start();
    ?>
    <div class="mainBannerSwiper">
        <ul class="swiper-wrapper">
            <?php
            foreach ( $a_main_slide_info as $a_single_slide ) :?>
                <li class="swiper-slide">
                    <div class="bg_wrap">
                        <img src="<?php echo $a_single_slide['thumbnail_pc']['url'] ?>" class="pc">
                        <img src="<?php echo $a_single_slide['thumbnail_mobile']['url'] ?>" class="mobile">
                    </div>
                    <div class="text_wrap">
                        <h3 class="title" style='color: black'><?php echo nl2br($a_single_slide['title']) ?></h3>
                        <p class="subtitle"><?php echo nl2br($a_single_slide['subtitle']) ?></p>
                        <a href="<?php echo $a_single_slide['button_url'] ?>" class="btn_st1" <?php if( isset( $a_single_slide['button_border_font_color'] ) ): ?> style='color: <?php echo $a_single_slide['button_border_font_color']?>; border-color: <?php echo $a_single_slide['button_border_font_color']?>;'<?php endif?>><?php echo $a_single_slide['button_label'] ?></a>
                    </div>
                </li>
            <?php endforeach;
            unset( $a_single_slide );
            unset( $a_main_slide_info );?>
        </ul>
        <div class="btn-wrap">
            <div class="progress-round-wrap">
                <svg class="progress-round" viewBox="0 0 110 110">
                    <circle class="circle-bg" r="50" cx="55" cy="55" />
                    <circle class="circle-go" r="50" cx="55" cy="55" />
                </svg>
                <button type="button" class="slide-start"><img src="<?php echo get_stylesheet_directory_uri() ?>/assets/images/ico_play.svg" alt="slide start"></button>
                <button type="button" class="slide-stop"><img src="<?php echo get_stylesheet_directory_uri() ?>/assets/images/ico_pause.svg" alt="slide stop"></button>
            </div>
            <div class="pagination"></div>
        </div>
    </div>
    <script>
        let time = 1.5;
        let swiper, isPause, tick, percentTime = 0;
        var mainBannerSwiper = new Swiper(".mainBannerSwiper", {
            pagination: {
                el: '.mainBannerSwiper .pagination',
                clickable: true,
                renderBullet: function(index, className) {
                    return '<span class="' + className + '">' + numFormat(index + 1) + '</span>';
                },
            },
            loop: true,
            effect: "fade",
            speed: 700,
            autoplay: false,
            navigation: {
                nextEl: ".mainBannerSwiper .swiper-button-next",
                prevEl: ".mainBannerSwiper .swiper-button-prev",
            },
            on: {

                sliderMove: function() {
                    resetCircle()
                },
                realIndexChange: function() {
                    resetCircle()
                },
                resize: function() {
                    resetCircle()
                },
            },
        });

        function resetCircle() {
            percentTime = 0;
        }

        function startProgressbar() {
            clearTimeout(tick);
            isPause = false;
            tick = setInterval(interval, 30);
        }
        const rBar = document.querySelector('.circle-go');
        const rLen = 2 * Math.PI * rBar.getAttribute('r');

        function interval() {
            if (isPause === false) {
                percentTime += 1 / (time + 0.1);
                rBar.style.strokeDasharray = String(rLen)
                rBar.style.strokeDashoffset = String(rLen * (1 - percentTime / 100))
                if (percentTime >= 100) {
                    mainBannerSwiper.slideNext()
                    resetCircle()
                    startProgressbar();
                }
            }
        }
        resetCircle();
        startProgressbar();

        jQuery('.mainBannerSwiper .slide-stop').on('click', function() {
            clearTimeout(tick);
            isPause = true;
            resetCircle();
            jQuery(this).hide();
            jQuery('.mainBannerSwiper .slide-start').show();
        });

        jQuery('.mainBannerSwiper .slide-start').on('click', function() {
            resetCircle();
            clearTimeout(tick);
            isPause = false;
            tick = setInterval(interval, 30);
            jQuery(this).hide();
            jQuery('.mainBannerSwiper .slide-stop').show();
        });

        function numFormat(variable) {
            variable = Number(variable).toString();
            if (Number(variable) < 10 && variable.length == 1)
                variable = "0" + variable;
            return variable;
        }
    </script>
    <?php

    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode('main_slide', 'main_slide_shortcode');


function main_latest_post_shortcode()
{
    global $n_theme_setup_page_id;  // set on functions.php
    $a_category_id = get_field( 'main_latest_post_category_name', $n_theme_setup_page_id );
    $n_posts_count = get_field( 'main_latest_post_posts_count', $n_theme_setup_page_id );
    $s_orderby = get_field( 'main_latest_post_orderby', $n_theme_setup_page_id );
    
    $a_query_args = array(
        'post_status' => 'publish',
        'category__in' => $a_category_id,
        'posts_per_page' => $n_posts_count ? $n_posts_count : 5,
    );
    $s_orderby = get_field( 'main_latest_post_orderby', $n_theme_setup_page_id );
    if( $s_orderby ) {
        $a_query_args[ 'orderby' ] = $s_orderby;
    }
    
    $query = new WP_Query(  // 특정 카테고리의 글을 추출하는 WP_Query
        $a_query_args
    );
    unset( $a_query_args );

    $s_theme_default_thumbnail_url = get_field( 'theme_default_thumbnail', $n_theme_setup_page_id );
    ob_start();

    // 글 루프 시작
    if ($query->have_posts()): ?>
        <div class="mainBlogSwiper">
            <div class="swiper-wrapper">
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <div class="swiper-slide">
                        <!-- 글의 태그 출력 -->
                        <?php $tags = get_the_tags(); ?>
						<div class="post-tags">
							<?php if ($tags) : 
								$n_idx = 0;
								foreach ($tags as $tag) : 
									$n_idx++; ?>
									<span><a href="<?php echo get_tag_link($tag->term_id); ?>">#<?php echo $tag->name; ?></a></span>
									<?php if( $n_idx > 1 ) {
										break;
									}
								endforeach; ?>
							<?php else : ?>
								<span class='no_tag_post'>#태그없음</span>
							<?php endif; ?>
						</div>
                        <!-- 글의 썸네일 출력 -->
						<a href="<?php the_permalink(); ?>" class="thumbnail">
							<?php if (has_post_thumbnail()) : ?>
								<?php the_post_thumbnail('large'); ?>
							<?php else: ?>
								<img src="<?php echo $s_theme_default_thumbnail_url ?>">
							<?php endif; ?>
						</a>

                        <!-- 글의 제목과 링크 출력 -->
                        <h3 class="title ellipsis-1"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

                        <!-- 글의 요약 출력 -->
                        <div class="description ellipsis-2"><a href="<?php the_permalink(); ?>"><?php the_excerpt(); ?></a></div>

                        <a href="<?php the_permalink(); ?>" class="more_btn">View more<img src="<?php echo get_stylesheet_directory_uri() ?>/assets/images/ico_long_arrow_right.svg" alt="slide next"></a>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata(); // 쿼리 리셋
                ?>
            </div>
            <div class="btn-wrap">
                <button type="button" class="slide-prev"><img src="<?php echo get_stylesheet_directory_uri() ?>/assets/images/Ico_arrow_left.svg" alt="slide prev"></button>
                <div class="pagination"></div>
                <button type="button" class="slide-next"><img src="<?php echo get_stylesheet_directory_uri() ?>/assets/images/Ico_arrow_right.svg" alt="slide next"></button>
            </div>
        </div>
        <script>
            var mainBlogSwiper = new Swiper(".mainBlogSwiper", {
                pagination: {
                    el: '.mainBlogSwiper .pagination',
                    type: 'custom',
                    renderCustom: function(swiper, current, total) {
                        var adjustedTotal = total;
                        return '<span class="hide">' + (current < adjustedTotal ? numFormat(current) : numFormat(current - 1)) + '</span>' +
                            '<span class="divider"></span>' +
                            '<span class="total hide">' + numFormat(adjustedTotal - 1) + '</span>';
                    }
                },
                speed: 700,
                autoplay: false,
                loop: false,
                navigation: {
                    nextEl: ".mainBlogSwiper .slide-next",
                    prevEl: ".mainBlogSwiper .slide-prev",
                },
                spaceBetween: 30,
                slidesPerView: 1,
                breakpoints: {
                    1300: {
                        slidesPerView: 4
                    },
                    1000: {
                        slidesPerView: 3
                    },
                    768: {
                        slidesPerView: 2
                    }
                },
            });

        </script>
        <?php
    else :
        ?>
        <?php
    endif;

    return ob_get_clean();
}
add_shortcode('main_latest_post', 'main_latest_post_shortcode');

// https://stackoverflow.com/questions/38415499/making-a-list-element-ul-li-mobile-friendly-responsive-in-html-css
function main_blogtag_shortcode()
{
    global $n_theme_setup_page_id;  // set on functions.php
	$a_converted_tag = array();
    $a_tag = get_tags();
    foreach ( $a_tag as $o_single_tag ) {
		$o_tmp_tag = new stdClass();
		$o_tmp_tag->term_id = $o_single_tag->term_id;
		$o_tmp_tag->name = $o_single_tag->name;
		$o_tmp_tag->count = $o_single_tag->count;
		$a_converted_tag[] = $o_tmp_tag;
	}
	unset($a_tag);
	usort( $a_converted_tag, "usort_tag_count" );
    $n_tags_count = get_field( 'main_latest_post_tags_count', $n_theme_setup_page_id );
	$a_converted_tag = array_slice( $a_converted_tag, 0, $n_tags_count ? $n_tags_count : 3, true );
    ob_start();
    echo "<ul class='main_blog_tag'>";
    foreach( $a_converted_tag as $o_single_tag ) {
		$s_tag_link = get_tag_link( $o_single_tag->term_id );  // 태그 링크를 가져옴
		echo '<li><a href="' . esc_url($s_tag_link) . '">#' . esc_html($o_single_tag->name) . '</a></li>';
	}
    echo "</ul>";
    unset($a_converted_tag);
    unset($o_single_tag);
    return ob_get_clean();
}
add_shortcode('main_blogtag', 'main_blogtag_shortcode');

function usort_tag_count($o_first, $o_second) {
	if( $o_first->count > $o_second->count ) {
	    return -1;
	}
	return 1;
}