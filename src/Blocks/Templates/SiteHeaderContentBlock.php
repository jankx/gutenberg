<?php

namespace Jankx\Gutenberg\Blocks\Templates;

if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}

use Jankx\Blocks\BlockAbstract;

class SiteHeaderContentBlock extends BlockAbstract
{
    protected $type = 'jankx/header-content';

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
        if (jankx_get_site_layout() !== 'jankx-fullpage') {
            /**
             *
             */
            $headerComponent = jankx_component(
                'header',
                apply_filters('jankx_component_header_props', array(
                    'preset' => 'default'
                )),
                false
            );
            ob_start();
            do_action('jankx_component_before_header');
            $before = ob_get_clean();

            $data = $headerComponent->buildComponentData();

            ob_start();
            do_action('jankx_component_after_header');
            $after = ob_get_clean();

            return $before . array_get($data, 'content') . $content . $after;
        }
    }
}
