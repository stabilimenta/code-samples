<?php
/**
 * @subpackage starfarm
 * Template Name: Page (with sidebar, fixed width)
 */
	get_header(); 
	global $theme_prefix;
?>
<div class="page-body full-width-sidebar">
	<div class="page-main">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
			<div id="primary">

			<?php 
				global $evdp_prefix;
				$feat_imgs_args = array ( 
					'post_type' => 'evg_art_portfolio',
					'meta_key' => $evdp_prefix . 'featured', 
					'meta_value' => 'yes',
					'numberposts' => '6',
				 );

				$homepage_art = new WP_Query($feat_imgs_args);

				// The Loop
				if ( $homepage_art->have_posts() ) {
					$image_count = $homepage_art->found_posts;
					$thumbs_str = '';
					?>
					<div class="horizontal-scroller">
						<div class="photobanner">
							<?php
								$photos_str = '';
								while ( $homepage_art->have_posts() ) : $homepage_art->the_post();
									//the loop!
									$attachment_id = $credit = $caption = $alt = '';
									$post_id = get_the_id();
					
									$images_array = get_post_meta($post_id, $evdp_prefix . 'file_list', true);
									if ( has_post_thumbnail() ) {
										$attachment_id = get_post_thumbnail_id();
									} else {
										reset($images_array);  // get first item in array
										$attachment_id = key($images_array); // get first item's key (which is where CMB2 is holding image ID
									}
						
									if ($attachment_id) { 
										if (isset(get_post($attachment_id)->post_excerpt)) {
											$caption = get_post($attachment_id)->post_excerpt;
										}
										$alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
										$photo_credit = get_post_meta($attachment_id, 'evcd_photo_credit', true);
										$photos_str .=	'<div class="item caption-overlay">';
										$photos_str .=		'<a title="View Details About ' . get_the_title($post_id) . '" href="' . get_permalink($post_id) . '" class="box-link">';
										$photos_str .=			wp_get_attachment_image( $attachment_id, 'md-nocrop' );
										$photos_str .=		'</a>';
										if ($caption) {
											$photos_str .=	'<div class="caption">';
											$photos_str .=		esc_html( $caption );
											if ($photo_credit) {
												$photos_str .=	'<div class="credit">Photo by ' . esc_html( $photo_credit ) . '</div>';
											}
											$photos_str .=	'</div>';
										}
										$photos_str .=	'</div>';
									} // end if $attachment_id
								endwhile;
								echo $photos_str;
								echo $photos_str; // this requires double output of image blocks
							?>
						</div><!-- /.photobanner -->
					</div><!-- /.horizontal-scroller -->
					<?php 
				} //end if $homepage_art
				wp_reset_postdata(); ?>
				
				<div class="page-width">
					<div id="home-content" class="content">
						<h1><?php the_title(); ?></h1>
						<?php the_content(); ?>
					</div><!-- .content -->
				</div>
									
			</div><!-- /#primary -->
			<div class="page-width">
				<?php get_sidebar(''); ?>
			</div>
		<?php endwhile; endif; ?>
	</div><!-- /.page-main -->
</div><!-- /.page-body -->
	
<?php get_footer(); ?>
