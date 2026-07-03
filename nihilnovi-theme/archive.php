<?php
/**
 * archive.php — Nihil Novi
 * Listado por categoría: disciplinas (Economía, Filosofía...) y Lecciones.
 * Se activa automáticamente cuando se visita /categoria/economia, etc.
 */
get_header();

// Datos del archivo
$cat         = get_queried_object();
$cat_slug    = $cat ? $cat->slug : '';
$cat_name    = $cat ? $cat->name : get_the_archive_title();
$cat_desc    = $cat ? $cat->description : '';
$is_lesson   = ( $cat_slug === 'leccion' || $cat_slug === 'lecciones' );

// Clase de disciplina para color
$disc_map = [
  'filosofia'   => 'fil',
  'economia'    => 'eco',
  'matematicas' => 'mat',
  'historia'    => 'his',
  'ciencia'     => 'cie',
  'leccion'     => 'eco',
  'lecciones'   => 'eco',
  'el-viaje'    => 'eco',
];
$disc_class = $disc_map[ $cat_slug ] ?? 'eco';
$disc_colors = [
  'fil' => '#7B6FA0',
  'eco' => '#C4973A',
  'mat' => '#4A8E6E',
  'his' => '#8E4A4A',
  'cie' => '#4A6E8E',
];
$disc_color = $disc_colors[ $disc_class ] ?? '#C4973A';

// Código de disciplina (FIL, ECO, etc.)
$disc_codes = [
  'fil' => 'FIL', 'eco' => 'ECO', 'mat' => 'MAT', 'his' => 'HIS', 'cie' => 'CIE',
];
$disc_code = $is_lesson ? 'NN' : ( $disc_codes[ $disc_class ] ?? 'NN' );
?>

<!-- ══════════ ARCHIVE HERO ══════════ -->
<section class="post-hero" style="min-height:42vh;" aria-label="Archivo de <?php echo esc_attr( $cat_name ); ?>">
  <div class="blob blob-1" style="opacity:0.35;" aria-hidden="true"></div>
  <div class="hero-grid" style="opacity:0.35;" aria-hidden="true"></div>

  <!-- Línea de color de la disciplina -->
  <div style="position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,transparent,<?php echo esc_attr($disc_color); ?>,transparent);"></div>

  <div class="post-hero-inner" style="padding-top:9rem;padding-bottom:3rem;">

    <!-- Migas de pan -->
    <nav class="breadcrumb" aria-label="Migas de pan">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__( 'Inicio', 'nihilnovi' ); ?></a>
      <span aria-hidden="true">/</span>
      <span class="breadcrumb-current" aria-current="page"><?php echo esc_html( $cat_name ); ?></span>
    </nav>

    <div class="post-meta-row" style="margin-bottom:1.4rem;">
      <span style="font-family:'JetBrains Mono',monospace;font-size:0.68rem;color:<?php echo esc_attr($disc_color); ?>;background:rgba(<?php
        list($r,$g,$b) = sscanf($disc_color,'#%02x%02x%02x');
        echo "$r,$g,$b";
      ?>,0.10);border:1px solid rgba(<?php echo "$r,$g,$b"; ?>,0.25);padding:0.25rem 0.65rem;">
        <?php echo esc_html( $disc_code ); ?>
      </span>
      <span style="font-family:'Inter',sans-serif;font-size:0.65rem;letter-spacing:0.15em;text-transform:uppercase;color:var(--ivory-3);">
        <?php echo $is_lesson ? 'Lecciones' : 'Disciplina'; ?>
      </span>
    </div>

    <h1 class="post-title" style="font-size:clamp(2rem,5vw,3.8rem);margin-bottom:<?php echo $cat_desc ? '1.2rem' : '0'; ?>;">
      <?php echo esc_html( $cat_name ); ?>
    </h1>

    <?php if ( $cat_desc ) : ?>
      <p class="post-subtitle" style="max-width:600px;">
        <?php echo esc_html( $cat_desc ); ?>
      </p>
    <?php endif; ?>

    <!-- Contador de entradas -->
    <div style="margin-top:1.5rem;font-family:'Inter',sans-serif;font-size:0.65rem;letter-spacing:0.15em;text-transform:uppercase;color:var(--ivory-3);">
      <?php
      $count = $cat ? $cat->count : 0;
      echo $count . ' ' . ( $is_lesson ? ( $count === 1 ? 'lección' : 'lecciones' ) : ( $count === 1 ? 'entrada' : 'entradas' ) );
      ?>
    </div>
  </div>
</section>

<!-- ══════════ ARCHIVE CONTENT ══════════ -->
<section class="nn-section" aria-label="Listado de <?php echo esc_attr($cat_name); ?>">
  <div class="section-inner">

    <?php if ( have_posts() ) : ?>

      <?php if ( $is_lesson ) : ?>
        <!-- Vista de lecciones: grid con código -->
        <div class="lessons-grid" style="grid-template-columns:repeat(3,1fr);">
          <?php while ( have_posts() ) : the_post();
            get_template_part( 'template-parts/content', 'lesson' );
          endwhile; ?>
        </div>

      <?php else : ?>
        <!-- Vista de artículos: listado editorial -->
        <div style="display:flex;flex-direction:column;border-top:1px solid var(--border);">
          <?php while ( have_posts() ) : the_post();
            get_template_part( 'template-parts/content', 'article' );
          endwhile; ?>
        </div>
      <?php endif; ?>

      <!-- Paginación -->
      <div style="margin-top:4rem;display:flex;justify-content:center;gap:0.5rem;">
        <?php
        the_posts_pagination([
          'mid_size'  => 2,
          'prev_text' => '← Anterior',
          'next_text' => 'Siguiente →',
        ]);
        ?>
      </div>

    <?php else : ?>
      <?php get_template_part( 'template-parts/content', 'none' ); ?>
    <?php endif; ?>

  </div>
</section>

<!-- Enlace de vuelta a todas las disciplinas -->
<div style="padding:3rem 4rem;border-top:1px solid var(--border);text-align:center;">
  <a href="<?php echo esc_url( home_url('/#disciplinas') ); ?>" class="btn btn-outline">
    ← Todas las disciplinas
  </a>
</div>

<?php get_footer(); ?>
