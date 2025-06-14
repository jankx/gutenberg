<?php
if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

function jankx_generate_gutenberg_templates($fileName) {
    return [dirname(JANKX_GUTENBERG_BOOT_FILE) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR  .'page.html'];
}

function jankx_get_gutenberg_template_by_name($fileName) {
    $templates = jankx_generate_gutenberg_templates($fileName);
    foreach($templates as $template) {
        if (file_exists($template)) {
            return $template;
        }
    }
    return null;
}

/**
 * Returns the markup for the current template.
 *
 * @access private
 * @since 5.8.0
 *
 * @global string   $_wp_current_template_id
 * @global string   $_wp_current_template_content
 * @global WP_Embed $wp_embed
 * @global WP_Query $wp_query
 *
 * @return string Block template markup.
 */
function jankx_get_the_block_template_html() {
	global $_wp_current_template_id, $_wp_current_template_content, $wp_embed, $wp_query;


	if ( ! $_wp_current_template_content ) {
		if ( is_user_logged_in() ) {
			return '<h1>' . esc_html__( 'No matching template found' ) . '</h1>';
		}
		return;
	}

	$content = $wp_embed->run_shortcode( $_wp_current_template_content );
	$content = $wp_embed->autoembed( $content );
	$content = shortcode_unautop( $content );
	$content = do_shortcode( $content );

	/*
	 * Most block themes omit the `core/query` and `core/post-template` blocks in their singular content templates.
	 * While this technically still works since singular content templates are always for only one post, it results in
	 * the main query loop never being entered which causes bugs in core and the plugin ecosystem.
	 *
	 * The workaround below ensures that the loop is started even for those singular templates. The while loop will by
	 * definition only go through a single iteration, i.e. `do_blocks()` is only called once. Additional safeguard
	 * checks are included to ensure the main query loop has not been tampered with and really only encompasses a
	 * single post.
	 *
	 * Even if the block template contained a `core/query` and `core/post-template` block referencing the main query
	 * loop, it would not cause errors since it would use a cloned instance and go through the same loop of a single
	 * post, within the actual main query loop.
	 *
	 * This special logic should be skipped if the current template does not come from the current theme, in which case
	 * it has been injected by a plugin by hijacking the block template loader mechanism. In that case, entirely custom
	 * logic may be applied which is unpredictable and therefore safer to omit this special handling on.
	 */
	if (
		$_wp_current_template_id &&
		str_starts_with( $_wp_current_template_id, get_stylesheet() . '//' ) &&
		is_singular() &&
		1 === $wp_query->post_count &&
		have_posts()
	) {
		while ( have_posts() ) {
			the_post();
			$content = do_blocks( $content );
		}
	} else {
		$content = do_blocks( $content );
	}

	$content = wptexturize( $content );
	$content = convert_smilies( $content );
	$content = wp_filter_content_tags( $content, 'template' );
	$content = str_replace( ']]>', ']]&gt;', $content );

	// Wrap block template in .wp-site-blocks to allow for specific descendant styles
	// (e.g. `.wp-site-blocks > *`).
	return $content;
}
