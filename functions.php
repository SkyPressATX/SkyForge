<?php
// Die if not WordPress
if (! defined('ABSPATH')) {
    die();
}

/**
 * Functions for SkyStache Theme
 */

/**
 * Find the Composer Autoloader Path
 *
 * @return String
 */
function requireComposerAutoloader()
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
requireComposerAutoloader();
