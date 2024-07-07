<?php

namespace Jankx\Gutenberg\Blocks\Templates;

use Jankx\Blocks\BlockAbstract;

class FooterWrapBlock extends BlockAbstract
{
    protected $type = 'jankx/footer-wrap';

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
        if (jankx_get_site_layout() === 'jankx-fullpage' || !jankx_template_has_footer()) {
            return '';
        }

        ob_start();
        do_action('jankx/template/footer/before', $this);
        $before = ob_get_clean();

        ob_start();
        do_action('jankx/template/footer/after');
        $after = ob_get_clean();

        return $before . $content . $after;
    }
}
