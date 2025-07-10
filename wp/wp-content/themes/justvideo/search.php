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
				<span class="post-category"><?php printf( esc_html( 'Search Results for %s', 'justvideo' ), '"' . get_search_query() . '"' ); ?></span>
			</span>				
			<h1>
				<?php printf( esc_html( 'Search Results for %s', 'justvideo' ), '"' . get_search_query() . '"' ); ?>			
			</h1>	
		</div><!-- .breadcrumbs -->

		<div id="recent-content" class="content-loop">

			<?php

			if ( have_posts() ) :	
						
			/* Start the Loop */
			while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */

				get_template_part( 'template-parts/content', 'loop' );

				endwhile;

				else :

					get_template_part( 'template-parts/content', 'none' );

				?>

			<?php endif; ?>

		</div>

		<?php get_template_part( 'template-parts/pagination', '' ); ?>		

		</main><!-- .site-main -->

	</div><!-- #primary -->

	</div><!-- .content-wrap -->

<?php get_footer(); ?>

