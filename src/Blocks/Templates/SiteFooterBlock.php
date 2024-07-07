<?php

namespace Jankx\Gutenberg\Blocks\Templates;

use Jankx\Blocks\BlockAbstract;

class SiteFooterBlock extends BlockAbstract
{
    protected $type = 'jankx/site-footer';

    public function getBlockJson(): array
    {
        return [
            'render_callback' => [$this, 'render'],
            'attributes' => [
            ]
        ];
    }

    public function render($data, $content)
    {
        return $content;
    }
}
