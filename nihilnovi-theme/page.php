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
<section class="post-hero" style="min-height:40vh;" aria-label="Encabezado de página">
  <div class="blob blob-1" style="opacity:0.3;" aria-hidden="true"></div>
  <div class="hero-grid" style="opacity:0.3;" aria-hidden="true"></div>

  <div class="post-hero-inner" style="padding-top:6rem;padding-bottom:3rem;">
    <div class="s-eyebrow" style="margin-bottom:1.4rem;">nihilnovi.xyz</div>
    <h1 class="post-title" style="font-size:clamp(2rem,5vw,3.8rem);">
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

<!-- Estilos del contenido de página -->
<style>
.post-content p  { font-size:1.08rem; color:var(--ivory-2); line-height:1.9; margin-bottom:1.6rem; }
.post-content h2 { font-family:'Playfair Display',serif; font-size:1.7rem; font-weight:400; color:var(--ivory); margin:3.5rem 0 1.2rem; line-height:1.3; }
.post-content h3 { font-family:'Playfair Display',serif; font-size:1.25rem; font-weight:400; color:var(--ivory); margin:2.5rem 0 0.8rem; }
.post-content h4 { font-family:'Inter',sans-serif; font-size:0.75rem; letter-spacing:.15em; text-transform:uppercase; color:var(--gold); margin:2rem 0 0.8rem; }
.post-content a  { color:var(--gold); border-bottom:1px solid var(--gold-border); transition:border-color .3s; }
.post-content a:hover { border-color:var(--gold); }
.post-content em { font-style:italic; color:var(--ivory); }
.post-content strong { color:var(--ivory); font-weight:500; }
.post-content blockquote { border-left:2px solid var(--gold); padding:.5rem 0 .5rem 2rem; margin:2.5rem 0; }
.post-content blockquote p { font-family:'Playfair Display',serif; font-style:italic; font-size:1.15rem; color:var(--ivory-2); }
.post-content ul, .post-content ol { margin:0 0 1.6rem 1.5rem; }
.post-content li { font-size:1.05rem; color:var(--ivory-2); line-height:1.8; margin-bottom:.4rem; }
.post-content hr { border:none; border-top:1px solid var(--border); margin:3rem 0; }
</style>

<?php get_footer(); ?>
