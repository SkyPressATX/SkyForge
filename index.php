<?php
try {
    if (! class_exists('SkyForge\\Init')) {
        throw new Exception('SkyForge failed to load');
    }
    wp_header();
    $skyforge = new \SkyForge\Init;
    echo $skyforge->render();
    wp_footer();
} catch (Exception $e) {
    echo "<h4>Error: {$e->getMessage()}</h4>";
}
