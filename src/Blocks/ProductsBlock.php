<?php

namespace Jankx\Gutenberg\Blocks;

use Jankx\Blocks\BlockAbstract;
use WooCommerce;

class ProductsBlock extends BlockAbstract
{
    protected $type = 'jankx/products';

    public function isEnabled(): bool
    {
        return class_exists(WooCommerce::class);
    }

    public function render()
    {
    }
}
