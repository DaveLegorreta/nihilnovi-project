<?php
/**
 * Nihil Novi — functions.php
 * Configuración principal del tema WordPress
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ─── SETUP ──────────────────────────────────── */
// ─── THEME CONFIGURATION ─────────────────────────────────────
// Registers core theme support like navigation menus, title tags, and post thumbnails.
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

/* ─── ASSET VERSION HELPER ───────────────────── */
// ponytail: cache-busting helper, avoids repeating file_exists+filemtime blocks
function nihilnovi_asset_ver( $path ) {
    $file = get_template_directory() . $path;
    return file_exists( $file ) ? filemtime( $file ) : '1.0.0';
}

/* ─── ENQUEUE SCRIPTS & STYLES ───────────────── */
// ─── ENQUEUE ASSETS (SCRIPTS & STYLES) ────────────────────────
// Enqueues Google Fonts, CSS stylesheets, and the GSAP/ScrollTrigger libraries.
function nihilnovi_scripts() {
    // Google Fonts
    wp_enqueue_style(
        'nihilnovi-fonts',
        'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,700;1,400;1,500&family=Source+Serif+4:ital,opsz,wght@0,8..60,300;0,8..60,400;1,8..60,300&family=Inter:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500&display=swap',
        [],
        null
    );

    // Main stylesheet (Dynamic cache busting)
    wp_enqueue_style(
        'nihilnovi-style',
        get_stylesheet_uri(),
        [ 'nihilnovi-fonts' ],
        nihilnovi_asset_ver( '/style.css' )
    );

    // GSAP
    wp_enqueue_script(
        'gsap',
        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js',
        [],
        '3.12.5',
        true
    );
    wp_enqueue_script(
        'gsap-scrolltrigger',
        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js',
        [ 'gsap' ],
        '3.12.5',
        true
    );

    // Main JS (Dynamic cache busting)
    wp_enqueue_script(
        'nihilnovi-main',
        get_template_directory_uri() . '/js/main.js',
        [ 'gsap', 'gsap-scrolltrigger' ],
        nihilnovi_asset_ver( '/js/main.js' ),
        true
    );
}
add_action( 'wp_enqueue_scripts', 'nihilnovi_scripts' );

/* ─── EXCERPT LENGTH ─────────────────────────── */
// ─── EXCERPT CONFIGURATION ───────────────────────────────────
// Defines the length limit for automatically generated post excerpts.
function nihilnovi_excerpt_length( $length ) { return 22; }
add_filter( 'excerpt_length', 'nihilnovi_excerpt_length' );

function nihilnovi_excerpt_more( $more ) { return '&hellip;'; }
add_filter( 'excerpt_more', 'nihilnovi_excerpt_more' );

/* ─── META BOX HELPER ────────────────────────── */
// ponytail: single callback for all meta fields, avoids 6 near-identical functions
function nihilnovi_meta_field_callback( $post, $args ) {
    $field = $args['args'];
    $val   = get_post_meta( $post->ID, $field['meta_key'], true );
    $name  = esc_attr( $field['post_key'] );
    $style = 'width:100%;background:#1a1a2e;border:1px solid #20203a;color:#ede8df;';

    if ( $field['type'] === 'textarea' ) {
        $rows = $field['rows'] ?? 5;
        $ph   = isset( $field['placeholder'] ) ? esc_attr( $field['placeholder'] ) : '';
        if ( ! empty( $field['help'] ) ) {
            echo '<p style="color:#9a9490;font-size:11px;margin-bottom:6px;">' . esc_html( $field['help'] ) . '</p>';
        }
        echo '<textarea name="' . $name . '" rows="' . $rows . '" style="' . $style . 'padding:8px 10px;resize:vertical;" placeholder="' . $ph . '">' . esc_textarea( $val ) . '</textarea>';
    } else {
        $ph = isset( $field['placeholder'] ) ? esc_attr( $field['placeholder'] ) : '';
        echo '<input type="text" name="' . $name . '" value="' . esc_attr( $val ) . '" style="' . $style . 'padding:6px 10px;" placeholder="' . $ph . '" />';
        if ( ! empty( $field['help'] ) ) {
            echo '<p style="color:#9a9490;font-size:11px;margin-top:4px;">' . esc_html( $field['help'] ) . '</p>';
        }
    }
    // Nonce solo en el primer campo
    if ( ! empty( $field['nonce'] ) ) {
        wp_nonce_field( 'nihilnovi_save_meta', 'nihilnovi_meta_nonce' );
    }
}

/* ─── CUSTOM POST META: LESSON CODE ─────────── */
// Agrega campos personalizados en el editor
function nihilnovi_add_lesson_meta() {
    $fields = [
        ['id' => 'nihilnovi_lesson_code', 'title' => __( 'Código de lección (ej: ECO-01)', 'nihilnovi' ), 'meta' => '_lesson_code', 'placeholder' => 'ECO-01', 'nonce' => true ],
        ['id' => 'nihilnovi_article_num', 'title' => __( 'Número de artículo (ej: 00, 01, 02)', 'nihilnovi' ), 'meta' => '_article_num', 'placeholder' => '00' ],
        ['id' => 'nihilnovi_read_time', 'title' => __( 'Tiempo de lectura (ej: 3 min)', 'nihilnovi' ), 'meta' => '_read_time', 'placeholder' => '3 min', 'help' => __( 'Si se deja vacío, se calcula automáticamente.', 'nihilnovi' ) ],
        ['id' => 'nihilnovi_subtitle', 'title' => __( 'Subtítulo o frase de apertura', 'nihilnovi' ), 'meta' => '_post_subtitle', 'type' => 'textarea', 'rows' => 2, 'placeholder' => __( 'Frase o subtítulo que aparece bajo el título principal...', 'nihilnovi' ), 'context' => 'normal', 'priority' => 'high' ],
        ['id' => 'nihilnovi_essentials', 'title' => __( 'Lo esencial — Puntos clave (uno por línea)', 'nihilnovi' ), 'meta' => '_lesson_essentials', 'type' => 'textarea', 'rows' => 5, 'help' => __( 'Escribe un punto por línea. Aparecen en la caja dorada "Lo esencial" dentro de la lección.', 'nihilnovi' ), 'placeholder' => "El mercado no es natural, es una institución.\nLos precios son señales, no verdades.\nEscasez no significa pobreza.", 'context' => 'normal' ],
        ['id' => 'nihilnovi_bibliography', 'title' => __( 'Bibliografía y fuentes (una por línea)', 'nihilnovi' ), 'meta' => '_bibliography', 'type' => 'textarea', 'rows' => 5, 'help' => __( 'Una referencia por línea. Ej: Mankiw, N.G. (2012). Principles of Economics. Cengage Learning.', 'nihilnovi' ), 'placeholder' => __( 'Un libro o fuente por línea...', 'nihilnovi' ), 'context' => 'normal' ],
        ['id' => 'nihilnovi_premium', 'title' => __( 'Contenido premium', 'nihilnovi' ), 'meta' => '_nihilnovi_is_premium', 'type' => 'checkbox', 'help' => __( 'Preparación para paywall. No afecta la visualización pública todavía.', 'nihilnovi' ) ],
    ];

    foreach ( $fields as $f ) {
        $type = $f['type'] ?? 'text';
        if ( $type === 'checkbox' ) {
            add_meta_box( $f['id'], $f['title'], 'nihilnovi_premium_callback', 'post', $f['context'] ?? 'side', $f['priority'] ?? 'default' );
            continue;
        }
        $callback = 'nihilnovi_meta_field_callback';
        $context  = $f['context'] ?? 'side';
        $priority = $f['priority'] ?? 'default';
        add_meta_box( $f['id'], $f['title'], $callback, 'post', $context, $priority, [
            'meta_key'    => $f['meta'],
            'post_key'    => $f['id'],
            'type'        => $type,
            'rows'        => $f['rows'] ?? 2,
            'placeholder' => $f['placeholder'] ?? '',
            'help'        => $f['help'] ?? '',
            'nonce'       => ! empty( $f['nonce'] ),
        ] );
    }
}
add_action( 'add_meta_boxes', 'nihilnovi_add_lesson_meta' );

function nihilnovi_premium_callback( $post ) {
    $is_premium = get_post_meta( $post->ID, '_nihilnovi_is_premium', true );
    echo '<label style="display:flex;align-items:center;gap:8px;cursor:pointer;">';
    echo '<input type="checkbox" name="nihilnovi_is_premium" value="1" ' . checked( $is_premium, '1', false ) . ' />';
    echo '<span>' . esc_html__( 'Marcar como contenido premium', 'nihilnovi' ) . '</span>';
    echo '</label>';
    echo '<p style="color:#9a9490;font-size:11px;margin-top:6px;">' . esc_html__( 'Preparación para paywall. No afecta la visualización pública todavía.', 'nihilnovi' ) . '</p>';
}

function nihilnovi_save_lesson_meta( $post_id ) {
    if ( ! isset( $_POST['nihilnovi_meta_nonce'] ) ) return;
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nihilnovi_meta_nonce'] ) ), 'nihilnovi_save_meta' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    $fields = [
        'nihilnovi_lesson_code'     => '_lesson_code',
        'nihilnovi_article_num'     => '_article_num',
        'nihilnovi_read_time'       => '_read_time',
        'nihilnovi_post_subtitle'   => '_post_subtitle',
        'nihilnovi_lesson_essentials' => '_lesson_essentials',
        'nihilnovi_bibliography'    => '_bibliography',
    ];
    // Campo premium (checkbox)
    if ( isset( $_POST['nihilnovi_is_premium'] ) ) {
        update_post_meta( $post_id, '_nihilnovi_is_premium', '1' );
    } else {
        delete_post_meta( $post_id, '_nihilnovi_is_premium' );
    }
    foreach ( $fields as $post_key => $meta_key ) {
        if ( ! isset( $_POST[ $post_key ] ) ) {
            continue;
        }
        // Textarea fields use sanitize_textarea_field.
        $value     = wp_unslash( $_POST[ $post_key ] );
        $sanitizer = in_array( $post_key, ['nihilnovi_lesson_essentials','nihilnovi_bibliography','nihilnovi_post_subtitle'], true )
            ? 'sanitize_textarea_field' : 'sanitize_text_field';
        update_post_meta( $post_id, $meta_key, $sanitizer( $value ) );
    }
}
add_action( 'save_post', 'nihilnovi_save_lesson_meta' );

/* ─── HELPER: Get discipline from category ───── */
// ponytail: direct array lookup instead of foreach+strpos
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
        'lecciones'   => 'eco',
        'el-viaje'    => 'eco',
    ];
    return $map[ $slug ] ?? 'eco';
}

/* ─── HELPER: Split text into lines ─────────── */
// ponytail: reused in single.php for essentials and bibliography
function nihilnovi_lines( $text ) {
    return array_filter( array_map( 'trim', explode( "\n", $text ) ) );
}

/* ─── HELPER: Estimate reading time ─────────── */
function nihilnovi_estimate_read_time( $content ) {
    $word_count  = str_word_count( strip_tags( $content ) );
    $minutes     = max( 1, (int) ceil( $word_count / 200 ) ); // 200 palabras/min
    return $minutes . ' min';
}

/* ─── ACF FIELDS ─────────────────────────────── */
add_action( 'acf/init', function() {
    $fields_file = get_template_directory() . '/inc/acf-fields.php';
    if ( file_exists( $fields_file ) ) require_once $fields_file;
});

/* ─── CUSTOMIZER ──────────────────────────────── */
$customizer_file = get_template_directory() . '/inc/customizer.php';
if ( file_exists( $customizer_file ) ) require_once $customizer_file;

/* ─── FALLBACK NAVIGATION ───────────────────────────────────── */
// Default navigation menu displayed when no menu is assigned in WP Admin.
function nihilnovi_fallback_nav() {
    echo '<ul class="nav-menu" role="menubar">';
    echo '<li><a href="' . esc_url( home_url( '/' ) ) . '#disciplinas">' . esc_html__( 'Disciplinas', 'nihilnovi' ) . '</a></li>';
    echo '<li><a href="' . esc_url( home_url( '/el-viaje' ) ) . '">' . esc_html__( 'El Viaje', 'nihilnovi' ) . '</a></li>';
    echo '<li><a href="' . esc_url( home_url( '/categoria/lecciones' ) ) . '">' . esc_html__( 'Lecciones', 'nihilnovi' ) . '</a></li>';
    echo '<li><a href="' . esc_url( home_url( '/blog' ) ) . '">' . esc_html__( 'Blog', 'nihilnovi' ) . '</a></li>';
    echo '<li><a href="' . esc_url( home_url( '/biblioteca' ) ) . '">' . esc_html__( 'Biblioteca', 'nihilnovi' ) . '</a></li>';
    echo '<li><a href="' . esc_url( home_url( '/sobre' ) ) . '">' . esc_html__( 'Sobre', 'nihilnovi' ) . '</a></li>';
    echo '</ul>';
}
