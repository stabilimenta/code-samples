<?php
	/*Template Name: Single Project Portfolio */
	global $evdp_prefix;
	global $theme_prefix;
	get_header();
	$post_id = get_the_id();
	$post_type = get_post_type();
	$post_type_obj = get_post_type_object( $post_type );
	$archive_title = apply_filters('post_type_archive_title', $post_type_obj->labels->archives );  
	$cpt_singular_name = $post_type_obj->labels->singular_name;
	$archive_link = get_post_type_archive_link( $post_type );	       
	$tax_id = $taxonomy = $query_var_term = $term_name = $term_link = $post_next_href = $post_prev_href = ''; 

	$allowed_html = array(
	  'a' => array(
		'href' => array(),
	  ),
	  'br' => array(),
	  'strong' => array(),
	  'b' => array(),
	  'em' => array(),
	  'i' => array(),
	);
	
	if (isset($_GET['term_id'])) {
		$tax_id = (int)$_GET['term_id'];
		$taxonomy = 'art_category';
		$query_var_term = 'term_id';
	} elseif (isset($_GET['tag_id'])) {
		$tax_id = (int)$_GET['tag_id'];
		$taxonomy = 'post_tag';
		$query_var_term = 'tag_id';
	} else {
		$tax_id = '';
		$taxonomy = '';
		$query_var_term = '';
	}
	
	if (( $tax_id !== '' ) && ( $tax_id !== 0 )) {  // int() returns 0 for non numbers
		$term = get_term( $tax_id, $taxonomy );
		if (isset($term->name)) $term_name = ucwords($term->name);
		$term_link = get_tag_link($tax_id);
		// limit our tag link to our custom post type
		if ($taxonomy == 'post_tag') $term_link = $term_link . "?post_type=" . $post_type;
		// get_posts in same custom taxonomy
		$args = array(
			'post_type'	=> $post_type,
			'posts_per_page'  => -1,
			'tax_query' => array(
				'relation' => 'AND',                     
					array(
						'taxonomy' => $taxonomy,
						'field' => 'id',
						'terms' => $tax_id,
						'include_children' => false,
						'operator' => 'IN'
					)
			),
		); 
	} else {
		$args = array(
			'post_type'	=> $post_type,
			'posts_per_page'  => -1,
		); 
	}
	
	$the_query = new WP_Query( $args );
	
	if ( $the_query->have_posts() ) {
		$ids = array();
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$ids[] = get_the_id();
		} // end while
		$ids_count = count($ids);
	
		$thisindex = array_search($post_id, $ids);
		// echo $thisindex;
		if ($thisindex == 0) {
			$previd = end($ids);         // get last value of the array
			reset($ids);
		} else {
			$previd = $ids[$thisindex-1];
		}
		end($ids);         		  // move the internal pointer to the end of the array
		$lastkey = key($ids);	  // fetches the key of the element pointed to by the internal pointer
		reset($ids);
		if ($thisindex == $lastkey) {
			$nextid = $ids[0];    // get first value of the array
		} else {
			$nextid = $ids[$thisindex+1];
		}

		if ($ids_count > 1) {
			if (!(($ids_count == 2) && ($thisindex == 1))) {
				if ( !empty($nextid) ) {
					if ( !empty($query_var_term)) {
						$post_next_href = get_permalink($nextid) . "?". $query_var_term ."=" . $tax_id;
					} else {
						$post_next_href = get_permalink($nextid);
					}
				}
			}
			if (!(($ids_count == 2) && ($thisindex == 0))) {
				if ( !empty($previd) ) {
					if ( !empty($query_var_term)) {
						$post_prev_href = get_permalink($previd) . "?". $query_var_term ."=" . $tax_id;
					} else {
						$post_prev_href = get_permalink($previd);
					}
				}
			}
		}
	} // endif
	wp_reset_postdata();

	
?>


<div class="page-body full-width-sidebar">
	<div class="page-main page-width">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumbs">
				<li><a href="<?php echo get_home_url(); ?>">Home</a></li>
				<?php 
					if (isset($archive_title)) {
						echo '<li>';
						if (isset($archive_link)) echo '<a href="' . $archive_link . '">';
						echo $archive_title;
						if (isset($archive_link)) echo '</a>';
						echo '</li>';
					}
					if ($term_name) {
						echo '<li>';
						if ($term_link) echo '<a href="' . $term_link . '">';
						echo $term_name;
						if ($term_link) echo '</a>';
						echo '</li>';
					}
				 ?>
				<li active" aria-current="page"><?php the_title(); ?></li>
			</ol>
		</nav>
		<div id="primary">
		<?php if ( have_posts() ) : ?>
			<?php if (($post_next_href) || ($post_prev_href) ) {
					$nav_hint = '';
					if ($term_name && $term_link) { 
						$nav_hint = ' in ' . $term_name;
					// } elseif ($archive_title && $archive_link) {
					// 	$nav_hint = ' in ' . $archive_title;
					} 
				?>
				<nav class="nav post-prev-next" aria-label="posts navigation">
					<ul class="pagination justify-content-between mb-2">
						<?php if ($post_prev_href) { ?>
							<li class="page-item">
								<a class="page-link" href="<?php echo $post_prev_href; ?>" aria-label="Previous<?php echo $nav_hint; ?>">
									<span aria-hidden="true">&laquo;</span>
									<span class="sr-only">Previous Item<?php echo $nav_hint; ?></span>
								</a>
							</li>
						<?php } ?>
						<?php if ($post_next_href) { ?>
							<li class="page-item">
								<a class="page-link" href="<?php echo $post_next_href; ?>" aria-label="Next<?php echo $nav_hint; ?>">
									<span class="sr-only">Next Item<?php echo $nav_hint; ?></span>
									<span aria-hidden="true">&raquo;</span>
								</a>
							</li>
						<?php } ?>
					</ul>
				</nav>
			<?php } ?>

			<div class="wrapper top-section">
				<header class="page-title">
					<div class="hgroup">
						<?php 
							if ($term_name !== '') {
								$subhead = '';
								
								if ($taxonomy == 'post_tag') {
									$subhead .= 'Tag: ';
								} else {
									$subhead .= 'Category: ';
								}
								
								$subhead .= '<span class="term">';
								if ($term_link) $subhead .= '<a href="' . $term_link . '">';
								$subhead .= $term_name;
								if ($term_link) $subhead .= '</a>';
								$subhead .= '</span>';
								if ($subhead !== '') echo '<h2 class="tax">' . $subhead . '</h2>'; 
							}
						?>
						<h1><?php the_title(); ?></h1>
					</div>
				</header>
			</div><!-- /.top-section -->
                  			
			<div id="main-content" class="content">
				<?php 
				// Get the list of files
				$images_array = get_post_meta( $post_id, $evdp_prefix . 'file_list', true );
				// file_list returns array, key= ID; value= URL

				if ($images_array) { 
					$image_count = count($images_array);
					$thumbs_str = '';
					if ($image_count > 20) 
						{ $img_range = 'count-gt-20'; }
					elseif ($image_count > 15) 
						{ $img_range = 'count-15-20'; }
					elseif ($image_count > 10) 
						{ $img_range = 'count-10-15'; }
					elseif ($image_count > 5) 
						{ $img_range = 'count-5-10'; }
					else 
						{ $img_range = 'count-1-5'; }
				?>
				<?php if($image_count > 1) { ?>
					<div id="portfolioCarousel" class="carousel slide fit-images" data-bs-ride="carousel" data-interval="false">
				<?php } else { ?>
					<div id="portfolioSingle" class="carousel slide fit-images">
				<?php } ?>
						<div class="carousel-viewport">
							<div class="carousel-inner">
							<?php
								$i = 0;
								foreach ( (array) $images_array as $attachment_id => $attachment_url ) {
									$img = $img_id = $credit = $caption = $supersize_url = $thumb_url = $alt = '';
									if ($attachment_id ) { 
										if (isset(get_post($attachment_id)->post_excerpt)) $caption = get_post($attachment_id)->post_excerpt;
										$thumb_url = wp_get_attachment_image_src( $attachment_id, 'thumb-nocrop' );
										$alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
										$supersize_url = wp_get_attachment_image_src( $attachment_id, 'supersize-photo' );
										$photo_credit = get_post_meta($attachment_id, 'pp_photo_credit', true);
										
										if (is_array($thumb_url)) {
										?>				 
											<div class="carousel-item caption-overlay <?php if ($i == 0) echo 'active'; ?>">
												<a title="Click for an even larger view" href="<?php echo $supersize_url[0]; ?>" class="lightbox-g1 cboxElement">
													<?php echo wp_get_attachment_image( $attachment_id, 'xlarge' );?>
												</a>
												<?php if ($caption) { ?>
													<figcaption>
														<?php echo esc_html( $caption ); ?>
														<?php if ($photo_credit) echo "<div class=\"credit\">Photo by " . esc_html( $photo_credit ) . "</div>"; ?>
													</figcaption>
												<?php } ?>
											</div>
										<?php
											$thumbs_str .= '<button type="button" data-bs-target="#portfolioCarousel" data-bs-slide-to="' . $i . '" aria-label="Slide ' . $i . '" ';
											if ($i == 0) $thumbs_str .= 'class="active" aria-current="true"';
											$thumbs_str .= '><img src="' . $thumb_url[0] . '" alt="' . $alt . '" width="' . $thumb_url[1] . '" width="' . $thumb_url[2] . '"></button>';

										} // end if $thumb_url
									} // end if $attachment_id
									$i++;
								} // end foreach ?>
								
							</div>
							<?php if($image_count > 1) { ?>
								<button class="carousel-control-prev" type="button" data-bs-target="#portfolioCarousel" data-bs-slide="prev">
									<span class="carousel-control-prev-icon" aria-hidden="true"></span>
									<span class="visually-hidden">Previous</span>
								</button>
								<button class="carousel-control-next" type="button" data-bs-target="#portfolioCarousel" data-bs-slide="next">
									<span class="carousel-control-next-icon" aria-hidden="true"></span>
									<span class="visually-hidden">Next</span>
								</button>
							<?php } ?>
						</div><!-- /.carousel-viewport -->
						<?php if($image_count > 1 && $thumbs_str != '') { ?>
							<div class="carousel-indicators thumbnails set-height">
								<?php echo $thumbs_str; ?>
							</div>
						<?php } ?>
					</div>




				<?php } else { ?>			
					<div class="single_pic" id="prop_photos">
						<img class="no-prop-photo" src="<?php echo plugins_url(); ?>/art-portfolio/images/no-photo.png" height="300" width="450" alt="No Photo Available">
					</div><!-- /#prop_photos -->
				<?php } //end if $images_array ?>			

				<div id="port_info">
					<div class="row gx-5 mt-6">
						<div class="col-12 col-md-7 align-self-center">

							<?php $item_status = get_post_meta($post_id, $evdp_prefix . 'status', true); ?>
							<?php $item_price = get_post_meta($post_id, $evdp_prefix . 'price', true); ?>
							<?php if ($item_status == 'available') { ?>
								<?php if ($item_price) { ?>
									<div class="callout price">$<?php echo wp_kses( $item_price, $allowed_html ); ?></div>
								<?php } ?>
							<?php } else { ?>
								<div class="callout price sold">
									SOLD!
								</div>
							<?php } ?>
							
							<?php 
							$proj_date = get_post_meta($post_id, $evdp_prefix . 'proj_date', true);
							if ($proj_date) echo "<div class=\"proj-date\">" . $proj_date . "</div>\n"; 
							?>
							
							<?php $item_desc = get_post_meta($post_id, $evdp_prefix . 'desc', true); ?>
							<?php if ($item_desc) { ?>
								<?php 
									//this is to clean up for a messy author!
									$item_desc = trim($item_desc);
									$item_desc = preg_replace('/&nbsp;$/', '', $item_desc);
									$item_desc = trim($item_desc);
								?>
								<div class="desc"><?php echo apply_filters( 'the_content', $item_desc ); ?></div>
							<?php } ?>
							
							<?php $item_materials = get_post_meta($post_id, $evdp_prefix . 'materials', true); ?>
							<?php if ($item_materials) { ?>
								<div class="materials mt-2">
									<h5>Materials</h5>
									<div><?php echo wp_kses( $item_materials, $allowed_html ); ?></div>
								</div>
							<?php } ?>
							

							<?php 
							$terms = get_the_terms($post->ID,'art_category');
							if ( !empty($terms) || has_tag() )  { ?>
								<div class="meta mt-2" id="port_tax">
									<h5>Related Projects</h5>
									<?php 
										if ( !empty( $terms ) ){
											echo "<div class=\"terms-list mt-1 categories\">\n";
											echo "<span class=\"text-uppercase\">Categories:</span>\n";
											$z = 0;
											foreach ( $terms as $term ) {
												if (($term->name) !='Featured Categories') { 
													if ($z > 0) echo ", ";
													echo "<a href=\"" . get_term_link( $term ) . "\" title=\"View all " . $term->name . " projects\">" . $term->name . "</a>";
													$z++;
												}
											}
											echo "</div><!-- \.categories -->\n";
										}
									?>
									<?php 
										$terms = get_the_terms($post->ID,'post_tag');
										if ( !empty( $terms ) ){
											$z = 0;
											echo "<div class=\"terms-list mt-1 tags\">\n";
											echo "<span class=\"text-uppercase\">Tags:</span>\n";
											$terms_html = '';
											foreach ( $terms as $term ) {
												$count=$term->count;
												if ($count > 1) { // don't create a link if this page is the only member of the given tag 
													if ($z > 0) echo  ", ";
													// tags are shared by all post types but we want to limit it to our custom post type so we append to link
													echo "<a href=\"" . get_term_link( $term ) . "?post_type=" . $post_type ."\" title=\"View all " . $term->name . " projects\">" . $term->name . "</a>";
													$z++;
												}
											}
											echo "</div><!-- \.tags -->\n";
										}			 
									?>
								</div><!-- /#port_tax -->
							<?php } ?>

						</div>
						<div class="col-12 mt-6 col-md-5 mt-md-0">
							
							<?php if ($item_status == 'available') { ?>
								<div class="availibility available">
									<?php 
										$item_inquired = get_the_title() . ' (' . $post_id . ')';
										global $wp;
										$current_url = home_url( add_query_arg( array(), $wp->request ) );
									?>
									<div class="card text-dark bg-light text-center mb-3">
										<div class="card-body">
											<?php
												$purchase_text = evcd_get_option($theme_prefix . 'purchase_text');
												if (isset($purchase_text)){
													echo apply_filters( 'the_content', $purchase_text );
												}
											?>
											<a class="btn" href="<?php echo esc_url( home_url( '/' ) ); ?>contact?inquiry=<?php echo urlencode($item_inquired); ?>&link=<?php echo urlencode($current_url); ?>">Contact me about purchasing this item.</a>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>


				</div><!-- /#port_info -->

							
			</div><!-- .content -->

			<?php wp_reset_query(); ?>

		<?php endif; ?>
		</div><!-- /#primary -->
		<?php get_sidebar(); ?>
	</div><!-- /.page-main -->
</div><!-- /.page-body -->
<?php get_footer(); ?>