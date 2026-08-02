<?php
require_once 'wp-load.php';
header('Content-Type: text/plain');
 = get_option('active_plugins', []);
echo "Plugins activos: " . count() . "
";
foreach ( as ) {
    echo "  - 
";
}
