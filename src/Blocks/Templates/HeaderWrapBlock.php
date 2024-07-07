<?php

namespace Jankx\Gutenberg\Blocks\Templates;

use Jankx\Blocks\BlockAbstract;

class HeaderWrapBlock extends BlockAbstract
{
    protected $type = 'jankx/header-wrap';

    public function getBlockJson(): array
    {
        return [
            'render_callback' => [$this, 'render'],
            'attributes' => [
                'close_tag' => [
                    'default' => false,
                    'type' => 'boolean'
                ]
            ]
        ];
    }

    public function render($data, $content)
    {
        var_dump($data);
        ob_start();
        if (true) {
            do_action('jankx/template/header/before', $this);

            do_action('jankx_component_before_header_content');
            return ob_get_clean();
        }

        do_action('jankx_component_after_header_content');

        do_action('jankx/template/header/after');
        return ob_get_clean();
    }
}
