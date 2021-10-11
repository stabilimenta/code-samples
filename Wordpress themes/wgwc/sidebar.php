<?php
/**
 * The sidebar containing the main widget area.
 *
 * If no active widgets in sidebar, let's hide it completely.
 *
 * @package WordPress
 * @subpackage WGWC
 */
?>

	<?php if ( is_active_sidebar( 'sidebar-1' ) ) : 
		$sidebars_widgets = wp_get_sidebars_widgets();
		$wrapper_classes = $post_count = '';
		$post_count = count( (array) $sidebars_widgets[ 'sidebar-1' ] );
		$wrapper_classes .= ' count-' . $post_count;
	?>
		<div id="secondary" class="widget-area<?php echo $wrapper_classes; ?>" role="complementary">
			<div class="inner">
				<?php dynamic_sidebar( 'sidebar-1' ); ?>
			</div>
		</div><!-- #secondary -->
	<?php endif; ?>
	
