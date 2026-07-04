<?php
/**
 * page.php — Nihil Novi
 * Plantilla para páginas genéricas: Sobre, Biblioteca, El Viaje, etc.
 * El contenido se escribe en el editor de WordPress (Páginas → Editar).
 */
get_header();

// Datos de la página
$page_id    = get_the_ID();
$page_title = get_the_title();
$subtitle   = get_post_meta( $page_id, '_post_subtitle', true );
?>

<!-- ══════════ PAGE HERO ══════════ -->
<section class="post-hero page-hero" aria-label="<?php echo esc_attr__( 'Encabezado de página', 'nihilnovi' ); ?>">
  <div class="blob blob-1" aria-hidden="true"></div>
  <div class="hero-grid" aria-hidden="true"></div>

  <div class="post-hero-inner">
    <div class="s-eyebrow">nihilnovi.xyz</div>
    <h1 class="post-title">
      <?php echo esc_html( $page_title ); ?>
    </h1>
    <?php if ( $subtitle ) : ?>
      <p class="post-subtitle"><?php echo esc_html( $subtitle ); ?></p>
    <?php endif; ?>
  </div>
</section>

<!-- ══════════ PAGE CONTENT ══════════ -->
<div class="post-body">
  <div class="post-body-inner">
    <?php
    if ( have_posts() ) :
      while ( have_posts() ) : the_post(); ?>
        <div class="post-content">
          <?php the_content(); ?>
        </div>
        <?php
        // Paginación de páginas largas
        wp_link_pages([
          'before' => '<div class="post-page-links">',
          'after'  => '</div>',
        ]);
      endwhile;
    endif;
    ?>
  </div>
</div>

<?php get_footer(); ?>
