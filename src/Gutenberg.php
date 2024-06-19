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

    public function boot()
    {
        add_filter('pre_get_block_templates', [$this, 'changeTemplatesPaths'], 9999, 3);
        add_filter('pre_get_block_file_template', [$this, 'get_block_file_template'], 9999, 3);

        // disable Jankx based CSS
        /**
         * @param \Jankx\Template\Page $page
         */
        add_action('jankx/template/render/start', function($page) {
            if ($page->isGutenbergSupport() && ($page->getLoadedLayout() === SiteLayout::LAYOUT_FULL_WIDTH)) {
                add_filter('jankx/layout/based/common-css', '__return_false');
            }
        });
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
