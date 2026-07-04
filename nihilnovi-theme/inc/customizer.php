<?php
/**
 * Nihil Novi — Customizer
 *
 * Registers the theme's Customizer panel, sections, settings, and controls.
 * Also hooks CSS variable output to wp_head and live-preview JS to
 * customize_preview_init.
 *
 * @package NihilNovi
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* ==========================================================================
 * 1. REGISTER PANEL, SECTIONS, SETTINGS & CONTROLS
 * ========================================================================== */

/**
 * Bind Customizer settings to the WP Customizer API.
 *
 * @param WP_Customize_Manager $wp_customize The Customizer manager instance.
 */
function nihilnovi_customize_register( $wp_customize ) {

    /* ------------------------------------------------------------------
     * MAIN PANEL
     * ------------------------------------------------------------------ */
    $wp_customize->add_panel( 'nihilnovi_panel', [
        'title'    => __( 'Nihil Novi — Configuración', 'nihilnovi' ),
        'priority' => 130,
    ] );

    /* ------------------------------------------------------------------
     * SECTION: COLORES
     * ------------------------------------------------------------------ */
    $wp_customize->add_section( 'nihilnovi_colors', [
        'title' => __( 'Nihil Novi — Colores', 'nihilnovi' ),
        'panel' => 'nihilnovi_panel',
    ] );

    /* Color de acento (dorado) */
    $wp_customize->add_setting( 'nihilnovi_color_gold', [
        'default'           => '#C4973A',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control(
        new WP_Customize_Color_Control( $wp_customize, 'nihilnovi_color_gold', [
            'label'   => __( 'Color de acento (dorado)', 'nihilnovi' ),
            'section' => 'nihilnovi_colors',
        ] )
    );

    /* Color de fondo */
    $wp_customize->add_setting( 'nihilnovi_color_bg', [
        'default'           => '#09090F',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control(
        new WP_Customize_Color_Control( $wp_customize, 'nihilnovi_color_bg', [
            'label'   => __( 'Color de fondo', 'nihilnovi' ),
            'section' => 'nihilnovi_colors',
        ] )
    );

    /* Color de texto principal */
    $wp_customize->add_setting( 'nihilnovi_color_text', [
        'default'           => '#EDE8DF',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control(
        new WP_Customize_Color_Control( $wp_customize, 'nihilnovi_color_text', [
            'label'   => __( 'Color de texto principal', 'nihilnovi' ),
            'section' => 'nihilnovi_colors',
        ] )
    );

    /* ------------------------------------------------------------------
     * SECTION: REDES SOCIALES
     * ------------------------------------------------------------------ */
    $wp_customize->add_section( 'nihilnovi_social', [
        'title' => __( 'Nihil Novi — Redes Sociales', 'nihilnovi' ),
        'panel' => 'nihilnovi_panel',
    ] );

    $social_fields = [
        'nihilnovi_social_twitter'   => __( 'Twitter / X', 'nihilnovi' ),
        'nihilnovi_social_instagram' => __( 'Instagram', 'nihilnovi' ),
        'nihilnovi_social_linkedin'  => __( 'LinkedIn', 'nihilnovi' ),
        'nihilnovi_social_youtube'   => __( 'YouTube / NotebookLM', 'nihilnovi' ),
    ];

    foreach ( $social_fields as $setting_id => $label ) {
        $wp_customize->add_setting( $setting_id, [
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ] );
        $wp_customize->add_control( $setting_id, [
            'label'   => $label,
            'section' => 'nihilnovi_social',
            'type'    => 'url',
        ] );
    }

    /* ------------------------------------------------------------------
     * SECTION: FOOTER
     * ------------------------------------------------------------------ */
    $wp_customize->add_section( 'nihilnovi_footer', [
        'title' => __( 'Nihil Novi — Footer', 'nihilnovi' ),
        'panel' => 'nihilnovi_panel',
    ] );

    $wp_customize->add_setting( 'nihilnovi_footer_copy', [
        'default'           => __( '© 2026 David Legorreta · nihilnovi.xyz', 'nihilnovi' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'nihilnovi_footer_copy', [
        'label'   => __( 'Texto de copyright', 'nihilnovi' ),
        'section' => 'nihilnovi_footer',
        'type'    => 'text',
    ] );
}
add_action( 'customize_register', 'nihilnovi_customize_register' );


/* ==========================================================================
 * 2. OUTPUT CSS VARIABLES TO <HEAD>
 *    Reads theme mods and overrides the design-token CSS custom properties.
 * ========================================================================== */

/**
 * Emit a <style> block that maps Customizer colour values to CSS variables.
 * Hooked to wp_head so it always overrides the stylesheet defaults.
 */
function nihilnovi_customizer_css() {
    $gold = esc_attr( get_theme_mod( 'nihilnovi_color_gold', '#C4973A' ) );
    $bg   = esc_attr( get_theme_mod( 'nihilnovi_color_bg',   '#09090F' ) );
    $text = esc_attr( get_theme_mod( 'nihilnovi_color_text', '#EDE8DF' ) );

    /* Derive --gold-light by appending 'dd' alpha suffix to the hex value */
    $gold_light = $gold . 'dd';
    ?>
    <style id="nihilnovi-customizer-css">
        :root {
            --gold:       <?php echo $gold; ?>;
            --gold-light: <?php echo $gold_light; ?>;
            --black:      <?php echo $bg; ?>;
            --ivory:      <?php echo $text; ?>;
        }
    </style>
    <?php
}
add_action( 'wp_head', 'nihilnovi_customizer_css' );


/* ==========================================================================
 * 3. LIVE PREVIEW — postMessage JS
 *    Allows colour changes to reflect instantly in the Customizer preview
 *    iframe without a full page refresh.
 * ========================================================================== */

/**
 * Enqueue the customize-preview script and attach inline JS for
 * postMessage transport on colour settings.
 */
function nihilnovi_customizer_live_preview() {
    wp_enqueue_script( 'customize-preview' );

    $inline_js = "
        ( function( $ ) {
            /* --gold */
            wp.customize( 'nihilnovi_color_gold', function( value ) {
                value.bind( function( newval ) {
                    document.documentElement.style.setProperty( '--gold', newval );
                    document.documentElement.style.setProperty( '--gold-light', newval + 'dd' );
                } );
            } );

            /* --black (background) */
            wp.customize( 'nihilnovi_color_bg', function( value ) {
                value.bind( function( newval ) {
                    document.documentElement.style.setProperty( '--black', newval );
                } );
            } );

            /* --ivory (text) */
            wp.customize( 'nihilnovi_color_text', function( value ) {
                value.bind( function( newval ) {
                    document.documentElement.style.setProperty( '--ivory', newval );
                } );
            } );
        } )( jQuery );
    ";

    wp_add_inline_script( 'customize-preview', $inline_js );
}
add_action( 'customize_preview_init', 'nihilnovi_customizer_live_preview' );


/* ==========================================================================
 * 4. HELPER — nihilnovi_social_links()
 *    Returns an array of active social network links saved in the Customizer.
 *    Only entries with a non-empty URL are included.
 *
 *  Usage in a template:
 *      $links = nihilnovi_social_links();
 *      foreach ( $links as $key => $data ) {
 *          echo '<a href="' . esc_url( $data['url'] ) . '">' . esc_html( $data['label'] ) . '</a>';
 *      }
 * ========================================================================== */

/**
 * Return an associative array of configured social links.
 *
 * @return array<string, array{label: string, icon: string, url: string}>
 */
function nihilnovi_social_links() {
    $links = [];

    $socials = [
        'twitter'   => [ 'label' => __( 'X', 'nihilnovi' ),  'icon' => 'X'  ],
        'instagram' => [ 'label' => __( 'IG', 'nihilnovi' ), 'icon' => 'IG' ],
        'linkedin'  => [ 'label' => __( 'LI', 'nihilnovi' ), 'icon' => 'LI' ],
        'youtube'   => [ 'label' => __( 'YT', 'nihilnovi' ), 'icon' => 'YT' ],
    ];

    foreach ( $socials as $key => $data ) {
        $url = get_theme_mod( 'nihilnovi_social_' . $key, '' );
        if ( $url ) {
            $links[ $key ] = array_merge( $data, [ 'url' => $url ] );
        }
    }

    return $links;
}
