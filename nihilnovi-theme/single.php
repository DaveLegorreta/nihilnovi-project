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
$cat_name   = $cat ? $cat->name : ( $is_lesson ? __( 'Lección', 'nihilnovi' ) : __( 'El Viaje', 'nihilnovi' ) );
$disc_class = nihilnovi_get_disc_class( $post_id );

// Código visible (lección o número de artículo)
$display_code = $lesson_code ?: ( $article_num ? str_pad( $article_num, 2, '0', STR_PAD_LEFT ) : '' );
?>

<!-- ══════════ POST HERO ══════════ -->
<section class="post-hero" aria-label="<?php echo esc_attr__( 'Encabezado del artículo', 'nihilnovi' ); ?>">

  <!-- Partículas de fondo -->
  <div class="blob blob-1" aria-hidden="true"></div>
  <div class="hero-grid" aria-hidden="true"></div>

  <div class="post-hero-inner">

    <!-- Migas de pan -->
    <nav class="breadcrumb" aria-label="<?php echo esc_attr__( 'Migas de pan', 'nihilnovi' ); ?>">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__( 'Inicio', 'nihilnovi' ); ?></a>
      <span aria-hidden="true">/</span>
      <?php if ( $cat ) : ?>
        <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"><?php echo esc_html( $cat_name ); ?></a>
        <span aria-hidden="true">/</span>
      <?php endif; ?>
      <span class="breadcrumb-current" aria-current="page"><?php echo esc_html( get_the_title() ); ?></span>
    </nav>

    <!-- Meta fila superior -->
    <div class="post-meta-row">
      <?php if ( $display_code ) : ?>
        <span class="post-num <?php echo esc_attr( $is_lesson ? 'lesson-code' : '' ); ?>">
          <?php echo esc_html( $display_code ); ?>
        </span>
      <?php endif; ?>

      <?php if ( $cat ) : ?>
        <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="post-cat <?php echo esc_attr( $disc_class ); ?>">
          <?php echo esc_html( $cat_name ); ?>
        </a>
      <?php endif; ?>

      <?php if ( $read_time ) : ?>
        <span class="post-read-time">
          <?php echo esc_html( $read_time ); ?>
        </span>
      <?php endif; ?>

      <time class="post-date" datetime="<?php echo esc_attr( get_the_date('c') ); ?>">
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
  <meta itemprop="datePublished" content="<?php echo esc_attr( get_the_date('c') ); ?>" />

  <div class="post-body-inner">

    <?php
    // ── "Lo esencial" para LECCIONES (antes del contenido) ──
    if ( $is_lesson && $essentials ) :
      $points = array_filter( array_map( 'trim', explode( "\n", $essentials ) ) );
      if ( ! empty( $points ) ) :
    ?>
    <aside class="lesson-essentials" aria-label="<?php echo esc_attr__( 'Lo esencial de esta lección', 'nihilnovi' ); ?>">
      <h4><?php echo esc_html__( 'Lo esencial', 'nihilnovi' ); ?></h4>
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
      'before'      => '<div class="post-page-links"><span>' . esc_html__( 'Páginas:', 'nihilnovi' ) . '</span>',
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
    <div class="post-bibliography" aria-label="<?php echo esc_attr__( 'Bibliografía', 'nihilnovi' ); ?>">
      <h4><?php echo esc_html__( 'Bibliografía y fuentes', 'nihilnovi' ); ?></h4>
      <ul>
        <?php foreach ( $refs as $ref ) : ?>
          <li><?php echo esc_html( $ref ); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endif; endif; ?>

    <?php /* TODO: implementar paywall - if ( get_post_meta( get_the_ID(), '_nihilnovi_is_premium', true ) ) { ... } */ ?>

    <!-- CTA de consultoría -->
    <?php get_template_part( 'template-parts/cta', 'consulting' ); ?>

    <!-- Tags -->
    <?php
    $tags = get_the_tags();
    if ( $tags ) :
    ?>
    <div class="post-tags">
      <?php foreach ( $tags as $tag ) : ?>
        <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="post-tag">
          #<?php echo esc_html( $tag->name ); ?>
        </a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

  </div>
</article>

<!-- ══════════ AUTOR ══════════ -->
<div class="post-author">
  <div class="post-author-inner">
    <div class="post-author-avatar">
      <span>DL</span>
    </div>
    <div>
      <div class="post-author-label"><?php echo esc_html__( 'Escrito por', 'nihilnovi' ); ?></div>
      <div class="post-author-name"><?php echo esc_html__( 'David Legorreta', 'nihilnovi' ); ?></div>
      <p class="post-author-bio">
        <?php
        printf(
          /* translators: %s: home URL */
          esc_html__( 'Filósofo de formación, operador de oficio. Estudiando economía en público en %s.', 'nihilnovi' ),
          '<a href="' . esc_url( home_url('/') ) . '">nihilnovi.xyz</a>'
        );
        ?>
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
<nav class="post-nav" aria-label="<?php echo esc_attr__( 'Navegación entre artículos', 'nihilnovi' ); ?>">
  <div class="post-nav-item prev">
    <?php if ( $prev ) : ?>
      <a href="<?php echo esc_url( get_permalink( $prev ) ); ?>">
        <span class="post-nav-label"><?php echo esc_html__( '← Anterior', 'nihilnovi' ); ?></span>
        <span class="post-nav-title"><?php echo esc_html( get_the_title( $prev ) ); ?></span>
      </a>
    <?php else : ?>
      <span class="post-nav-label disabled"><?php echo esc_html__( '← Inicio de serie', 'nihilnovi' ); ?></span>
    <?php endif; ?>
  </div>

  <div class="post-nav-item next">
    <?php if ( $next ) : ?>
      <a href="<?php echo esc_url( get_permalink( $next ) ); ?>">
        <span class="post-nav-label"><?php echo esc_html__( 'Siguiente →', 'nihilnovi' ); ?></span>
        <span class="post-nav-title"><?php echo esc_html( get_the_title( $next ) ); ?></span>
      </a>
    <?php else : ?>
      <span class="post-nav-label disabled"><?php echo esc_html__( 'Continúa pronto →', 'nihilnovi' ); ?></span>
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
<section class="related-posts" aria-label="<?php echo esc_attr__( 'Artículos relacionados', 'nihilnovi' ); ?>">
  <div class="related-posts-inner">
    <div class="s-eyebrow"><?php echo esc_html__( 'Seguir leyendo', 'nihilnovi' ); ?></div>
    <div class="articles-row">
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
        <a href="<?php the_permalink(); ?>" class="art-cta"><?php echo esc_html__( 'Leer', 'nihilnovi' ); ?></a>
      </article>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- CTA de newsletter -->
<?php get_template_part( 'template-parts/cta', 'newsletter' ); ?>

<!-- Libros recomendados (afiliados) -->
<?php get_template_part( 'template-parts/affiliate', 'books' ); ?>

<?php get_footer(); ?>
