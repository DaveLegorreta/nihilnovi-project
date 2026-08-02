<?php
header('Content-Type: text/plain; charset=utf-8');
require_once('../../../wp-load.php');

echo "Attempting to purge Endurance_Page_Cache...\n";
if (class_exists('Endurance_Page_Cache')) {
    try {
        $epc = new Endurance_Page_Cache();
        $epc->purge_all();
        echo "SUCCESS: Purged all cache via Endurance_Page_Cache!\n";
    } catch (\Throwable $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
    }
} else {
    echo "Endurance_Page_Cache class not found.\n";
}

try {
    wp_cache_flush();
    echo "SUCCESS: Flushed WordPress object cache.\n";
} catch (\Throwable $e) {}
