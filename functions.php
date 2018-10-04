<?php
// Die if not WordPress
if (! defined('ABSPATH')) {
    die();
}

/**
 * Functions for SkyForge Theme
 */

/**
 * Require compose autoloader
 *
 * @method skyforgeRequireComposerAutoloader
 *
 * @since 0.1.0
 *
 * @return null
 */
function skyforgeRequireComposerAutoloader()
{
    if (file_exists(ABSPATH . "/wp-content/vendor/autoload.php")) {
        require_once ABSPATH . "/wp-content/vendor/autoload.php";
        return;
    }

    if (file_exists(__DIR__ . '/vendor/autoload.php')) {
        require_once __DIR__ . '/vendor/autoload.php';
        return;
    }
}
skyforgeRequireComposerAutoloader();

/**
 * Enqueue Static Assets
 *
 * @method skyforgeEnqueueStylesheets
 *
 * @since 0.1.0
 *
 * @return none
 */
function skyforgeEnqueueStylesheets()
{
    /*
        Enqueue CSS Files
     */
    wp_register_style('skyforge_bootstrap_css', '//stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css', [], '4.1.3');
    wp_register_style('skyforge_style', get_template_directory_uri() . '/style.css', [], '0.1.0'); // Parent CSS
    if (file_exists(get_stylesheet_directory() . '/style.css')) {
        wp_register_style('skyforge_child_style', get_stylesheet_directory_uri() . '/style.css'); // Child CSS
    }
    wp_enqueue_style('skyforge_bootstrap_css', 'skyforge_bootstrap_css');
    wp_enqueue_style('skyforge_style', 'skyforge_style');
    wp_enqueue_style('skyforge_child_style', 'skyforge_child_style');

    /*
        Enqueue Javascript Files
     */
    wp_register_script('skyforge_bootstrap_js', '//stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js', ['jquery'], '4.1.3', true);
    wp_enqueue_script('skyforge_bootstrap_js', 'skyforge_bootstrap_js');
}
add_action('wp_enqueue_scripts', 'skyforgeEnqueueStylesheets');

/**
 * Register Theme Navigation Menus
 *
 * @method registerThemeMenu
 *
 * @since 0.1.0
 *
 */
function registerThemeMenu()
{
    $args = apply_filters('skyforge_theme_menus', [
        'main'  => 'Main Navigation Menu'
    ]);
    register_nav_menus($args);
}
registerThemeMenu();
