<?php

namespace Jankx\Gutenberg\Blocks;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\Blocks\BlockAbstract;

class ContactForm7Block extends BlockAbstract
{
    protected $type = 'jankx/contact-form-7';

    public function render($data, $content)
    {
    }
}
