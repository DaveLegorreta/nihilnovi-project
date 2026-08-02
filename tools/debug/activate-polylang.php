<?php
require_once 'wp-load.php';
header('Content-Type: text/plain');

echo "=== Activación Polylang ===

";

 = 'polylang/polylang.php';
 = get_option('active_plugins', []);

if (in_array(, )) {
    echo "Polylang ya está activo
";
} else {
    activate_plugin();
    echo "Polylang activado
";
}

// Verificar función PLL
if (function_exists('PLL')) {
    echo "Función PLL disponible
";
     = PLL()->model;
     = ->get_languages_list();
    echo "Idiomas configurados: " . count() . "
";
    foreach ( as ) {
        echo "  - " . ->name . " (" . ->locale . ")
";
    }
} else {
    echo "Función PLL NO disponible. Plugin no cargado correctamente.
";
}

echo "
=== Fin ===
";
