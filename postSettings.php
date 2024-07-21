<?php

function tt3child_register_acf_blocks() {
    /**
     * We register our block's with WordPress's handy
     * register_block_type();
     *
     * @link https://developer.wordpress.org/reference/functions/register_block_type/
     */
    register_block_type( __DIR__ . '/blocks/promotion' );
}
// Here we call our tt3child_register_acf_block() function on init.
add_action( 'init', 'tt3child_register_acf_blocks' );