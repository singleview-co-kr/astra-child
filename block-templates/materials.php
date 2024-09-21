<?php
if (! defined('ABSPATH') ) {
    exit;  // Exit if accessed directly.
}
$selected_material = get_field('selected_material');
?>

<div class="sv-custom-block">
    <div class="selected-material">
        <p class="sec-label">준비물</p>
        <?php if (!empty($selected_material)) : ?>
            <?php if (is_admin()) : ?>
                <?php
                $cnt = 1;
                foreach ($selected_material as $item) : 
                    if( empty($item['product']) ) : // pure image object ?>
                        <p><?php echo $cnt++ ?>.선택된 항목 : <strong><img src="<?php echo $item['image']['sizes']['thumbnail'] ?>" alt="<?php echo $item['title']; ?>"><?php echo $item['title']; ?></strong></p>
                    <?php else :  // WC product object 
                        $n_product_id = intval( $item['product'][0] );?>
                        <p><?php echo $cnt++ ?>.선택된 항목 : <strong><?php echo get_the_post_thumbnail( $n_product_id, 'thumbnail' ); ?><?php echo get_the_title( $n_product_id ); ?></strong></p>
                    <?php endif;
                endforeach; ?>
            <?php else : ?>
                <ul>
                    <?php foreach ($selected_material as $item) : ?>
                        <li>
                            <?php if( empty($item['product']) ) : // pure image object ?>
                                <div class="thumbnail">
                                    <img src="<?php echo $item['image']['sizes']['large'] ?>" alt="">
                                </div>
                                <p class="title"><?php echo $item['title']; ?></p>
                            <?php else : // WC product object 
                                $n_product_id = intval( $item['product'][0] ); ?>
                                <div class="thumbnail">
                                    <a href="<?php echo get_permalink($n_product_id); ?>"><?php echo get_the_post_thumbnail( $n_product_id, 'thumbnail' ); ?></a>
                                </div>
                                <p class="title"><?php echo get_the_title( $n_product_id ); ?></p>
                            <?php endif ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif;
        endif ?>
    </div>
</div>
