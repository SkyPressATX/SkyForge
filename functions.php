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
    // Parent
    wp_enqueue_style('skyforge-style', get_template_directory_uri() . '/style.css');
    // Child
    if (file_exists(get_stylesheet_directory() . '/style.css')) {
        wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css');
    }
}
add_action('wp_enqueue_scripts', 'skyforgeEnqueueStylesheets');
