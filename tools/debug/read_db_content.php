<?php
header('Content-Type: text/plain; charset=utf-8');
require_once('../../../wp-load.php');

global $wpdb;
$row = $wpdb->get_row("SELECT post_content FROM {$wpdb->posts} WHERE ID = 961");
if ($row) {
    $content = $row->post_content;
    $idx = strpos($content, 'espacio de frontera');
    if ($idx !== false) {
        echo "DB SNIPPET:\n" . substr($content, $idx, 400) . "\n";
    } else {
        echo "Phrase not found in DB content\n";
    }
} else {
    echo "Post 961 not found\n";
}
