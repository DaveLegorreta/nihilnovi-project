<?php
header('Content-Type: text/plain; charset=utf-8');
require_once('../../../wp-load.php');

$xml_path = './nihilnovi_articles_import.xml';
if(!file_exists($xml_path)) {
    die("XML NOT FOUND\n");
}

$xml = simplexml_load_file($xml_path);
if(!$xml) {
    die("XML INVALID\n");
}

$namespaces = $xml->getNamespaces(true);
$wp = $namespaces['wp'] ?? 'http://wordpress.org/export/1.2/';
$content = $namespaces['content'] ?? 'http://purl.org/rss/1.0/modules/content/';

kses_remove_filters();

foreach($xml->channel->item as $item) {
    $wp_data = $item->children($wp);
    $post_name = (string)$wp_data->post_name;
    if ($post_name !== 'tales-de-mileto-agua-hilozoismo-filosofia') {
        continue;
    }
    
    $title = (string)$item->title;
    $content_data = $item->children($content);
    $post_content = (string)$content_data->encoded;
    
    echo "Attempting to update post via wp_update_post...\n";
    echo "Content length to upload: " . strlen($post_content) . "\n";
    
    $existing_post = get_page_by_path($post_name, OBJECT, 'post');
    if ($existing_post) {
        $post_id = $existing_post->ID;
        echo "Found post ID: $post_id\n";
        
        global $wpdb;
        $wpdb->show_errors();
        
        $res = wp_update_post([
            'ID'           => $post_id,
            'post_title'   => $title,
            'post_content' => $post_content,
            'post_status'  => 'publish'
        ], true); // true returns WP_Error on failure
        
        if (is_wp_error($res)) {
            echo "ERROR: " . $res->get_error_message() . "\n";
        } else {
            echo "SUCCESS: Updated post ID: $res\n";
            $updated_post = get_post($post_id);
            echo "Verification - Post in DB length: " . strlen($updated_post->post_content) . "\n";
            echo "Verification - DB has crisol map: " . (strpos($updated_post->post_content, 'crisol-map-container') !== false ? 'YES' : 'NO') . "\n";
        }
        
        if (!empty($wpdb->last_error)) {
            echo "WPDB ERROR: " . $wpdb->last_error . "\n";
        }
    } else {
        echo "POST NOT FOUND FOR SLUG\n";
    }
}
