<?php
use Jankx\Gutenberg\Gutenberg;
if (!function_exists('locate_block_template')) {
    return;
}

if (!defined('JANKX_GUTENBERG_BOOT_FILE')) {
    define('JANKX_GUTENBERG_BOOT_FILE', __FILE__);
}

class Jankx_Gutenberg_Bootstraper {
    public function __construct()
    {
        add_theme_support('block-templates');

        // load helpers
        require_once dirname(__FILE__) . '/helpers.php';
    }

    public function bootstrap() {
        $gutenberg = Gutenberg::getInstance();

        $gutenberg->boot();

        add_action('after_setup_theme', [$gutenberg, 'setup']);
    }
}


$bootstraper = new Jankx_Gutenberg_Bootstraper();
$bootstraper->bootstrap();

