<?php

namespace Jankx\Gutenberg\Blocks;

if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}

use Jankx\Blocks\BlockAbstract;

class PostsTabsBlock extends BlockAbstract
{
    protected $type = 'jankx/posts-tabs';

    public function render($data, $content)
    {
    }
}
