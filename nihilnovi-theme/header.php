<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="<?php bloginfo( 'description' ); ?>" />
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

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
<nav class="nn-nav" id="nn-nav" role="navigation" aria-label="Navegación principal">

  <div class="nav-left">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav-logo" aria-label="Nihil Novi — Inicio">
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
    <div class="lang-switch" aria-label="Selector de idioma">
      <button class="lang-btn active" aria-pressed="true">ES</button>
      <span class="lang-sep" aria-hidden="true">·</span>
      <button class="lang-btn" aria-pressed="false">EN</button>
      <span class="lang-sep" aria-hidden="true">·</span>
      <button class="lang-btn" aria-pressed="false">IT</button>
    </div>
    <a href="<?php echo esc_url( home_url( '/el-viaje' ) ); ?>" class="nav-cta">Explorar</a>
  </div>

  <button class="nav-toggle" id="nav-toggle" aria-label="Abrir menú" aria-expanded="false">
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

<?php
/**
 * Fallback navigation — shown when no menu is assigned in WP Admin
 */
function nihilnovi_fallback_nav() {
  echo '<ul class="nav-menu" role="menubar">';
  echo '<li><a href="' . esc_url( home_url( '/' ) ) . '#disciplinas">Disciplinas</a></li>';
  echo '<li><a href="' . esc_url( home_url( '/el-viaje' ) ) . '">El Viaje</a></li>';
  echo '<li><a href="' . esc_url( home_url( '/categoria/lecciones' ) ) . '">Lecciones</a></li>';
  echo '<li><a href="' . esc_url( home_url( '/blog' ) ) . '">Blog</a></li>';
  echo '<li><a href="' . esc_url( home_url( '/biblioteca' ) ) . '">Biblioteca</a></li>';
  echo '<li><a href="' . esc_url( home_url( '/sobre' ) ) . '">Sobre</a></li>';
  echo '</ul>';
}
