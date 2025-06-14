<?php

namespace Jankx\Gutenberg\Blocks;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\Blocks\BlockAbstract;

class SocialSharingBlock extends BlockAbstract
{
    protected $type = 'jankx/social-sharing';

    public function render($data, $content)
    {
    }
}
