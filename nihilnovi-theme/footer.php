<!-- ─── FOOTER SECTION ────────────────────────────────────────
     Renders the copyright, social media links, and footer menu.
-->
<!-- ══════════ FOOTER ══════════ -->
<footer class="nn-footer" role="contentinfo">

  <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-brand">Nihil Novi</a>

  <?php
  wp_nav_menu([
    'theme_location' => 'footer',
    'container'      => false,
    'menu_class'     => 'footer-nav',
    'items_wrap'     => '<ul class="footer-nav">%3$s</ul>',
    'fallback_cb'    => false,
  ]);
  ?>

  <div style="display:flex;align-items:center;gap:1.5rem;">
    <?php
    // Redes sociales desde el Customizer
    if ( function_exists('nihilnovi_social_links') ) :
      $socials = nihilnovi_social_links();
      if ( ! empty($socials) ) :
    ?>
    <div style="display:flex;gap:0.8rem;" aria-label="Redes sociales">
      <?php foreach ( $socials as $key => $s ) : ?>
        <a href="<?php echo esc_url($s['url']); ?>"
           target="_blank" rel="noopener noreferrer"
           aria-label="<?php echo esc_attr($s['label']); ?>"
           style="font-family:'Inter',sans-serif;font-size:0.6rem;letter-spacing:0.1em;
                  text-transform:uppercase;color:var(--ivory-3);
                  border:1px solid var(--border-2);padding:0.3rem 0.6rem;
                  transition:all .3s;"
           onmouseover="this.style.color='var(--gold)';this.style.borderColor='var(--gold-border)'"
           onmouseout="this.style.color='var(--ivory-3)';this.style.borderColor='var(--border-2)'">
          <?php echo esc_html($s['icon']); ?>
        </a>
      <?php endforeach; ?>
    </div>
    <?php endif; endif; ?>

    <span class="footer-copy">
      <?php
      $copy = get_theme_mod('nihilnovi_footer_copy', '© ' . date('Y') . ' David Legorreta · nihilnovi.xyz');
      echo esc_html($copy);
      ?>
    </span>
  </div>

</footer>

<?php wp_footer(); ?>

<!-- ══════════ MOBILE MENU STYLES ══════════ -->
<style>
.mobile-menu {
  position: fixed; top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(9,9,15,0.98); z-index: 190;
  display: flex; align-items: center; justify-content: center;
  opacity: 0; pointer-events: none; transition: opacity 0.35s;
}
.mobile-menu.open { opacity: 1; pointer-events: all; }
.mobile-nav-list { list-style: none; text-align: center; display: flex; flex-direction: column; gap: 2rem; }
.mobile-nav-list a { font-family: 'Playfair Display', serif; font-size: 2rem; color: var(--ivory); transition: color 0.3s; }
.mobile-nav-list a:hover { color: var(--gold); }
.nav-toggle.open span:nth-child(1) { transform: rotate(45deg) translate(4px, 4px); }
.nav-toggle.open span:nth-child(2) { opacity: 0; }
.nav-toggle.open span:nth-child(3) { transform: rotate(-45deg) translate(4px, -4px); }
</style>



</body>
</html>
