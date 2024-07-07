<?php

namespace Jankx\Gutenberg\Blocks\Templates;

use Jankx\Blocks\BlockAbstract;
use Jankx\Component\Components\Header;

class ContainerBlock extends BlockAbstract
{
    protected $type = 'jankx/container';

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
        return jankx_open_container('c-header-container', 'jankx_component_header_container', false)
            . $content
            . jankx_close_container('c-header-container', 'jankx_component_header_container', false);
    }
}
