<?php
/**
 * content-none.php — Nihil Novi
 * Estado vacío — se muestra cuando no hay entradas en el listado.
 * Uso: get_template_part('template-parts/content', 'none');
 */
?>
<div style="padding:6rem 2rem;text-align:center;border:1px dashed var(--border-2);grid-column:1/-1;">
  <div style="font-family:'Playfair Display',serif;font-size:4rem;color:var(--gold);opacity:0.15;margin-bottom:1.5rem;" aria-hidden="true">
    ∅
  </div>
  <div class="s-eyebrow" style="justify-content:center;margin-bottom:1rem;"><?php echo esc_html__( 'Todavía no hay contenido aquí', 'nihilnovi' ); ?></div>
  <p style="font-family:'Source Serif 4',serif;font-size:0.98rem;color:var(--ivory-3);line-height:1.8;max-width:440px;margin:0 auto 2rem;">
    <?php
    if ( is_search() ) {
      printf( esc_html__( 'No se encontraron resultados para "%s". Prueba con otras palabras.', 'nihilnovi' ), esc_html( get_search_query() ) );
    } elseif ( is_category() ) {
      echo esc_html__( 'Esta sección está en construcción. El contenido llega pronto — semana a semana.', 'nihilnovi' );
    } else {
      echo esc_html__( 'El contenido de esta sección llegará pronto. El viaje empieza.', 'nihilnovi' );
    }
    ?>
  </p>
  <a href="<?php echo esc_url( home_url('/') ); ?>" class="btn btn-outline"><?php echo esc_html__( 'Volver al inicio', 'nihilnovi' ); ?></a>
</div>
