<!-- ─── FOOTER SECTION ────────────────────────────────────────
     Renders the copyright, social media links, and footer menu.
-->
<!-- ══════════ NEWSLETTER ══════════ -->
<?php get_template_part( 'template-parts/cta', 'newsletter' ); ?>

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
    $socials = [
        'twitter'   => [ 'label' => __( 'X', 'nihilnovi' ),  'icon' => 'X'  ],
        'instagram' => [ 'label' => __( 'IG', 'nihilnovi' ), 'icon' => 'IG' ],
        'linkedin'  => [ 'label' => __( 'LI', 'nihilnovi' ), 'icon' => 'LI' ],
        'youtube'   => [ 'label' => __( 'YT', 'nihilnovi' ), 'icon' => 'YT' ],
    ];
    $has_social = false;
    foreach ( $socials as $key => $data ) {
        $url = get_theme_mod( 'nihilnovi_social_' . $key, '' );
        if ( $url ) {
            if ( ! $has_social ) {
                echo '<div class="footer-socials" aria-label="' . esc_attr__( 'Redes sociales', 'nihilnovi' ) . '">';
                $has_social = true;
            }
            echo '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr( $data['label'] ) . '" class="footer-social-link">' . esc_html( $data['icon'] ) . '</a>';
        }
    }
    if ( $has_social ) {
        echo '</div>';
    }
    ?>

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
