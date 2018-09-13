<?php
get_header();
try {
    if (! class_exists('SkyForge\\Init')) {
        throw new Exception('SkyForge failed to load');
    }
    global $wp;
    $skyforge = new \SkyForge\Init;
    echo $skyforge->render(add_query_arg(array(), $wp->request));
} catch (Exception $e) {
    echo "<h4>Error: {$e->getMessage()}</h4>";
}
get_footer();
