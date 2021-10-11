<?php
/**
 * @subpackage WGWC
 * Template Name: Page
 */

	get_header(); 
?>
<div class="page-width">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="page-title">
			<h1><?php the_title(); ?></h1>
		</div>
		<div class="page-content">
			<?php the_content(); ?>
		</div>
	<?php endwhile; endif; ?>
</div>

<?php get_footer(); ?>