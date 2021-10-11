<?php
/**
 * The template for displaying Archive pages
 *
 * @package WordPress
 * @subpackage starfarm
 */

get_header(); ?>
	
<div class="page-body has-sidebar">
	<div class="page-main full-width">
		<header class="page-header">
			<div class="page-title-wrap">
				<?php
					echo "<h1 class=\"page-title\">\n";
					$subhead = '';
					$myTermTitle = single_term_title("", false);
					if ($myTermTitle) {
						$subhead = $myTermTitle;
					}
					$blog_page_id = get_option('page_for_posts');
					echo get_page($blog_page_id)->post_title;
					if ($subhead) {
						echo ": <span class=\"terms\">" . $subhead . "</span>";
					} 
					echo "</h1>\n";
				?>
			</div><!-- /.page-title-wrap -->
		</header>
		<div id="primary">
			<div id="main-content" class="content">
				<?php if ( have_posts() ) { ?>
					<?php evoartist_content_nav( 'nav-above' ); ?>
					<?php while ( have_posts() ) : the_post(); ?>
						<?php 
							// get_template_part( 'content', 'post' );
							include(locate_template('content-post.php')); // use this method instead of get_template so you can pass variables
						?>
						<?php comments_template( '', true ); ?>
					<?php endwhile; ?>
					<?php evoartist_content_nav( 'nav-below' ); ?>
				<?php } // end if have posts
					else {
						get_template_part( 'content', 'none' );
				 } ?>
			</div>
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