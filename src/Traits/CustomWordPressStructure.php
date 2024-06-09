<?php

namespace Jankx\Gutenberg\Traits;

use Jankx;
use Jankx\Gutenberg\Gutenberg;
use Jankx\TemplateAndLayout;
use WP_Block_Template;
use WP_Query;

trait CustomWordPressStructure
{
    /**
    * Retrieves the template files from the theme.
    *
    * @since 5.9.0
    * @since 6.3.0 Added the `$query` parameter.
    * @access private
    *
    * @param string $template_type Template type. Either 'wp_template' or 'wp_template_part'.
    * @param array  $query {
    *     Arguments to retrieve templates. Optional, empty by default.
    *
    *     @type string[] $slug__in     List of slugs to include.
    *     @type string[] $slug__not_in List of slugs to skip.
    *     @type string   $area         A 'wp_template_part_area' taxonomy value to filter by (for 'wp_template_part' template type only).
    *     @type string   $post_type    Post type to get the templates for.
    * }
    *
    * @return array Template
    */
    public static function get_block_templates_files($template_type, $query = array())
    {
        if ('wp_template' !== $template_type && 'wp_template_part' !== $template_type) {
            return null;
        }

       // Prepare metadata from $query.
        $slugs_to_include = isset($query['slug__in']) ? $query['slug__in'] : array();
        $slugs_to_skip    = isset($query['slug__not_in']) ? $query['slug__not_in'] : array();
        $area             = isset($query['area']) ? $query['area'] : null;
        $post_type        = isset($query['post_type']) ? $query['post_type'] : '';

        $stylesheet = get_stylesheet();
        $template   = get_template();
        $themes     = array(
            $stylesheet => get_stylesheet_directory(),
        );
       // Add the parent theme if it's not the same as the current theme.
        if ($stylesheet !== $template) {
            $themes[ $template ] = get_template_directory();
        }
        $themes[Jankx::ENGINE_ID] = Gutenberg::getRootPath();

        $template_files = array();
        foreach (apply_filters('jankx/gutenberg/directories', $themes) as $theme_slug => $theme_dir) {
            $template_base_paths  = get_block_theme_folders($theme_slug);
            $theme_template_files = _get_block_templates_paths($theme_dir . '/' . $template_base_paths[ $template_type ]);
            foreach ($theme_template_files as $template_file) {
                $template_base_path = $template_base_paths[ $template_type ];
                $template_slug      = substr(
                    $template_file,
                    // Starting position of slug.
                    strpos($template_file, $template_base_path . DIRECTORY_SEPARATOR) + 1 + strlen($template_base_path),
                    // Subtract ending '.html'.
                    -5
                );

                // Skip this item if its slug doesn't match any of the slugs to include.
                if (! empty($slugs_to_include) && ! in_array($template_slug, $slugs_to_include, true)) {
                    continue;
                }

                // Skip this item if its slug matches any of the slugs to skip.
                if (! empty($slugs_to_skip) && in_array($template_slug, $slugs_to_skip, true)) {
                    continue;
                }

                /*
                * The child theme items (stylesheet) are processed before the parent theme's (template).
                * If a child theme defines a template, prevent the parent template from being added to the list as well.
                */
                if (isset($template_files[ $template_slug ])) {
                    continue;
                }

                $new_template_item = array(
                   'slug'  => $template_slug,
                   'path'  => $template_file,
                   'theme' => $theme_slug,
                   'type'  => $template_type,
                );


                if ('wp_template_part' === $template_type) {
                    $candidate = _add_block_template_part_area_info($new_template_item);
                    if (! isset($area) || ( isset($area) && $area === $candidate['area'] )) {
                        $template_files[ $template_slug ] = $candidate;
                    }
                }

                if ('wp_template' === $template_type) {
                    $candidate = _add_block_template_info($new_template_item);

                    if (
                        ! $post_type ||
                        ( $post_type && isset($candidate['postTypes']) && in_array($post_type, $candidate['postTypes'], true) )
                    ) {
                        $template_files[ $template_slug ] = $candidate;
                    }
                }
            }
        }


        return array_values($template_files);
    }


   /**
 * Builds a unified template object based on a theme file.
 *
 * @since 5.9.0
 * @since 6.3.0 Added `modified` property to template objects.
 * @access private
 *
 * @param array  $template_file Theme file.
 * @param string $template_type Template type. Either 'wp_template' or 'wp_template_part'.
 * @return WP_Block_Template Template.
 */
    public static function build_block_template_result_from_file($template_file, $template_type)
    {
        $default_template_types   = get_default_block_template_types();
        $theme                    = get_stylesheet();

        $template                 = new WP_Block_Template();
        $template->id             = $theme . '//' . $template_file['slug'];
        $template->theme          = $theme;
        $template->content        = file_get_contents($template_file['path']);
        $template->slug           = $template_file['slug'];
        $template->source         = 'theme';
        $template->type           = $template_type;
        $template->title          = ! empty($template_file['title']) ? $template_file['title'] : $template_file['slug'];
        $template->status         = 'publish';
        $template->has_theme_file = true;
        $template->is_custom      = true;
        $template->modified       = null;

        if ('wp_template' === $template_type && isset($default_template_types[ $template_file['slug'] ])) {
            $template->description = $default_template_types[ $template_file['slug'] ]['description'];
            $template->title       = $default_template_types[ $template_file['slug'] ]['title'];
            $template->is_custom   = false;
        }

        if ('wp_template' === $template_type && isset($template_file['postTypes'])) {
            $template->post_types = $template_file['postTypes'];
        }

        if ('wp_template_part' === $template_type && isset($template_file['area'])) {
            $template->area = $template_file['area'];
        }

        $before_block_visitor = '_inject_theme_attribute_in_template_part_block';
        $after_block_visitor  = null;
        $hooked_blocks        = get_hooked_blocks();
        if (! empty($hooked_blocks) || has_filter('hooked_block_types')) {
            $before_block_visitor = make_before_block_visitor($hooked_blocks, $template);
            $after_block_visitor  = make_after_block_visitor($hooked_blocks, $template);
        }
        $blocks            = parse_blocks($template->content);
        $template->content = traverse_and_serialize_blocks($blocks, $before_block_visitor, $after_block_visitor);

        return $template;
    }

    protected function checkTemplatesIsExistsInTheme($templates)
    {
        $pre = apply_filters('jankx/gutenberg/precheck-templates', null);
        if (!is_null($pre)) {
            return $pre;
        }

        $templateFiles = [];
        $templateFiles = array_map(function ($template) {
            $files = [
                get_stylesheet_directory() . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $template . '.html',
                get_stylesheet_directory() . DIRECTORY_SEPARATOR . 'block-templates' . DIRECTORY_SEPARATOR . $template . '.html'
            ];

            if (is_child_theme()) {
                get_template_directory() . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $template . '.html';
                get_template_directory() . DIRECTORY_SEPARATOR . 'block-templates' . DIRECTORY_SEPARATOR . $template . '.html';
            }
            return $files;
        }, $templates);
        foreach ($templateFiles as $files) {
            foreach ($files as $f) {
                if (file_exists($f)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function changeTemplatesPaths($block_template, $query, $template_type)
    {
        $templateAndLayout = TemplateAndLayout::get_instance();
        $pageType = $templateAndLayout->loadPageType();
        $method = 'get_' . $pageType . '_templates';

        if (!method_exists($templateAndLayout, $method)) {
            return $block_template;
        }
        $templates = call_user_func([$templateAndLayout, $method]);
        if ($this->checkTemplatesIsExistsInTheme($templates)) {
            return $block_template;
        }

        $post_type     = isset($query['post_type']) ? $query['post_type'] : '';
        $wp_query_args = array(
            'post_status'         => array( 'auto-draft', 'draft', 'publish' ),
            'post_type'           => $template_type,
            'posts_per_page'      => -1,
            'no_found_rows'       => true,
            'lazy_load_term_meta' => false,
            'tax_query'           => array(
                array(
                    'taxonomy' => 'wp_theme',
                    'field'    => 'name',
                    'terms'    => get_stylesheet(),
                ),
            ),
        );

        if ('wp_template_part' === $template_type && isset($query['area'])) {
            $wp_query_args['tax_query'][]           = array(
                'taxonomy' => 'wp_template_part_area',
                'field'    => 'name',
                'terms'    => $query['area'],
            );
            $wp_query_args['tax_query']['relation'] = 'AND';
        }

        if (! empty($query['slug__in'])) {
            $wp_query_args['post_name__in']  = $query['slug__in'];
            $wp_query_args['posts_per_page'] = count(array_unique($query['slug__in']));
        }

        // This is only needed for the regular templates/template parts post type listing and editor.
        if (isset($query['wp_id'])) {
            $wp_query_args['p'] = $query['wp_id'];
        } else {
            $wp_query_args['post_status'] = 'publish';
        }

        $template_query = new WP_Query($wp_query_args);
        $query_result   = array();
        foreach ($template_query->posts as $post) {
            $template = _build_block_template_result_from_post($post);

            if (is_wp_error($template)) {
                continue;
            }

            if ($post_type && ! $template->is_custom) {
                continue;
            }

            if (
                $post_type &&
                isset($template->post_types) &&
                ! in_array($post_type, $template->post_types, true)
            ) {
                continue;
            }

            $query_result[] = $template;
        }

        if (! isset($query['wp_id'])) {
            /*
            * If the query has found some use templates, those have priority
            * over the theme-provided ones, so we skip querying and building them.
            */
            $query['slug__not_in'] = wp_list_pluck($query_result, 'slug');
            $template_files        = static::get_block_templates_files($template_type, $query);

            foreach ($template_files as $template_file) {
                $query_result[] = static::build_block_template_result_from_file($template_file, $template_type);
            }
        }

        // This is case handle by WordPress
        if ($query_result == null) {
            return $block_template;
        }

        /**
         * Filters the array of queried block templates array after they've been fetched.
         *
         * @since 5.9.0
         *
         * @param WP_Block_Template[] $query_result Array of found block templates.
         * @param array               $query {
         *     Arguments to retrieve templates. All arguments are optional.
         *
         *     @type string[] $slug__in  List of slugs to include.
         *     @type int      $wp_id     Post ID of customized template.
         *     @type string   $area      A 'wp_template_part_area' taxonomy value to filter by (for 'wp_template_part' template type only).
         *     @type string   $post_type Post type to get the templates for.
         * }
         * @param string              $template_type wp_template or wp_template_part.
         */

        return apply_filters('get_block_templates', $query_result, $query, $template_type);
    }
}
