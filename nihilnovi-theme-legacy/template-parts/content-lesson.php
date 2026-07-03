<?php
/**
 * content-lesson.php — Nihil Novi
 * Tarjeta de lección reutilizable para index.php, archive.php y homepage.
 * Uso: get_template_part('template-parts/content', 'lesson');
 */
$post_id     = get_the_ID();
$lesson_code = get_post_meta( $post_id, '_lesson_code', true ) ?: 'NN-01';
$read_time   = get_post_meta( $post_id, '_read_time', true ) ?: nihilnovi_estimate_read_time( get_the_content() );
?>

<a href="<?php the_permalink(); ?>" class="lesson-card" id="post-<?php the_ID(); ?>">
  <div class="lesson-top">
    <span class="lesson-code"><?php echo esc_html( $lesson_code ); ?></span>
    <span class="lesson-time"><?php echo esc_html( $read_time ); ?></span>
  </div>
  <span class="lesson-title"><?php the_title(); ?></span>
  <p class="lesson-summary"><?php echo wp_trim_words( get_the_excerpt(), 18 ); ?></p>
  <span class="lesson-arrow" aria-hidden="true">→</span>
</a>
