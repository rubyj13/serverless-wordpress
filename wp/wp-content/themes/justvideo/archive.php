<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package justvideo
 */

get_header(); ?>

	<?php get_template_part('template-parts/sidebar', 'left'); ?>

	<div class="content-wrap clear">

	<div id="primary" class="content-area full-width clear">
				
		<main id="main" class="site-main clear">

			<div class="breadcrumbs clear">
				<span class="breadcrumbs-nav">
					<a href="<?php echo esc_url(home_url()); ?>"><?php esc_html_e('Home', 'justvideo'); ?></a>
					<span class="post-category"><?php echo wp_kses_post( get_the_archive_title('') ); ?></span>
				</span>				
				<h1>
					<?php echo wp_kses_post( get_the_archive_title('') ); ?>					
				</h1>	
				<?php
					if (is_category()) {
						$term_description = term_description();
						if ( ! empty( $term_description ) ) {
							printf( '<div class="taxonomy-description">%s</div>', wp_kses_post($term_description) );
						}
					}
				?>			
			</div><!-- .breadcrumbs -->

			<div id="recent-content" class="content-loop">

				<?php

				if ( have_posts() ) :	
				
				$i = 1;		
					
				/* Start the Loop */
				while ( have_posts() ) : the_post();

					get_template_part('template-parts/content', 'loop');

					$i++;

				endwhile;

				else :

					get_template_part( 'template-parts/content', 'none' );

				endif; 

				?>

			</div><!-- #recent-content -->

			<?php get_template_part( 'template-parts/pagination', '' ); ?>

		</main><!-- .site-main -->

	</div><!-- #primary -->

	</div><!-- .content-wrap -->
	
<?php get_footer(); ?>

