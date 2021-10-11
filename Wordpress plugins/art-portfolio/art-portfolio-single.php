<?php
/**
* Template Name: EVG Art Portfolio Single
*
*/

get_header(); ?>
	<?php global $evdp_prefix; ?>
	<div id="primary" class="site-content">
		<div id="content" role="main">
			<header class="page-header">
				<h2 class="page-title"><?php the_title(); ?></h2>
			</header>

			<div class="portfolio single-portfolio">
			<?php 
			// Get the list of files
			$entries = get_post_meta( get_the_ID(), $evdp_prefix . 'file_list', true );
			// file_list returns array, key= ID; value= URL

			if ($entries) { ?>
				<section class="<?php echo ((count($entries, 0) > 1) ? 'flexslider art-portfolio-slider' : 'single_pic'); ?>" id="prop_photos">
					<ul class="slides">
						<?php foreach ( (array) $entries as $attachment_id => $attachment_url ) {
							$img = $img_id = $credit = $caption = $supersize_url = $thumb_url = $alt = '';
				
							if ($attachment_id ) { 
								$caption = get_post($attachment_id)->post_content;
								$thumb_url = wp_get_attachment_image_src( $attachment_id, 'thumb' );
								$alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
							?>
					 
								<li data-thumb="<?php echo $thumb_url[0]; ?>">
									<figure>
										<a title="<?php echo $alt; ?>" href="<?php echo $supersize_url[0]; ?>" class="lightbox-g1 cboxElement"><?php echo wp_get_attachment_image( $attachment_id, 'large' );?></a>
										<?php if ($caption) { ?>
										<figcaption>
											<div class="inner">
												<?php echo $caption; ?>
											</div>
										</figcaption>
										<?php } ?>
									</figure>
								</li><?php } // end if $attachment_id
						} // end foreach ?></ul>
				</section><!-- /#prop_photos -->
			<?php } else { ?>			
				<section class="single_pic" id="prop_photos">
					<img class="no-prop-photo" src="<?php echo plugins_url(); ?>/art-portfolio/images/no-photo.png" height="300" width="450" alt="No Photo Available">
				</section><!-- /#prop_photos -->
			<?php } //end if $entries ?>			
			
				<section id="prop_info">
					<?php 
						$evdp_desc = ( get_post_meta( get_the_ID(), $evdp_prefix . 'desc', 1 ) );
						if ($evdp_desc) {
					 ?>
						<div class="description">
							<?php echo wpautop($evdp_desc); ?>
						</div>
					<?php } ?>
				</section><!-- /#port_info -->
				<section id="port_tax">
					<?php 
						$terms = get_the_terms($post->ID,'art_category');
						if ( !empty( $terms ) && !is_wp_error( $terms ) ){
							echo '<div class="terms-list categories">' . "\n";
							echo '<strong>This project is listed in these categories:</strong>' . "\n";
							$z = 0;
							foreach ( $terms as $term ) {
								if (($term->name) !='Featured Categories') { 
									if ($z > 0) { echo ', '; }
									echo '<a href="' . get_term_link( $term ) . '" title="View all ' . $term->name . '">' . $term->name . '</a>' . "\n";
									$z++;
								}
							}
							echo '</div><!-- \.categories -->' . "\n";
						}
					?>
					<?php echo the_tags('<div class="terms-list tags"><strong>This project’s tags:</strong>', ', ', '</div><!--/.tags-->'); ?>
				</section><!-- /#port_tax -->
			</div><!-- /.portfolio -->
			<?php
				// Reset Post Data
				wp_reset_postdata();
			?>
		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>
