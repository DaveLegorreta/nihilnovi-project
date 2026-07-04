<?php
/**
 * template-parts/card-playlist.php — Nihil Novi
 * Tarjeta de artículo para el grid tipo playlist (archive padre).
 */
$post_id    = get_the_ID();
$read_time  = get_post_meta( $post_id, '_read_time', true ) ?: nihilnovi_estimate_read_time( get_the_content() );
$disc_class = nihilnovi_get_disc_class( $post_id );
$title_initial = mb_substr( get_the_title(), 0, 1 );
?>
<article class="playlist-card">
  <a href="<?php the_permalink(); ?>" class="playlist-card-thumb" data-initial="<?php echo esc_attr( $title_initial ); ?>">
    <?php if ( has_post_thumbnail() ) : ?>
      <?php the_post_thumbnail( 'medium', ['class' => 'playlist-card-thumb-img'] ); ?>
    <?php endif; ?>
  </a>
  <div class="playlist-card-body">
    <div class="playlist-card-meta">
      <?php if ( $read_time ) : ?>
        <span class="playlist-card-time"><?php echo esc_html( $read_time ); ?></span>
      <?php endif; ?>
      <span class="playlist-card-discipline <?php echo esc_attr( $disc_class ); ?>">
        <?php echo esc_html( strtoupper( $disc_class ) ); ?>
      </span>
    </div>
    <a href="<?php the_permalink(); ?>" class="playlist-card-title">
      <?php the_title(); ?>
    </a>
    <p class="playlist-card-excerpt">
      <?php echo wp_trim_words( get_the_excerpt(), 18 ); ?>
    </p>
    <a href="<?php the_permalink(); ?>" class="playlist-card-cta">
      <?php echo esc_html__( 'Leer →', 'nihilnovi' ); ?>
    </a>
  </div>
</article>
