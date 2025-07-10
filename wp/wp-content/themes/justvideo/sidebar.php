<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package justvideo
 */


?>

<aside id="secondary" class="widget-area sidebar">

<?php if ( is_active_sidebar( 'sidebar-1' ) && ( !is_single() ) ) { ?>

	<?php dynamic_sidebar( 'sidebar-1' ); ?>

<?php } else { ?>

	<?php if ( is_single() ) { ?>

	<?php
		// Get the taxonomy terms of the current page for the specified taxonomy.
		$terms = wp_get_post_terms( get_the_ID(), 'category', array( 'fields' => 'ids' ) );

		// Bail if the term empty.
		if ( empty( $terms ) ) {
			return;
		}

		// Posts query arguments.
		$query = array(
			'post__not_in' => array( get_the_ID() ),
			'tax_query'    => array(
				array(
					'taxonomy' => 'category',
					'field'    => 'id',
					'terms'    => $terms,
					'operator' => 'IN'
				)
			),
			'posts_per_page' => 10,
			'post_type'      => 'post',
		);

		// Allow dev to filter the query.
		$args = apply_filters( 'justvideo_related_posts_args', $query );

		// The post query
		$related = new WP_Query( $args );

		if ( $related->have_posts() ) : $i = 1; ?>

			<div class="entry-related clear">
				<?php while ( $related->have_posts() ) : $related->the_post(); ?>
					<div <?php post_class(); ?>>
						<?php if ( has_post_thumbnail() ) { ?>
							<a class="thumbnail-link" href="<?php the_permalink(); ?>">
								<div class="thumbnail-wrap">
									<?php 
										the_post_thumbnail('justvideo_post_thumb'); 
									?>
								</div><!-- .thumbnail-wrap -->
								<?php if( (justvideo_has_embed_code() || justvideo_has_embed()) ) { ?>
									<div class="icon-play"><i class="genericon genericon-play"></i></div>
								<?php } ?>							
							</a>
						<?php } ?>				
						<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<div class="entry-meta">
							<?php echo esc_html( human_time_diff(get_the_time('U'), current_time('timestamp')) ) . ' '.  esc_html( 'ago', 'justvideo' ); ?>
						</div>
					</div><!-- .hentry -->
				<?php $i++; endwhile; ?>
			</div><!-- .entry-related -->

		<?php endif;

		// Restore original Post Data.
		wp_reset_postdata();
	?>

	<?php } // if it post ?>

<?php } ?>

</aside><!-- #secondary -->

