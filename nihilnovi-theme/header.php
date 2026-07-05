<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
        <?php pll_the_languages( ['show_flags' => 0, 'show_names' => 1, 'dropdown' => 0] ); ?>
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
