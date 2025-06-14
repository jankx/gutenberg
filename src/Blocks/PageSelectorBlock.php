<?php

namespace Jankx\Gutenberg\Blocks;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\Blocks\BlockAbstract;

class PageSelectorBlock extends BlockAbstract
{
    protected $type = 'jankx/page-selector';

    public function render($data, $content)
    {
    }
}
