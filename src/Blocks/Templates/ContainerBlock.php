<?php

namespace Jankx\Gutenberg\Blocks\Templates;

use Jankx\Blocks\BlockAbstract;
use Jankx\Component\Components\Header;

class ContainerBlock extends BlockAbstract
{
    protected $type = 'jankx/container';

    public function render($data, $content)
    {
        if (true) {
            return jankx_open_container('c-header-container', 'jankx_component_header_container', false);
        }
        return jankx_close_container('c-header-container', 'jankx_component_header_container', false);
    }
}
