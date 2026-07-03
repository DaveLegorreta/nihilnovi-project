<?php
/**
 * content-article.php — Nihil Novi
 * Tarjeta de artículo reutilizable para index.php, archive.php y relacionados.
 * Uso: get_template_part('template-parts/content', 'article');
 */
$post_id    = get_the_ID();
$art_num    = get_post_meta( $post_id, '_article_num', true );
$read_time  = get_post_meta( $post_id, '_read_time', true ) ?: nihilnovi_estimate_read_time( get_the_content() );
$cat        = get_the_category();
$cat_name   = $cat ? esc_html( $cat[0]->name ) : '';
$cat_url    = $cat ? get_category_link( $cat[0]->term_id ) : '';
$disc_class = nihilnovi_get_disc_class( $post_id );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('art-card-list'); ?>>
  <div style="display:flex;gap:0;border-bottom:1px solid var(--border);padding:2.2rem 0;transition:background .3s;">

    <!-- Número y meta -->
    <div style="min-width:100px;padding-right:2rem;flex-shrink:0;">
      <?php if ( $art_num ) : ?>
        <div style="font-family:'JetBrains Mono',monospace;font-size:1.2rem;color:var(--gold);opacity:0.25;line-height:1;margin-bottom:0.5rem;">
          <?php echo str_pad( esc_html($art_num), 2, '0', STR_PAD_LEFT ); ?>
        </div>
      <?php endif; ?>
      <?php if ( $read_time ) : ?>
        <div style="font-family:'Inter',sans-serif;font-size:0.6rem;letter-spacing:0.1em;text-transform:uppercase;color:var(--ivory-3);">
          <?php echo esc_html($read_time); ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- Contenido principal -->
    <div style="flex:1;">
      <div class="art-meta" style="margin-bottom:0.7rem;">
        <?php if ( $cat_name ) : ?>
          <a href="<?php echo esc_url($cat_url); ?>" class="art-cat <?php echo esc_attr($disc_class); ?>"
             style="color:var(--<?php echo esc_attr($disc_class); ?>);">
            <?php echo $cat_name; ?>
          </a>
        <?php endif; ?>
        <time class="art-date" datetime="<?php echo get_the_date('c'); ?>">
          <?php echo get_the_date('j M Y'); ?>
        </time>
      </div>

      <a href="<?php the_permalink(); ?>" class="art-title" style="font-size:1.2rem;">
        <?php the_title(); ?>
      </a>

      <p class="art-excerpt" style="margin-top:0.6rem;margin-bottom:1rem;">
        <?php echo wp_trim_words( get_the_excerpt(), 22 ); ?>
      </p>

      <a href="<?php the_permalink(); ?>" class="art-cta"><?php echo esc_html__( 'Leer', 'nihilnovi' ); ?></a>
    </div>

  </div>
</article>
