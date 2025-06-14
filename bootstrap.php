<?php
if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}
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
        add_theme_support('block-template-parts');

        // load helpers
        require_once dirname(__FILE__) . '/helpers.php';
    }

    public function bootstrap() {
        $gutenberg = Gutenberg::getInstance();

        $gutenberg->boot();

        add_action('after_setup_theme', [$gutenberg, 'setup']);

        Jankx::getInstance()->instance('GUTENBERG_ROOT', dirname(JANKX_GUTENBERG_BOOT_FILE));
    }
}


$bootstraper = new Jankx_Gutenberg_Bootstraper();
$bootstraper->bootstrap();

