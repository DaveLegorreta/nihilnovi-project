<?php
header('Content-Type: text/plain; charset=utf-8');
require_once('../../../wp-load.php');

echo "CLASSES FOUND RELATED TO CACHE OR HOSTINGER:\n";
$classes = get_declared_classes();
foreach($classes as $c) {
    if (stripos($c, 'cache') !== false || stripos($c, 'hostinger') !== false || stripos($c, 'purge') !== false) {
        echo "- Class: " . $c . "\n";
        $methods = get_class_methods($c);
        foreach($methods as $m) {
            if (stripos($m, 'purge') !== false || stripos($m, 'clean') !== false || stripos($m, 'clear') !== false || stripos($m, 'flush') !== false) {
                echo "    -> Method: " . $m . "\n";
            }
        }
    }
}

echo "\nFUNCTIONS FOUND RELATED TO CACHE OR HOSTINGER:\n";
$funcs = get_defined_functions();
foreach($funcs['user'] as $f) {
    if (stripos($f, 'cache') !== false || stripos($f, 'hostinger') !== false || stripos($f, 'purge') !== false) {
        echo "- Function: " . $f . "\n";
    }
}
