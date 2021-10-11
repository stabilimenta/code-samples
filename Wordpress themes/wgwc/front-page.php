<?php
/**
 * @subpackage WGWC
 * Template Name: Front Page
 */
	global $evep_prefix;

	get_header(); 

	$args = array( 
		'post_type' => 'evg_employees',
		'posts_per_page' => -1,
		'orderby' => 'ID',
		'order' => 'ASC',
		'post_status' => 'publish',
		'meta_key' => $evep_prefix . 'position',
		'meta_value' => 'Attorney',
		// 		'tax_query' => array(
		// 			array(
		// 				'taxonomy' => 'employees_category',
		// 				'field'    => 'slug',
		// 				'terms'    => 'partners',
		// 			),
		// 		),
	);
	
	$the_query = new WP_Query( $args );

	// The Loop
	if ( $the_query->have_posts() ) : ?>
		<section id="intro">
			<h1 class="page-width"><?php bloginfo( 'description' ); ?></h1>
			<div class="row">
				<?php
					while ( $the_query->have_posts() ) : $the_query->the_post();
						$emp_name = $headshot = '';
						$post_id = get_the_id();
						$emp_name = get_the_title();
						$headshot_id = get_post_meta( $post_id, $evep_prefix . 'main-image_id', true );
						$slugs = wp_get_post_terms($post_id,'employees_category',['fields'=>'slugs']);

						if ($headshot_id ) { 
							$img_alt = get_post_meta($headshot_id, '_wp_attachment_image_alt', true);
							if ($img_alt == '') {
								$img_alt = get_the_title($headshot_id);
								$img_alt = ucwords(str_replace("-", " ", $img_alt));
							}
							$sizes = "(min-width: 1400px) 350px, (min-width: 1200px) 25vw, (min-width: 767px) 50vw, 50vw";
							$headshot = wp_get_attachment_image( $headshot_id, 'full', '', array( 'alt' => $img_alt, 'sizes' => ''  ));
						} // end if $headshot_id
						
						if ( !empty($emp_name) && !empty($headshot_id) && in_array('partners', $slugs) ) { ?>
							<div class="col staff overlay">
								<div class="img-wrap">
									<?php echo $headshot; ?>
								</div>
								<div class="txt-wrap">
									<h3><a href="<?php echo get_the_permalink(); ?>" aria-label="Read more about <?php echo $emp_name; ?>"><?php echo $emp_name; ?></a></h3>
									<a href="<?php echo get_the_permalink(); ?>" class="more" aria-label="Read more about <?php echo $emp_name; ?>">More</a>
								</div>
							</div>
							<?php 
						}

					endwhile;
				?>
			</div>
		</section>
	<?php endif;
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<section id="results" class="page-width">
		<?php the_content(); ?>
	</section>
<?php endwhile; endif; ?>

<?php if ( $the_query->have_posts() ) : ?>
	<section id="attorneys" class="page-width">
		<h2>Attorneys</h2>
		<?php
			while ( $the_query->have_posts() ) : $the_query->the_post();
				$emp_name = $hero_photo = '';
				$post_id = get_the_id();
				$emp_name = get_the_title();
				$hero_photo_id = get_post_meta( $post_id, $evep_prefix . 'hero-image_id', true );
				if ($hero_photo_id ) { 
					$img_alt = get_post_meta($hero_photo_id, '_wp_attachment_image_alt', true);
					if ($img_alt == '') {
						$img_alt = get_the_title($hero_photo_id);
						$img_alt = ucwords(str_replace("-", " ", $img_alt));
					}
					$sizes = "(min-width: 1200px) 1170px, (min-width: 575px) calc(100vw - 60px), calc(100vw - 30px)";
					$hero_photo = wp_get_attachment_image( $hero_photo_id, 'full', '', array( 'alt' => $img_alt, 'sizes' => ''  ));
				} // end if $hero_photo_id
				if ( !empty($emp_name) && !empty($hero_photo_id) ) { ?>
					<div class="staff overlay">
						<div class="img-wrap">
							<?php echo $hero_photo; ?>
						</div>
						<div class="txt-wrap">
							<h3><a href="<?php echo get_the_permalink(); ?>" aria-label="Read more about <?php echo $emp_name; ?>"><?php echo $emp_name; ?></a></h3>
							<a href="<?php echo get_the_permalink(); ?>" class="more" aria-label="Read more about <?php echo $emp_name; ?>">More</a>
						</div>
					</div>
					<?php 
				}
			endwhile;
		?>
	</section>
<?php 
endif;
wp_reset_postdata();
?>

<section id="contact" class="page-width">
	<h2>Contact</h2>
	<div class="address-box">
		<h3><?php echo do_shortcode('[contactinfo include="business"]'); ?></h3>
		<div class="contact-info">
			<?php echo do_shortcode('[contactinfo include="address,phone,fax"]'); ?>
		</div>
	</div>
	<div id="map-container">
		<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3073.6099094134206!2d-105.02040088505507!3d39.61346911202245!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x876c78d5f8e3b2f7%3A0x97386e792cca0cf5!2sWadsworth%20Garber%20Warner%20Conrardy%2C%20P.C.!5e0!3m2!1sen!2sus!4v1607521375399!5m2!1sen!2sus" width="1170" height="638" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
	</div>
</section>


<?php get_footer(); ?>