<?php
/**
 * index.php — Nihil Novi
 * Plantilla de respaldo requerida por WordPress.
 * Se usa para el listado del blog cuando no hay otra plantilla específica.
 */
get_header();
?>

<main style="padding-top: 5rem;">

  <!-- Blog Header -->
  <section class="blog-hero" style="padding: 8rem 4rem 4rem;">
    <div class="blog-hero-inner">
      <div class="s-eyebrow"><?php echo esc_html__( 'El archivo', 'nihilnovi' ); ?></div>
      <h1 class="s-title">
        <?php
        if ( is_category() ) {
          single_cat_title();
        } elseif ( is_search() ) {
          printf( esc_html__( 'Resultados para: %s', 'nihilnovi' ), '<span class="search-query">' . esc_html( get_search_query() ) . '</span>' );
        } elseif ( is_archive() ) {
          the_archive_title();
        } else {
          echo wp_kses_post( __( 'Todos los <em>artículos</em>', 'nihilnovi' ) );
        }
        ?>
      </h1>
    </div>
  </section>

  <!-- Blog Grid -->
  <div class="nn-section" style="border-top: 1px solid var(--border);">
    <div class="section-inner">

      <?php if ( have_posts() ) : ?>
        <div style="border:1px solid var(--border);">
          <?php while ( have_posts() ) : the_post();
            $num = get_post_meta( get_the_ID(), '_article_num', true );
            $lesson_code = get_post_meta( get_the_ID(), '_lesson_code', true );
            $read_time = get_post_meta( get_the_ID(), '_read_time', true ) ?: nihilnovi_estimate_read_time( get_the_content() );
            $cat = get_the_category(); $cat_name = $cat ? $cat[0]->name : '';
            $cat_url  = $cat ? get_category_link( $cat[0]->term_id ) : '';
            $disc_class = nihilnovi_get_disc_class( get_the_ID() );
            $display_code = $lesson_code ?: ( $num ? str_pad($num,2,'0',STR_PAD_LEFT) : '' );
          ?>
          <article style="display:grid;grid-template-columns:90px 1fr;border-bottom:1px solid var(--border);transition:background .3s;"
                   onmouseover="this.style.background='var(--card)'" onmouseout="this.style.background='transparent'">

            <!-- Número -->
            <div style="padding:2rem;border-right:1px solid var(--border);display:flex;flex-direction:column;align-items:center;justify-content:flex-start;gap:.4rem;">
              <?php if ( $display_code ) : ?>
                <span style="font-family:'JetBrains Mono',monospace;font-size:1rem;color:var(--gold);opacity:.2;line-height:1;">
                  <?php echo esc_html($display_code); ?>
                </span>
              <?php endif; ?>
              <span style="font-family:'Inter',sans-serif;font-size:.58rem;letter-spacing:.1em;text-transform:uppercase;color:var(--ivory-3);">
                <?php echo esc_html($read_time); ?>
              </span>
            </div>

            <!-- Contenido -->
            <div style="padding:2rem 2.5rem;">
              <div class="art-meta" style="margin-bottom:.7rem;">
                <?php if ($cat_name) : ?>
                  <a href="<?php echo esc_url($cat_url); ?>"
                     style="font-family:'Inter',sans-serif;font-size:.6rem;letter-spacing:.1em;text-transform:uppercase;color:var(--<?php echo esc_attr($disc_class); ?>);">
                    <?php echo esc_html( $cat_name ); ?>
                  </a>
                <?php endif; ?>
                <time style="font-family:'Inter',sans-serif;font-size:.6rem;color:var(--ivory-3);margin-left:auto;"
                      datetime="<?php echo get_the_date('c'); ?>">
                  <?php echo get_the_date('j M Y'); ?>
                </time>
              </div>
              <a href="<?php the_permalink(); ?>" class="art-title" style="font-size:1.2rem;">
                <?php the_title(); ?>
              </a>
              <p class="art-excerpt" style="margin-top:.6rem;margin-bottom:1rem;">
                <?php echo wp_trim_words( get_the_excerpt(), 24 ); ?>
              </p>
              <a href="<?php the_permalink(); ?>" class="art-cta"><?php echo esc_html__( 'Leer', 'nihilnovi' ); ?></a>
            </div>
          </article>
          <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <div style="margin-top: 3rem; display: flex; justify-content: center;">
          <?php the_posts_pagination([
            'mid_size'  => 2,
            'prev_text' => esc_html__( '← Anterior', 'nihilnovi' ),
            'next_text' => esc_html__( 'Siguiente →', 'nihilnovi' ),
          ]); ?>
        </div>

      <?php else : ?>
        <div style="text-align: center; padding: 5rem 0;">
          <p class="s-desc"><?php echo wp_kses_post( __( 'Aún no hay artículos publicados.<br>El viaje empieza pronto.', 'nihilnovi' ) ); ?></p>
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-gold" style="margin-top: 2rem; display: inline-block;"><?php echo esc_html__( 'Volver al inicio', 'nihilnovi' ); ?></a>
        </div>
      <?php endif; ?>

    </div>
  </div>

</main>

<?php get_footer(); ?>
