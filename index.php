<?php
try {
    if (! class_exists('SkyForge\\Init')) {
        throw new Exception('SkyForge failed to load');
    }

    $skyforge = new \SkyForge\Init;
    echo $skyforge->render();
} catch (Exception $e) {
    echo "<h4>Error: {$e->getMessage()}</h4>";
}
