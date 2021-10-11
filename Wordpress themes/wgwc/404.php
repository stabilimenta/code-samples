<?php
/**
 * @subpackage WGWC
 * 404 pages (Not Found) template
 */

	get_header(); 
	$has_photo = 0;
	if (! empty(evcd_get_option($theme_prefix . '404_image_id'))) {
		$has_photo = 1;
	}
		
?>
<div class="page-width <?php if ($has_photo === 1) echo 'has-photo'; ?>">
	<div class="page-title">
		<h1>Page Not Found!</h1>
	</div>
	<div class="row page-content">
		<?php  if ($has_photo === 1) include( locate_template( 'featured-photo.php', false, false ) ); ?>
		<div class="col text">
			<h2>Sorry!</h2>
			<h3>The page you're looking for isn't here.</h3>
			<p>Please look in the main menu in the top right corner, or try searching&hellip;</p>
			<?php include 'searchform.php'; ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>