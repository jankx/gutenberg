<?php

namespace Jankx\Gutenberg\Blocks;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\Blocks\BlockAbstract;
use Jankx\WooCommerce\Renderer\ProductsRenderer;
use Jankx\PostLayout\Layout\Card;
use WooCommerce;

class ProductsBlock extends BlockAbstract
{
    protected $type = 'jankx/products';

    public function isEnabled(): bool
    {
        return class_exists(WooCommerce::class) && defined('JANKX_WOOCOMMERCE_FILE_LOADER');
    }

    public function render($data, $content)
    {
        // Check plugin before render
        if (!function_exists('wc_get_loop_prop')) {
            return 'WooCommerce plugin is not activated';
        }
        $settings = [];
        $productsModule = new ProductsRenderer(array(
            'widget_title' => array_get($settings, 'title', 10),
            'categories' => array_get($settings, 'product_categories', array()),
            'tags' => array_get($settings, 'product_tags', array()),
            'layout' => $this->get_responsive_setting('layout', Card::LAYOUT_NAME),
            'limit' => $this->get_responsive_setting('limit', 10),
        ));
        if (($url = array_get($settings, 'readmore_url', ''))) {
            $productsModule->setReadMore($url);
        }

        $productsModule->setLayoutOptions(array(
            'columns_tablet' => array_get($settings, 'columns_tablet', 2),
            'columns_mobile' => array_get($settings, 'columns', 1),
            'columns' => $this->get_responsive_setting('columns', 4),
            'rows' => $this->get_responsive_setting('rows', 1),
            'thumbnail_size'  => array_get($settings, 'thumbnail_size', 'medium'),
        ));

        // Set Woocommerce loop columns
        wc_get_loop_prop('columns', $this->get_responsive_setting('columns', 4));

        // Render the content
        $productsContent = '';
        $widgetTitle = array_get($settings, 'title');
        if ($widgetTitle && $productsContent) {
            $productsContent .= sprintf('<h2 class="products-widget-title"><span>%s</span></h2>', $widgetTitle);
        }
        return $productsContent . $productsModule->render();
    }
}
