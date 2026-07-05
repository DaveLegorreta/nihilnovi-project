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
$cat_id      = $cat ? $cat->term_id : 0;
$is_lesson   = ( $cat_slug === 'leccion' || $cat_slug === 'lecciones' );

// Detectar si es categoría padre (tiene hijos)
$child_cats = get_categories(['parent' => $cat_id, 'hide_empty' => false]);
$is_parent  = !empty($child_cats);

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

// Símbolos filosóficos/griegos para animación de fondo
$philosophy_symbols = ['α','β','γ','δ','ε','ζ','η','θ','λ','μ','ξ','π','ρ','σ','τ','φ','χ','ψ','ω','∴','∵','⊥','∧','∨','¬','∀','∃','∈','∉','⊂','⊃','∪','∩','∅','∞','∂','∇','∫','∑','∏','√','±','∓','×','÷','·','°','∠','∟','⊾','⊿','⋮','⋯','⋰','⋱','§','¶','†','‡','‖','‽','¿','¡','«','»','‹','›','„','"','"','\'','\'','‚','‘','’','…','–','—','‐','‑','‒','‾','_','·','•','◦','‣','⁃','-','*','†','‡','•','·','▪','▫','◾','◽','◆','◇','◈','▣','▤','▥','▦','▧','▨','▩','▬','▭','▮','▯','▰','▱','▲','△','▴','▵','▸','▹','►','▻','▼','▽','▾','▿','◀','◁','◂','◃','◄','◅','◆','◇','◈','◉','◊','○','◌','◍','◎','●','◐','◑','◒','◓','◔','◕','◖','◗','◘','◙','◚','◛','◜','◝','◞','◟','◠','◡','◢','◣','◤','◥','◦','◧','◨','◩','◪','◫','◬','◭','◮','◯','◰','◱','◲','◳','◴','◵','◶','◷','◸','◹','◺','◻','◼','◽','◾','◿'];

// Términos filosóficos alemanes para segundo layer de animación
$german_terms = ['Sein','Dasein','Weltanschauung','Erkenntnis','Vernunft','Aufhebung','Gestalt','Zeit','Raum','Ursache','Wirkung','Begriff','Wahrheit','Freiheit','Gewissen','Sittlichkeit','Schönheit','Erscheinung','Wesen','Grund','Existenz','Transzendenz','Immanenz','Ontologie','Phänomenologie','Hermeneutik','Dialektik','Subjekt','Objekt','Synthesis','Analyse','Thesis','Antithesis','Kategorisch','Apriori','Aposteriori','Synthetic','Analytisch','Deduktion','Induktion','Abduktion','Pragmatik','Semantik','Syntax','Logik','Ethik','Ästhetik','Metaphysik','Epistemologie','Teleologie','Kausalität','Noumenon','Phänomenon','Monadologie','Leibniz','Kant','Hegel','Heidegger','Husserl','Wittgenstein','Nietzsche','Schopenhauer','Fichte','Schelling','Marx','Engels','Adorno','Horkheimer','Marcuse','Habermas','Gadamer','Ricoeur','Derrida','Foucault','Deleuze','Guattari','Lyotard','Baudrillard','Vattimo','Agamben','Negri','Zizek','Badiou','Ranciere','Balibar','Laclau','Mouffe','Butler','Spivak','Said','Chakrabarty','Bhabha','Glissant','Césaire','Fanon','Memmi','Sartre','Camus','Merleau-Ponty','Beauvoir','Arendt','Strauss','Voegelin','Oakeshott','Berlin','Popper','Kuhn','Feyerabend','Lakatos','Laudan','Putnam','Quine','Davidson','Rawls','Nozick','Dworkin','Hart','Kelsen','Schmitt','Strauss','Voegelin','Oakeshott','Berlin','Popper','Kuhn','Feyerabend','Lakatos','Laudan','Putnam','Quine','Davidson','Rawls','Nozick','Dworkin','Hart','Kelsen','Schmitt'];
?>

<!-- ══════════ ARCHIVE HERO ══════════ -->
<section class="archive-hero" aria-label="<?php echo esc_attr( sprintf( __( 'Archivo de %s', 'nihilnovi' ), $cat_name ) ); ?>">
  
  <!-- Símbolos filosóficos flotantes (animación tipo sistema solar) -->
  <div class="archive-symbols" aria-hidden="true">
    <?php 
    $total_symbols = count($philosophy_symbols);
    for ($i = 0; $i < 24; $i++) : 
      $symbol = $philosophy_symbols[$i % $total_symbols];
      $left = rand(5, 95);
      $top = rand(5, 90);
      $size = rand(12, 28);
      $delay = $i * 0.8;
      $duration = rand(15, 35);
      $opacity = rand(3, 12) / 100;
    ?>
      <span class="floating-symbol" style="left:<?php echo $left; ?>%;top:<?php echo $top; ?>%;font-size:<?php echo $size; ?>px;animation-delay:<?php echo $delay; ?>s;animation-duration:<?php echo $duration; ?>s;opacity:<?php echo $opacity; ?>;"><?php echo $symbol; ?></span>
    <?php endfor; ?>
  </div>

  <!-- Términos filosóficos alemanes flotantes (segundo layer, más difuso) -->
  <div class="archive-terms" aria-hidden="true">
    <?php 
    $total_terms = count($german_terms);
    for ($i = 0; $i < 12; $i++) : 
      $term = $german_terms[$i % $total_terms];
      $left = rand(2, 98);
      $top = rand(5, 85);
      $size = rand(14, 22);
      $delay = $i * 1.5 + 2;
      $duration = rand(40, 70);
      $opacity = rand(4, 10) / 100;
    ?>
      <span class="floating-term" style="left:<?php echo $left; ?>%;top:<?php echo $top; ?>%;font-size:<?php echo $size; ?>px;animation-delay:<?php echo $delay; ?>s;animation-duration:<?php echo $duration; ?>s;opacity:<?php echo $opacity; ?>;"><?php echo $term; ?></span>
    <?php endfor; ?>
  </div>

  <!-- Línea de color de la disciplina -->
  <div class="archive-hero-line" style="background:linear-gradient(90deg,transparent,<?php echo esc_attr($disc_color); ?>,transparent);"></div>

  <div class="archive-hero-inner">

    <!-- Migas de pan -->
    <nav class="breadcrumb" aria-label="<?php echo esc_attr__( 'Migas de pan', 'nihilnovi' ); ?>">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__( 'Inicio', 'nihilnovi' ); ?></a>
      <span aria-hidden="true">/</span>
      <span class="breadcrumb-current" aria-current="page"><?php echo esc_html( $cat_name ); ?></span>
    </nav>

    <div class="post-meta-row" style="margin-bottom:1rem;">
      <span style="font-family:'JetBrains Mono',monospace;font-size:0.68rem;color:<?php echo esc_attr($disc_color); ?>;background:rgba(<?php
        list($r,$g,$b) = sscanf($disc_color,'#%02x%02x%02x');
        echo "$r,$g,$b";
      ?>,0.10);border:1px solid rgba(<?php echo "$r,$g,$b"; ?>,0.25);padding:0.25rem 0.65rem;">
        <?php echo esc_html( $disc_code ); ?>
      </span>
      <span style="font-family:'Inter',sans-serif;font-size:0.65rem;letter-spacing:0.15em;text-transform:uppercase;color:var(--ivory-3);">
        <?php echo $is_lesson ? esc_html__( 'Lecciones', 'nihilnovi' ) : esc_html__( 'Disciplina', 'nihilnovi' ); ?>
      </span>
    </div>

    <h1 class="post-title" style="font-size:clamp(2rem,5vw,3.8rem);margin-bottom:<?php echo $cat_desc ? '1rem' : '0'; ?>;">
      <?php echo esc_html( $cat_name ); ?>
    </h1>

    <?php if ( $cat_desc ) : ?>
      <p class="post-subtitle" style="max-width:600px;">
        <?php echo esc_html( $cat_desc ); ?>
      </p>
    <?php endif; ?>

    <!-- Contador de entradas -->
    <div style="margin-top:1rem;font-family:'Inter',sans-serif;font-size:0.65rem;letter-spacing:0.15em;text-transform:uppercase;color:var(--ivory-3);">
      <?php
      $count = $cat ? $cat->count : 0;
      echo esc_html( $count ) . ' ' . esc_html( $is_lesson
        ? _n( 'lección', 'lecciones', $count, 'nihilnovi' )
        : _n( 'entrada', 'entradas', $count, 'nihilnovi' )
      );
      ?>
    </div>
  </div>
</section>

<?php if ( $is_parent && ! $is_lesson ) : ?>
<!-- ══════════ VISTA PADRE: GRID DE COLECCIONES ══════════ -->
<section class="nn-section" aria-label="<?php echo esc_attr( sprintf( __( 'Colecciones de %s', 'nihilnovi' ), $cat_name ) ); ?>">
  <div class="section-inner">
    
    <?php
    // Separar subcategorías por tipo (épocas vs temas)
    // Épocas: slugs predefinidos
    $epoca_slugs = ['presocraticos', 'clasicos', 'helenisticos', 'medievales', 'modernos', 'contemporaneos', 'historia-antigua', 'historia-medieval', 'historia-moderna', 'historia-contemporanea'];
    $epocas = [];
    $temas = [];
    
    foreach ( $child_cats as $subcat ) {
      if ( in_array( $subcat->slug, $epoca_slugs, true ) ) {
        $epocas[] = $subcat;
      } else {
        $temas[] = $subcat;
      }
    }
    
    // Mostrar grupos de subcategorías
    $groups = [
        __( 'Por época', 'nihilnovi' )  => $epocas,
        __( 'Por tema', 'nihilnovi' )   => $temas,
    ];
    foreach ( $groups as $group_label => $group_cats ) {
        if ( empty( $group_cats ) ) continue;
    ?>
      <div class="subcategory-group">
        <h2 class="subcategory-group-title"><?php echo esc_html( $group_label ); ?></h2>
        <?php foreach ( $group_cats as $subcat ) :
          $subcat_posts = new WP_Query([
            'cat' => $subcat->term_id,
            'posts_per_page' => 4,
          ]);
          if ( $subcat_posts->have_posts() ) :
        ?>
          <div class="subcategory-row">
            <div class="subcategory-header">
              <div class="subcategory-title-group">
                <h3 class="subcategory-title"><?php echo esc_html( $subcat->name ); ?></h3>
                <span class="subcategory-count"><?php echo esc_html( $subcat->count ); ?> <?php echo esc_html( _n( 'artículo', 'artículos', $subcat->count, 'nihilnovi' ) ); ?></span>
              </div>
              <a href="<?php echo esc_url( get_category_link( $subcat->term_id ) ); ?>" class="subcategory-link">
                <?php echo esc_html__( 'Ver todos →', 'nihilnovi' ); ?>
              </a>
            </div>
            <div class="playlist-grid">
              <?php while ( $subcat_posts->have_posts() ) : $subcat_posts->the_post(); 
                get_template_part( 'template-parts/card', 'playlist' );
              endwhile; wp_reset_postdata(); ?>
            </div>
          </div>
        <?php endif; endforeach; ?>
      </div>
    <?php } ?>
    
  </div>
</section>

<?php else : ?>
<!-- ══════════ VISTA HIJO O LECCIÓN: LISTADO EDITORIAL ══════════ -->
<?php
// Forzar 9 items por página para grid 3x3 sin espacios vacíos
if ( ! $is_lesson ) {
    global $wp_query;
    $args = array_merge( $wp_query->query_vars, ['posts_per_page' => 9] );
    query_posts( $args );
}
?>
<section class="nn-section" aria-label="<?php echo esc_attr( sprintf( __( 'Listado de %s', 'nihilnovi' ), $cat_name ) ); ?>">
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
        <!-- Vista de artículos: grid de tarjetas -->
        <div class="articles-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:var(--border);border:1px solid var(--border);">
          <?php while ( have_posts() ) : the_post();
            $art_num = get_post_meta(get_the_ID(),'_article_num',true);
            $cat = get_the_category(); $cat_name = $cat ? $cat[0]->name : ''; $disc_class = nihilnovi_get_disc_class(get_the_ID());
          ?>
          <article class="art-card" style="background:var(--card);padding:2rem;transition:background .3s;">
            <div class="art-meta" style="margin-bottom:0.8rem;">
              <?php if ($art_num) : ?><span class="art-num"><?php echo str_pad(esc_html($art_num),2,'0',STR_PAD_LEFT); ?></span><?php endif; ?>
              <?php if ($cat_name) : ?><span class="art-cat <?php echo esc_attr($disc_class); ?>" style="color:var(--<?php echo esc_attr($disc_class); ?>);"><?php echo esc_html($cat_name); ?></span><?php endif; ?>
              <span class="art-date"><?php echo get_the_date('j M Y'); ?></span>
            </div>
            <a href="<?php the_permalink(); ?>" class="art-title" style="font-size:1.1rem;"><?php the_title(); ?></a>
            <p class="art-excerpt" style="margin-top:0.6rem;"><?php echo wp_trim_words(get_the_excerpt(),18); ?></p>
            <a href="<?php the_permalink(); ?>" class="art-cta" style="margin-top:1rem;display:inline-block;"><?php echo esc_html__( 'Leer', 'nihilnovi' ); ?></a>
          </article>
          <?php endwhile; ?>
        </div>
      <?php endif; ?>
      <!-- Paginación -->
      <div style="margin-top:4rem;display:flex;justify-content:center;gap:0.5rem;">
        <?php
        the_posts_pagination([
          'mid_size'  => 2,
          'prev_text' => esc_html__( '← Anterior', 'nihilnovi' ),
          'next_text' => esc_html__( 'Siguiente →', 'nihilnovi' ),
        ]);
        ?>
      </div>
    <?php else : ?>
      <?php get_template_part( 'template-parts/content', 'none' ); ?>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

<!-- Enlace de vuelta a todas las disciplinas -->
<div style="padding:3rem 4rem;border-top:1px solid var(--border);text-align:center;">
  <a href="<?php echo esc_url( home_url('/#disciplinas') ); ?>" class="btn btn-outline">
    <?php echo esc_html__( '← Todas las disciplinas', 'nihilnovi' ); ?>
  </a>
</div>

<?php get_footer(); ?>
