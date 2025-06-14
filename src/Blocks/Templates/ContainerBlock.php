<?php

namespace Jankx\Gutenberg\Blocks\Templates;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

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
        ob_start();
        jankx_open_container('c-header-container', 'jankx_component_header_container', false);
        $start = ob_get_clean();

        ob_start();
        jankx_close_container('c-header-container', 'jankx_component_header_container', false);
        $end = ob_get_clean();

        return $start . $content . $end;
    }
}
