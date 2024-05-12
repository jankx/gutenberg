<?php

add_theme_support('block-templates');

function jankx_is_support_block_template()
{
    return apply_filters(
        'jankx/gutenberg/enabled',
        get_theme_support('block-templates')
    );
}
