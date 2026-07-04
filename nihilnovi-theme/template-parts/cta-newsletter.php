<?php
/**
 * Template part: CTA de newsletter.
 *
 * @package Nihil Novi
 */
?>
<section class="nn-cta-newsletter" aria-label="<?php echo esc_attr__( 'Newsletter', 'nihilnovi' ); ?>">
	<div class="cta-newsletter-inner">
		<h4 class="cta-newsletter-title"><?php echo esc_html__( 'Recibe el análisis antes que nadie', 'nihilnovi' ); ?></h4>
		<p class="cta-newsletter-text">
			<?php echo esc_html__( 'Un correo semanal con ideas de filosofía, economía y operaciones aplicadas a negocios reales. Sin ruido, sin spam.', 'nihilnovi' ); ?>
		</p>

		<?php if ( function_exists( 'mc4wp_show_form' ) ) : ?>
			<?php mc4wp_show_form(); ?>
		<?php else : ?>
			<form class="cta-newsletter-form" action="#" method="post" novalidate>
				<label for="nn-newsletter-email" class="screen-reader-text">
					<?php echo esc_html__( 'Correo electrónico', 'nihilnovi' ); ?>
				</label>
				<input
					type="email"
					id="nn-newsletter-email"
					name="nn-newsletter-email"
					placeholder="<?php echo esc_attr__( 'Tu correo electrónico', 'nihilnovi' ); ?>"
					required
				/>
				<button type="submit" class="btn btn-gold">
					<?php echo esc_html__( 'Suscribirme', 'nihilnovi' ); ?>
				</button>
			</form>
			<p class="cta-newsletter-note">
				<?php echo esc_html__( 'Formulario estático: conecta Mailchimp for WP para activar envíos.', 'nihilnovi' ); ?>
			</p>
		<?php endif; ?>
	</div>
</section>
