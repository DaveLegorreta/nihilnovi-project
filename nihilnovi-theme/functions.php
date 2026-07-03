<?php
/**
 * Nihil Novi — functions.php
 * Configuración principal del tema WordPress
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ─── SETUP ──────────────────────────────────── */
function nihilnovi_setup() {
    load_theme_textdomain( 'nihilnovi', get_template_directory() . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'html5', [
        'search-form', 'comment-form', 'comment-list',
        'gallery', 'caption', 'style', 'script',
    ]);
    add_theme_support( 'custom-logo', [
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    register_nav_menus([
        'primary' => __( 'Navegación principal', 'nihilnovi' ),
        'footer'  => __( 'Pie de página', 'nihilnovi' ),
    ]);
}
add_action( 'after_setup_theme', 'nihilnovi_setup' );

/* ─── ENQUEUE SCRIPTS & STYLES ───────────────── */
function nihilnovi_scripts() {
    // Google Fonts
    wp_enqueue_style(
        'nihilnovi-fonts',
        'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,700;1,400;1,500&family=Source+Serif+4:ital,opsz,wght@0,8..60,300;0,8..60,400;1,8..60,300&family=Inter:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500&display=swap',
        [],
        null
    );

    // Main stylesheet (Dynamic cache busting)
    $css_ver = file_exists( get_stylesheet_directory() . '/style.css' ) 
        ? filemtime( get_stylesheet_directory() . '/style.css' ) 
        : '1.0.0';
    wp_enqueue_style(
        'nihilnovi-style',
        get_stylesheet_uri(),
        [ 'nihilnovi-fonts' ],
        $css_ver
    );

    // Main JS (Dynamic cache busting)
    $js_ver = file_exists( get_template_directory() . '/js/main.js' ) 
        ? filemtime( get_template_directory() . '/js/main.js' ) 
        : '1.0.0';
    wp_enqueue_script(
        'nihilnovi-main',
        get_template_directory_uri() . '/js/main.js',
        [],
        $js_ver,
        true
    );
}
add_action( 'wp_enqueue_scripts', 'nihilnovi_scripts' );

/* ─── EXCERPT LENGTH ─────────────────────────── */
function nihilnovi_excerpt_length( $length ) { return 22; }
add_filter( 'excerpt_length', 'nihilnovi_excerpt_length' );

function nihilnovi_excerpt_more( $more ) { return '&hellip;'; }
add_filter( 'excerpt_more', 'nihilnovi_excerpt_more' );

/* ─── CUSTOM POST META: LESSON CODE ─────────── */
// Agrega un campo "Código de lección" (ej: ECO-01, FIL-02) en el editor
function nihilnovi_add_lesson_meta() {
    // Código de lección
    add_meta_box( 'nihilnovi_lesson_code', 'Código de lección (ej: ECO-01)', 'nihilnovi_lesson_code_callback', 'post', 'side', 'default' );
    // Número de artículo
    add_meta_box( 'nihilnovi_article_num', 'Número de artículo (ej: 00, 01, 02)', 'nihilnovi_article_num_callback', 'post', 'side', 'default' );
    // Tiempo de lectura
    add_meta_box( 'nihilnovi_read_time', 'Tiempo de lectura (ej: 3 min)', 'nihilnovi_read_time_callback', 'post', 'side', 'default' );
    // Subtítulo / lede
    add_meta_box( 'nihilnovi_subtitle', 'Subtítulo o frase de apertura', 'nihilnovi_subtitle_callback', 'post', 'normal', 'high' );
    // Lo esencial (lecciones)
    add_meta_box( 'nihilnovi_essentials', 'Lo esencial — Puntos clave (uno por línea)', 'nihilnovi_essentials_callback', 'post', 'normal', 'default' );
    // Bibliografía
    add_meta_box( 'nihilnovi_bibliography', 'Bibliografía y fuentes (una por línea)', 'nihilnovi_bibliography_callback', 'post', 'normal', 'default' );
}
add_action( 'add_meta_boxes', 'nihilnovi_add_lesson_meta' );

function nihilnovi_lesson_code_callback( $post ) {
    $code = get_post_meta( $post->ID, '_lesson_code', true );
    echo '<input type="text" name="nihilnovi_lesson_code" value="' . esc_attr( $code ) . '" style="width:100%;background:#1a1a2e;border:1px solid #20203a;color:#ede8df;padding:6px 10px;" placeholder="ECO-01" />';
    wp_nonce_field( 'nihilnovi_save_meta', 'nihilnovi_meta_nonce' );
}
function nihilnovi_article_num_callback( $post ) {
    $num = get_post_meta( $post->ID, '_article_num', true );
    echo '<input type="text" name="nihilnovi_article_num" value="' . esc_attr( $num ) . '" style="width:100%;background:#1a1a2e;border:1px solid #20203a;color:#ede8df;padding:6px 10px;" placeholder="00" />';
}
function nihilnovi_read_time_callback( $post ) {
    $val = get_post_meta( $post->ID, '_read_time', true );
    echo '<input type="text" name="nihilnovi_read_time" value="' . esc_attr( $val ) . '" style="width:100%;background:#1a1a2e;border:1px solid #20203a;color:#ede8df;padding:6px 10px;" placeholder="3 min" />';
    echo '<p style="color:#9a9490;font-size:11px;margin-top:4px;">Si se deja vacío, se calcula automáticamente.</p>';
}
function nihilnovi_subtitle_callback( $post ) {
    $val = get_post_meta( $post->ID, '_post_subtitle', true );
    echo '<textarea name="nihilnovi_post_subtitle" rows="2" style="width:100%;background:#1a1a2e;border:1px solid #20203a;color:#ede8df;padding:8px 10px;resize:vertical;" placeholder="Frase o subtítulo que aparece bajo el título principal...">' . esc_textarea( $val ) . '</textarea>';
}
function nihilnovi_essentials_callback( $post ) {
    $val = get_post_meta( $post->ID, '_lesson_essentials', true );
    echo '<p style="color:#9a9490;font-size:11px;margin-bottom:6px;">Escribe un punto por línea. Aparecen en la caja dorada "Lo esencial" dentro de la lección.</p>';
    echo '<textarea name="nihilnovi_lesson_essentials" rows="5" style="width:100%;background:#1a1a2e;border:1px solid #20203a;color:#ede8df;padding:8px 10px;resize:vertical;" placeholder="El mercado no es natural, es una institución.&#10;Los precios son señales, no verdades.&#10;Escasez no significa pobreza.">' . esc_textarea( $val ) . '</textarea>';
}
function nihilnovi_bibliography_callback( $post ) {
    $val = get_post_meta( $post->ID, '_bibliography', true );
    echo '<p style="color:#9a9490;font-size:11px;margin-bottom:6px;">Una referencia por línea. Ej: Mankiw, N.G. (2012). Principles of Economics. Cengage Learning.</p>';
    echo '<textarea name="nihilnovi_bibliography" rows="5" style="width:100%;background:#1a1a2e;border:1px solid #20203a;color:#ede8df;padding:8px 10px;resize:vertical;" placeholder="Un libro o fuente por línea...">' . esc_textarea( $val ) . '</textarea>';
}

function nihilnovi_save_lesson_meta( $post_id ) {
    if ( ! isset( $_POST['nihilnovi_meta_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['nihilnovi_meta_nonce'], 'nihilnovi_save_meta' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    $fields = [
        'nihilnovi_lesson_code'     => '_lesson_code',
        'nihilnovi_article_num'     => '_article_num',
        'nihilnovi_read_time'       => '_read_time',
        'nihilnovi_post_subtitle'   => '_post_subtitle',
        'nihilnovi_lesson_essentials' => '_lesson_essentials',
        'nihilnovi_bibliography'    => '_bibliography',
    ];
    foreach ( $fields as $post_key => $meta_key ) {
        if ( isset( $_POST[ $post_key ] ) ) {
            // Textarea fields use sanitize_textarea_field
            $sanitizer = in_array( $post_key, ['nihilnovi_lesson_essentials','nihilnovi_bibliography','nihilnovi_post_subtitle'] )
                ? 'sanitize_textarea_field' : 'sanitize_text_field';
            update_post_meta( $post_id, $meta_key, $sanitizer( $_POST[ $post_key ] ) );
        }
    }
}
add_action( 'save_post', 'nihilnovi_save_lesson_meta' );

/* ─── HELPER: Get discipline from category ───── */
function nihilnovi_get_disc_class( $post_id ) {
    $cats = get_the_category( $post_id );
    if ( ! $cats ) return 'eco';
    $slug = strtolower( $cats[0]->slug );
    $map = [
        'filosofia'   => 'fil',
        'economia'    => 'eco',
        'matematicas' => 'mat',
        'historia'    => 'his',
        'ciencia'     => 'cie',
        'leccion'     => 'eco',
        'el-viaje'    => 'eco',
    ];
    foreach ( $map as $key => $class ) {
        if ( strpos( $slug, $key ) !== false ) return $class;
    }
    return 'eco';
}

/* ─── HELPER: Estimate reading time ─────────── */
function nihilnovi_estimate_read_time( $content ) {
    $word_count  = str_word_count( strip_tags( $content ) );
    $minutes     = max( 1, (int) ceil( $word_count / 200 ) ); // 200 palabras/min
    return $minutes . ' min';
}

/* ─── WIDGETIZED SIDEBAR (optional) ─────────── */
function nihilnovi_widgets_init() {
    register_sidebar([
        'name'          => __( 'Barra lateral del blog', 'nihilnovi' ),
        'id'            => 'sidebar-1',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ]);
}
add_action( 'widgets_init', 'nihilnovi_widgets_init' );

/* ─── ACF FIELDS ─────────────────────────────── */
add_action( 'acf/init', function() {
    $fields_file = get_template_directory() . '/inc/acf-fields.php';
    if ( file_exists( $fields_file ) ) require_once $fields_file;
});

/* ─── CUSTOMIZER ──────────────────────────────── */
$customizer_file = get_template_directory() . '/inc/customizer.php';
if ( file_exists( $customizer_file ) ) require_once $customizer_file;
