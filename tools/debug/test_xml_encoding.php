<?php
header('Content-Type: text/plain; charset=utf-8');

$xml_path = './nihilnovi_articles_import.xml';
if(!file_exists($xml_path)) {
    die("XML NOT FOUND\n");
}

$xml_content = file_get_contents($xml_path);
echo "XML RAW FILE BYTES LENGTH: " . strlen($xml_content) . "\n";

// Let's find 'espacio de frontera' in raw file content
$idx = strpos($xml_content, 'espacio de frontera');
if ($idx !== false) {
    echo "RAW XML SNIPPET: " . substr($xml_content, $idx, 200) . "\n";
}

$xml = simplexml_load_file($xml_path);
if(!$xml) {
    die("XML INVALID\n");
}

$namespaces = $xml->getNamespaces(true);
$wp = $namespaces['wp'] ?? 'http://wordpress.org/export/1.2/';
$content = $namespaces['content'] ?? 'http://purl.org/rss/1.0/modules/content/';

foreach($xml->channel->item as $item) {
    $wp_data = $item->children($wp);
    $post_name = (string)$wp_data->post_name;
    if ($post_name !== 'tales-de-mileto-agua-hilozoismo-filosofia') {
        continue;
    }
    
    $content_data = $item->children($content);
    $post_content = (string)$content_data->encoded;
    
    echo "PARSED CONTENT UTF-8 CHECK:\n";
    $idx2 = strpos($post_content, 'espacio de frontera');
    if ($idx2 !== false) {
        $snippet = substr($post_content, $idx2, 200);
        echo "SNIPPET: " . $snippet . "\n";
        echo "IS UTF-8: " . (mb_detect_encoding($snippet, 'UTF-8', true) ? 'YES' : 'NO') . "\n";
    }
}
