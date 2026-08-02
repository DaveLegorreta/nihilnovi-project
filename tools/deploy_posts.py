import os
import sys
import json
import ftplib
import urllib.request
from pathlib import Path

def load_credentials():
    paths = [Path('sftp_credentials.json'), Path('../sftp_credentials.json')]
    for p in paths:
        if p.exists():
            with open(p, 'r', encoding='utf-8') as f:
                return json.load(f)
    print("Error: No se encontró sftp_credentials.json")
    sys.exit(1)

def main():
    creds = load_credentials()
    host = creds.get('host')
    user = creds.get('username')
    password = creds.get('password')
    port = creds.get('port', 21)
    
    if '@' in user and port == 2222:
        port = 21

    print("Conectando FTP...")
    try:
        ftp = ftplib.FTP_TLS()
        ftp.connect(host, port, timeout=15)
        ftp.login(user, password)
        ftp.prot_p()
    except Exception as e:
        ftp = ftplib.FTP()
        ftp.connect(host, port, timeout=15)
        ftp.login(user, password)
        ftp.set_pasv(True)
        
    try:
        base_dir = ftp.pwd()
    except:
        base_dir = '/'
        
    try:
        dirs = ftp.nlst()
    except:
        dirs = []
        
    remote_theme_base = ""
    if 'style.css' in dirs or 'functions.php' in dirs:
        remote_theme_base = '/'
    elif 'wp-content' in dirs:
        remote_theme_base = '/wp-content/themes/nihilnovi-theme'
    elif 'public_html' in dirs:
        remote_theme_base = '/public_html/wp-content/themes/nihilnovi-theme'
    else:
        try:
            ftp.cwd('wp-content')
            ftp.cwd('themes')
            dirs_themes = ftp.nlst()
            if 'nihilnovi-theme' in dirs_themes or 'twentytwentyfour' in dirs_themes:
                remote_theme_base = '/wp-content/themes/nihilnovi-theme'
            ftp.cwd(base_dir)
        except:
            pass
            
    if not remote_theme_base:
        if len([d for d in dirs if d not in ('.', '..', '.ftpquota')]) == 0:
            remote_theme_base = '/'
        else:
            print("No se detectó la carpeta del tema.")
            sys.exit(1)
            
    ftp.cwd(base_dir)
    for part in [p for p in remote_theme_base.split('/') if p]:
        ftp.cwd(part)
        
    print(f"Directorio remoto del tema: {ftp.pwd()}")
    
    xml_file = 'data/nihilnovi_articles_import.xml'
    if not os.path.exists(xml_file):
        print("El XML no existe")
        sys.exit(1)
        
    print("Subiendo XML al tema...")
    with open(xml_file, 'rb') as f:
        ftp.storbinary('STOR nihilnovi_articles_import.xml', f)
        
    php_code = r"""<?php
header('Content-Type: text/plain; charset=utf-8');

try {
    require_once('../../../wp-load.php');
} catch (\Throwable $e) {
    die("Fatal Error loading WordPress: " . $e->getMessage() . "\n");
}

function run_antigravity_import() {
    global $wp_rewrite;
    
    // Inicializar el objeto de reescritura si no estuviera completamente cargado
    if (!isset($wp_rewrite)) {
        $wp_rewrite = new WP_Rewrite();
    }
    if (empty($wp_rewrite->rules)) {
        $wp_rewrite->init();
    }
    
    try {
        $xml_path = './nihilnovi_articles_import.xml';
        if(!file_exists($xml_path)) {
            die("Error: No se encontró el XML de importación.\n");
        }

        $xml = simplexml_load_file($xml_path);
        if(!$xml) {
            die("Error: XML inválido o corrupto.\n");
        }

        $namespaces = $xml->getNamespaces(true);
        $wp = $namespaces['wp'] ?? 'http://wordpress.org/export/1.2/';
        $content = $namespaces['content'] ?? 'http://purl.org/rss/1.0/modules/content/';

        $articles_updated = 0;
        $articles_inserted = 0;

        // Desactivar filtros KSES para permitir scripts y estilos
        kses_remove_filters();

        foreach($xml->channel->item as $item) {
            $title = (string)$item->title;
            $wp_data = $item->children($wp);
            $post_date = (string)$wp_data->post_date;
            $post_name = (string)$wp_data->post_name;
            
            $meta_json_str = (string)$item->meta_json;
            $meta_data = $meta_json_str ? json_decode($meta_json_str, true) : [];
            
            $content_data = $item->children($content);
            $post_content = (string)$content_data->encoded;
            
            $cats = [];
            foreach($item->category as $cat) {
                $cats[] = (string)$cat;
            }
            
            // Buscar si el post ya existe por slug (post_name)
            $existing_post = get_page_by_path($post_name, OBJECT, 'post');
            
            if ($existing_post) {
                $post_id = $existing_post->ID;
                wp_update_post([
                    'ID'           => $post_id,
                    'post_title'   => $title,
                    'post_content' => $post_content,
                    'post_status'  => 'publish'
                ]);
                $articles_updated++;
            } else {
                $post_id = wp_insert_post([
                    'post_title'   => $title,
                    'post_content' => $post_content,
                    'post_status'  => 'publish',
                    'post_date'    => $post_date,
                    'post_name'    => $post_name,
                    'post_type'    => 'post'
                ]);
                $articles_inserted++;
            }
            
            if($post_id && !is_wp_error($post_id)) {
                wp_set_object_terms($post_id, $cats, 'category');
                if (is_array($meta_data)) {
                    foreach ($meta_data as $key => $val) {
                        update_post_meta($post_id, $key, $val);
                    }
                }
            }
        }

        // Limpiar cachés de manera segura
        try { wp_cache_flush(); } catch (\Throwable $e) {}
        if (class_exists('LiteSpeed_Cache_API')) {
            try { LiteSpeed_Cache_API::purge_all(); } catch (\Throwable $e) {}
        }
        if (function_exists('w3tc_pgcache_flush')) {
            try { w3tc_pgcache_flush(); } catch (\Throwable $e) {}
        }
        if (function_exists('wp_cache_clean_cache')) {
            try {
                global $file_prefix;
                wp_cache_clean_cache($file_prefix);
            } catch (\Throwable $e) {}
        }
        if (function_exists('sg_cachepress_purge_cache')) {
            try { sg_cachepress_purge_cache(); } catch (\Throwable $e) {}
        }

        echo "SUCCESS: Updated: $articles_updated | Added: $articles_inserted\n";

    } catch (\Throwable $e) {
        echo "FATAL IMPORT ERROR: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine() . "\n";
    }
}

// Ejecutar cuando WordPress y todos los plugins/temas estén completamente cargados
if (did_action('wp_loaded')) {
    run_antigravity_import();
} else {
    add_action('wp_loaded', 'run_antigravity_import');
}
"""
    
    temp_file = 'antigravity_auto_import.php'
    print(f"Subiendo importador autónomo temporal...")
    with open(temp_file, 'w', encoding='utf-8') as f:
        f.write(php_code)
        
    with open(temp_file, 'rb') as f:
        ftp.storbinary(f'STOR {temp_file}', f)
        
    # Trigger execution
    url = f"https://nihilnovi.xyz/wp-content/themes/nihilnovi-theme/{temp_file}"
    print(f"Llamando a {url}")
    try:
        req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'})
        response = urllib.request.urlopen(req, timeout=90)
        print("Respuesta del servidor:")
        resp_bytes = response.read()
        resp_text = resp_bytes.decode('utf-8', errors='replace')
        print(resp_text)
    except Exception as e:
        print(f"Error HTTP durante la ejecución: {e}")
        
    print("Borrando archivos temporales remotos...")
    try:
        ftp.delete(temp_file)
    except Exception as e:
        print(f"No se pudo borrar {temp_file} remoto: {e}")
        
    try:
        ftp.delete('nihilnovi_articles_import.xml')
    except Exception as e:
        print(f"No se pudo borrar XML remoto: {e}")
        
    if os.path.exists(temp_file):
        os.remove(temp_file)
        
    ftp.quit()
    print("Completado.")

if __name__ == '__main__':
    main()
