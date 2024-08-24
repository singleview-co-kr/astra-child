<?php
if (! defined('ABSPATH') ) {
    exit;  // Exit if accessed directly.
}

function main_slide_shortcode()
{
    $query = new WP_Query(['post_type' => 'main_slide', 'posts_per_page' => -1]);

    ob_start();
    ?>
    <div class="mainBannerSwiper">
        <ul class="swiper-wrapper">
            <?php
            while ($query->have_posts()) :
                $query->the_post();
                $mainSlideData = get_fields();
                ?>
                <li class="swiper-slide">
                    <div class="bg_wrap">
                        <img src="<?php echo $mainSlideData['thumbnail_pc']['url'] ?>" class="pc">
                        <img src="<?php echo $mainSlideData['thumbnail_mobile']['url'] ?>" class="mobile">
                    </div>
                    <div class="text_wrap">
                        <h3 class="title"><?php echo nl2br($mainSlideData['title']) ?></h3>
                        <p class="subtitle"><?php echo nl2br($mainSlideData['subtitle']) ?></p>
                        <a href="<?php echo $mainSlideData['button_url'] ?>" class="btn_st1"><?php echo $mainSlideData['button_label'] ?></a>
                    </div>
                </li>
            <?php endwhile; ?>
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
    // 특정 카테고리의 글을 추출하는 WP_Query
    $query = new WP_Query(
        array(
        //'category_name' => 'blog', // 여기에 해당 카테고리의 슬러그를 입력하세요.
        'posts_per_page' => 10, // 출력하고 싶은 글의 수
        )
    );

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
								<img src="<?php echo get_stylesheet_directory_uri() ?>/assets/images/thumb_logo_yuhanclorox.jpg">
							<?php endif; ?>
						</a>

                        <!-- 글의 제목과 링크 출력 -->
                        <h3 class="title ellipsis-1"><?php the_title(); ?></h3>

                        <!-- 글의 요약 출력 -->
                        <div class="description ellipsis-2"><?php the_excerpt(); ?></div>

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
	$a_converted_tag = array();
    $a_tag = get_tags();
    foreach ( $a_tag as $o_single_tag ) {
        //$tag_link = get_tag_link( $tag->term_id );
		$o_tmp_tag = new stdClass();
		$o_tmp_tag->name = $o_single_tag->name;
		$o_tmp_tag->count = $o_single_tag->count;
		$a_converted_tag[ $o_single_tag->term_id ] = $o_tmp_tag;
	}
	unset($a_tag);
	usort( $a_converted_tag, "usort_tag_count" );
	$a_converted_tag = array_slice( $a_converted_tag, 0, 7, true );

    ob_start();
    echo "<ul class='main_blog_tag'>";
    foreach( $a_converted_tag as $n_tag_id => $o_single_tag ) {
		$s_tag_link = get_tag_link( $n_tag_id );  // 태그 링크를 가져옴
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