<?php
try {
    if (! class_exists('SkyForge\\Init')) {
        throw new Exception('SkyForge failed to load');
    }
    get_header();
    $skyforge = new \SkyForge\Init;
    echo $skyforge->render();
    get_footer();
} catch (Exception $e) {
    echo "<h4>Error: {$e->getMessage()}</h4>";
}
