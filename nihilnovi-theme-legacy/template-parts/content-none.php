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
  <div class="s-eyebrow" style="justify-content:center;margin-bottom:1rem;">Todavía no hay contenido aquí</div>
  <p style="font-family:'Source Serif 4',serif;font-size:0.98rem;color:var(--ivory-3);line-height:1.8;max-width:440px;margin:0 auto 2rem;">
    <?php
    if ( is_search() ) {
      echo 'No se encontraron resultados para "' . esc_html( get_search_query() ) . '". Prueba con otras palabras.';
    } elseif ( is_category() ) {
      echo 'Esta sección está en construcción. El contenido llega pronto — semana a semana.';
    } else {
      echo 'El contenido de esta sección llegará pronto. El viaje empieza.';
    }
    ?>
  </p>
  <a href="<?php echo esc_url( home_url('/') ); ?>" class="btn btn-outline">Volver al inicio</a>
</div>
