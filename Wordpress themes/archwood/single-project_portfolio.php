<?php
	/*Template Name: Single Project Portfolio */
	global $pp_prefix;
	global $theme_prefix;
	get_header();
	$post_id = get_the_id();
	$post_type = get_post_type();
	$post_type_obj = get_post_type_object( $post_type );
	$archive_title = apply_filters('post_type_archive_title', $post_type_obj->labels->archives );  
	$cpt_singular_name = $post_type_obj->labels->singular_name;
	$archive_link = get_post_type_archive_link( $post_type );	       
	$tax_id = $taxonomy = $query_var_term = $term_name = $term_link = $post_next_href = $post_prev_href = ''; 
	
	if (isset($_GET['term_id'])) {
		$tax_id = (int)$_GET['term_id'];
		$taxonomy = 'project_type';
		$query_var_term = 'term_id';
	}
	elseif (isset($_GET['tag_id'])) {
		$tax_id = (int)$_GET['tag_id'];
		$taxonomy = 'post_tag';
		$query_var_term = 'tag_id';
	}
	else {
		$tax_id = '';
		$taxonomy = '';
		$query_var_term = '';
	}
	
?>
<div class="page-body">
	<div class="page-main full-width">
		<div id="primary">
		<?php if ( have_posts() ) : ?>
			<?php  
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
				// The Loop
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

					// echo '<h3>This Project ID: '. $post_id . '</h3>';
					// echo '<h3>Prev ID: '. $previd . '</h3>';
					// echo '<h3>Next ID: '. $nextid . '</h3>';

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
                  

			<header class="page-title">
				<?php 
					echo "<h1>";
					if (isset($archive_link)) echo '<a href="' . $archive_link . '">';
					if (isset($archive_title)) echo $archive_title;
					if (isset($archive_link)) echo '</a>';
					echo "</h1>";
				 ?>
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
						if ($subhead !== '') echo '<h2 class="subhead tax">' . $subhead . '</h2>'; 
					}
				?>
			</header>
			
			<?php if (($post_next_href) || ($post_prev_href) ) { ?>
				<nav id="port-single-nav" class="post-nav port-nav" role="navigation">
				<?php 
					$nav_label = 'Next';
					if (isset($cpt_singular_name)) $nav_label .= ' <span class="cut">' . $cpt_singular_name . '</span>';
					if ($post_next_href) {
						echo "<div class=\"next\"><a rel=\"next\" href=\"" . $post_next_href . "\">" . $nav_label . "</a></div>\n";
					}
					$nav_label = 'Previous';
					if (isset($cpt_singular_name)) $nav_label .= ' <span class="cut">' . $cpt_singular_name . '</span>';
					if ($post_prev_href) {
						echo "<div class=\"prev\"><a rel=\"prev\" href=\"" . $post_prev_href . "\">" . $nav_label . "</a></div>\n";
					}
					
					// breadcrumbs
					echo "<div class=\"breadcrumbs\">";
					if (isset($archive_link)) echo '<a href="' . $archive_link . '">';
					if (isset($archive_title)) echo $archive_title;
					if (isset($archive_link)) echo '</a>';
					if ($term_name) echo ' : ';
					if ($term_link) echo '<a href="' . $term_link . '">';
					if ($term_name) echo $term_name;
					if ($term_link) echo '</a>';
					echo "</div>\n";
				?>
				</nav><!-- #nav-above .navigation -->
			<?php } ?>
			
			<div id="main-content" class="content">
				<header class="post-title project-title">
					<h3><?php the_title(); ?></h3>
					<?php 
						$proj_date = get_post_meta($post_id, $pp_prefix . 'proj_date', true);
						if ($proj_date) echo "<div class=\"proj-date\">" . $proj_date . "</div>\n"; 
					 ?>
				</header>
				<?php 
				// Get the list of files
				$images_array = get_post_meta( $post_id, $pp_prefix . 'file_list', true );
				// file_list returns array, key= ID; value= URL

				if ($images_array) { 
					$image_count = count($images_array);
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
					<section class="project-portfolio <?php echo (($image_count > 1) ? 'project-portfolio-slider ' . $img_range : 'single_pic'); ?>" id="port_photos">
						<?php if ($image_count > 1) echo "<div class=\"flexslider\">\n"; ?>
							<ul class="slides">
								<?php foreach ( (array) $images_array as $attachment_id => $attachment_url ) {
									$img = $img_id = $credit = $caption = $supersize_url = $thumb_url = $alt = '';
									if ($attachment_id ) { 
										if (isset(get_post($attachment_id)->post_excerpt)) $caption = get_post($attachment_id)->post_excerpt;
										if (isset(get_post($attachment_id)->post_excerpt)) $caption = get_post($attachment_id)->post_excerpt;
										$thumb_url = wp_get_attachment_image_src( $attachment_id, 'thumb-nocrop' );
										$alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
										$supersize_url = wp_get_attachment_image_src( $attachment_id, 'supersize-photo' );
										$photo_credit = get_post_meta($attachment_id, 'pp_photo_credit', true);
										if (is_array($thumb_url)) {
									?>					 
										<li data-thumb="<?php echo $thumb_url[0]; ?>">
											<figure>
												<a title="Click for an even larger view" href="<?php echo $supersize_url[0]; ?>" class="lightbox-g1 cboxElement"><?php echo wp_get_attachment_image( $attachment_id, 'xlarge' );?></a>
												<?php if ($caption) { ?>
												<figcaption>
													<div class="inner">
														<?php echo esc_html( $caption ); ?>
														<?php if ($photo_credit) echo "<div class=\"credit\">Photo by " . esc_html( $photo_credit ) . "</div>"; ?>
													</div>
												</figcaption>
												<?php } ?>
											</figure>
										</li><?php
										} // end if $thumb_url
									} // end if $attachment_id
								} // end foreach ?></ul>
						<?php if ($image_count > 1) echo "</div>\n"; ?>
					</section><!-- /#prop_photos -->
				<?php } else { ?>			
					<section class="single_pic" id="prop_photos">
						<img class="no-prop-photo" src="<?php echo plugins_url(); ?>/design-portfolio/images/no-photo.png" height="300" width="450" alt="No Photo Available">
					</section><!-- /#prop_photos -->
				<?php } //end if $images_array ?>			

				<?php $project_desc = get_post_meta($post_id, $pp_prefix . 'project_desc', true); ?>
				<?php if ($project_desc) { ?>
				<section id="port_info">
					<div class="desc"><?php echo apply_filters( 'the_content', $project_desc ); ?></div>
				</section><!-- /#port_info -->
				<?php } ?>

				
				<?php
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

					$args = array( 
						'post_type' => 'testimonial', 
						'posts_per_page' => -1, 
						'meta_query' => array(
							array(
								'key'		=> $theme_prefix . 'assoc-pp-selector',
								'value' 	=> $post_id,
								'compare'	=> '=',
							),
						),
						'post_status' => 'publish'
					);

					$get_quotes = new WP_Query( $args );
					$quote = '';
					if( $get_quotes->have_posts() ) :
						$output = "<section id=\"port_testimonials\" class=\"testimonials pretty\">\n";
						while( $get_quotes->have_posts() ) : $get_quotes->the_post();			
							$tstml_id = get_the_id();
							$name = get_the_title();
							$quote = get_post_meta($tstml_id, $tstml_prefix . 'the_quote', true);
							$credls = get_post_meta($tstml_id, $tstml_prefix . 'spk_crd', true);
							$qlink = get_post_meta($tstml_id, $tstml_prefix . 'quote_link', true);
							$qpic_id = get_post_meta($tstml_id, $tstml_prefix . 'photo_1_id', true);
							if ($qpic_id ) {
								$qimage = wp_get_attachment_image( $qpic_id, 'thumbnail' );
								$qcaption = get_post($qpic_id)->post_excerpt;
								$qcredit = get_post_meta($qpic_id, 'pp_photo_credit', true);
							}
							if ($quote)
							{
								$output .= "<blockquote>\n";
								if ($qpic_id) { 
									$output .= "<div class=\"photo\">\n";
									if ($qimage) {
										$output .= "<div class=\"photo-wrap\">\n";  // use if placing img
										$output .= $qimage . "\n";
										$output .= "</div><!-- /.photo-wrap -->\n";
									}
									if ($qcaption) { 
										$output .= "<div class=\"caption\">" . wp_kses( $qcaption, $allowed_html ) . "</div>\n";
									}
									if ($qcredit) { 
										$output .= "<div class=\"credit\">" . wp_kses( $qcredit, $allowed_html ) . "</div>\n";
									}
									$output .= "</div><!-- /.photo -->\n";
								}
								$output .= "<div class=\"quote\">";
								$quote = wp_kses( $quote, $allowed_html );
								$quote = wpautop( $quote);
								$output .= $quote;
								$output .= "</div>\n";
								$output .= "<div class=\"speaker\">";
								if ($qlink) $output .= "<a href=\"" . esc_url($qlink) . "\">";
								$output .= "<span class=\"spk-name\">" . wp_kses( $name, $allowed_html );
								if ($credls !== '') {
									$output .= ",</span> <span class=\"spk-credit\">" . wp_kses( $credls, $allowed_html ) . "</span>";
								}
								else 
								{
									$output .= "</span>";
								}
								if ($qlink) $output .= "</a>";
								$output .= "</div><!-- /.speaker -->\n";

								$output .= "</blockquote>\n";
							} // end if $quote

						endwhile;
						$output .= "</section><!-- /#port_testimonials -->\n";
						echo $output;
					endif;
					wp_reset_postdata();
					
				?>
			
				<?php 
					$terms = get_the_terms($post->ID,'project_type');
					if ( !empty($terms) || has_tag() )  { ?>
						<section id="port_tax">
							<h4>Related Projects</h4>
							<?php 
								if ( !empty( $terms ) ){
									echo "<div class=\"terms-list categories\">\n";
									echo "<strong>Categories:</strong>\n";
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
									echo "<div class=\"terms-list tags\">\n";
									echo "<strong>Tags:</strong>\n";
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
						</section><!-- /#port_tax -->
				<?php } ?>
			</div><!-- .content -->

		<?php wp_reset_query(); ?>

		<?php endif; ?>
		</div><!-- /#primary -->
		<?php //  get_sidebar(); ?>
	</div><!-- /.page-main -->
</div><!-- /.page-body -->

<?php get_footer(); ?>