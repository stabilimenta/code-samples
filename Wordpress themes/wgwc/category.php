<?php
/**
 * The template for displaying Category pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage WGWC
 */


	get_header(); 
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
?>
<div class="page-body has-sidebar">
	<div class="page-main full-width">
		<div id="primary">
			<div id="main-content" class="content">
				<div class="title-wrapper">
					<h2><?php echo single_cat_title( '', false ); ?></h2>
					<?php
						// Show an optional term description.
						$term_description = term_description();
						if ( ! empty( $term_description ) ) :
							echo "<div class=\"taxonomy-description\">" . $term_description . "</div>\n";
						endif;
					?>
				</div><!-- /.title-wrapper -->
				<?php if ( function_exists( 'evcd_content_nav' ) ) evcd_content_nav( 'nav-above' ); ?>
				<div class="content archive-loop">
					<?php while ( have_posts() ) : the_post(); ?>
						<article id="post-<?php the_ID(); ?>">
							<?php if ( has_post_thumbnail() ) { ?>
								<div class="thumbnail">
									<a href="<?php echo get_permalink(); ?>"><?php the_post_thumbnail('thumb'); ?></a>
								</div>
							<?php } ?>
							<div class="txt-wrap">
								<div class="title-wrapper">
									<?php 
										$categories = get_the_category();
										$separator = ', ';
										$output = '';
										if ( ! empty( $categories ) ) {
											echo "<div class=\"surhead banded cats\"><span>";
												foreach( $categories as $category ) {
													$output .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . esc_attr( sprintf( __( 'View all posts in %s', 'gibson-realty' ), $category->name ) ) . '">' . esc_html( $category->name ) . '</a>' . $separator;
												}
												echo trim( $output, $separator );
											echo "</span></div>\n";
										}
									?>
									<h3 class="post-title"><?php the_title(); ?></h3>
									<div class="date"><?php echo get_the_date('F j, Y'); ?></div>
								</div><!-- .title-wrapper -->
								<div class="entry-summary">
									<?php the_excerpt(); ?>
								</div><!-- .entry-summary -->
							</div><!-- .txt-wrap -->
							<div class="btn btn-small"><a href="<?php echo get_permalink(); ?>" title="Read More">Read More</a></div>
						</article>
					<?php endwhile; ?>
				</div><!-- /.archive-loop -->
				<?php if ( function_exists( 'evcd_content_nav' ) ) evcd_content_nav( 'nav-below' ); ?>
			</div><!-- #content -->

		</div><!-- /#primary -->
		<?php get_sidebar('blog'); ?>
	</div><!-- /.page-main -->
</div><!-- /.page-body -->

<?php get_footer(); ?>