<?php
header('Content-Type: text/plain; charset=utf-8');
require_once('../../../wp-load.php');

$active_plugins = get_option('active_plugins');
echo "ACTIVE PLUGINS:\n";
foreach($active_plugins as $plugin) {
    echo "- " . $plugin . "\n";
}
