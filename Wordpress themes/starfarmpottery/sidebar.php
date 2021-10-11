<?php
/**
 * The sidebar containing the main widget area.
 *
 * If no active widgets in sidebar, let's hide it completely.
 *
 * @package WordPress
 * @subpackage starfarm
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
				<?php 
					//dynamic_sidebar( 'sidebar-1' );
					
					/* 
						Bug in wordpress where widget is being wrapped in a <p> and 
						there's no way to remove it in the admin.
						This is a hack to remove that <p>.
					*/
					ob_start();
					dynamic_sidebar('sidebar-1');
					$out = ob_get_clean();
					
					$re = '/<aside (.+?) widget_block"><p>(?s)(.+?)<\/p>\s*<\/aside>/m';
					$subst = '<aside $1 widget_block">$2</aside>';
					$out = preg_replace($re, $subst, $out);

					echo $out;
					
				?>
			</div>
		</div><!-- #secondary -->
	<?php endif; ?>
	
