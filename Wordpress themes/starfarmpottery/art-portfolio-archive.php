<?php
/**
 * @subpackage starfarm
 * Template Name: Project Portfolio Archive
 */ 
	// $title_tag = 'mud';

	get_header(); 
	global $evdp_prefix;

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
	global $wp_query;
	$post_type = get_post_type();
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$term_id = (isset(get_queried_object()->term_id)) ? (get_queried_object()->term_id) : '';
	$taxonomy = (isset(get_queried_object()->taxonomy)) ? (get_queried_object()->taxonomy) : '';
	
	$cat_image_id = $term_name = $term_link = '';
	$post_type_obj = get_post_type_object( get_post_type() );
	$archive_title = apply_filters('post_type_archive_title', $post_type_obj->labels->archives );        
	$archive_link = get_post_type_archive_link( $post_type );	       
	if ($term_id !== '') {
		$cat_image_id = get_term_meta( $term_id, $evdp_prefix . 'cat_image_id', 1 );
		//$cat_desc = get_term_meta( $term_id, $evdp_prefix . 'cat_intro_text', 1);
		$cat_desc = category_description($term_id);
		$term_name = single_term_title("", false);
		$term_link = get_tag_link($term_id);
		// limit our tag link to our custom post type
		if ($taxonomy == 'post_tag') $term_link = $term_link . "?post_type=" . $post_type;
	}
	$cat_meta = 'false';
	if( (get_query_var('paged') == 0) && !empty($cat_desc) ) $cat_meta = 'true'; 
?>
<div class="page-body full-width-sidebar">
	<div class="page-main page-width">
		<div id="primary">
		<?php if ( have_posts() ) : ?>
			<nav aria-label="breadcrumb">
				<ol class="breadcrumbs">
					<li><a href="<?php echo get_home_url(); ?>">Home</a></li>
					<?php 
						if (isset($archive_title)) {
							if ($term_name) {
								$term_name = ucwords_title($term_name);
								echo '<li><a href="' . $archive_link . '">' . $archive_title . '</a></li>';
								echo '<li class="active" aria-current="page">' . $term_name . '</li>';
							} else {
								echo '<li class="active" aria-current="page">' . $archive_title . '</li>';
							}
						}
					 ?>
				</ol>
			</nav>
			<div class="wrapper top-section">
				<header class="page-title<?php if ($cat_meta == 'false') echo ' no-cat-meta'; ?>">
					<div class="hgroup">
						<h1><?php 
							if (isset($archive_title)) {
								echo $archive_title;
							} else {
								echo 'Art Gallery';
							}
						 ?></h1>
						<?php 
							if ($term_name !== '') {
								$term_name = ucwords($term_name);

								$subhead = '';
								
								$subhead .= '<span class="term">';
								if ($taxonomy == 'post_tag') {
									$subhead .= 'Tag: ';
								}
								$subhead .= $term_name;
								$subhead .= '</span>';
								if ($subhead !== '') echo '<h2 class="tax">' . $subhead . '</h2>'; 
							}
						?>
						<?php if (!empty($cat_desc)) { ?>			
							<div class="text intro cat-desc">
								<?php echo $cat_desc; ?>
							</div>		
						<?php } ?>
					</div>
					<div id="category-selector"<?php if ($cat_meta == 'false') echo ' class="no-cat-meta"'; ?>>
						<div id="projects-menu" class="wrapper-dropdown">
							<!-- <div class="label">View By Category</div> -->
							<button class="menu-control" type="button" aria-expanded="false" aria-controls="categorySelectMenu">
								View By Category
							</button>
							<ul class="dropdown" id="categorySelectMenu">
								<?php 
									$this_cpt = get_post_type();
									$cpt_obj = get_post_type_object($this_cpt);
									$cpt_slug = $cpt_obj->rewrite['slug'];
									$cpt_base_url =  get_home_url() . '/' . $cpt_slug . '/'; 
									echo '<li><a href="' . $cpt_base_url . '">Show All</a></li>';
					
									//list terms in a given taxonomy using wp_list_categories
									$args = array(
										'hide_empty' => true,
										'orderby' => 'name',
										'show_count' => 0,
										'pad_counts' => 0,
										'hierarchical' => 0,
										'taxonomy' => 'art_category',
										'title_li' => '',
										'use_desc_for_title' => 0,
										'exclude' => 48
									);
									wp_list_categories($args);
								 ?>
							</ul>
						</div><!-- /.wrapper-dropdown -->
					</div><!-- /#category-selector -->
				</header>

				<?php if ( 1 < 0) { ?>
					ALWAYS FALSE CONDITIONAL TO HIDE THIS BLOCK FOR THIS WEBSITE
					<?php if ( get_query_var('paged') == 0 && (($cat_image_id && $cat_image_id !== '') || !empty($cat_desc) ) ) { ?>
						<div class="intro-wrapper">
							<?php if ($cat_image_id && $cat_image_id !== '') { 
								$cat_meta = 'true'; ?>
								<div class="photo featured-photo cat-photo">					
									<?php echo wp_get_attachment_image( $cat_image_id, 'large' ); ?>
								</div>
							<?php } ?>
							<?php if (!empty($cat_desc)) { ?>			
								<div class="text intro cat-desc">
									<?php echo $cat_desc; ?>
								</div>
							<?php } ?>
						</div>
					<?php } ?>
				<?php } ?>


			</div><!-- /.top-section -->

			<?php if ( function_exists( 'evcd_post_nav' ) ) evcd_post_nav( 'nav-above' ); ?>

			<div class="archive-loop projects grid mt-6 mb-6 clearfix">
				<div class="grid-sizer"></div>
				<div class="gutter-sizer"></div>
				<?php while ( have_posts() ) : the_post(); 
					// pp_get_template( 'display-projects-template.php' );
					$post_id = get_the_id();
					$post_name = $post->post_name;
					$post_link = get_permalink();
					if ($term_id !== '') {
						if ($taxonomy == 'post_tag') {
							$post_link = $post_link . "?tag_id=" . $term_id;
						} elseif ($taxonomy == 'art_category') {
							$post_link = $post_link . "?term_id=" . $term_id;
						}
					}
					$proj_title = esc_html( get_the_title());
					$proj_photos = get_post_meta( $post_id, $evdp_prefix . 'file_list', true );
					if ($proj_photos) {
						$count = count($proj_photos);
						if ( has_post_thumbnail() ) {
							$attachment_id = get_post_thumbnail_id();
							$count++;							
						} else {
							reset($proj_photos);  // get first item in array
							$attachment_id = key($proj_photos); // get first item's key (which is where CMB2 is holding image ID
						}
						if ($attachment_id ) { 
							echo "<div id=\"item-" . $post_name ."\" class=\"post item project grid-item\">\n";
							echo "\t\t\t\t\t<a class=\"box-link\" href=\"" . $post_link . "\" tabindex=\"0\">\n";
							echo "\t\t\t\t\t\t<div class=\"photo\">";
							$img_alt = get_post_meta($attachment_id, "_wp_attachment_image_alt", true);
							if ($img_alt == '') {
								$img_alt = get_the_title ($attachment_id);
								$img_alt = ucwords(str_replace("-", " ", $img_alt));
							}
							$img_src_size = 'sm-nocrop';
							// $sizes =  '(max-width: 479px) calc(.48 * (100vw - 30px)), ';
							// $sizes .= '(max-width: 575px) calc(.313 * (100vw - 30px)), ';
							// $sizes .= '(max-width: 959px) calc(.313 * (100vw - 60px)), ';
							// $sizes .= '(max-width: 1199px) calc(.235 * (100vw - 60px)), ';
							// $sizes .= '275px';
							$sizes =  '(max-width: 479px) calc(.48 * (100vw - 30px)),';
							$sizes .= '(min-width: 480px) calc(.313 * (100vw - 30px)), ';
							$sizes .= '(min-width: 576px) calc(.313 * (100vw - 60px)), ';
							$sizes .= '(min-width: 960px) calc(.235 * (100vw - 60px)), ';
							$sizes .= '(min-width: 1200px) 275px';
							$sizes .= '275px';

		
							echo wp_get_attachment_image( $attachment_id, $img_src_size, '', array( 'alt' => $img_alt, 'sizes' => $sizes));

// 							$image_attributes = wp_get_attachment_image_src( $attachment_id, $img_src_size );
// 							if ( $image_attributes ) {
// 								$retina_image_src = wp_get_attachment_image_src( $attachment_id, 'medium' );
// 								$retina_image_src = $retina_image_src[0];
// 								echo "<img alt=\"" . $img_alt . "\" src=\"" . $image_attributes[0] . "\" width=\"" . $image_attributes[1] . "\" height=\"" . $image_attributes[2] . "\"   srcset=\"" . $image_attributes[0] . ", " . $retina_image_src . " 2x\" />";
// 							}
							// if ( $count > 1 ) echo "<div class=\"num-pics\">" . --$count . " <span>More " . ( $count > 1 ? 'Photos' : 'Photo') . "</span></div>\n";
							echo "</div>\n";
							echo "<div class=\"text-wrap\">\n";
							echo "<h4 class=\"item-title\">" . $proj_title . "</h4>\n";
							
							$item_status = get_post_meta($post_id, $evdp_prefix . 'status', true);
							$item_price = get_post_meta($post_id, $evdp_prefix . 'price', true);
	
	
							$my_content = get_post_meta($post_id, $evdp_prefix . 'project_desc', true);
							if ( '' != $my_content ) {
								$descrip = strip_tags($my_content);
								$descrip = strip_shortcodes( $descrip );
								$descrip = apply_filters('the_content', $descrip);
								$descrip = str_replace(']]>', ']]>', $descrip);
								$excerpt_length = 20; // 20 words
								$excerpt_more = apply_filters('excerpt_more', '&hellip;');
								$descrip = wp_trim_words( $descrip, $excerpt_length, $excerpt_more );
								echo "<div class=\"description\">" . $descrip . "</div>\n";
							}
	
							// echo "<div class=\"read-more smaller\">View Details</div>\n";
							echo "</div><!-- ./text-wrap -->\n";
							/*
							if ($item_status == 'available') {
								if ($item_price) {
									echo "<div class=\"availibility available price\"> $" . wp_kses( $item_price, $allowed_html ) . "</div>\n";
								}
							} else {
								echo "<div class=\"availibility not-available sold\">Sold</div>\n";
							}
							*/
							echo "</a>\n";
							echo "</div><!-- /.item -->\n";
						}
					} //end if $proj_photos
					?>
				<?php endwhile; ?>
			</div><!-- /.archive loop -->
			<?php if (($wp_query->max_num_pages) > 1 ) { ?>
				<div class="page-load-status" style="display:none;">
					<div class="loader-ellips infinite-scroll-request">
						<span class="loader-ellips__dot"></span>
						<span class="loader-ellips__dot"></span>
						<span class="loader-ellips__dot"></span>
						<span class="loader-ellips__dot"></span>
					</div>
					<p class="scroller-status__message infinite-scroll-last">---</p>
					<p class="scroller-status__message infinite-scroll-error"">All content loaded.</p>
				</div>
			<?php } ?>

			<?php if ( function_exists( 'evcd_post_nav' ) ) evcd_post_nav( 'nav-below' ); ?>
			
		<?php 
			endif;
			wp_reset_postdata();
		?>		
		</div><!-- /#primary -->
		<?php  get_sidebar(); ?>
	</div><!-- /.page-main -->
</div><!-- /.page-body -->
<?php get_footer(); ?>