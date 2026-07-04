<?php
/**
 * Template part: CTA de newsletter (diseño nativo de homepage).
 *
 * Usa el mismo estilo visual que la sección .nn-newsletter original de front-page.php.
 * Ahora se usa en footer (global) y al final de artículos (single.php).
 *
 * @package Nihil Novi
 */
?>
<section class="nn-newsletter fade" aria-label="<?php echo esc_attr__( 'Suscripción', 'nihilnovi' ); ?>">
	<div class="newsletter-inner">
		<div class="s-eyebrow" style="justify-content:center;margin-bottom:1.4rem;"><?php echo esc_html__( 'El viaje, en tu correo', 'nihilnovi' ); ?></div>
		<h2><?php echo esc_html__( 'Una entrega por semana.', 'nihilnovi' ); ?><br><em><?php echo esc_html__( 'Sin algoritmos.', 'nihilnovi' ); ?></em></h2>
		<p><?php echo esc_html__( 'Artículos, lecciones y el material de estudio de esa semana. Directo. Sin curation de plataforma.', 'nihilnovi' ); ?></p>

		<?php if ( function_exists( 'mc4wp_show_form' ) ) : ?>
			<?php mc4wp_show_form(); ?>
		<?php else : ?>
			<form class="form-row" method="post" novalidate>
				<input
					type="email"
					name="email"
					placeholder="<?php echo esc_attr__( 'tu@correo.com', 'nihilnovi' ); ?>"
					required
					autocomplete="email"
					aria-label="<?php echo esc_attr__( 'Tu correo', 'nihilnovi' ); ?>"
				/>
				<button type="submit"><?php echo esc_html__( 'Suscribirme', 'nihilnovi' ); ?></button>
			</form>
		<?php endif; ?>

		<p class="form-note"><?php echo esc_html__( 'Sin spam · Sin venta de datos · Baja cuando quieras', 'nihilnovi' ); ?></p>
	</div>
</section>
