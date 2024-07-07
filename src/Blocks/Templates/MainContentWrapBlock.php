<?php

namespace Jankx\Gutenberg\Blocks\Templates;

use Jankx\Blocks\BlockAbstract;
use Jankx\SiteLayout\SiteLayout;

class MainContentWrapBlock extends BlockAbstract
{
    protected $type = 'jankx/main-content-wrap';

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
        $siteLayout = SiteLayout::getInstance();
        if ($siteLayout->getCurrentLayout() === SiteLayout::LAYOUT_FULL_WIDTH) {
            return $content;
        }

        ob_start();
        do_action('jankx/template/page/content/before', $this);
        $before = ob_get_clean();

        ob_start();
        do_action('jankx/template/page/content/after');
        $after = ob_get_clean();

        return $before . $content . $after;
    }
}
