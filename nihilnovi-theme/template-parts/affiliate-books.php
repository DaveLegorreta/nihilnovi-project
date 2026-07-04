<?php
/**
 * Template part: Bloque de libros recomendados (afiliados Amazon Associates).
 *
 * Puedes pasar un array de libros desde single.php mediante:
 *   get_template_part( 'template-parts/affiliate-books', null, ['books' => $books] );
 *
 * Si no se pasa nada, se usa un array de ejemplo con clásicos filosóficos.
 *
 * IMPORTANTE: Reemplaza YOUR-ASSOCIATE-TAG en las URLs por tu tag real de Amazon Associates.
 */

$args = get_query_var( 'affiliate_books_args' );
if ( ! is_array( $args ) ) {
	$args = $args ?: [];
}

$books = $args['books'] ?? [
	[
		'title'  => __( 'La República', 'nihilnovi' ),
		'author' => __( 'Platón', 'nihilnovi' ),
		'cover'  => '', // Dejar vacío para placeholder.
		'asin'   => 'B0XXXXPLATO',
	],
	[
		'title'  => __( 'Ética a Nicómaco', 'nihilnovi' ),
		'author' => __( 'Aristóteles', 'nihilnovi' ),
		'cover'  => '',
		'asin'   => 'B0XXXXARIST',
	],
	[
		'title'  => __( 'Crítica de la Razón Pura', 'nihilnovi' ),
		'author' => __( 'Immanuel Kant', 'nihilnovi' ),
		'cover'  => '',
		'asin'   => 'B0XXXXKANT1',
	],
];

$books = array_slice( $books, 0, 3 );
if ( empty( $books ) ) {
	return;
}

$associate_tag = apply_filters( 'nihilnovi_amazon_associate_tag', 'YOUR-ASSOCIATE-TAG' );
?>

<!-- ══════════ LIBROS RECOMENDADOS (AFILIADOS) ══════════ -->
<!-- Reemplaza YOUR-ASSOCIATE-TAG por tu tag real de Amazon Associates. -->
<section class="nn-affiliate-books" aria-label="<?php echo esc_attr__( 'Libros recomendados', 'nihilnovi' ); ?>">
	<div class="affiliate-books-inner">
		<h4 class="affiliate-books-title"><?php echo esc_html__( 'Libros recomendados', 'nihilnovi' ); ?></h4>
		<p class="affiliate-books-note"><?php echo esc_html__( 'Enlaces de afiliado: si compras a través de ellos, el proyecto recibe una pequeña comisión sin costo extra para ti.', 'nihilnovi' ); ?></p>

		<div class="affiliate-books-grid">
			<?php foreach ( $books as $book ) :
				$title  = $book['title']  ?? '';
				$author = $book['author'] ?? '';
				$cover  = $book['cover']  ?? '';
				$asin   = $book['asin']   ?? '';
				$url    = 'https://www.amazon.com/dp/' . ( $asin ?: 'ASIN' ) . '?tag=' . esc_attr( $associate_tag );
			?>
			<article class="affiliate-book-card">
				<?php if ( $cover ) : ?>
					<div class="affiliate-book-cover">
						<img src="<?php echo esc_url( $cover ); ?>" alt="<?php echo esc_attr( sprintf( __( 'Portada de %s', 'nihilnovi' ), $title ) ); ?>" loading="lazy" />
					</div>
				<?php else : ?>
					<div class="affiliate-book-cover affiliate-book-cover-placeholder" aria-hidden="true">
						<span><?php echo esc_html( mb_substr( $title, 0, 1 ) ); ?></span>
					</div>
				<?php endif; ?>
				<div class="affiliate-book-meta">
					<h5 class="affiliate-book-name"><?php echo esc_html( $title ); ?></h5>
					<?php if ( $author ) : ?>
						<p class="affiliate-book-author"><?php echo esc_html( $author ); ?></p>
					<?php endif; ?>
				</div>
				<a href="<?php echo esc_url( $url ); ?>" class="btn btn-gold affiliate-book-cta" target="_blank" rel="sponsored noopener">
					<?php echo esc_html__( 'Ver en Amazon', 'nihilnovi' ); ?>
				</a>
			</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
