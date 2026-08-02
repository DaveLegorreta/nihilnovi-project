<?php
/**
 * Polylang URL Configuration — Nihil Novi
 * Configura Polylang para URLs limpias y homepage funcional
 * 
 * USO: Subir a public_html, visitar via navegador, eliminar después.
 */

require_once __DIR__ . '/wp-load.php';
header('Content-Type: text/plain; charset=utf-8');

echo "=== Configuración Polylang URLs ===\n\n";

// Verificar que Polylang esté activo
if (!function_exists('PLL')) {
    echo "ERROR: Polylang no está activo\n";
    exit;
}

// 1. Configurar URL modifications
$options = get_option('polylang', []);
if (!is_array($options)) {
    $options = [];
}

// URL con prefijo de idioma: /en/, /it/
$options['force_lang'] = 1;

// Ocultar el prefijo del idioma por defecto (español)
$options['hide_default'] = 1;

// Redireccionar al idioma del navegador
$options['browser'] = 1;

update_option('polylang', $options);
echo "1. URLs configuradas: /en/ y /it/ (español sin prefijo)\n";

// 2. Configurar el homepage para todos los idiomas
// Obtener la página de inicio
$front_page_id = get_option('page_on_front');
if ($front_page_id) {
    echo "2. Página de inicio encontrada (ID: $front_page_id)\n";
    
    // Vincular la página de inicio a todos los idiomas
    $languages = PLL()->model->get_languages_list();
    foreach ($languages as $lang) {
        $lang_slug = $lang->slug;
        
        // Si es español, es la versión original
        if ($lang_slug === 'es') {
            PLL()->model->post->set_language($front_page_id, $lang);
            echo "   - Página vinculada a español (original)\n";
        } else {
            // Para otros idiomas, crear una versión traducida
            // o vincular la misma página (fallback)
            echo "   - $lang_slug: necesita versión traducida\n";
        }
    }
} else {
    echo "2. No hay página de inicio configurada (usa latest posts)\n";
}

// 3. Configurar posts existentes al idioma español
$posts = get_posts(['post_type' => 'post', 'posts_per_page' => -1, 'fields' => 'ids']);
$count = 0;
foreach ($posts as $post_id) {
    $lang = PLL()->model->post->get_language($post_id);
    if (!$lang) {
        PLL()->model->post->set_language($post_id, 'es');
        $count++;
    }
}
echo "3. $count posts vinculados al español\n";

// 4. Configurar páginas existentes al idioma español
$pages = get_posts(['post_type' => 'page', 'posts_per_page' => -1, 'fields' => 'ids']);
$count_pages = 0;
foreach ($pages as $page_id) {
    $lang = PLL()->model->post->get_language($page_id);
    if (!$lang) {
        PLL()->model->post->set_language($page_id, 'es');
        $count_pages++;
    }
}
echo "4. $count_pages páginas vinculadas al español\n";

// 5. Configurar categorías al idioma español
$categories = get_categories(['hide_empty' => false, 'fields' => 'ids']);
$count_cats = 0;
foreach ($categories as $cat_id) {
    $lang = PLL()->model->term->get_language($cat_id);
    if (!$lang) {
        PLL()->model->term->set_language($cat_id, 'es');
        $count_cats++;
    }
}
echo "5. $count_cats categorías vinculadas al español\n";

echo "\n=== Configuración completada ===\n";
echo "Ahora el homepage debería funcionar en todos los idiomas.\n";
echo "Para contenido en EN/IT, crea versiones traducidas desde el editor de WP.\n";
echo "\n⚠️  IMPORTANTE: Eliminar este archivo (polylang-config.php) inmediatamente.\n";
