<?php

namespace Jankx\Gutenberg;

use Jankx\Gutenberg\Traits\CustomWordPressStructure;

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
    }

    public static function getRootPath()
    {
        return dirname(JANKX_GUTENBERG_BOOT_FILE);
    }

    public function boot()
    {
        add_filter('pre_get_block_templates', [$this, 'changeTemplatesPaths'], 9999, 3);
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
        add_action('enqueue_block_editor_assets', function () {
            wp_enqueue_script(
                'my-custom-block',
                jankx_get_path_url(dirname(JANKX_GUTENBERG_BOOT_FILE)) . '/build/index.js',
                array( 'wp-blocks', 'wp-element', 'wp-editor' ),
                static::VERSION
            );
        });
    }
}
