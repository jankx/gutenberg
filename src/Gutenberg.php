<?php

namespace Jankx\Gutenberg;

use Jankx\Gutenberg\Traits\CustomWordPressStructure;
use Jankx\SiteLayout\SiteLayout;

class Gutenberg
{
    use CustomWordPressStructure;

    const VERSION = '1.0.0';

    protected static $instance;

    protected function __construct()
    {
        $this->init();

        $this->registerBlocks();
        $this->registerScripts();
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
        // Reset all filter hook for get_block_templates
        remove_all_filters('get_block_templates');
    }

    public static function getRootPath()
    {
        return dirname(JANKX_GUTENBERG_BOOT_FILE);
    }

    public function createCoreGroupWrapper($parsed_block, $parent_block)
    {
        if ($parsed_block['blockName'] === 'core/group' && $parent_block == $parsed_block) {
            ob_start();
            jankx_open_container();
            $container = ob_get_clean();
            array_unshift($parsed_block['innerContent'], $container);
            array_unshift($parsed_block['innerContent'], sprintf('<div %s>', jankx_generate_html_attributes([
                'class' => 'jankx-base'
            ])));

            ob_start();
            jankx_close_container();
            $closeContainer = ob_get_clean();
            array_push($parsed_block['innerContent'], '</div>');
            array_push($parsed_block['innerContent'], $closeContainer);
        }
        return $parsed_block;
    }

    public function boot()
    {
        add_filter('pre_get_block_templates', [$this, 'changeTemplatesPaths'], 9999, 3);
        add_filter('pre_get_block_file_template', [$this, 'get_block_file_template'], 9999, 3);

        // disable Jankx based CSS
        /**
         * @param \Jankx\Template\Page $page
         */
        add_action('jankx/template/render/start', function ($page) {
            if ($page->isGutenbergSupport() && (in_array($page->getLoadedLayout(), [SiteLayout::LAYOUT_FULL_WIDTH]))) {
                add_filter('jankx/layout/based/common-css', '__return_false');

                add_action('jankx_component_before_header_content', [$this, 'openWrapper']);
                add_action('jankx_component_after_header_content', [$this, 'closeWrapper']);

                add_action('jankx/template/footer/before_content', [$this, 'openWrapper']);
                add_action('jankx/template/footer/after_content', [$this, 'closeWrapper']);

                add_filter('jankx_template_footer_widget_wrapper_class', function ($classes) {
                    $classes[] = 'jankx-base';

                    return $classes;
                });

                add_action('jankx/template/page/content/before', function () {
                    add_filter('render_block_data', [$this, 'createCoreGroupWrapper'], 10, 2);
                });

                add_action('jankx/template/page/content/after', function () {
                    remove_filter('render_block_data', [$this, 'createCoreGroupWrapper'], 10);
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
    }

    public function registerBlocks()
    {
    }

    public function registerScripts()
    {
    }
}
