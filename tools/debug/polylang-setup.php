<?php
/**
 * Polylang Setup Script — Nihil Novi
 * Verifica estado de Polylang y configura idiomas ES/EN/IT
 * 
 * USO: Subir a raíz de WP, visitar via navegador, eliminar después.
 * SEGURIDAD: Este script debe eliminarse inmediatamente después de usar.
 */

// Cargar WordPress
require_once __DIR__ . '/wp-load.php';

header('Content-Type: text/plain; charset=utf-8');
echo "=== Polylang Setup — Nihil Novi ===\n\n";

// 1. Verificar si Polylang está instalado
$plugin_file = 'polylang/polylang.php';
$is_installed = file_exists(WP_PLUGIN_DIR . '/' . $plugin_file);
echo "1. Polylang instalado: " . ($is_installed ? "SÍ" : "NO") . "\n";

if (!$is_installed) {
    echo "ERROR: Polylang no está instalado. Instalar primero.\n";
    exit;
}

// 2. Verificar si está activo
$active_plugins = get_option('active_plugins', []);
$is_active = in_array($plugin_file, $active_plugins);
echo "2. Polylang activo: " . ($is_active ? "SÍ" : "NO") . "\n";

// 3. Activar si no está activo
if (!$is_active) {
    echo "3. Activando Polylang...\n";
    activate_plugin($plugin_file);
    echo "   ✓ Polylang activado\n";
} else {
    echo "3. Polylang ya estaba activo\n";
}

// 4. Verificar/crear idiomas
function ensure_language($slug, $locale, $name, $rtl = false) {
    // Verificar si el idioma ya existe
    $existing = get_term_by('slug', $slug, 'language');
    
    if ($existing) {
        echo "   ✓ Idioma '$name' ($slug) ya existe (ID: {$existing->term_id})\n";
        return $existing->term_id;
    }
    
    // Crear el idioma usando la API de Polylang
    if (!function_exists('PLL')) {
        echo "   ✗ ERROR: Función PLL() no disponible. ¿Polylang activo?\n";
        return false;
    }
    
    $model = PLL()->model;
    
    // Datos del idioma según formato de Polylang
    $language_data = [
        'slug'           => $slug,
        'name'           => $name,
        'locale'         => $locale,
        'rtl'            => $rtl ? 1 : 0,
        'term_group'     => 0,
    ];
    
    // Insertar idioma
    $term_id = $model->add_language($language_data);
    
    if ($term_id && !is_wp_error($term_id)) {
        echo "   ✓ Idioma '$name' ($slug) creado (ID: $term_id)\n";
        return $term_id;
    } else {
        echo "   ✗ ERROR creando idioma '$name': ";
        if (is_wp_error($term_id)) {
            echo $term_id->get_error_message();
        } else {
            echo "Respuesta desconocida";
        }
        echo "\n";
        return false;
    }
}

echo "\n4. Configurando idiomas...\n";

// Verificar que PLL esté disponible
if (!function_exists('PLL')) {
    echo "   ✗ ERROR: Polylang no está completamente cargado.\n";
    echo "   Posible solución: Recargar esta página o activar Polylang manualmente.\n";
    exit;
}

// Crear idiomas
$es_id = ensure_language('es', 'es_ES', 'Español');
$en_id = ensure_language('en', 'en_US', 'English');
$it_id = ensure_language('it', 'it_IT', 'Italiano');

// 5. Configurar idioma por defecto (español)
if ($es_id) {
    $default_lang = get_option('polylang');
    if (is_array($default_lang)) {
        $default_lang['default_lang'] = 'es';
        update_option('polylang', $default_lang);
        echo "\n5. Idioma por defecto configurado: Español (es)\n";
    }
}

// 6. Configurar URLs
$options = get_option('polylang');
if (is_array($options)) {
    $options['force_lang'] = 1;  // /es/, /en/, /it/ en URLs
    update_option('polylang', $options);
    echo "6. URLs configuradas: /es/, /en/, /it/\n";
}

// 7. Resumen
echo "\n=== RESUMEN ===\n";
echo "Polylang: ACTIVO\n";
echo "Idiomas configurados:\n";
$languages = PLL()->model->get_languages_list();
foreach ($languages as $lang) {
    $is_default = ($lang->slug === 'es') ? ' [DEFAULT]' : '';
    echo "  - {$lang->name} ({$lang->locale}){$is_default}\n";
}

echo "\n✓ Configuración completada.\n";
echo "⚠️  IMPORTANTE: Eliminar este archivo (polylang-setup.php) inmediatamente.\n";
