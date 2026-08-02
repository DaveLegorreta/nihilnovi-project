<?php
header('Content-Type: text/plain; charset=utf-8');
require_once('../../../wp-load.php');

global $wpdb;
$row = $wpdb->get_row("SELECT * FROM {$wpdb->posts} WHERE post_name = 'tales-de-mileto-agua-hilozoismo-filosofia' AND post_type = 'post'");
if (!$row) {
    echo "POST NOT FOUND IN DATABASE\n";
} else {
    echo "POST ID: " . $row->ID . "\n";
    echo "POST TITLE: " . $row->post_title . "\n";
    echo "CONTENT LENGTH: " . strlen($row->post_content) . "\n";
    echo "CONTAINS CRISOL MAP: " . (strpos($row->post_content, 'crisol-map-container') !== false ? "YES" : "NO") . "\n";
    echo "CONTAINS MAGNET TAG: " . (strpos($row->post_content, 'talesMagnetCanvas') !== false ? "YES" : "NO") . "\n";
    echo "CONTAINS MAGNET WORD: " . (strpos($row->post_content, 'magnetita') !== false ? "YES" : "NO") . "\n";
}
