<?php

namespace Jankx\Gutenberg\Filters;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\Blocks\GutenbergFilterAbstract;

class PostTitleFilter extends GutenbergFilterAbstract
{
    protected $filterTag = 'core/post-title';

    public function apply($blockContent, $parsedBlock, $wpBlock)
    {
        if (is_front_page()) {
            return '';
        }
        return $blockContent;
    }
}
