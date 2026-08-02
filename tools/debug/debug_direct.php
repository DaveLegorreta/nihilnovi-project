<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/plain; charset=utf-8');

echo "STEP 1: BEFORE WP-LOAD\n";
require_once('../../../wp-load.php');
echo "STEP 2: AFTER WP-LOAD\n";

$xml_path = './nihilnovi_articles_import.xml';
if(!file_exists($xml_path)) {
    die("XML NOT FOUND\n");
}
echo "STEP 3: XML FILE EXISTS, LOADING...\n";

$xml = simplexml_load_file($xml_path);
if(!$xml) {
    die("XML INVALID\n");
}
echo "STEP 4: XML LOADED SUCCESSFULLY\n";

$namespaces = $xml->getNamespaces(true);
$wp = $namespaces['wp'] ?? 'http://wordpress.org/export/1.2/';
$content = $namespaces['content'] ?? 'http://purl.org/rss/1.0/modules/content/';

foreach($xml->channel->item as $item) {
    $wp_data = $item->children($wp);
    $post_name = (string)$wp_data->post_name;
    if ($post_name !== 'tales-de-mileto-agua-hilozoismo-filosofia') {
        continue;
    }
    echo "STEP 5: FOUND SLUG IN XML\n";
    
    $title = (string)$item->title;
    $content_data = $item->children($content);
    $post_content = (string)$content_data->encoded;
    
    global $wpdb;
    $row = $wpdb->get_row("SELECT ID FROM {$wpdb->posts} WHERE post_name = 'tales-de-mileto-agua-hilozoismo-filosofia' AND post_type = 'post'");
    
    if ($row) {
        $post_id = $row->ID;
        echo "STEP 6: FOUND POST ID via SQL: $post_id\n";
        
        $wpdb->show_errors();
        $res = $wpdb->update(
            $wpdb->posts,
            array('post_content' => $post_content, 'post_title' => $title),
            array('ID' => $post_id)
        );
        
        if ($res === false) {
            echo "STEP 7: SQL UPDATE ERROR: " . $wpdb->last_error . "\n";
        } else {
            echo "STEP 7: SUCCESS: Updated post ID $post_id directly in DB!\n";
            $updated_post = $wpdb->get_row("SELECT post_content FROM {$wpdb->posts} WHERE ID = $post_id");
            echo "Direct DB verification - content length: " . strlen($updated_post->post_content) . "\n";
            
            // Let's clear caching layers
            try { wp_cache_flush(); } catch (\Throwable $e) {}
            if (class_exists('Endurance_Page_Cache')) {
                $epc = new Endurance_Page_Cache();
                $epc->purge_all();
                echo "Purged Endurance Cache.\n";
            }
        }
    } else {
        echo "STEP 6: POST NOT FOUND VIA SQL BY SLUG!\n";
    }
}
echo "STEP 8: SCRIPT FINISHED\n";
