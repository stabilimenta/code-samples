<?php
/**
 * @subpackage WGWC
 * Search Results template
 */
	global $evep_prefix;
	$this_search_term = get_search_query();
	get_header(); 
?>

<div class="page-width <?php if ($has_photo === 1) echo 'has-photo'; ?>">
	<header class="page-title">
		<h1><?php
		if ( is_404 ()) :
			echo "Looking for something?";
		elseif (is_search()) :
			echo "Search Results";
		elseif ( is_archive('project_portfolio') || is_singular('project_portfolio') ) :
				echo "Portfolio Search Results";
		else :
			echo "Searching... ";
		endif; 
		?></h1>
		<h2 class="subhead terms"><?php echo (strlen( trim($this_search_term) ) != 0 ? 'Search Terms: ' . $this_search_term : 'No Terms Entered'); ?></h2>
	</header>
	<div class="page-content">
		<?php if ( have_posts() ) : ?>
			<?php if ( function_exists( 'evcd_content_nav' ) ) evcd_content_nav( 'nav-above' ); ?>
			<div class="content post-listing archive-loop search">
				<?php while ( have_posts() ) : the_post(); ?>
					<div class="item article" id="post-<?php the_ID(); ?>">
						<?php $post_id = get_the_id(); ?>
						<div class="txt-wrap">
							<div class="title-wrapper">
								<h4 class="post-title"><?php the_title(); ?></h4>
								<?php if ( 'post' == get_post_type() ) : ?>
									<div class="date"><?php echo get_the_date('F j, Y'); ?></div>
								<?php endif; ?>
							</div><!-- .title-wrapper -->
							<div class="entry-summary">
								<?php
									if ( 'evg_employees' == get_post_type() ) :
										$my_content = get_post_meta($post_id, $evep_prefix . 'bio', true);
										if ( '' != $my_content ) :
											$descrip = strip_tags($my_content);
											$descrip = strip_shortcodes( $descrip );
											$descrip = apply_filters('the_content', $descrip);
											$descrip = str_replace(']]>', ']]>', $descrip);
											$excerpt_length = 40; // 20 words
											$excerpt_more = apply_filters('excerpt_more', '&hellip;');
											$descrip = wp_trim_words( $descrip, $excerpt_length, $excerpt_more );
											echo '<p>' . $descrip . '</p>';
										endif;
									else :
										the_excerpt();
									endif;
								?>
							</div><!-- .entry-summary -->
						</div><!-- .txt-wrap -->
						<div class="btn btn-small"><a href="<?php echo get_permalink(); ?>" title="Read More">Read More</a></div>
					</div>
				<?php endwhile; ?>
			</div><!-- /.archive-loop -->
			<?php if ( function_exists( 'evcd_content_nav' ) ) evcd_content_nav( 'nav-below' ); ?>
			<div>
				<h4>Search Again</h4>
				<?php include 'searchform.php'; ?>
			</div>
		<?php else : ?>
			<div class="content">
				<p>Sorry, but nothing matched your search criteria. Please try again with some different keywords.</p>
				<?php include 'searchform.php'; ?>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php get_footer(); ?>
