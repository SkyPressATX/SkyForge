<?php
// Die if not WordPress
if (! defined('ABSPATH')) {
    die();
}

/**
 * Functions for SkyForge Theme
 */

/**
 * Find the Composer Autoloader Path
 *
 * @return String
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
// Require Composer Autoloader
skyforgeRequireComposerAutoloader();

/**
 * Enqueue Stylesheets
 *
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
