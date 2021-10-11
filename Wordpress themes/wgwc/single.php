<?php
/**
 * The Template for displaying all single posts
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
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>">
					<?php if ( has_post_thumbnail() ) {
						include( locate_template( 'featured-photo.php', false, false ) );
					} ?>
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
							<h2 class="post-title"><?php the_title(); ?></h2>
							<div class="date"><?php echo get_the_date('F j, Y'); ?></div>
						</div><!-- .title-wrapper -->
						<div class="entry-summary">
							<?php the_content(); ?>
						</div><!-- .entry-summary -->
					</div><!-- .txt-wrap -->
				</article>
			<?php endwhile; ?>
			</div>
		</div><!-- /#primary -->
		<?php get_sidebar('blog'); ?>
	</div><!-- /.page-main -->
</div><!-- /.page-body -->

<?php get_footer(); ?>