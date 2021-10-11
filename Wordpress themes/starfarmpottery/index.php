<?php
/**
 * The main template file.
 * @package WordPress
 * @subpackage starfarm
 */

	global $evdp_prefix;
	get_header(); 
	$blog_id = get_option('page_for_posts', true);
	$post = get_post($blog_id); 
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$page_title = get_the_title($blog_id); 

?>
<div class="page-body has-sidebar <?php if ($has_photo === 1) echo 'has-photo'; ?>">
	<div class="page-main page-width">
		<nav class="breadcrumb-trail" aria-label="breadcrumb">
			<ol class="breadcrumbs">
				<li><a href="<?php echo get_home_url(); ?>">Home</a></li>
				<li class="active" aria-current="page"><?php echo $page_title; ?></li>
			</ol>
		</nav>
		<div id="primary">
			<header class="page-title">
				<h1><?php echo $page_title; ?></h1>
			</header>
			<?php 
				if (1 == $paged) {
					$content = apply_filters('the_content', $post->post_content); 
					if ($content !== '') { ?>
						<div id="blog-intro" class="tint-1">
							<?php echo $content; ?>
						</div>
						<?php
					}
				}
			?>
			<?php
				$i=0;
				if ( have_posts() ) : 
					$post_count = $wp_query->post_count;
					while ( have_posts() ) : the_post();
						$i++;
						if (($i == 1) && (1 == $paged)) { // first loop of first page ?>
								<div class="content current-post mt-3">
									<article id="post-<?php the_ID(); ?>">
										<?php if ( has_post_thumbnail() ) {
											include( locate_template( 'featured-photo.php', false, false ) );
										} ?>
										<div class="txt-wrap">
											<div class="title-wrapper mb-1">
												<?php 
													$categories = get_the_category();
													$separator = ', ';
													$output = '';
													if ( ! empty( $categories ) ) {
														foreach( $categories as $category ) {
															if($category->name !== 'Uncategorized') {
																$output .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . esc_attr( sprintf( __( 'View all posts in %s', 'gibson-realty' ), $category->name ) ) . '">' . esc_html( $category->name ) . '</a>' . $separator;
															}
														}
														if ($output != '') {
															echo "<div class=\"surhead banded cats\"><span>";
															echo trim( $output, $separator );
															echo "</span></div>\n";
														} 
													}
												?>
												<h2 class="post-title"><?php echo ucwords_title(get_the_title()); ?></h2>
												<div class="date mt-1"><?php echo get_the_date('F j, Y'); ?></div>
											</div><!-- .title-wrapper -->
											<div class="entry-summary">
												<?php the_content(); ?>
											</div><!-- .entry-summary -->
											<div class="comment-link mt-3"><a href="<?php the_permalink() ?>#comments">Click Here to Leave a Comment</a></div>

										</div><!-- .txt-wrap -->
									</article>
								</div>
							<?php 
						} else { // not first loop of first page... ?>
							<?php  
							if (($i == 2 && 1 == $paged) || ($i == 1 && 1 < $paged)) { //2nd loop of first page OR 1st loop of every page after first
								if ( function_exists( 'evcd_post_nav' ) ) evcd_post_nav( 'nav-above' ); ?>
								<section>
									<h2 class="section-head">Earlier Entries in <span class="blog-title"><?php echo $page_title; ?></span></h2>
									<div class="content post-listing archive-loop">
								<?php 
							} ?>
							<div class="item article clearfix" id="post-<?php the_ID(); ?>">
								<?php if ( has_post_thumbnail() ) { ?>
									<div class="photo">
										<a class="box-link" href="<?php echo get_permalink(); ?>"><?php the_post_thumbnail('thumb'); ?></a>
									</div>
								<?php } ?>
								<div class="txt-wrap">
									<div class="title-wrapper mb-1">
										<?php 
										$categories = get_the_category();
										$separator = ', ';
										$output = '';
										if ( ! empty( $categories ) ) {
											foreach( $categories as $category ) {
												if($category->name !== 'Uncategorized') {
													$output .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . esc_attr( sprintf( __( 'View all posts in %s', 'gibson-realty' ), $category->name ) ) . '">' . esc_html( $category->name ) . '</a>' . $separator;
												}
											}
											if ($output != '') {
												echo '<h4 class="surhead tax">' . trim( $output, $separator ) . '</div>';
											} 
										}
										?>
										<h3 class="post-title"><?php echo ucwords_title(get_the_title()); ?></h3>
										<?php if ( 'post' == get_post_type() ) : ?>
											<div class="date mt-1"><?php echo get_the_date('F j, Y'); ?></div>
										<?php endif; ?>
									</div><!-- .title-wrapper -->
									<div class="entry-summary">
										<?php the_excerpt(); ?>
									</div><!-- .entry-summary -->
									<div><a class="btn btn-sm" href="<?php echo get_permalink(); ?>" title="Read More">Read More</a></div>
								</div><!-- .txt-wrap -->
							</div>
							<?php 
							if ($i == $post_count) { //last loop closes outer wrap, no matter the page ?>
									</div><!-- /.archive-loop -->
									<?php if ( function_exists( 'evcd_post_nav' ) ) evcd_post_nav( 'nav-below' ); ?>
								</section>
								<?php 
							} //endif
						} //endif ?>
					<?php 
					endwhile;						
				endif;
			?>
		</div><!-- /#primary -->
		<?php 
		if (is_active_sidebar('blog')) {
			get_sidebar('blog'); 
		} else {
			get_sidebar(); 
		}
		?>
	</div><!-- /.page-main -->
</div><!-- /.page-body -->

<?php get_footer(); ?>