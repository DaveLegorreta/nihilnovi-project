<?php
/**
 * 404.php — Nihil Novi
 * Página de error 404 — elegante y útil.
 */
get_header();
?>

<section style="min-height:100vh;display:flex;align-items:center;justify-content:center;
                padding:9rem 4rem 6rem;position:relative;overflow:hidden;"
         aria-label="Página no encontrada">

  <!-- Fondo atmosférico -->
  <div class="blob blob-1" style="opacity:0.25;" aria-hidden="true"></div>
  <div class="blob blob-2" style="opacity:0.15;" aria-hidden="true"></div>

  <!-- Número 404 de fondo -->
  <div aria-hidden="true" style="position:absolute;font-family:'Playfair Display',serif;
       font-size:clamp(15rem,35vw,30rem);font-weight:400;color:var(--gold);
       opacity:0.04;line-height:1;top:50%;left:50%;
       transform:translate(-50%,-50%);pointer-events:none;user-select:none;
       white-space:nowrap;">
    404
  </div>

  <!-- Contenido central -->
  <div style="position:relative;z-index:1;text-align:center;max-width:600px;">

    <div class="s-eyebrow" style="justify-content:center;margin-bottom:1.8rem;">
      Error 404
    </div>

    <h1 style="font-family:'Playfair Display',serif;
               font-size:clamp(2rem,5vw,3.5rem);
               font-weight:400;line-height:1.2;
               color:var(--ivory);margin-bottom:1.2rem;">
      Esta página no existe.<br>
      <em style="color:var(--gold);">Aún.</em>
    </h1>

    <p style="font-family:'Source Serif 4',serif;font-size:1rem;
              color:var(--ivory-3);line-height:1.8;margin-bottom:3rem;">
      O existió y cambió de dirección. O nunca existió y alguien inventó el link.
      En cualquier caso, no es el fin del mundo — es solo una página que no encontramos.
    </p>

    <!-- Acciones sugeridas -->
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;margin-bottom:3.5rem;">
      <a href="<?php echo esc_url( home_url('/') ); ?>" class="btn btn-gold">
        Volver al inicio
      </a>
      <a href="<?php echo esc_url( home_url('/?cat=el-viaje') ); ?>" class="btn btn-outline">
        Ver los artículos
      </a>
    </div>

    <!-- Buscador -->
    <div style="margin-bottom:3rem;">
      <div style="font-family:'Inter',sans-serif;font-size:0.65rem;
                  letter-spacing:0.15em;text-transform:uppercase;
                  color:var(--ivory-3);margin-bottom:1rem;">
        O busca lo que necesitas
      </div>
      <form role="search" method="get" action="<?php echo esc_url( home_url('/') ); ?>"
            style="display:flex;border:1px solid var(--border-2);max-width:420px;margin:0 auto;">
        <input type="search" name="s"
               placeholder="Buscar en nihilnovi.xyz"
               value="<?php echo esc_attr( get_search_query() ); ?>"
               style="flex:1;background:transparent;border:none;outline:none;
                      padding:0.9rem 1.2rem;font-family:'Inter',sans-serif;
                      font-size:0.85rem;color:var(--ivory);" />
        <button type="submit"
                style="background:var(--gold);border:none;padding:0.9rem 1.2rem;
                       font-family:'Inter',sans-serif;font-size:0.7rem;
                       letter-spacing:0.1em;text-transform:uppercase;
                       color:var(--black);white-space:nowrap;transition:background .25s;">
          Buscar
        </button>
      </form>
    </div>

    <!-- Links directos a disciplinas -->
    <div>
      <div style="font-family:'Inter',sans-serif;font-size:0.65rem;
                  letter-spacing:0.15em;text-transform:uppercase;
                  color:var(--ivory-3);margin-bottom:1rem;">
        O explora las disciplinas
      </div>
      <div style="display:flex;gap:0.5rem;flex-wrap:wrap;justify-content:center;">
        <a href="<?php echo esc_url( home_url('/?cat=filosofia') ); ?>" class="disc-pill fil">Filosofía</a>
        <a href="<?php echo esc_url( home_url('/?cat=economia') ); ?>" class="disc-pill eco">Economía</a>
        <a href="<?php echo esc_url( home_url('/?cat=matematicas') ); ?>" class="disc-pill mat">Matemáticas</a>
        <a href="<?php echo esc_url( home_url('/?cat=historia') ); ?>" class="disc-pill his">Historia</a>
        <a href="<?php echo esc_url( home_url('/?cat=ciencia') ); ?>" class="disc-pill cie">Ciencia</a>
      </div>
    </div>

  </div>
</section>

<?php get_footer(); ?>
