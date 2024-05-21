<?php

namespace Jankx\Gutenberg;

class Gutenberg
{
    protected static $instance;

    protected function __construct() {
    }

    public static function getInstance() {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function init()
    {
        // add_filter('pre_get_block_file_template', [$this, 'changeTemplatesPaths'])
    }
}
