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
    $css_ver = file_exists( get_stylesheet_directory() . '/style.css' ) 
        ? filemtime( get_stylesheet_directory() . '/style.css' ) 
        : '1.0.0';
    wp_enqueue_style(
        'nihilnovi-style',
        get_stylesheet_uri(),
        [ 'nihilnovi-fonts' ],
        $css_ver
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
    $js_ver = file_exists( get_template_directory() . '/js/main.js' ) 
        ? filemtime( get_template_directory() . '/js/main.js' ) 
        : '1.0.0';
    wp_enqueue_script(
        'nihilnovi-main',
        get_template_directory_uri() . '/js/main.js',
        [ 'gsap', 'gsap-scrolltrigger' ],
        $js_ver,
        true
    );

    // Presocratics Widgets JS (Dynamic cache busting)
    $widgets_ver = file_exists( get_template_directory() . '/js/widgets-presocraticos.js' ) 
        ? filemtime( get_template_directory() . '/js/widgets-presocraticos.js' ) 
        : '1.0.0';
    wp_enqueue_script(
        'nihilnovi-widgets-presocraticos',
        get_template_directory_uri() . '/js/widgets-presocraticos.js',
        [],
        $widgets_ver,
        true
    );
}
add_action( 'wp_enqueue_scripts', 'nihilnovi_scripts' );

/* ─── ASSET ATTRIBUTES: INTEGRITY & CROSSORIGIN ─ */
// Añade SRI (Subresource Integrity) y crossorigin a scripts de terceros cargados desde CDN.
function nihilnovi_script_loader_attributes( $tag, $handle, $src ) {
    $attributes = [
        'gsap'               => [
            'integrity'   => 'sha512-7eHRwcbYkK4d9g/6tD/mhkf++eoTHwpNM9woBxtPUBWm67zeAfFC+HrdoE2GanKeocly/VxeLvIqwvCdk7qScg==',
            'crossorigin' => 'anonymous',
        ],
        'gsap-scrolltrigger' => [
            'integrity'   => 'sha512-onMTRKJBKz8M1TnqqDuGBlowlH0ohFzMXYRNebz+yOcc5TQr/zAKsthzhuv0hiyUKEiQEQXEynnXCvNTOk50dg==',
            'crossorigin' => 'anonymous',
        ],
    ];

    if ( ! isset( $attributes[ $handle ] ) ) {
        return $tag;
    }

    $attrs = $attributes[ $handle ];
    $tag   = str_replace( ' src=', ' integrity="' . esc_attr( $attrs['integrity'] ) . '" crossorigin="' . esc_attr( $attrs['crossorigin'] ) . '" src=', $tag );

    return $tag;
}
add_filter( 'script_loader_tag', 'nihilnovi_script_loader_attributes', 10, 3 );

/* ─── EXCERPT LENGTH ─────────────────────────── */
// ─── EXCERPT CONFIGURATION ───────────────────────────────────
// Defines the length limit for automatically generated post excerpts.
function nihilnovi_excerpt_length( $length ) { return 22; }
add_filter( 'excerpt_length', 'nihilnovi_excerpt_length' );

function nihilnovi_excerpt_more( $more ) { return '&hellip;'; }
add_filter( 'excerpt_more', 'nihilnovi_excerpt_more' );

/* ─── CUSTOM POST META: LESSON CODE ─────────── */
// Agrega un campo "Código de lección" (ej: ECO-01, FIL-02) en el editor
function nihilnovi_add_lesson_meta() {
    // Código de lección
    add_meta_box( 'nihilnovi_lesson_code', __( 'Código de lección (ej: ECO-01)', 'nihilnovi' ), 'nihilnovi_lesson_code_callback', 'post', 'side', 'default' );
    // Número de artículo
    add_meta_box( 'nihilnovi_article_num', __( 'Número de artículo (ej: 00, 01, 02)', 'nihilnovi' ), 'nihilnovi_article_num_callback', 'post', 'side', 'default' );
    // Tiempo de lectura
    add_meta_box( 'nihilnovi_read_time', __( 'Tiempo de lectura (ej: 3 min)', 'nihilnovi' ), 'nihilnovi_read_time_callback', 'post', 'side', 'default' );
    // Subtítulo / lede
    add_meta_box( 'nihilnovi_subtitle', __( 'Subtítulo o frase de apertura', 'nihilnovi' ), 'nihilnovi_subtitle_callback', 'post', 'normal', 'high' );
    // Lo esencial (lecciones)
    add_meta_box( 'nihilnovi_essentials', __( 'Lo esencial — Puntos clave (uno por línea)', 'nihilnovi' ), 'nihilnovi_essentials_callback', 'post', 'normal', 'default' );
    // Bibliografía
    add_meta_box( 'nihilnovi_bibliography', __( 'Bibliografía y fuentes (una por línea)', 'nihilnovi' ), 'nihilnovi_bibliography_callback', 'post', 'normal', 'default' );
    // Contenido premium (preparación para futuro paywall)
    add_meta_box( 'nihilnovi_premium', __( 'Contenido premium', 'nihilnovi' ), 'nihilnovi_premium_callback', 'post', 'side', 'default' );
}
add_action( 'add_meta_boxes', 'nihilnovi_add_lesson_meta' );

function nihilnovi_lesson_code_callback( $post ) {
    $code = get_post_meta( $post->ID, '_lesson_code', true );
    echo '<input type="text" name="nihilnovi_lesson_code" value="' . esc_attr( $code ) . '" style="width:100%;background:#1a1a2e;border:1px solid #20203a;color:#ede8df;padding:6px 10px;" placeholder="' . esc_attr__( 'ECO-01', 'nihilnovi' ) . '" />';
    wp_nonce_field( 'nihilnovi_save_meta', 'nihilnovi_meta_nonce' );
}
function nihilnovi_article_num_callback( $post ) {
    $num = get_post_meta( $post->ID, '_article_num', true );
    echo '<input type="text" name="nihilnovi_article_num" value="' . esc_attr( $num ) . '" style="width:100%;background:#1a1a2e;border:1px solid #20203a;color:#ede8df;padding:6px 10px;" placeholder="' . esc_attr__( '00', 'nihilnovi' ) . '" />';
}
function nihilnovi_read_time_callback( $post ) {
    $val = get_post_meta( $post->ID, '_read_time', true );
    echo '<input type="text" name="nihilnovi_read_time" value="' . esc_attr( $val ) . '" style="width:100%;background:#1a1a2e;border:1px solid #20203a;color:#ede8df;padding:6px 10px;" placeholder="' . esc_attr__( '3 min', 'nihilnovi' ) . '" />';
    echo '<p style="color:#9a9490;font-size:11px;margin-top:4px;">' . esc_html__( 'Si se deja vacío, se calcula automáticamente.', 'nihilnovi' ) . '</p>';
}
function nihilnovi_subtitle_callback( $post ) {
    $val = get_post_meta( $post->ID, '_post_subtitle', true );
    echo '<textarea name="nihilnovi_post_subtitle" rows="2" style="width:100%;background:#1a1a2e;border:1px solid #20203a;color:#ede8df;padding:8px 10px;resize:vertical;" placeholder="' . esc_attr__( 'Frase o subtítulo que aparece bajo el título principal...', 'nihilnovi' ) . '">' . esc_textarea( $val ) . '</textarea>';
}
function nihilnovi_essentials_callback( $post ) {
    $val = get_post_meta( $post->ID, '_lesson_essentials', true );
    echo '<p style="color:#9a9490;font-size:11px;margin-bottom:6px;">' . esc_html__( 'Escribe un punto por línea. Aparecen en la caja dorada "Lo esencial" dentro de la lección.', 'nihilnovi' ) . '</p>';
    echo '<textarea name="nihilnovi_lesson_essentials" rows="5" style="width:100%;background:#1a1a2e;border:1px solid #20203a;color:#ede8df;padding:8px 10px;resize:vertical;" placeholder="' . esc_attr__( "El mercado no es natural, es una institución.\nLos precios son señales, no verdades.\nEscasez no significa pobreza.", 'nihilnovi' ) . '">' . esc_textarea( $val ) . '</textarea>';
}
function nihilnovi_bibliography_callback( $post ) {
    $val = get_post_meta( $post->ID, '_bibliography', true );
    echo '<p style="color:#9a9490;font-size:11px;margin-bottom:6px;">' . esc_html__( 'Una referencia por línea. Ej: Mankiw, N.G. (2012). Principles of Economics. Cengage Learning.', 'nihilnovi' ) . '</p>';
    echo '<textarea name="nihilnovi_bibliography" rows="5" style="width:100%;background:#1a1a2e;border:1px solid #20203a;color:#ede8df;padding:8px 10px;resize:vertical;" placeholder="' . esc_attr__( 'Un libro o fuente por línea...', 'nihilnovi' ) . '">' . esc_textarea( $val ) . '</textarea>';
}
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

/**
 * Helper to get a discipline's URL dynamically.
 * Priority: ACF Custom field URL > WordPress Category Archive URL > Hardcoded Fallback.
 */
function nihilnovi_get_discipline_url( $slug, $acf_field ) {
    $url = function_exists( 'get_field' ) ? get_field( $acf_field ) : '';
    if ( ! empty( $url ) ) {
        return $url;
    }
    $cat = get_term_by( 'slug', $slug, 'category' );
    return $cat ? get_term_link( $cat ) : home_url( '/categoria/' . $slug );
}
