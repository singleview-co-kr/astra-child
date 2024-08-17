<?php

$selected_material = get_field('selected_material');
?>

<div class="sv-custom-block">
    <div class="selected-material">
        <p class="sec-label">준비물</p>
        <?php if (!empty($selected_material)) { ?>
            <?php if (is_admin()) : ?>
                <?php
                $cnt = 1;
                foreach ($selected_material as $item) : ?>
                    <p><?= $cnt ?>.선택된 항목 : <strong><img src="<?= $item['image']['sizes']['thumbnail'] ?>" alt=""><?php echo $item['title']; ?></strong></p>
                <?php
                    $cnt++;
                endforeach; ?>
            <?php else : ?>
                <ul>
                    <?php foreach ($selected_material as $item) : ?>
                        <li>
                            <div class="thumbnail">
                                <img src="<?= $item['image']['sizes']['large'] ?>" alt="">
                            </div>
                            <p class="title"><?php echo $item['title']; ?></p>
                    </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif ?>
        <?php } ?>
    </div>
</div>