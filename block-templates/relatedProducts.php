<?php
$products = get_field('product');
?>

<div class="wv-custom-block">
    <div class="related-product">
        <p class="sec-label">함께 구매하면 좋아요</p>
        <?php if (!empty($products)) { ?>
            <?php if (is_admin()) : ?>
                <?php
                $cnt = 1;
                foreach ($products as $item) : ?>
                    <p><?= $cnt ?>.선택된 항목 : <strong><?php echo get_the_title($item->ID); ?></strong></p>
                <?php
                    $cnt++;
                endforeach; ?>
            <?php else : ?>
                <div class="related_productSwiper">
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
            <?php endif ?>
        <?php } ?>
    </div>
</div>
<?php if (!is_admin() && !empty($products)) { ?>
    <script>
        const related_productSwiper = new Swiper('.related_productSwiper', {
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
<?php } ?>