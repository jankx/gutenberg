<?php

namespace Jankx\Gutenberg\Blocks;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\Blocks\BlockAbstract;

class LinkTabsBlock extends BlockAbstract
{
    protected $type = 'jankx/link-tabs';

    public function render($data, $content)
    {
    }
}
