<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.evolutionarygraphics.com
 * @since             1.1.0
 * @package           evg_art_portfolio
 *
 * @wordpress-plugin
 * Plugin Name:       EVG Art Portfolio
 * Plugin URI:        http://www.evolutionarygraphics.com/plugins
 * Description:       Creates Art Portfolio CPT, meta fields and display functions.
 * Version:           1.0.0
 * Author:            Charlie Covington
 * Author URI:        http://www.evolutionarygraphics.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       evg-art-portfolio
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$evdp_prefix = 'evdp_';

// Styling for the custom post type admin

define( 'PROPERTIES_PLUGIN_PATH', plugin_dir_url(__FILE__) );

add_action( 'admin_enqueue_scripts', 'evdp_admin_enqueue_styles' );

function evdp_admin_enqueue_styles() {
	//** Admin Styles
	wp_register_style( 'evdp-admin-css', PROPERTIES_PLUGIN_PATH . 'css/admin-style.css' );
	wp_enqueue_style ( 'evdp-admin-css' );
}



if ( ! function_exists('evdp_reg_portfolios') ) {

	// Register Custom Post Type
	function evdp_reg_portfolios() {

		$labels = array(
			'name'                  => _x( 'Pottery Gallery', 'Post Type General Name', 'evg-art-portfolio' ),
			'singular_name'         => _x( 'Gallery Item', 'Post Type Singular Name', 'evg-art-portfolio' ),
			'menu_name'             => __( 'Pottery Gallery', 'evg-art-portfolio' ),
			'name_admin_bar'        => __( 'Admin Pottery Gallery', 'evg-art-portfolio' ),
			'archives'              => __( 'Pottery Gallery', 'evg-art-portfolio' ),
			'parent_item_colon'     => __( 'Gallery:', 'evg-art-portfolio' ),
			'all_items'             => __( 'All Items', 'evg-art-portfolio' ),
			'add_new_item'          => __( 'New Gallery Item', 'evg-art-portfolio' ), // at top of new item page
			'add_new'               => __( 'Add New Item', 'evg-art-portfolio' ),
			'new_item'              => __( 'New Gallery Item', 'evg-art-portfolio' ),
			'edit_item'             => __( 'Edit Gallery Item', 'evg-art-portfolio' ),
			'update_item'           => __( 'Update', 'evg-art-portfolio' ),
			'view_item'             => __( 'View Gallery Item', 'evg-art-portfolio' ),
			'search_items'          => __( 'Search Gallery', 'evg-art-portfolio' ),
			'not_found'             => __( 'Not found', 'evg-art-portfolio' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'evg-art-portfolio' ),
			'insert_into_item'      => __( 'Insert into item', 'evg-art-portfolio' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'evg-art-portfolio' ),
			'items_list'            => __( 'Gallery list', 'evg-art-portfolio' ),
			'items_list_navigation' => __( 'Gallery list navigation', 'evg-art-portfolio' ),
			'filter_items_list'     => __( 'Filter items list', 'evg-art-portfolio' ),
		);
		$args = array(
			// 'label'                 => __( 'Gallery', 'evg-art-portfolio' ),
			'description'           => __( 'Pottery Gallery Manager', 'evg-art-portfolio' ),
			'labels'                => $labels,
			'supports'           	=> array( 'title', 'thumbnail'),
			'taxonomies'            => array( 'post_tag', 'art_category' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'				=> 'dashicons-portfolio',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'rewrite'               => array( 'slug' => 'pottery-gallery' ),
		);
		register_post_type( 'evg_art_portfolio', $args );

	}
	add_action( 'init', 'evdp_reg_portfolios', 0 );

	//CREATE CUSTOM TAXONOMY

	add_action( 'init', 'create_my_taxonomies', 0 );

	function create_my_taxonomies(){
		register_taxonomy(
		  'art_category',
		  'evg_art_portfolio', array(
				'labels' => array(
					'name' => 'Gallery Categories',
					'singular_name' => 'Gallery Category',
					'add_new_item' => 'Add New Gallery Category',
					'new_item_name' => "New Gallery Category"
				),
				'show_ui' 		=> true,
				'show_tagcloud' => false,
				'hierarchical'  => true

		   )
		);
	}

	// some functions to run only on activation
	function evdp_plugin_activate() {
		evdp_reg_portfolios();
		flush_rewrite_rules();
	}
	register_activation_hook( __FILE__, 'evdp_plugin_activate' );

}

// CMB2 CUSTOMIZED FIELDS

if ( ! function_exists('sm_cmb_render_text_currency') ) {

	// render numbers
	add_action( 'cmb2_render_text_currency', 'sm_cmb_render_text_currency', 10, 5 );
	function sm_cmb_render_text_currency( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
		echo $field_type_object->input( array( 'class' => 'cmb2-text-small', 'type' => 'text' ) );
	}

	// sanitize the field
	add_filter( 'cmb2_sanitize_text_currency', 'sm_cmb2_sanitize_text_currency', 10, 2 );
	function sm_cmb2_sanitize_text_currency( $null, $new ) {
		$new = preg_replace( "/[^0-9\.]/", "", $new );           // strip out everything but numbers and "."
		if ($new != '') $new = number_format((float)$new, 2, '.', '');							// force it to 2 decimals
		return $new;
	}

}

/*
 * Plugin Name: CMB2 Custom Field Type - Star Rating
 * Description: Makes available a 'star_rating' CMB2 Custom Field Type. Based on https://github.com/WebDevStudios/CMB2/wiki/Adding-your-own-field-types#example-4-multiple-inputs-one-field-lets-create-an-address-field
 * Author: Evan Herman
 * Author URI: https://www.evan-herman.com
 * Version: 0.1.0
 */

/**
 * Template tag for displaying an star rating from the CMB2 star rating field type (on the front-end)
 *
 * @since  0.1.0
 *
 * @param  string  $metakey The 'id' of the 'star rating' field (the metakey for get_post_meta)
 * @param  integer $post_id (optional) post ID. If using in the loop, it is not necessary
 */
function eh_cmb2_star_rating_field( $metakey, $post_id = 0 ) {
	echo eh_cmb2_get_star_rating_field( $metakey, $post_id );
}

/**
 * Template tag for returning a star rating from the CMB2 star rating field type (on the front-end)
 *
 * @since  0.1.0
 *
 * @param  string  $metakey The 'id' of the 'star rating' field (the metakey for get_post_meta)
 * @param  integer $post_id (optional) post ID. If using in the loop, it is not necessary
 */
function eh_cmb2_get_star_rating_field( $metakey, $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$rating = get_post_meta( $post_id, $metakey, 1 );
	$stars_container = '<section class="cmb2-star-container">';
	$x = 1;
	$total = 5;
		while( $x <= $rating ) {
			$stars_container .= '<span class="dashicons dashicons-star-filled"></span>';
			$x++;
		}
		if( $rating < $total ) {
			while( $rating < $total ) {
				$stars_container .= '<span class="dashicons dashicons-star-empty"></span>';
				$rating++;
			}
		}
	$stars_container .= '</section>';
	wp_enqueue_style( 'dashicons' );
	return $stars_container;
}

/**
 * Render 'star rating' custom field type
 *
 * @since 0.1.0
 *
 * @param array  $field           			The passed in `CMB2_Field` object
 * @param mixed  $value       				The value of this field escaped.
 *                                   					It defaults to `sanitize_text_field`.
 *                                   					If you need the unescaped value, you can access it
 *                                   					via `$field->value()`
 * @param int    $object_id          		The ID of the current object
 * @param string $object_type        	The type of object you are working with.
 *                                  				 	Most commonly, `post` (this applies to all post-types),
 *                                   					but could also be `comment`, `user` or `options-page`.
 * @param object $field_type_object  	The `CMB2_Types` object
 */
function eh_cmb2_render_star_rating_field_callback( $field, $value, $object_id, $object_type, $field_type_object ) {
	// enqueue styles
	wp_enqueue_style( 'star-rating-metabox-css', plugin_dir_url(__FILE__) . '/css/star-rating-field-type.css', array( 'cmb2-styles' ), 'all', false );
	?>
		<section id="cmb2-star-rating-metabox">
			<fieldset>
				<span class="star-cb-group">
					<?php
						$y = 5;
						while( $y > 0 ) {
							?>
								<input type="radio" id="rating-<?php echo $y; ?>" name="<?php echo $field_type_object->_id( false ); ?>" value="<?php echo $y; ?>" <?php checked( $value, $y ); ?>/>
								<label for="rating-<?php echo $y; ?>"><?php echo $y; ?></label>
							<?php
							$y--;
						}
					?>
				</span>
			</fieldset>
		</section>
	<?php
	echo $field_type_object->_desc( true );

}
add_filter( 'cmb2_render_star_rating', 'eh_cmb2_render_star_rating_field_callback', 10, 5 );

// Add CBM2 library for meta boxes
// central location in this case
if ( file_exists( ABSPATH . 'wp-content/CMB2/init.php' ) ) {
	require_once ABSPATH . 'wp-content/CMB2/init.php';
}

// if ( file_exists( dirname( __FILE__ ) . '/lib/cmb2/init.php' ) ) {
// 	require_once dirname( __FILE__ ) . '/lib/cmb2/init.php';
// } elseif ( file_exists( dirname( __FILE__ ) . '/lib/CMB2/init.php' ) ) {
// 	require_once dirname( __FILE__ ) . '/lib/CMB2/init.php';
// }

// ADD METABOX FIELDS FOR PORTFOLIO

function evdp_portfolio_metaboxes( $meta_boxes ) {

	global $evdp_prefix;
	
	$this_year = date("Y");

	$year_created = array();
	for ($x = $this_year; $x >= 1990; $x--) // run loop backwards so current year is first
	{
		$year_created[$x] = $x;
	}

	$cmb_portmeta = new_cmb2_box( array(
		'id'         => $evdp_prefix . 'meta_container',
		'title'      => 'Gallery Item Data',
		'object_types' => array('evg_art_portfolio'), // post type 
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
	) );

	$cmb_portmeta->add_field( array(
		'name'    => 'Year Made',
		'id'      => $evdp_prefix . 'date',
		'type'    => 'select',
		'options' => $year_created,
		'row_classes'	=> 'quarter',
	) );

	$cmb_portmeta->add_field( array(
		'name'         => 'Images',
		'desc'         => 'Upload multiple images. Drag-and-Drop to reorder. Main image goes first.',
		'id'           => $evdp_prefix . 'file_list',
		'type'         => 'file_list',
		'preview_size' => 'thumbnail', // Default: array( 50, 50 )
	) );
	
	$cmb_portmeta->add_field( array(
		'name' => 'Description',
		'id'   => $evdp_prefix . 'desc',
		'type' => 'wysiwyg',
		'options' => array('media_buttons' => false,'textarea_rows' => 5,'teeny' => true,) 
	) );
	
	$cmb_portmeta->add_field( array(
		'name' => 'Materials',
		'id'   => $evdp_prefix . 'materials',
		'type' => 'text'
	) );

/*
	$cmb_portmeta = new_cmb2_box( array(
		'id'         => 'evdp_meta_side_container',
		'title'      => 'Featured',
		'object_types' => array('evg_art_portfolio'), // post type 
		'context'    => 'normal',
		'priority'   => 'default',
		'show_names' => true, // Show field names on the left
	) );
*/

	$cmb_portmeta->add_field( array(
		'name' => 'Price',
		'id'   => $evdp_prefix . 'price',
		'type' => 'text_money'
	) );

	$cmb_portmeta->add_field( array(
		'name'    => 'Availability Status',
		'id'      => $evdp_prefix . 'status',
		'type'    => 'select',
		'options' => array(
			'available'   => 'Available',
			'sold'     => 'Sold',
			'gifted'     => 'Gifted (shows as "Sold")',
			'discarded'     => 'Discarded (shows as "Unavailable")',
		),
	) );

	$cmb_portmeta->add_field( array(
		'name' => 'Importance Rating',
		'id'   => $evdp_prefix . 'stars_rating',
		'desc' => 'This can be used to sort the portfolio page, so your favorites come first.',
		'type' => 'star_rating',
		'default' => '3'
	) );
	
	$cmb_portmeta->add_field( array(
		'name'    => 'Featured',
		'id'      => $evdp_prefix . 'featured',
		'type'    => 'radio',
		'options'          => array(
			'yes' => 'Yes',
			'no'   => 'No',
		),
		'default' => 'no'
// 		,'row_classes'	=> 'stacked',
// 		'before_row'   => '<div class="half">',
// 		'after_row'   => '</div><!-- /.half --></div><!-- /.row -->',
	) );

	/**
	 * Metabox to add fields to categories and tags
	 */
	$cmb_term = new_cmb2_box( array(
		'id'               => $evdp_prefix . 'cat_metabox',
		'title'            => 'Category Metabox', // Doesn't output for term boxes
		'object_types'     => array( 'term' ), // Tells CMB2 to use term_meta vs post_meta
		'taxonomies'       => array( 'art_category' ), // Tells CMB2 which taxonomies should have these fields
		// 'new_term_section' => true, // Will display in the "Add New Category" section
	) );
	$cmb_term->add_field( array(
		'name'    => 'Category Singular Name',
		'id'      => $evdp_prefix . 'cat_singular_name',
		'type'    => 'text_medium'
	) );
	$cmb_term->add_field( array(
		'name'    => 'Category Image',
		'desc'    => 'Select/upload a category featured image.',
		'id'      => $evdp_prefix . 'cat_image',
		'type'    => 'file',
		// Optional:
		'options' => array(
			'url' => true, // Hide the text input for the url
		),
		'text'    => array(
			'add_upload_file_text' => 'Add File' // Change upload button text. Default: "Add or Upload File"
		),
		// query_args are passed to wp.media's library query.
		// 		'query_args' => array(
		// 			'type' => 'application/pdf', // Make library only display PDFs.
		// 		),
	) );

}

add_action( 'cmb2_admin_init', 'evdp_portfolio_metaboxes' );

/* // CREATE CUSTOM COLUMN FOR ADMIN DISPLAY //*/

/* Changes default admin ordering to be by _cmb_date */

function evg_art_portfolio_order($query){
	global $evdp_prefix;
	$post_type = $query->get('post_type');
	/* Check post types. */
	if ( $post_type == "evg_art_portfolio" || is_tax("art_category") ) {
		/* Post Column: e.g. title */
		if(is_admin()) {  
			if($query->get('orderby') == ''){
				$query->set('meta_key', $evdp_prefix . 'date');
				$query->set('orderby', 'meta_value_num');
			}
			/* Post Order: ASC / DESC */
			if($query->get('order') == ''){
				$query->set('order', 'DESC');
			}
		} else { // not admin page
			if($query->get('posts_per_page') == ''){
				$query->set('posts_per_page', 12);
			}
			/*
			if($query->get('orderby') == ''){
				
				$query->set('meta_query', array(
					'relation' => 'AND',
					'rating_clause' => array(
						'key' => $evdp_prefix . 'stars_rating',
						'compare' => 'EXISTS',
					),
					'date_clause' => array(
						'key' => $evdp_prefix . 'date',
						'compare' => 'EXISTS',
					),
				));
				$query->set('orderby', array(
					'rating_clause' => 'DESC',
					'date_clause' => 'DESC',
				));
			}
			*/
		}
	}
}

add_action('pre_get_posts', 'evg_art_portfolio_order');


function evdp_excerpt($text, $excerpt_length) {
	global $post;
	if (!$excerpt_length || !is_int($excerpt_length))$excerpt_length = 20;
	if ( '' != $text ) {
		$text = strip_shortcodes( $text );
		$text = apply_filters('the_content', $text);
		$text = str_replace(']]>', ']]>', $text);
		$excerpt_more = " ...";
		$text = wp_trim_words( $text, $excerpt_length, $excerpt_more );
	}
	return apply_filters('the_excerpt', $text);
}

// Then define the columns

add_filter( "manage_evg_art_portfolio_posts_columns", "evg_art_portfolio_custom_columns" );
function evg_art_portfolio_custom_columns( $cols ) {

	$cols = array(
		'cb'			=>     '<input type="checkbox" />',
		'title'			=> __( 'Title' ),
		'photo'			=> __( 'Photos' ),
		'_project_date' => __( 'Year' ),
		'_project_desc' => __( 'Description' ),
		'_categories' => __('Type' ),
		'_rating' 	=> __( 'Rating' ),
		'_featured' 	=> __( 'Featured' ),
	);
	return $cols;
}

// Custom Column View

add_action( "manage_evg_art_portfolio_posts_custom_column", "evg_art_portfolio_display_custom_columns");

function evg_art_portfolio_display_custom_columns( $pp_column ) {
  
  	global $evdp_prefix;

	global $post;
	$post_id = get_the_ID();
	switch ( $pp_column ) {
		case "photo":
			$prop_photos = get_post_meta( get_the_ID(), $evdp_prefix . 'file_list', true );
			if ($prop_photos) {
				$count = count($prop_photos);
				reset($prop_photos);  // get first item in array
				$prop_photo_id = key($prop_photos); // get first item's key (which is where CMB2 is holding image ID
				echo '<div class="container">';
				echo '<a href="' . get_edit_post_link( $post_id) . '">';
					echo wp_get_attachment_image( $prop_photo_id, 'thumbnail' );
				// echo '<div class="num_pics">'.$count.' <span>'.($count == 1 ? 'photo' : 'photos').'</span></div>';
				echo '</a>';
				echo '</div>';
			} else {
				echo '<div class="container">';
				echo '<a href="' . get_edit_post_link( $post_id) . '">';
				echo '<img class="no-photo" src="' . plugins_url( 'images/no-photo.png"', __FILE__ ) . ' width="150" alt="No Photo Available">';
				echo '</a>';
				echo '</div>';
			}
			break;
		case "_project_desc":
			$proj_desc = get_post_meta($post_id, $evdp_prefix . 'desc', true );
			echo evdp_excerpt($proj_desc, 10);
			break;
		case "_project_date":
			$proj_date = get_post_meta( $post_id, $evdp_prefix . 'date', true );
			echo $proj_date;
			break;
		case '_categories' :
			$terms = get_the_terms( $post_id, 'art_category' );

			/* If terms were found. */
			if ( !empty( $terms ) ) {

				$out = array();

				/* Loop through each term, linking to the 'edit posts' page for the specific term. */
				foreach ( $terms as $term ) {
					$out[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'art_category' => $term->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'art_category', 'display' ) )
					);
				}

				/* Join the terms, separating them with a comma. */
				echo join( ', ', $out );
			}

			/* If no terms were found, output a default message. */
			else {
				_e( 'Not categorized!' );
			}
			break;
		case '_rating' :
// 			$proj_rating = get_post_meta( $post_id, $evdp_prefix . 'stars_rating', true );
// 			echo $proj_rating;
			echo eh_cmb2_get_star_rating_field ($evdp_prefix . 'stars_rating', $post_id);
			break;
		case "_featured":
			$featured_on_featured = get_post_meta($post_id, $evdp_prefix . 'featured', true );
			if ($featured_on_featured == "yes"){
				echo '<span class="dashicons dashicons-yes" style="text-align:center;"></span>';
			}
			break;
	}
}

// SORTABLE COLUMNS


// add_action( 'wp_enqueue_scripts', 'evdp_load_javascript_files' );
function evdp_load_javascript_files() {
	// wp_enqueue_style( 'portfolio-styles', plugins_url( 'css/art-portfolio-styles.css' , __FILE__ ) );
	// wp_register_script( 'jquery.flexslider', plugins_url( 'js/jquery.flexslider-min.js' , __FILE__ ), array('jquery'), '2.1', true );
	// wp_register_script( 'jquery.colorbox', plugins_url( 'js/jquery.colorbox-min.js' , __FILE__ ), array('jquery'), '1.6.4', true );
	// 	wp_register_script( 'portfolio-scripts', plugins_url( 'js/portfolio-scripts.js' , __FILE__ ), array('jquery', 'jquery.flexslider'), '1.0', true );
	//	wp_register_script( 'jquery.easing', plugins_url( 'js/jquery.easing.js' , __FILE__ ), array('jquery'), '1.3', true );
	// 	wp_register_script( 'jquery.mousewheel', plugins_url( 'js/FlexSlider-w-Lightbox/jquery.mousewheel.js' , __FILE__ ), array('jquery'), '3.0.6', true );
	
	// wp_enqueue_script('jquery.flexslider');
	// wp_enqueue_script('masonry'); // Wordpress built in version
	// wp_enqueue_script('jquery.colorbox');
	//	wp_enqueue_script('jquery.easing');
	// 	wp_enqueue_script('jquery.mousewheel');
	// 	wp_enqueue_script( 'portfolio-scripts' );
}

add_filter( 'template_include', 'include_template_function', 1 );

function include_template_function( $template_path ) {

	if ( get_post_type() == 'evg_art_portfolio' ) {
		if ( is_single() ) {

			// checks if the file exists in the theme first,
			// otherwise serve the file from the plugin
			if ( locate_template( 'art-portfolio-single.php' ) ) {
				$template_path = locate_template( 'art-portfolio-single.php' );
			} else {
				$template_path = plugin_dir_path( __FILE__ ) . 'art-portfolio-single.php';
			}
		} 
		elseif ( is_archive() ) {
			if ( locate_template( 'art-portfolio-archive.php' ) ) {
				$template_path = locate_template( 'art-portfolio-archive.php' );
			} else { 
				$template_path = plugin_dir_path( __FILE__ ) . 'art-portfolio-archive.php';
           }
      }
	}
	return $template_path;
}
/*

if ( ! function_exists( 'evdp_paged_queries_nav' ) ) :
	//Displays navigation to next/previous pages when applicable.
	function evdp_paged_queries_nav( $html_id ) {
	global $wp_query;
		$html_id = esc_attr( $html_id );
		$big = 999999999; // need an unlikely integer
		$page_nums = paginate_links( array(
			'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format' => '?paged=%#%',
			'current' => max( 1, get_query_var('paged') ),
			'total' => $wp_query->max_num_pages,
			'prev_next'    => False
		) );
		if ($page_nums) { 
		?>
		<nav id="<?php echo $html_id; ?>" class="post-nav" role="navigation">
			<h3 class="assistive-text">Post navigation</h3>
			<div class="prev"><?php previous_posts_link('Previous'); ?></div>
			<div class="next"><?php next_posts_link('Next'); ?></div>
			<div class="page-nums"><?php echo $page_nums; ?></div>
		</nav><!-- #<?php echo $html_id; ?> .navigation -->
		<?php 
		} // end if $page_nums
	}
endif;

//something to get tags to link... not sure why this works or is necessary.
add_filter('pre_get_posts', 'query_post_type');
function query_post_type($query) {
  if(is_category() || is_tag()) {
    $post_type = get_query_var('post_type');
	if($post_type)
	    $post_type = $post_type;
	else
	    $post_type = array('post','evg_art_portfolio','nav_menu_item'); // replace cpt to your custom post type
    $query->set('post_type',$post_type);
	return $query;
    }
}
*/

/*
// Add image sizes used by plugin
if ( function_exists( 'add_image_size' ) ) { 
	// 	add_image_size( 'evg_large_nocrop', 850, 99999);
	// 	add_image_size( 'evg_medium_large_nocrop', 550, 99999);
	// 	add_image_size( 'evg_medium_nocrop', 390, 99999);
	// 	add_image_size( 'evg_small_nocrop', 175, 99999);
    // add_image_size( 'evg-thumb', 150, 150, true);
    // add_image_size( 'evg-thumb', 150, 150 ); //( not cropped) This is used for admin pages
    // add_image_size( 'small-photo', 220, 500 ); //( not cropped)
    // add_image_size( 'supersize-photo', 1200, 900 ); //( not cropped)
}
*/

function get_portfolio_func( $atts ){

	global $evdp_prefix;

	$atts = shortcode_atts( 
		array(
			'number' => '6',
			'order' => '', // this post type defaults to DESC
			'orderby' => '', // this post type defaults to a dual ordering by meta stars and then meta date
			'ids' => '', // custom taxonomy term IDs
			'category' => '', // custom taxonomy term IDs
			'class' => '', // css class names added to container
		), 
		$atts
	);

	$output = '';
	$numposts = $atts['number'];
	$ordposts = $atts['order'];
	$ordbyposts = $atts['orderby'];
	$idsposts = $atts['ids'];
	$catposts = $atts['category'];
	$css_classes = $atts['class'];
	$css_classes = str_replace(",", " ", $css_classes);
	
	$tax_query = array('relation' => 'AND');
	
    if ($catposts !== '') {
		$catposts = explode(',', $catposts);
        $tax_query[] =  array(
			'taxonomy' => 'art_category',
			'terms' => $catposts,
		);
    }
    
    if ($idsposts !== '') {
		$idsposts = explode(',', $idsposts);
    }
    
	$args = array( 
		'post_type' => 'evg_art_portfolio', 
		'tax_query' => $tax_query,
		'posts_per_page' => $numposts,
		'orderby' => $ordbyposts,
		'order' => $ordposts,
		'post__in' => $idsposts,
		'post_status' => 'publish' 
	);
	
	$get_portfolio = new WP_Query( $args );
	$i = 0;
	$quote = '';
	if( $get_portfolio->have_posts() ) {
		$output .= "<div class=\"portfolio portfolio-wrap evg-portfolio port-sc\">\n";
		$output .= "<ul class=\"inner " . $css_classes . "\">\n";
		while( $get_portfolio->have_posts() ) : $get_portfolio->the_post();
			global $post;
			$id = get_the_ID();

			$primary_cat = '';
			$categories = get_the_terms($id, 'art_category');
			if ( ! empty( $categories ) ) {
				// first try getting first category name as fallback
				$primary_cat = esc_html( $categories[0]->name ); 
				// secondly, try getting meta singular name of first category as something better for a fallback name
				$primary_cat_id = $categories[0]->term_id;
				$first_cat_singular = '';
				$first_cat_singular = get_term_meta( $primary_cat_id, $evdp_prefix . 'cat_singular_name', true );
				if ($first_cat_singular !== '') {
					$primary_cat = get_term_meta( $primary_cat_id, $evdp_prefix . 'cat_singular_name', true ); 
				}
				// Yoast plugin dependent Primary Category Feature
				$yoast_cat = '';
				$yoast_cat_id = '';
				$yoast_cat_id = get_post_meta( $id, '_yoast_wpseo_primary_art_category', true );
				if ($yoast_cat_id !== '') {
					//thirdly, try yoast primary category term name
					$yoast_term = get_term($yoast_cat_id, 'art_category');
					$primary_cat = $yoast_term->name;
					//finally, try meta singular name for yoast term
					$yoast_meta = '';
					$yoast_meta = get_term_meta( $yoast_cat_id, $evdp_prefix . 'cat_singular_name', true );
					if ($yoast_meta !== '') {
						$primary_cat = get_term_meta( $yoast_cat_id, $evdp_prefix . 'cat_singular_name', true );
					}
				}
				//end Yoast
			}
			$i++;
			$port_photos = get_post_meta( $id, $evdp_prefix . 'file_list', true );
			if ($port_photos) {

				$img_size = 'large';
				if (strpos($css_classes, 'col2') !== false) {
					$img_size = 'large';
				}
				if (strpos($css_classes, 'col3') !== false) {
					$img_size = 'medium_large';
				}
				if (strpos($css_classes, 'col4') !== false) {
					$img_size = 'medium_large';
				}

				$count = count($port_photos);
				$more = $count - 1;
				if ( has_post_thumbnail() ) {
					$attachment_id = get_post_thumbnail_id();							
				} else {
					reset($port_photos);  // get first item in array
					$attachment_id = key($port_photos); // get first item's key (which is where CMB2 is holding image ID
				}
				$thumb_url = wp_get_attachment_image_url( $attachment_id, 'thumbnail' );

				$output .=  "<li ";
				if (isset($thumb_url)) $output .= "data-thumb=\"" . $thumb_url . "\"";
				$output .= " class=\"item " . ($primary_cat !== "" ? (str_replace(" ", "-", strtolower($primary_cat))) . "-item " : "") . ($i % 2 == 0 ? "even" : "odd") . " item-" . $i ."\">\n";
				$output .=  "<a class=\"box-link\" href=\"" . get_permalink() . "\" title=\"View Details About This " . $primary_cat . " Piece\">\n"; 

				$output .=  "<div class=\"photo\">\n";

					$img_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
					if ($img_alt == '') {
						$img_alt = get_the_title ($attachment_id);
						$img_alt = ucwords(str_replace("-", " ", $img_alt));
					}
					$img_html = wp_get_attachment_image( $attachment_id, $img_size, "", array( "alt" => $img_alt));
		
					$output .=  $img_html;
				$output .=  "</div>\n";
			} else {
				$output .=  "<li class=\"item " . ($primary_cat !== "" ? (str_replace(" ", "-", strtolower($primary_cat))) . "-item " : "") . ($i % 2 == 0 ? "even" : "odd") . " item-" . $i ."\">\n";
				$output .=  "<a class=\"box-link\" href=\"" . get_permalink() . "\" title=\"View Details About This " . $primary_cat . " Piece\">\n"; 
				$output .=  "<div class=\"photo no-photo\"><img class=\"no-prop-photo\" src=\"" . plugins_url() . "/art-portfolio/images/no-photo.png\" height=\"300\" width=\"450\" alt=\"No Photo Available\"></div>\n";
			} //end if $port_photos
			$output .=  "<div class=\"text\">\n";
				if ($primary_cat !== "") $output .=  "<div class=\"primary-cat\">" . $primary_cat . "</div>\n";
				$output .=  "<div class=\"item-title\">" . get_the_title() . "</div>\n";
				$evdp_desc = get_post_meta( $id, $evdp_prefix . 'desc', 1 );
				if ($evdp_desc) {
					$output .=  "<div class=\"description\">" . wp_trim_words($evdp_desc, 22) . "</div>\n";
				}
				$output .=  "<div class=\"link read-more\">View Project";
					// if ($more > 0) $output .=  " <span class=\"num_pics\">+" . $more . " more photos!</span>";
				$output .=  "</div><!-- ./read-more -->\n";
			$output .=  "</div><!-- ./text -->\n";
			$output .=  "</a><!-- /.box-link -->\n";
			$output .=  "</li><!-- /.item -->\n";
		
		endwhile;
		$output .= "</ul><!-- /.inner -->\n";
		$output .= "</div><!-- /.portfolio .wrapper -->\n";
	}
	wp_reset_postdata();
	return $output;

}
add_shortcode( 'evg-portfolio', 'get_portfolio_func' );

add_action('admin_menu', 'evg_art_portfolio_register_submenu_page_page');
 
function evg_art_portfolio_register_submenu_page_page() {
    add_submenu_page(
        'edit.php?post_type=evg_art_portfolio',
        'How to Use Gallery Shortcodes',
        'Shortcode Instructions',
        'manage_options',
        'evg_art_portfolio-instructions-page',
        'evg_art_portfolio_submenu_page_callback' );
}
 
function evg_art_portfolio_submenu_page_callback() {
?>
		<h1>How to Use Gallery Shortcodes</h1>
		<p>The Pottery Gallery supports a Shortcode method to insert <strong>Gallery posts</strong> into your site by these methods:</p>
		<ol>
			<li>via Shortcodes added to your pages or widgets</li>
			<li>via <code>&lt;?php echo do_shortcode('[evg-portfolio]'); ?&gt;</code> added to your templates</li>
		</ol>
		<h3>These are the shortcode attributes and their default values. All of these are optional.</h3>
		<ol>
			<li><code>number = 6</code> // set to -1 for all</li>
			<li><code>order = 'DESC'</code> // this post type defaults to DESC with a pre_get_posts function</li>
			<li><code>orderby = ''</code> // this post type defaults to a dual ordering by meta rating value (stars) and then meta date with a pre_get_posts function. It can be overridden with normal WP_Query parameters, including: <strong>date, none, ID, author, title, date, modified, parent, rand, comment_count, menu_order, meta_value, meta_value_num, post__in</strong></li>
			<li><code>ids =''</code> // comma delimited list of just the post IDs you want</li>
			<li><code>category =''</code> // comma delimited list of Gallery Categories IDs</li>
			<li><code>class = ''</code> // css class names added to container; col2, col3, col4 will give columns</li>
		</ol>
		<h3>Examples:</h3>
			<code>[evg-portfolio]</code><br>
			<code>[evg-portfolio number="10" order="ASC" orderby="date" category="23, 24, 25" class="flexslider, my-container"]</code>.
<?php }
