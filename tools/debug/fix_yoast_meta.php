<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/plain; charset=utf-8');
require_once('../../../wp-load.php');

global $wpdb;

$post_id = 961;
$clean_metadesc = 'Análisis profundo sobre Tales de Mileto. Descubre cómo su postulación del agua como arché y su hilozoísmo inauguraron la ciencia física y la filosofía.';
$clean_focuskw = 'Tales de Mileto';
$clean_seo_title = 'Tales de Mileto - El Agua y el Hilozoísmo | Nihil Novi';

// Update Yoast meta directly
$metas = [
    '_yoast_wpseo_metadesc' => $clean_metadesc,
    '_yoast_wpseo_focuskw' => $clean_focuskw,
    '_yoast_wpseo_title' => $clean_seo_title,
];

foreach ($metas as $key => $val) {
    $existing = $wpdb->get_var($wpdb->prepare(
        "SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = %s",
        $post_id, $key
    ));
    
    if ($existing !== null) {
        $res = $wpdb->update(
            $wpdb->postmeta,
            ['meta_value' => $val],
            ['post_id' => $post_id, 'meta_key' => $key]
        );
        echo "Updated $key: " . ($res !== false ? "OK" : "FAIL: " . $wpdb->last_error) . "\n";
    } else {
        $wpdb->insert($wpdb->postmeta, [
            'post_id' => $post_id,
            'meta_key' => $key,
            'meta_value' => $val
        ]);
        echo "Inserted $key\n";
    }
}

// Also fix the theme's custom meta description if present
$theme_desc = $wpdb->get_var($wpdb->prepare(
    "SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = '_nihilnovi_meta_description'",
    $post_id
));
if ($theme_desc !== null) {
    $wpdb->update(
        $wpdb->postmeta,
        ['meta_value' => $clean_metadesc],
        ['post_id' => $post_id, 'meta_key' => '_nihilnovi_meta_description']
    );
    echo "Updated _nihilnovi_meta_description\n";
}

// Clear all caches
try { wp_cache_flush(); } catch (\Throwable $e) {}
if (class_exists('Endurance_Page_Cache')) {
    $epc = new Endurance_Page_Cache();
    $epc->purge_all();
    echo "Purged Endurance Cache.\n";
}

echo "DONE\n";
