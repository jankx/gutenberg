<?php

namespace Jankx\Gutenberg\Blocks\Templates;

if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}

use Jankx\Blocks\BlockAbstract;

class HeaderWrapBlock extends BlockAbstract
{
    protected $type = 'jankx/header-wrap';

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
        do_action('jankx/template/header/before', $this);
        do_action('jankx/component/header/content/before');
        $before = ob_get_clean();

        ob_start();
        do_action('jankx/component/header/content/after');
        do_action('jankx/template/header/after');
        $after = ob_get_clean();

        return $before . $content . $after;
    }
}
