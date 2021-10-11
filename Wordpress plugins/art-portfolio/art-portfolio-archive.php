 <?php /* Template Name: EVG Art Portfolio Archive */

get_header(); ?>

	<!-- THIS IS THE PLUGIN ARCHIVE PAGE. -->
	<div id="primary" class="site-content">
		<div id="content" class="properties-content" role="main">		
		<?php 
			global $evdp_prefix;

			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

			$query_args = array(
				'post_type' => 'evg_art_portfolio',
				'order' => 'DESC',
				'posts_per_page' => 16,
				'paged'     => $paged,
// 				'orderby' => 'meta_value_num',
// 				'meta_key' => $evdp_prefix . 'stars_rating',
				'meta_query' => array(
					'relation' => 'AND',
					'rating_clause' => array(
						'key' => $evdp_prefix . 'stars_rating',
						'compare' => 'EXISTS',
					),
					'date_clause' => array(
						'key' => $evdp_prefix . 'date',
						'compare' => 'EXISTS',
					), 
				),
				'orderby' => array(
					'rating_clause' => 'DESC',
					'date_clause' => 'DESC',
				),
			);
 			$the_query = new WP_Query( $query_args );
			$i = 0;
			global $wp_query;
			// Put default query object in a temp variable
			$tmp_query = $wp_query;
			// Now wipe it out completely
			$wp_query = null;
			// Re-populate the global with our custom query
			$wp_query = $the_query;

			// The Loop
			if ( $the_query->have_posts() ) :
				$the_count = $the_query->found_posts;
		?>
			<div class="wrapper">
				<header class="page-header">
					<h2 class="page-title">Art Portfolio</h2>
				</header>
			</div>
			<?php if ( function_exists( 'evdp_paged_queries_nav' ) ) evdp_paged_queries_nav( 'nav-above' ); ?>
			
			<div class="archive-loop art-portfolio masonry-container <?php echo $the_count > 1 ? "multi-display" : "single-display";  ?>">
				<div class="grid-sizer"></div>
				<div class="grid-item"></div>
				<div class="grid-item-double"></div>
				<div class="gutter-sizer"></div>
			<?php
			while ( $the_query->have_posts() ) : $the_query->the_post();
				$i++;
				$class = $i % 2 == 0 ? "even" : "odd";
				if (!isset($class))  $class='';
				echo '<div class="item ' . $class .'">';
				$prop_photos = get_post_meta( get_the_ID(), $evdp_prefix . 'file_list', true );
				if ($prop_photos) {
					$count = count($prop_photos);
					$more = $count - 1;
					reset($prop_photos);  // get first item in array
					$prop_photo_id = key($prop_photos); // get first item's key (which is where CMB2 is holding image ID
					echo '<div class="photo">';
					echo '<a href="' . get_permalink() . '">';
				 
					$img_src = wp_get_attachment_image_url( $prop_photo_id, 'medium' );
					$img_srcset = wp_get_attachment_image_srcset( $prop_photo_id, 'medium' );
					$img_alt = get_post_meta($prop_photo_id, '_wp_attachment_image_alt', true);
					echo '<img src="' . esc_url( $img_src ) . '"';
					echo ' srcset="' . esc_attr( $img_srcset ) . '"';
					echo ' sizes="(max-width : 750px) 100vw, 32vw" alt="' . $img_alt . '">';
								// (max-width: 50em) 87vw, 680px
					if ($more > 0) echo '<div class="num_pics">' . $more . ' more photos!</div>';
					echo '</a>';
					echo '</div>';
				} else {
					echo '<div class="photo">';
					echo '<a href="' . get_permalink() . '">';
					echo '<img class="no-prop-photo" src="' . plugins_url() . '/art-portfolio/images/no-photo.png" height="300" width="450" alt="No Photo Available">';
					echo '</a>';
					echo '</div>';
				
				} //end if $prop_photos
				echo '<div class="text">';
					echo '<h5 class="address">';
						$evdp_address = ( get_post_meta( get_the_ID(), $evdp_prefix . 'address', 1 ) );
						if ($evdp_address) echo '<span class="street">' . $evdp_address . ',</span> ';
						$evdp_city = ( get_post_meta( get_the_ID(), $evdp_prefix . 'city', 1 ) );
						if ($evdp_city) echo '<span class="city">' . $evdp_city . '</span>, ';
						$evdp_state = ( get_post_meta( get_the_ID(), $evdp_prefix . 'state', 1 ) );
						if ($evdp_state) echo '<span class="state">' . $evdp_state . '</span> ';
						$evdp_zip = ( get_post_meta( get_the_ID(), $evdp_prefix . 'zipcode', 1 ) );
						if ($evdp_zip) echo ' <span class="zip">' . $evdp_zip . '</span>';
					echo '</h5>';
					$evdp_desc = ( get_post_meta( get_the_ID(), $evdp_prefix . 'desc', 1 ) );
					if ($evdp_desc) {
					echo '<div class="description">' . wp_trim_words( $evdp_desc, 30 ) . '</div>';
					// echo wpautop($evdp_desc);
					}
				$evdp_rent = ( get_post_meta( get_the_ID(), $evdp_prefix . 'rent', 1 ) );
				if ($evdp_rent) echo '<div class="rent">$' . $evdp_rent . ' / month</div>';
				echo '<div class="btn btn-small">';
					echo '<a href="' . get_permalink() . '">View Details</a>';
				echo '</div>';
				echo '</div><!-- ./text -->';
				echo '</div><!-- /.property -->';
			endwhile;
			?>
			</div><!-- /.properties -->

			<?php  if ( function_exists( 'evdp_paged_queries_nav' ) ) evdp_paged_queries_nav( 'nav-below' ); ?>

		<?php endif; ?>
		<?php 
			wp_reset_postdata();
			// Restore original query object
			$wp_query = null;
			$wp_query = $tmp_query;
		?>
		</div><!-- #content -->
				
	</div><!-- #primary -->

<?php get_footer(); ?>