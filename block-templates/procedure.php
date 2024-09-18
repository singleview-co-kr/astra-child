<?php
if (! defined('ABSPATH') ) {
    exit;  // Exit if accessed directly.
}

$procedure_title = get_field('procedure_title');
$procedure = get_field('procedure');
?>

<div class="sv-custom-block">
    <div class="procedure">
        <p class="sec-label"><?php echo $procedure_title != '' ? $procedure_title : '과정' ?></p>
        <?php if (!empty($procedure)) { ?>
            <?php if (is_admin()) : ?>
                <?php
                $cnt = 1;
                foreach ($procedure as $item) : ?>
                    <p><?php echo $cnt ?>.선택된 항목 : <strong><img src="<?php echo $item['image']['sizes']['thumbnail'] ?>" alt=""><?php echo $item['title']; ?></strong></p>
                    <?php
                    $cnt++;
                endforeach; ?>
            <?php else : ?>
                <ul>
                    <?php
                    foreach ($procedure as $item) : ?>
                        <li>
                            <span class="cnt"><?php echo $cnt?></span>
                            <div class="text-wrap">
                                <p class="title"><?php echo $item['title']; ?></p>
                                <?php if (!empty($item['description'])) : ?>
                                    <p class="description"><?php echo $item['description']; ?></p>
                                <?php endif ?>
                            </div>
                            <?php if (!empty($item['image'])) : ?>
                                <div class="thumbnail">
                                    <img src="<?php echo $item['image']['sizes']['large'] ?>" alt="">
                                </div>
                            <?php endif ?>
                        </li>
                        <?php
                    endforeach; ?>
                </ul>
            <?php endif ?>
        <?php } ?>
    </div>
</div>
