<?php

namespace Jankx\Gutenberg\Blocks\Templates;

use Jankx\Blocks\BlockAbstract;

class SiteFooterContentBlock extends BlockAbstract
{
    protected $type = 'jankx/footer-content';

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

        do_action('jankx/template/footer/widgets');

        do_action('jankx/template/footer/content/before');

        $start = ob_get_clean();

        $footer = jankx_component('footer');

        ob_start();
        do_action('jankx/template/footer/content/after');
        $end = ob_get_clean();

        return $start . $footer . $content . $end;
    }
}
