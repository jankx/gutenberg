<?php

namespace Jankx\Gutenberg\Blocks;

if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}

use Jankx\Blocks\BlockAbstract;
use Jankx\PostLayout\Layout\Card;
use Jankx\Widget\Renderers\PostsRenderer;

class PostsBlock extends BlockAbstract
{
    protected $type = 'jankx/posts';

    public function render($data, $content)
    {
        $output = '';

        $settings = [];
        $postsRenderer = PostsRenderer::prepare(array(
            'post_type' => array_get($settings, 'post_type', 'post'),
            'post_format'  => array_get($settings, 'post_format', 'standard'),
            'show_excerpt'  => array_get($settings, 'show_post_excerpt', 'no') === 'yes',
            'show_postdate'  => array_get($settings, 'show_postdate', 'no') == 'yes',
            'excerpt_length'  => array_get($settings, 'excerpt_length', 15),
            'categories'  => array_get($settings, 'post_categories', []),
            'tags'  => array_get($settings, 'post_tags', []),
            'show_title'  => array_get($settings, 'show_post_title', true),
            'show_paginate'  => array_get($settings, 'show_paginate', false),
            'thumbnail_position'  => array_get($settings, 'thumbnail_position', 'top'),
            'show_thumbnail'  => array_get($settings, 'show_post_thumbnail', true),
            'thumbnail_size'  => array_get($settings, 'thumbnail_size', 'thumbnail'),
            'last_columns_items'  => array_get($settings, 'last_columns_items', 3),
            'show_dot'  => array_get($settings, 'show_carousel_pagination', 'no') === 'yes',
            'show_nav'  => $this->get_responsive_setting('show_carousel_nav', 'yes') === 'yes',
            'orderby'  => array_get($settings, 'orderby', 'none'),
            'order'  => array_get($settings, 'sort', 'ASC'),
            'specific_data' => array_get($settings, 'specific_data', ''),
            'columns_mobile' => array_get($settings, 'columns_mobile'),
            'columns_tablet' => array_get($settings, 'columns_tablet'),
            'columns'  => $this->get_responsive_setting('columns', 4),
            'posts_per_page'  => $this->get_responsive_setting('posts_per_page', 10),
            'layout'  => $this->get_responsive_setting('post_layout', Card::LAYOUT_NAME),
            'rows'  => $this->get_responsive_setting('rows', 1),
        ));

        if (($widgetTitle = array_get($settings, 'title')) && $postsRenderer->hasContent()) {
            $output .= sprintf(
                '<h3 class="jankx-posts-title">
                   <span>%s</span>
                </h3>',
                $widgetTitle
            );
        }
        return $output . $postsRenderer->render();
    }
}
