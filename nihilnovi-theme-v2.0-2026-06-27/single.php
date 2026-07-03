<?php
/**
 * single.php — Nihil Novi
 * Plantilla de lectura para artículos y lecciones.
 * Detecta automáticamente si es lección o artículo y adapta el diseño.
 */
get_header();

// ── Datos del post ─────────────────────────────
$post_id       = get_the_ID();
$lesson_code   = get_post_meta( $post_id, '_lesson_code', true );
$article_num   = get_post_meta( $post_id, '_article_num', true );
$read_time     = get_post_meta( $post_id, '_read_time', true ) ?: nihilnovi_estimate_read_time( get_the_content() );
$subtitle      = get_post_meta( $post_id, '_post_subtitle', true );
$essentials    = get_post_meta( $post_id, '_lesson_essentials', true ); // un punto por línea
$bibliography  = get_post_meta( $post_id, '_bibliography', true );      // un ítem por línea

// ¿Es lección?
$is_lesson = ! empty( $lesson_code ) || has_category( 'leccion' );

// Categoría + clase de disciplina
$cats       = get_the_category();
$cat        = $cats ? $cats[0] : null;
$cat_name   = $cat ? $cat->name : ( $is_lesson ? 'Lección' : 'El Viaje' );
$disc_class = nihilnovi_get_disc_class( $post_id );

// Código visible (lección o número de artículo)
$display_code = $lesson_code ?: ( $article_num ? str_pad( $article_num, 2, '0', STR_PAD_LEFT ) : '' );
?>

<!-- ══════════ POST HERO ══════════ -->
<section class="post-hero" aria-label="Encabezado del artículo">

  <!-- Partículas de fondo -->
  <div class="blob blob-1" style="opacity:0.4;" aria-hidden="true"></div>
  <div class="hero-grid" style="opacity:0.4;" aria-hidden="true"></div>

  <div class="post-hero-inner">

    <!-- Meta fila superior -->
    <div class="post-meta-row">
      <?php if ( $display_code ) : ?>
        <span class="post-num <?php echo esc_attr( $is_lesson ? 'lesson-code' : '' ); ?>"
              style="<?php echo $is_lesson ? 'background:var(--gold-dim);border:1px solid var(--gold-border);padding:0.25rem 0.65rem;font-family:JetBrains Mono,monospace;font-size:0.75rem;' : ''; ?>">
          <?php echo esc_html( $display_code ); ?>
        </span>
      <?php endif; ?>

      <?php if ( $cat ) : ?>
        <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="post-cat <?php echo esc_attr( $disc_class ); ?>"
           style="color:var(--<?php echo esc_attr( $disc_class ); ?>);border-color:rgba(var(--<?php echo esc_attr( $disc_class ); ?>-rgb,196,151,58),0.3);">
          <?php echo esc_html( $cat_name ); ?>
        </a>
      <?php endif; ?>

      <?php if ( $read_time ) : ?>
        <span class="post-read-time" style="font-family:'Inter',sans-serif;font-size:0.62rem;letter-spacing:0.1em;text-transform:uppercase;color:var(--ivory-3);">
          <?php echo esc_html( $read_time ); ?>
        </span>
      <?php endif; ?>

      <time class="post-date" datetime="<?php echo get_the_date('c'); ?>">
        <?php echo get_the_date('j \d\e F, Y'); ?>
      </time>
    </div>

    <!-- Título -->
    <h1 class="post-title"><?php the_title(); ?></h1>

    <!-- Subtítulo o excerpt -->
    <?php if ( $subtitle ) : ?>
      <p class="post-subtitle"><?php echo esc_html( $subtitle ); ?></p>
    <?php elseif ( has_excerpt() ) : ?>
      <p class="post-subtitle"><?php echo esc_html( get_the_excerpt() ); ?></p>
    <?php endif; ?>

  </div>
</section>

<!-- ══════════ POST BODY ══════════ -->
<article class="post-body" id="lectura" itemscope itemtype="https://schema.org/Article">

  <meta itemprop="author" content="David Legorreta" />
  <meta itemprop="datePublished" content="<?php echo get_the_date('c'); ?>" />

  <div class="post-body-inner">

    <?php
    // ── "Lo esencial" para LECCIONES (antes del contenido) ──
    if ( $is_lesson && $essentials ) :
      $points = array_filter( array_map( 'trim', explode( "\n", $essentials ) ) );
      if ( ! empty( $points ) ) :
    ?>
    <aside class="lesson-essentials" aria-label="Lo esencial de esta lección">
      <h4>Lo esencial</h4>
      <ul>
        <?php foreach ( $points as $point ) : ?>
          <li><?php echo esc_html( $point ); ?></li>
        <?php endforeach; ?>
      </ul>
    </aside>
    <?php endif; endif; ?>

    <!-- Contenido principal escrito en el editor de WordPress -->
    <div class="post-content" itemprop="articleBody">
      <?php the_content(); ?>
    </div>

    <!-- Paginación de contenido largo (nextpage) -->
    <?php
    wp_link_pages([
      'before'      => '<div class="post-page-links"><span>Páginas:</span>',
      'after'       => '</div>',
      'link_before' => '<span>',
      'link_after'  => '</span>',
    ]);
    ?>

    <?php
    // ── "Lo esencial" para LECCIONES (si no está al inicio) ──
    // Descomenta el bloque de abajo si prefieres los puntos AL FINAL
    // if ( $is_lesson && $essentials ) { ... }
    ?>

    <?php
    // ── BIBLIOGRAFÍA ──
    if ( $bibliography ) :
      $refs = array_filter( array_map( 'trim', explode( "\n", $bibliography ) ) );
      if ( ! empty( $refs ) ) :
    ?>
    <div class="post-bibliography" aria-label="Bibliografía">
      <h4>Bibliografía y fuentes</h4>
      <ul>
        <?php foreach ( $refs as $ref ) : ?>
          <li><?php echo esc_html( $ref ); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endif; endif; ?>

    <!-- Tags -->
    <?php
    $tags = get_the_tags();
    if ( $tags ) :
    ?>
    <div class="post-tags" style="margin-top:3rem;padding-top:2rem;border-top:1px solid var(--border);display:flex;flex-wrap:wrap;gap:0.5rem;">
      <?php foreach ( $tags as $tag ) : ?>
        <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>"
           style="font-family:'JetBrains Mono',monospace;font-size:0.65rem;color:var(--ivory-3);border:1px solid var(--border-2);padding:0.25rem 0.65rem;transition:all 0.3s;"
           class="post-tag">
          #<?php echo esc_html( $tag->name ); ?>
        </a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

  </div>
</article>

<!-- ══════════ AUTOR ══════════ -->
<div style="padding:3rem 4rem;border-top:1px solid var(--border);background:var(--black-2);">
  <div style="max-width:720px;margin:0 auto;display:flex;gap:1.5rem;align-items:flex-start;">
    <div style="flex-shrink:0;width:48px;height:48px;background:var(--gold-dim);border:1px solid var(--gold-border);display:flex;align-items:center;justify-content:center;">
      <span style="font-family:'Playfair Display',serif;font-size:1rem;color:var(--gold);">DL</span>
    </div>
    <div>
      <div style="font-family:'Inter',sans-serif;font-size:0.65rem;letter-spacing:0.15em;text-transform:uppercase;color:var(--gold);margin-bottom:0.4rem;">Escrito por</div>
      <div style="font-family:'Playfair Display',serif;font-size:1rem;color:var(--ivory);margin-bottom:0.4rem;">David Legorreta</div>
      <p style="font-family:'Source Serif 4',serif;font-size:0.85rem;color:var(--ivory-3);line-height:1.7;margin:0;">
        Filósofo de formación, operador de oficio. Estudiando economía en público en <a href="<?php echo esc_url(home_url('/')); ?>" style="color:var(--gold);border-bottom:1px solid var(--gold-border);">nihilnovi.xyz</a>.
      </p>
    </div>
  </div>
</div>

<!-- ══════════ NAVEGACIÓN PREV/NEXT ══════════ -->
<?php
// Navegación dentro de la misma categoría para mantener coherencia temática
$prev = get_previous_post( true, '', 'category' );
$next = get_next_post( true, '', 'category' );
if ( $prev || $next ) :
?>
<nav class="post-nav" aria-label="Navegación entre artículos">
  <div class="post-nav-item prev">
    <?php if ( $prev ) : ?>
      <a href="<?php echo esc_url( get_permalink( $prev ) ); ?>" style="display:block;text-decoration:none;">
        <span class="post-nav-label">← Anterior</span>
        <span class="post-nav-title"><?php echo esc_html( get_the_title( $prev ) ); ?></span>
      </a>
    <?php else : ?>
      <span class="post-nav-label" style="opacity:0.3;">← Inicio de serie</span>
    <?php endif; ?>
  </div>

  <div class="post-nav-item next">
    <?php if ( $next ) : ?>
      <a href="<?php echo esc_url( get_permalink( $next ) ); ?>" style="display:block;text-decoration:none;text-align:right;">
        <span class="post-nav-label">Siguiente →</span>
        <span class="post-nav-title"><?php echo esc_html( get_the_title( $next ) ); ?></span>
      </a>
    <?php else : ?>
      <span class="post-nav-label" style="opacity:0.3;display:block;text-align:right;">Continúa pronto →</span>
    <?php endif; ?>
  </div>
</nav>
<?php endif; ?>

<!-- ══════════ RELACIONADOS ══════════ -->
<?php
$related = new WP_Query([
  'posts_per_page'      => 3,
  'post_status'         => 'publish',
  'post__not_in'        => [ $post_id ],
  'category__in'        => wp_get_post_categories( $post_id ),
  'ignore_sticky_posts' => 1,
]);

if ( $related->have_posts() ) :
?>
<section style="padding:5rem 4rem;border-top:1px solid var(--border);" aria-label="Artículos relacionados">
  <div style="max-width:1240px;margin:0 auto;">
    <div class="s-eyebrow" style="margin-bottom:2rem;">Seguir leyendo</div>
    <div class="articles-row" style="grid-template-columns:repeat(3,1fr);">
      <?php while ( $related->have_posts() ) : $related->the_post();
        $r_code = get_post_meta(get_the_ID(),'_lesson_code',true) ?: get_post_meta(get_the_ID(),'_article_num',true);
        $r_cats = get_the_category();
        $r_cat  = $r_cats ? $r_cats[0]->name : '';
      ?>
      <article class="art-card">
        <div class="art-meta">
          <?php if ($r_code) : ?><span class="art-num"><?php echo esc_html($r_code); ?></span><?php endif; ?>
          <?php if ($r_cat) : ?><span class="art-cat"><?php echo esc_html($r_cat); ?></span><?php endif; ?>
          <span class="art-date"><?php echo get_the_date('j M Y'); ?></span>
        </div>
        <a href="<?php the_permalink(); ?>" class="art-title"><?php the_title(); ?></a>
        <p class="art-excerpt"><?php echo wp_trim_words(get_the_excerpt(),16); ?></p>
        <a href="<?php the_permalink(); ?>" class="art-cta">Leer</a>
      </article>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- CSS específico del single -->
<style>
/* Estilos del contenido escrito en el editor WP */
.post-content { font-family: 'Source Serif 4', serif; }
.post-content p { font-size: 1.08rem; color: var(--ivory-2); line-height: 1.9; margin-bottom: 1.6rem; }
.post-content h2 { font-family: 'Playfair Display', serif; font-size: 1.7rem; font-weight: 400; color: var(--ivory); margin: 3.5rem 0 1.2rem; line-height: 1.3; }
.post-content h3 { font-family: 'Playfair Display', serif; font-size: 1.25rem; font-weight: 400; color: var(--ivory); margin: 2.5rem 0 0.8rem; }
.post-content h4 { font-family: 'Inter', sans-serif; font-size: 0.75rem; letter-spacing: 0.15em; text-transform: uppercase; color: var(--gold); margin: 2rem 0 0.8rem; }
.post-content em { font-style: italic; color: var(--ivory); }
.post-content strong { color: var(--ivory); font-weight: 500; }
.post-content a { color: var(--gold); border-bottom: 1px solid var(--gold-border); transition: border-color 0.3s; }
.post-content a:hover { border-color: var(--gold); }
.post-content blockquote { border-left: 2px solid var(--gold); padding: 0.5rem 0 0.5rem 2rem; margin: 2.5rem 0; }
.post-content blockquote p { font-family: 'Playfair Display', serif; font-style: italic; font-size: 1.15rem; color: var(--ivory-2); }
.post-content ul, .post-content ol { margin: 0 0 1.6rem 1.5rem; }
.post-content li { font-size: 1.05rem; color: var(--ivory-2); line-height: 1.8; margin-bottom: 0.4rem; }
.post-content img { max-width: 100%; margin: 2rem auto; }
.post-content hr { border: none; border-top: 1px solid var(--border); margin: 3rem 0; }
.post-content code { font-family: 'JetBrains Mono', monospace; font-size: 0.85em; background: var(--card); color: var(--gold); padding: 0.15em 0.4em; }
.post-content pre { background: var(--card); border: 1px solid var(--border); padding: 1.5rem; margin: 2rem 0; overflow-x: auto; }
.post-content pre code { background: none; padding: 0; }

/* Categoría del post con color de disciplina */
.post-cat { border: 1px solid; padding: 0.2rem 0.7rem; font-family: 'Inter', sans-serif; font-size: 0.65rem; letter-spacing: 0.12em; text-transform: uppercase; }
.post-cat.fil { color: var(--fil) !important; border-color: rgba(123,111,160,0.3) !important; }
.post-cat.eco { color: var(--eco) !important; border-color: rgba(196,151,58,0.3) !important; }
.post-cat.mat { color: var(--mat) !important; border-color: rgba(74,142,110,0.3) !important; }
.post-cat.his { color: var(--his) !important; border-color: rgba(142,74,74,0.3) !important; }
.post-cat.cie { color: var(--cie) !important; border-color: rgba(74,110,142,0.3) !important; }

.post-tag:hover { border-color: var(--gold) !important; color: var(--gold) !important; }

@media (max-width: 768px) {
  .post-hero { padding: 8rem 1.6rem 4rem; }
  .post-body { padding: 3rem 1.6rem; }
  .post-body-inner { max-width: 100%; }
  section[aria-label="Artículos relacionados"],
  div[aria-label="Escrito por"] { padding-left: 1.6rem; padding-right: 1.6rem; }
  .articles-row { grid-template-columns: 1fr !important; }
}
</style>

<?php get_footer(); ?>
