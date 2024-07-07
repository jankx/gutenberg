<?php

namespace Jankx\Gutenberg\Blocks\Templates;

use Jankx\Blocks\BlockAbstract;

class SiteHeaderBlock extends BlockAbstract
{
    protected $type = 'jankx/site-header';

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
