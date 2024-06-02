<?php


function jankx_is_support_block_template()
{
    return apply_filters(
        'jankx/gutenberg/enabled',
        get_theme_support('block-templates')
    );
}

function jankx_generate_gutenberg_templates($fileName) {
    return [dirname(JANKX_GUTENBERG_BOOT_FILE) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR  .'page.html'];
}

function jankx_get_gutenberg_template_by_name($fileName) {
    $templates = jankx_generate_gutenberg_templates($fileName);
    foreach($templates as $template) {
        if (file_exists($template)) {
            return $template;
        }
    }
    return null;
}
