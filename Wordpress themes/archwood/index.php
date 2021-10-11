<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Archwood
 */


	get_header(); 
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
?>
<div class="page-body has-sidebar">
	<div class="page-main full-width">
		<div id="primary">
			<?php 
				if (1 == $paged) :
					$id = get_option('page_for_posts', true);; 
					$post = get_post($id); 
					$content = apply_filters('the_content', $post->post_content); 
					if ($content !== '') {
						echo "<div id=\"main-content\" class=\"content\">" . $content . "</div>\n";
					}
				endif;
			?>
			<?php
				$i=0;
				// Start the Loop.
				if ( have_posts() ) : 
				$post_count = $wp_query->post_count;
			?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php 
						$i++;
						if (($i == 1) && (1 == $paged)) :
					?>
						<?php if ( function_exists( 'evcd_content_nav' ) ) evcd_content_nav( 'nav-above' ); ?>
						<div class="content current-post">
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
						</div>
						<?php elseif ((($i == 2) && (1 == $paged)) || (($i == 1) && (1 !== $paged))) : ?>
							<?php if ( function_exists( 'evcd_content_nav' ) ) evcd_content_nav( 'nav-above' ); ?>
							<div class="content archive-loop">
								<article id="post-<?php the_ID(); ?>" class="<?php echo ($i == 1) ? "full" : "excerpt"; ?>">
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
						<?php else : ?>
								<article id="post-<?php the_ID(); ?>" class="<?php echo ($i == 1) ? "full" : "excerpt"; ?>">
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
						<?php endif; ?>
						<?php if ($i == $post_count) : ?>
							</div><!-- /.archive-loop -->
						<?php endif; ?>
					<?php endwhile; ?>
					<?php if ( function_exists( 'evcd_content_nav' ) ) evcd_content_nav( 'nav-below' ); ?>
				<?php endif; ?>


		</div><!-- /#primary -->
		<?php get_sidebar('blog'); ?>
	</div><!-- /.page-main -->
</div><!-- /.page-body -->

<?php get_footer(); ?>