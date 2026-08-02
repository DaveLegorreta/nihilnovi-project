<?php
header('Content-Type: text/plain; charset=utf-8');
require_once('../../../wp-load.php');

global $wpdb;
$rows = $wpdb->get_results("SELECT ID, post_title, post_name, post_status, post_type, LENGTH(post_content) as content_len FROM {$wpdb->posts} WHERE (post_name LIKE '%tales-de-mileto%' OR post_title LIKE '%Tales de Mileto%') AND post_type IN ('post', 'revision', 'page')");

echo "FOUND " . count($rows) . " POSTS:\n";
foreach($rows as $r) {
    echo "ID: " . $r->ID . " | Title: " . $r->post_title . " | Slug: " . $r->post_name . " | Status: " . $r->post_status . " | Type: " . $r->post_type . " | Length: " . $r->content_len . "\n";
}
