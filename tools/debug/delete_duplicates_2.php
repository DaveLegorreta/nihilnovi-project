<?php
header('Content-Type: text/plain; charset=utf-8');
require_once('../../../wp-load.php');

global $wpdb;

$slugs_to_delete = ['la-escuela-milesia-el-agua', 'fil-01-b'];
foreach ($slugs_to_delete as $slug) {
    $rows = $wpdb->get_results($wpdb->prepare("SELECT ID, post_title FROM {$wpdb->posts} WHERE post_name = %s", $slug));
    foreach ($rows as $row) {
        if ($row->ID != 961) {
            echo "Deleting duplicate post: ID {$row->ID} | Title: {$row->post_title} | Slug: {$slug}\n";
            wp_delete_post($row->ID, true);
        }
    }
}
try { wp_cache_flush(); } catch (\Throwable $e) {}
