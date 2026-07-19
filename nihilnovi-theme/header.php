<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <?php
  // ── SEO META DESCRIPTION ──
  $meta_desc = '';
  if ( is_single() || is_page() ) {
    $meta_desc = get_post_meta( get_the_ID(), '_post_subtitle', true );
    if ( empty( $meta_desc ) ) {
      $meta_desc = get_the_excerpt();
    }
    if ( empty( $meta_desc ) ) {
      $meta_desc = wp_trim_words( get_the_content(), 30, '...' );
    }
  } elseif ( is_category() ) {
    $meta_desc = sprintf( __( 'Artículos y lecciones de %s en Nihil Novi.', 'nihilnovi' ), single_cat_title( '', false ) );
  } elseif ( is_home() || is_front_page() ) {
    $meta_desc = __( 'Nihil Novi — Hub editorial sobre la Historia del Pensamiento Humano. Filosofía, Economía, Matemáticas, Historia y Ciencia.', 'nihilnovi' );
  }
  if ( ! empty( $meta_desc ) ) :
  ?>
  <meta name="description" content="<?php echo esc_attr( wp_strip_all_tags( $meta_desc ) ); ?>" />
  <?php endif; ?>
  
  <?php
  // ── OPEN GRAPH ──
  $og_title = is_single() || is_page() ? get_the_title() : ( is_category() ? single_cat_title( '', false ) : 'Nihil Novi' );
  $og_desc  = ! empty( $meta_desc ) ? $meta_desc : __( 'Nihil Novi — Historia del Pensamiento Humano', 'nihilnovi' );
  $og_type  = is_single() ? 'article' : 'website';
  $og_url   = is_single() || is_page() ? get_permalink() : ( is_category() ? get_category_link( get_queried_object_id() ) : home_url( '/' ) );
  ?>
  <meta property="og:title" content="<?php echo esc_attr( $og_title ); ?>" />
  <meta property="og:description" content="<?php echo esc_attr( wp_strip_all_tags( $og_desc ) ); ?>" />
  <meta property="og:type" content="<?php echo esc_attr( $og_type ); ?>" />
  <meta property="og:url" content="<?php echo esc_url( $og_url ); ?>" />
  <meta property="og:site_name" content="Nihil Novi" />
  <?php if ( is_single() && has_post_thumbnail() ) : ?>
  <meta property="og:image" content="<?php echo esc_url( get_the_post_thumbnail_url( null, 'large' ) ); ?>" />
  <?php endif; ?>
  
  <?php
  // ── SCHEMA.ORG ARTICLE JSON-LD ──
  if ( is_single() ) :
    $post_id   = get_the_ID();
    $author    = get_the_author_meta( 'display_name', get_post_field( 'post_author', $post_id ) );
    $pub_date  = get_the_date( 'c', $post_id );
    $mod_date  = get_the_modified_date( 'c', $post_id );
    $headline  = get_the_title( $post_id );
    $excerpt   = get_the_excerpt( $post_id );
    $cat_names = [];
    $cats = get_the_category( $post_id );
    foreach ( $cats as $cat ) {
      $cat_names[] = $cat->name;
    }
  ?>
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Article",
    "headline": "<?php echo esc_js( $headline ); ?>",
    "description": "<?php echo esc_js( wp_strip_all_tags( $excerpt ) ); ?>",
    "author": {
      "@type": "Person",
      "name": "<?php echo esc_js( $author ); ?>"
    },
    "publisher": {
      "@type": "Organization",
      "name": "Nihil Novi",
      "url": "<?php echo esc_url( home_url( '/' ) ); ?>"
    },
    "datePublished": "<?php echo esc_js( $pub_date ); ?>",
    "dateModified": "<?php echo esc_js( $mod_date ); ?>",
    "mainEntityOfPage": {
      "@type": "WebPage",
      "@id": "<?php echo esc_url( get_permalink( $post_id ) ); ?>"
    },
    "articleSection": "<?php echo esc_js( implode( ', ', $cat_names ) ); ?>"
  }
  </script>
  <?php endif; ?>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-B03HWRXEWN"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-B03HWRXEWN');
  </script>
  
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- ─── GLOBAL BACKGROUND ──────────────────────────────────────
     Gradient aura effects and interactive dot matrix animation.
-->
<!-- Fondo Global: Auras + Matriz de Puntos -->
<div class="nn-global-bg" aria-hidden="true">
  <div class="nn-bg-dot-matrix"></div>
  <div class="nn-bg-dot-matrix-blurred"></div>
  <div class="nn-bg-dot-matrix-heavy"></div>
  <div class="nn-bg-blob nn-bg-blob-1"></div>
  <div class="nn-bg-blob nn-bg-blob-2"></div>
  <div class="nn-bg-blob nn-bg-blob-3"></div>
</div>

<!-- Scroll Progress -->
<div id="nn-progress"></div>

<!-- ══════════ NAVIGATION ══════════ -->
<nav class="nn-nav" id="nn-nav" role="navigation" aria-label="<?php echo esc_attr__( 'Navegación principal', 'nihilnovi' ); ?>">

  <div class="nav-left">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav-logo" aria-label="<?php echo esc_attr__( 'Nihil Novi — Inicio', 'nihilnovi' ); ?>">
      Nihil Novi
    </a>
    <div class="nav-dot" aria-hidden="true"></div>
  </div>

  <?php
  wp_nav_menu([
    'theme_location' => 'primary',
    'container'      => false,
    'menu_class'     => 'nav-menu',
    'items_wrap'     => '<ul id="primary-menu" class="nav-menu" role="menubar">%3$s</ul>',
    'fallback_cb'    => 'nihilnovi_fallback_nav',
  ]);
  ?>

  <div class="nav-right">
    <div class="lang-switch" aria-label="<?php echo esc_attr__( 'Selector de idioma', 'nihilnovi' ); ?>">
      <?php if ( function_exists( 'pll_the_languages' ) ) : ?>
        <?php pll_the_languages( ['show_flags' => 0, 'show_names' => 1, 'dropdown' => 0, 'display_names_as' => 'slug'] ); ?>
      <?php else : ?>
        <span class="lang-btn active"><?php echo esc_html__( 'ES', 'nihilnovi' ); ?></span>
        <span class="lang-sep" aria-hidden="true">·</span>
        <span class="lang-btn" style="opacity:0.4;cursor:default;"><?php echo esc_html__( 'EN', 'nihilnovi' ); ?></span>
        <span class="lang-sep" aria-hidden="true">·</span>
        <span class="lang-btn" style="opacity:0.4;cursor:default;"><?php echo esc_html__( 'IT', 'nihilnovi' ); ?></span>
        <span class="lang-sep" aria-hidden="true">·</span>
        <span class="lang-btn" style="opacity:0.4;cursor:default;"><?php echo esc_html__( 'DE', 'nihilnovi' ); ?></span>
      <?php endif; ?>
    </div>
    <a href="<?php echo esc_url( home_url( '/el-viaje' ) ); ?>" class="nav-cta"><?php echo esc_html__( 'Explorar', 'nihilnovi' ); ?></a>
  </div>

  <button class="nav-toggle" id="nav-toggle" aria-label="<?php echo esc_attr__( 'Abrir menú', 'nihilnovi' ); ?>" aria-expanded="false">
    <span></span><span></span><span></span>
  </button>

</nav>

<!-- Mobile menu (hidden by default) -->
<div id="mobile-menu" class="mobile-menu" aria-hidden="true">
  <?php
  wp_nav_menu([
    'theme_location' => 'primary',
    'container'      => false,
    'menu_class'     => 'mobile-nav-list',
    'fallback_cb'    => 'nihilnovi_fallback_nav',
  ]);
  ?>
</div>
