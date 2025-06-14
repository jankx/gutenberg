<?php

namespace Jankx\Gutenberg\Blocks;

if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}

use Jankx\Blocks\BlockAbstract;

class SocialSharingBlock extends BlockAbstract
{
    protected $type = 'jankx/social-sharing';

    public function render($data, $content)
    {
    }
}
