<?php
/**
 * Blog sidebar
 *
 * If no active widgets in sidebar, let's hide it completely.
 *
 * @package WordPress
 * @subpackage WGWC
 */
?>

	<?php if ( is_active_sidebar( 'sidebar-blog' ) ) : 
			$sidebars_widgets = wp_get_sidebars_widgets();
			$sidebar_count = '';
			$sidebar_count = count( (array) $sidebars_widgets[ 'sidebar-blog' ] );
	?>
		<div id="secondary" class="widget-area alt count-<?php echo $sidebar_count . ( $sidebar_count > 1 ? ' multi-widgets' : ' single-widget') . ($sidebar_count % 2 == 0 ? ' even' : ' odd') ; ?>" role="complementary">
			<div class="inner">
				<?php dynamic_sidebar( 'sidebar-blog' ); ?>
			</div>
		</div><!-- #secondary -->
	<?php endif; ?>
	
