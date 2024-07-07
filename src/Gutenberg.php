<?php

namespace Jankx\Gutenberg;

use Jankx;
use Jankx\Blocks\BlockInterface;
use Jankx\Gutenberg\Blocks\ContactForm7Block;
use Jankx\Gutenberg\Blocks\LinkTabsBlock;
use Jankx\Gutenberg\Blocks\PageSelectorBlock;
use Jankx\Gutenberg\Blocks\PostsBlock;
use Jankx\Gutenberg\Blocks\PostsTabsBlock;
use Jankx\Gutenberg\Blocks\ProductsBlock;
use Jankx\Gutenberg\Blocks\SocialSharingBlock;
use Jankx\Gutenberg\Blocks\Templates\ContainerBlock;
use Jankx\Gutenberg\Blocks\Templates\FooterWrapBlock;
use Jankx\Gutenberg\Blocks\Templates\HeaderWrapBlock;
use Jankx\Gutenberg\Blocks\Templates\SiteFooterBlock;
use Jankx\Gutenberg\Blocks\Templates\SiteFooterContentBlock;
use Jankx\Gutenberg\Blocks\Templates\SiteHeaderBlock;
use Jankx\Gutenberg\Blocks\Templates\SiteHeaderContentBlock;
use Jankx\Gutenberg\Traits\CustomWordPressStructure;

class Gutenberg
{
    use CustomWordPressStructure;

    const VERSION = '1.0.0';

    protected static $instance;

    protected $packageInfo = [];

    protected $blocks = [];

    protected function __construct()
    {
        $this->init();
    }

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    protected function init()
    {
        $this->packageInfo = json_decode(
            file_get_contents(dirname(JANKX_GUTENBERG_BOOT_FILE) . DIRECTORY_SEPARATOR . 'composer.json'),
            true
        );
        // Reset all filter hook for get_block_templates
        remove_all_filters('get_block_templates');

        add_action('init', [$this, 'registerBlocks']);
    }

    public static function getRootPath()
    {
        return dirname(JANKX_GUTENBERG_BOOT_FILE);
    }

    public function boot()
    {
        add_filter('pre_get_block_templates', [$this, 'changeTemplatesPaths'], 9999, 3);
        add_filter('pre_get_block_file_template', [$this, 'get_block_file_template'], 9999, 3);

        // disable Jankx based CSS
        /**
         * @param \Jankx\Template\Page $page
         */
        add_action('jankx/template/page/render/start', function ($page) {
            if ($page->isGutenbergSupport()) {
                add_filter('jankx/layout/based/common-css', '__return_false');
                add_filter('jankx/layout/main_content/classes', function ($classes) {
                    array_push($classes, 'list-none-style');

                    return $classes;
                });
            }
        });
    }

    public function openWrapper()
    {
        printf('<div %s>', jankx_generate_html_attributes(['class' => 'jankx-base']));
    }

    public function closeWrapper()
    {
        echo '</div>';
    }

    /**
     * Run setup via after_setup_theme
     */
    public function setup()
    {
        $this->blocks = apply_filters(
            'jankx/gutenberg/blocks',
            [
                PostsBlock::class,
                ContactForm7Block::class,
                LinkTabsBlock::class,
                PageSelectorBlock::class,
                PostsTabsBlock::class,
                SocialSharingBlock::class,
                ProductsBlock::class,


                // template blocks
                SiteHeaderBlock::class,
                HeaderWrapBlock::class,
                ContainerBlock::class,
                SiteHeaderContentBlock::class,

                SiteFooterBlock::class,
                FooterWrapBlock::class,
                SiteFooterContentBlock::class,
            ]
        );
        add_action('admin_enqueue_scripts', [$this, 'registerScripts']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueScripts'], 20);
        add_filter('block_categories_all', [$this, 'registerCategory']);
        add_filter('render_block_core/template-part', function ($content, $parsed_block) {
            if (strpos($content, '<div class="wp-block-template-part">') !== false) {
                $content = str_replace('<div class="wp-block-template-part">', '', $content);
                $content = substr($content, 0, -6);
            }
            return $content;
        }, 10, 2);
    }

    public function registerBlocks()
    {
        $jankx = Jankx::getInstance();
        foreach ($this->blocks as $blockClass) {
            if (!class_exists($blockClass, true) && is_a($blockClass, BlockInterface::class, true)) {
                continue;
            }
            /**
             * @var \Jankx\Blocks\BlockInterface
             */
            $block = new $blockClass();
            $block->setBlockBaseDirectory($jankx->get('GUTENBERG_ROOT') . DIRECTORY_SEPARATOR . 'build');
            $jankx->instance($blockClass, $block);

            // Register Jankx Blocks
            $block->register();
        }
    }

    protected function registerJsonBlocks()
    {
        $jankx = Jankx::getInstance();
        $blocks = [];
        foreach ($this->blocks as $blockClass) {
            /**
             * @var \Jankx\Blocks\BlockInterface
             */
            $block = $jankx->get($blockClass);
            if (!is_a($block, BlockInterface::class)) {
                continue;
            }
            $blocks[$block->getType()] = $block->getBlockJson();
        }
        return $blocks;
    }

    public function registerScripts()
    {
        wp_register_script(
            'jankx-gutenberg',
            jankx_get_path_url(Gutenberg::getRootPath()) . '/build/jankx-gutenberg.js',
            ['react',  'react-dom', 'wp-block-editor', 'wp-blocks', 'wp-i18n'],
            array_get($this->packageInfo, 'version')
        );

        wp_localize_script('jankx-gutenberg', 'jankx_blocks', $this->registerJsonBlocks());
    }

    public function enqueueScripts()
    {
        wp_enqueue_script('jankx-gutenberg');
    }

    public function registerCategory($categories)
    {
        $categories[] = array(
            'slug' => 'jankx',
            'title' => apply_filters(
                'jankx/gutenberg/category/name',
                Jankx::FRAMEWORK_NAME
            )
        );
        return $categories;
    }
}
