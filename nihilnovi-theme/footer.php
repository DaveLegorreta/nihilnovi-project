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

  <div class="footer-socials-wrap">
    <?php
    // Redes sociales desde el Customizer
    if ( function_exists('nihilnovi_social_links') ) :
      $socials = nihilnovi_social_links();
      if ( ! empty($socials) ) :
    ?>
    <div class="footer-socials" aria-label="<?php echo esc_attr__( 'Redes sociales', 'nihilnovi' ); ?>">
      <?php foreach ( $socials as $key => $s ) : ?>
        <a href="<?php echo esc_url($s['url']); ?>"
           target="_blank" rel="noopener noreferrer"
           aria-label="<?php echo esc_attr($s['label']); ?>"
           class="footer-social-link">
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

</body>
</html>
