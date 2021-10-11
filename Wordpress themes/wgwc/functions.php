<?php
/**
 * WGWC theme functions and definitions.
*/

$theme_prefix = 'wgwc_';

/**
	Customize Login Page
*/
function my_login_logo() { ?>
    <style type="text/css">
        body.login div#login {
			width: 320px;
			padding: 20px;
			background: #FFF;
		}		
		body.login div#login h1 {
			padding: 10px;
			background: #000;
		}
        body.login div#login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri() ?>/images/login-logo.png);
            background-position: left center;
            padding-bottom: 0;
            background-size: contain;
            width: 320px;
            height: 102px;
            margin: 0;
        }
        body.login div#login form {
			margin-top: 10px;
			padding: 0;
			box-shadow: none;
			border: 0 none;
		}
		body.login div#login #nav,
		body.login div#login #backtoblog
		{
			padding: 0;
			margin: 10px 0 0 0;
		}
		
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

// THIS GIVES US SOME OPTIONS FOR STYLING THE ADMIN AREA
/*
function custom_colors() {
   echo '<style type="text/css">
            #poststuff h3.acf_section { background-color: #CCC; color: #FFF; font-weight: bold; font-size: 18px; text-transform: uppercase; padding: 3px 10px; }
			.acf_postbox > .inside > .field.field_type-message { padding: 20px 0 10px 0; }
			.wp-core-ui .attachment .thumbnail .centered img { max-width: 100%; max-height: 100%; }
			.wp-core-ui .attachment-preview
			{
				-webkit-box-shadow: none;
				box-shadow: none;
				background: none;
			}
         </style>';
}

add_action('admin_head', 'custom_colors');
*/

/*
 * Let WordPress manage the document title.
 * By adding theme support, we declare that this theme does not use a
 * hard-coded <title> tag in the document head, and expect WordPress to
 * provide it for us.
 */
// add_theme_support( 'title-tag' );

// Custom Page Titles

function insert_custom_title() {
	global $theme_prefix;
	echo "<title>";
	if (have_posts()) : the_post();
		$customTitle = get_post_meta(get_the_ID(), $theme_prefix . 'seo-page-title', true);
		if ($customTitle) {
			echo $customTitle;
		} elseif (is_tag()) {
			echo ucwords(single_tag_title('', false)) . ' | ' . get_bloginfo('name');
		} elseif (is_archive()) {
			wp_title(''); echo 'Archive'; 
		} elseif ((is_single()) || (is_page()) && (!(is_front_page())) ) { // single post or page but not front
			echo get_the_title() . ' | ' . get_bloginfo('name') . ' | ' . get_bloginfo('description');
		} elseif ( is_home() || is_front_page() ) {
			echo get_bloginfo('name') . ' | ' . get_bloginfo('description'); 
		} elseif (is_search()) {
			echo 'Search' . ' | ' . get_bloginfo('name');
		} elseif (is_404()) {
			echo 'Page Not Found (404 Error)' . ' | ' . get_bloginfo('name');
		} else {
			echo get_the_title() . ' | ' . get_bloginfo('name') . ' | ' . get_bloginfo('description');
		}
		$paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
	    if ($paged>1) {
	         echo ' - page '. $paged; 
		}
		rewind_posts();
    else:
      echo 'Page Not Found | ' . get_bloginfo('name');
	endif;
	echo "</title>\n";
}

add_action('wp_head','insert_custom_title');

if ( ! function_exists( 'evcd_content_nav' ) ) :
	/**
	 * Displays navigation to next/previous pages when applicable.
	 *
	*/
	function evcd_content_nav( $html_id ) {
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
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'WGWC' ); ?></h3>
			<div class="prev"><?php previous_posts_link( __( 'Previous', 'WGWC' ) ); ?></div>
			<div class="next"><?php next_posts_link( __( 'Next', 'WGWC' ) ); ?></div>
			<div class="page-nums"><?php echo $page_nums; ?></div>
		</nav><!-- #<?php echo $html_id; ?> .navigation -->
		<?php 
		} // end if $page_nums
	}
endif;

/*
add_action( 'init', 'vipx_allow_contenteditable_on_divs' );
function vipx_allow_contenteditable_on_divs() {
    global $allowedposttags;
 
    $tags = array( 'div' );
    $new_attributes = array( 'contenteditable' => array() );
 
    foreach ( $tags as $tag ) {
        if ( isset( $allowedposttags[ $tag ] ) && is_array( $allowedposttags[ $tag ] ) )
            $allowedposttags[ $tag ] = array_merge( $allowedposttags[ $tag ], $new_attributes );
    }
}
add_filter('tiny_mce_before_init', 'vipx_filter_tiny_mce_before_init');

function vipx_filter_tiny_mce_before_init( $options ) {
 
    if ( ! isset( $options['extended_valid_elements'] ) ) {
        $options['extended_valid_elements'] = '';
    } else {
        $options['extended_valid_elements'] .= ',';
    }
 
    if ( ! isset( $options['custom_elements'] ) ) {
        $options['custom_elements'] = '';
    } else {
        $options['custom_elements'] .= ',';
    }
 
    $options['extended_valid_elements'] .= 'div[contenteditable|class|id]';
    $options['custom_elements']         .= 'div[contenteditable|class|id|style]';
    return $options;
}
*/

// custom admin menu labels
function change_post_menu_label() {
    global $menu;
    $menu[5][0] = 'Blog';
}
add_action( 'admin_menu', 'change_post_menu_label' );

// make widgets execute shortcodes
add_filter('widget_text', 'do_shortcode');

function disable_wp_emojicons() {

  // all actions related to emojis
  remove_action( 'admin_print_styles', 'print_emoji_styles' );
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
  remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
  remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

  // filter to remove TinyMCE emojis
  add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
}

add_action( 'init', 'disable_wp_emojicons' );

function disable_emojicons_tinymce( $plugins ) {
  if ( is_array( $plugins ) ) {
    return array_diff( $plugins, array( 'wpemoji' ) );
  } else {
    return array();
  }
}

function evcd_setup() {

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style('editor-style.css');

	add_theme_support('editor-font-sizes');

	// Menus
	register_nav_menu( 'top-menu', __( 'Top Menu', 'evcd_textdomain' ) );
	register_nav_menu( 'side-menu', __( 'Side Menu', 'evcd_textdomain' ) );

	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 550, 660, true );
}
add_action( 'after_setup_theme', 'evcd_setup' );

/**
 * Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link.
 *
 */
function evcd_page_menu_args( $args ) {
	if ( ! isset( $args['show_home'] ) )
		$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'evcd_page_menu_args' );

/**
 * Registers our main widget area and the front page widget areas.
 *
 */
function evcd_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Header Widgets', 'evcd_textdomain' ),
		'id' => 'header-widgets',
		'description' => __( 'Header widgets. Will appear on all pages, unless you have an additional header.php file', 'evcd_textdomain' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'evcd_textdomain' ),
		'id' => 'sidebar-1',
		'description' => __( 'Main Sidebar. Appears with default page template.', 'evcd_textdomain' ),
		'before_widget' => '<aside id="%1$s" class="item widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>',
	) );
	// 
	// 	register_sidebar( array(
	// 		'name' => __( 'Alt Sidebar', 'evcd_textdomain' ),
	// 		'id' => 'sidebar-alt',
	// 		'description' => __( 'An alternative sidebar, for your convenience.', 'evcd_textdomain' ),
	// 		'before_widget' => '<aside id="%1$s" class="item widget %2$s">',
	// 		'after_widget' => '</aside>',
	// 		'before_title' => '<h3 class="widget-title"><span>',
	// 		'after_title' => '</span></h3>',
	// 	) );
	// 
	// 	register_sidebar( array(
	// 		'name' => __( 'Blog Sidebar', 'evcd_textdomain' ),
	// 		'id' => 'sidebar-blog',
	// 		'description' => __( 'Used on the blog.', 'evcd_textdomain' ),
	// 		'before_widget' => '<aside id="%1$s" class="item widget %2$s">',
	// 		'after_widget' => '</aside>',
	// 		'before_title' => '<h3 class="widget-title"><span>',
	// 		'after_title' => '</span></h3>',
	// 	) );
	// 
	// 	register_sidebar( array(
	// 		'name' => __( 'Homepage Sidebar', 'evcd_textdomain' ),
	// 		'id' => 'sidebar-homepage',
	// 		'description' => __( 'Sidebar used only on the homepage.', 'evcd_textdomain' ),
	// 		'before_widget' => '<aside id="%1$s" class="item widget %2$s">',
	// 		'after_widget' => '</aside>',
	// 		'before_title' => '<h3 class="widget-title"><span>',
	// 		'after_title' => '</span></h3>',
	// 	) );
	// 
	// 	register_sidebar( array(
	// 		'name' => __( 'Footer Widgets 1', 'evcd_textdomain' ),
	// 		'id' => 'footer-widgets-1',
	// 		'description' => __( 'First footer widget', 'evcd_textdomain' ),
	// 		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
	// 		'after_widget' => '</aside>',
	// 		'before_title' => '<h3 class="widget-title">',
	// 		'after_title' => '</h3>',
	// 	) );
	// 
	// 	register_sidebar( array(
	// 		'name' => __( 'Footer Widgets 2', 'evcd_textdomain' ),
	// 		'id' => 'footer-widgets-2',
	// 		'description' => __( 'Last footer widget. Excellent spot for a copyright.', 'evcd_textdomain' ),
	// 		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
	// 		'after_widget' => '</aside>',
	// 		'before_title' => '<h3 class="widget-title">',
	// 		'after_title' => '</h3>',
	// 	) );
}
add_action( 'widgets_init', 'evcd_widgets_init' );

// keep visual editor from stripping out valid markup
function myextensionTinyMCE($init) {
    // Command separated string of extended elements
    $ext = 'span[id|name|class|style]';

    // Add to extended_valid_elements if it alreay exists
    if ( isset( $init['extended_valid_elements'] ) ) {
        $init['extended_valid_elements'] .= ',' . $ext;
    } else {
        $init['extended_valid_elements'] = $ext;
    }

    // Super important: return $init!
    return $init;
}

add_filter('tiny_mce_before_init', 'myextensionTinyMCE' );

// add some new image sizes
if ( function_exists( 'add_image_size' ) ) {
    // uncropped sizes, for website screenshots
	//  add_image_size( 'win_width', 1600, 10000 ); 	// for parallax backgrounds
	// 	add_image_size( 'xlarge', 1040, 800, false);
	//  add_image_size( 'med-nocrop', 500, 500 ); //( not cropped) This is used for WGWC-slideshow.php template
	// 	add_image_size( 'portrait-small', 120, 160, true );
	add_image_size( 'hero-xl', 1755, 975, true );
	add_image_size( 'hero-lg', 1170, 650, true );
	add_image_size( 'hero-md', 708, 383, true );
	add_image_size( 'hero-sm', 345, 231, true );
	add_image_size( 'headshot-xl', 855, 855, true );
	add_image_size( 'headshot-lg', 570, 570, true );
	add_image_size( 'headshot-md', 414, 414, true );
	add_image_size( 'headshot-sm', 192, 192, true );
}

function evcd_scripts() {
	wp_register_script('theme-scripts', get_template_directory_uri() . '/js/theme-scripts.js', array ( 'jquery' ), '1.91', true);
	wp_enqueue_script('theme-scripts');
}
add_action( 'wp_enqueue_scripts', 'evcd_scripts', 100 );

function load_evcd_wp_admin_style() {
	wp_register_script('admin-scripts', get_template_directory_uri() . '/js/admin.js', array ( 'jquery' ), '1.1', true);
	wp_enqueue_script('admin-scripts');
	wp_register_style( 'admin-theme', get_template_directory_uri() . '/admin-style.css' );
	wp_enqueue_style ( 'admin-theme' );
}
add_action( 'admin_enqueue_scripts', 'load_evcd_wp_admin_style' );

/* fix WP's horrible caption constructor 
function jk_img_caption_shortcode_filter($val, $attr, $content = null)
{
	extract(shortcode_atts(array(
		'id'      => '',
		'align'   => '',
		'width'   => '',
		'caption' => ''
	), $attr));
	
	// No caption, no dice... But why width? 
	if ( 1 > (int) $width || empty($caption) )
		return $val;
 
	if ( $id )
		$id = esc_attr( $id );
     
	$content = str_replace('style="max-width: 100%; height: auto;" ', '', $content);

	return do_shortcode( $content ) . '<p class="wp-caption-text">' . $caption . '</p>';
}
add_filter( 'img_caption_shortcode', 'jk_img_caption_shortcode_filter', 10, 3 );
 */

add_filter( 'img_caption_shortcode', 'cleaner_caption', 10, 3 );

function cleaner_caption( $output, $attr, $content ) {

	/* We're not worried abut captions in feeds, so just return the output here. */
	if ( is_feed() )
		return $output;

	/* Set up the default arguments. */
	$defaults = array(
		'id' => '',
		'align' => 'alignnone',
		'width' => '',
		'caption' => ''
	);

	/* Merge the defaults with user input. */
	$attr = shortcode_atts( $defaults, $attr );

	/* If the width is less than 1 or there is no caption, return the content wrapped between the [caption]< tags. */
	if ( 1 > $attr['width'] || empty( $attr['caption'] ) )
		return $content;
	if (!empty( $attr['id'] )) {
		$photo_id = preg_replace('/[^0-9]/', '', $attr['id']); // hacky way to get the friggin' ID :(
		$photo_credit = get_post_meta($photo_id, 'pp_photo_credit', true);
	}
	/* Set up the attributes for the caption <div>. */
	$attributes = ( !empty( $attr['id'] ) ? ' id="' . esc_attr( $attr['id'] ) . '"' : '' );
	$attributes .= ' class="caption ' . esc_attr( $attr['align'] ) . '"';
	// $attributes .= ' style="width: ' . esc_attr( $attr['width'] ) . 'px"';

	/* Open the caption <div>. */
	$output = '<figure' . $attributes .'>';

	/* Allow shortcodes for the content the caption was created for. */
	$output .= do_shortcode( $content );

	/* Append the caption text. */
	$output .= '<figcaption>' . $attr['caption'];
	if (!empty($photo_credit))
		$output .= '<div class="credit">Photo by ' . esc_html( $photo_credit ) . '</div>';
	$output .= '</figcaption>';

	/* Close the caption </div>. */
	$output .= '</figure>';

	/* Return the formatted, clean caption. */
	return $output;
}

function email_encode( $atts, $email ){
	return '<a href="mailto:'.antispambot($email).'">'.antispambot($email).'</a>';
}
add_shortcode( 'enc_email', 'email_encode' );

function year_shortcode() {
  $year = date('Y');
  return $year;
}
add_shortcode('year', 'year_shortcode');

function clean_css($css_str) {
	$css_str = esc_html($css_str);
	$css_str = str_replace(array('.', ',', ';'), ' ' , $css_str); // replace . , ; with a space
	$css_str = preg_replace("/[^a-zA-Z0-9\s\-\_]/", "", $css_str); 
	return ' ' . preg_replace('/\s+/', ' ', $css_str); // replace multiple spaces with one
}

// Add specific CSS class by filter.

 
if ( has_post_thumbnail() ){
	add_filter( 'body_class', function( $classes ) {
		return array_merge( $classes, array( 'featured-photo-page' ) );
	} );
}

// Add CBM2 library for meta boxes
// central location in this case
if ( file_exists( ABSPATH . 'wp-content/CMB2/init.php' ) ) {
	require_once ABSPATH . 'wp-content/CMB2/init.php';
}

/**
 * Gets a number of terms and displays them as options
 * @param  CMB2_Field $field 
 * @return array An array of options that matches the CMB2 options array
 */
function cmb2_get_term_options( $field ) {
	$args = $field->args( 'get_terms_args' );
	$args = is_array( $args ) ? $args : array();

	$args = wp_parse_args( $args, array( 'taxonomy' => 'category' ) );

	$taxonomy = $args['taxonomy'];

	$terms = (array) cmb2_utils()->wp_at_least( '4.5.0' )
		? get_terms( $args )
		: get_terms( $taxonomy, $args );

	// Initate an empty array
	$term_options = array();
	$term_options[ 'none' ] = 'none';
	if ( ! empty( $terms ) ) {
		foreach ( $terms as $term ) {
			$term_options[ $term->term_id ] = $term->name;
		}
	}

	return $term_options;
}

//add_action( 'cmb2_admin_init', 'evcd_register_page_metaboxes' );

function evcd_register_page_metaboxes() {

	global $theme_prefix;
	
	$extra_content_sections = new_cmb2_box( array(
		'id'            => $theme_prefix . 'page-sections',
		'title'         => esc_html__( 'Extra Content Sections', 'evcd_textdomain' ),
		'object_types'  => array( 'page', ), // Post type
		'show_on'      => array( 'key' => 'page-template', 'value' => 'page-full-width.php' ),
		'priority'     => 'high',
		'closed'       => false,
	) );

	$group_field_id = $extra_content_sections->add_field( array(
		'id'          => $theme_prefix . 'content-group',
		'type'        => 'group',
		// 'repeatable'  => false, // use false if you want non-repeatable group
		'options'     => array(
			'group_title'   => esc_html__( 'Content Section {#}', 'evcd_textdomain' ), // since version 1.1.4, {#} gets replaced by row number
			'add_button'    => esc_html__( 'Add Another Content Section', 'evcd_textdomain' ),
			'remove_button' => esc_html__( 'Remove Content Section', 'evcd_textdomain' ),
			'sortable'      => true, // beta
			// 'closed'     => true, // true to have the groups closed by default
		),
	) );

	// Id's for group's fields only need to be unique for the group. Prefix is not needed.

	$extra_content_sections->add_group_field( $group_field_id, array(
		'name'    => esc_html__( 'Content', 'evcd_textdomain' ),
		'id'      => 'section-content',
		'type'    => 'wysiwyg',
		'options' => array( 'textarea_rows' => 20, ),
		'before_row'   => '
			<div class="cmb2-tabs">
				<ul class="tabs-nav">
					<li class="current"><a href="#tab-content-1">Content</a></li>
					<li><a href="#tab-content-2">Styling + Options</a></li>
				</ul>
				<div class="tab-content tab-content-1 current">
					<div>
		',
		'after_row'   => '
				</div><!-- ./row -->
		',
	) );

	$extra_content_sections->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Content CSS classes', 'evcd_textdomain' ),
		// 'desc' => esc_html__( 'Applies css class to inner content wrapper', 'evcd_textdomain' ),
		'id'   => 'content-classes',
		'type' => 'text',
		'desc'             => esc_html__( 'Class names: full, twothirds, half, third, quarter, dark, light, right, left, center, padded, two-cols, three-cols, four-cols', 'evcd_textdomain' ),
		'row_classes'	=> 'content-classes',
		'before_row'   => '
			<div>
		',
		'after_row'    => '
					</div><!-- ./row -->
				</div><!-- /.tab-content -->
		',
	) );

	$extra_content_sections->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Section ID', 'evcd_textdomain' ),
		// 'desc' => esc_html__( 'CSS ID for this section', 'evcd_textdomain' ),
		'id'   => 'section-ID',
		'type' => 'text_medium',
		'desc' => esc_html__( 'Used for menu linking. For example, if ID is "my-section" menu link is "#my-section".', 'evcd_textdomain' ),
		'row_classes'	=> 'half',
		'before_row'   => '
			<div class="tab-content tab-content-2">
				<div>
		',
	) );

	$extra_content_sections->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Section Wrapper CSS classes', 'evcd_textdomain' ),
		// 'desc' => esc_html__( 'Applies css class to inner content wrapper', 'evcd_textdomain' ),
		'id'   => 'section-css-classes',
		'type' => 'text_medium',
		'row_classes'	=> 'half',
		'after_row'	=> '
				</div><!-- ./row -->
		',
	) );

	$extra_content_sections->add_group_field( $group_field_id, array(
		'name'    => esc_html__( 'Background Color', 'evcd_textdomain' ),
		'id'   => 'section-background-color',
		'type' => 'colorpicker',
		'options' => array(
			'alpha' => true, // Make this a rgba color picker.
		),
		'row_classes'	=> 'half',
		'before_row'   => '<div>',
		'desc'             => esc_html__( 'Note: background-color overlays background-image, so if you use both, adjust color transparency.', 'evcd_textdomain' ),
	) );

	$extra_content_sections->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Background Image', 'evcd_textdomain' ),
		'id'   => 'section-image',
		'type' => 'file',
		'options' => array(
			'url' => false, // Hide the text input for the url
		),
		'row_classes'	=> 'half',
		'after_row'   => '</div><!-- ./row -->',
	) );

	$extra_content_sections->add_group_field( $group_field_id, array(
		'name'             => esc_html__( 'Parallax Background', 'evcd_textdomain' ),
		'desc'             => esc_html__( 'Does not work on mobiles.', 'evcd_textdomain' ),
		'id'               => 'section-parallax',
		'type'             => 'radio_inline',
		'default' => 'false',
		'options'          => array(
			'false' => esc_html__( 'Off', 'evcd_textdomain' ),
			'true'   => esc_html__( 'On', 'evcd_textdomain' ),
		),
		'before_row'   => '<div>',
		'after_row'    => '
					</div><!-- ./row -->
				</div><!-- /.tab-content -->
			</div><!-- /.cmb2-tabs -->
		',
	) );

	$page_options = new_cmb2_box( array(
		'id'            => $theme_prefix . 'page-options',
		'title'         => esc_html__( 'WGWC Theme Page Options', 'evcd_textdomain' ),
		'object_types'  => array( 'page', ), // Post type
		'priority'     => 'high',
        'context'      => 'side', //  'normal', 'advanced', or 'side'
		// 'closed'       => true,
	) );
	
	$page_options->add_field( array(
		'name'             => esc_html__( 'Show Page Title', 'evcd_textdomain' ),
		'id'               => $theme_prefix . 'header-title',
		'type'             => 'radio_inline',
		'default' => 'true',
		'options'          => array(
			'true'   => esc_html__( 'Yes', 'evcd_textdomain' ),
			'false' => esc_html__( 'No', 'evcd_textdomain' ),
		),
	) );

/*
	// This next one is dependent on EVG Sliders plugin.
	if ( function_exists('sldr_reg_cpt') ) {
		$page_options->add_field( array(
			'name'             => esc_html__( 'EVG Slider in Header', 'evcd_textdomain' ),
			'desc'             => esc_html__( 'Choose Slider Group', 'evcd_textdomain' ),
			'id'               => $theme_prefix . 'header-slider',
			'type'             => 'text',
		) );
	}
*/

	$page_options->add_field( array(
		'name'             => esc_html__( 'SEO Title Tag', 'evcd_textdomain' ),
		'id'               => $theme_prefix . 'seo-page-title',
		'type' => 'text',
		'row_classes'	=> 'seo-title-count',
		'desc' => '55 characters max. This sets it for this page only.',
		'after_field'   => '
			<div class="the-title-count"><span id="title-chars">55</span> characters remaining.</div>
		',
	) );

	$page_options->add_field( array(
		'name'             => esc_html__( 'SEO Meta Page Description', 'evcd_textdomain' ),
		'id'               => $theme_prefix . 'seo-page-description',
		'type' => 'textarea_small',
		'row_classes'	=> 'seo-desc-count',
		'desc' => '160 characters max. This sets it for this page only, and will override what is set in Theme Options.',
		'after_field'   => '
			<div class="the-count"><span id="chars">160</span> characters remaining.</div>
		',
	) );

}
	
// function cmb2_get_your_post_type_post_options() {
// 	return evcd_get_post_list_as_options( array( 'post_type' => 'project_portfolio', 'numberposts' => -1 ) );
// }

function contactinfo_submenu_page_callback() {
	$my_theme = wp_get_theme();
?>
		<h1>Contact Info Usage</h1>
		<p>The contact info data set and shortcode are functions of the theme <strong><?php echo $my_theme->get( 'Name' ); ?></strong>. It supports a Shortcode method to insert <strong>contact info</strong> into your site by these methods:</p>
		<ol>
			<li>via Shortcodes added to your pages <code>[contactinfo]</code> or <code>[contactinfo include="business, name, title, phone, fax, address, email"]</code>. The number or order of the parameters doesn't matter. No parameters returns all contact info.</li>
			<li>via <code>&lt;?php echo do_shortcode('[contactinfo]'); ?&gt;</code> added to your templates.</li>
		</ol>
		<p>It also supports another optional parameter, 'style' => 'inline', which if included will change the wrapper tags to <code>&lt;span&gt;</code>. If not used if will use <code>&lt;div&gt;</code>.</p>
		<p>Note, labels are applied to items and can be hidden with the stylesheet, by adding: <code>span.type { display: none; }</code></p>
		<p class="big">Shortcode example: <strong><code class="big">[contactinfo include="phone" style="inline"]</code></strong></p>
<?php }

/**
 * CMB2 Theme Options
 * @version 0.1.0
 */
class Evcd_Admin {

	/**
 	 * Option key, and option page slug
 	 * @var string
 	 */
	private $key = 'evcd_options';

	/**
 	 * Options page metabox id
 	 * @var string
 	 */
	private $metabox_id = 'evcd_option_metabox';

	/**
	 * Options Page title
	 * @var string
	 */
	protected $title = '';

	/**
	 * Options Page hook
	 * @var string
	 */
	protected $options_page = '';

	/**
	 * Holds an instance of the object
	 *
	 * @var Evcd_Admin
	 */
	protected static $instance = null;

	/**
	 * Returns the running object
	 *
	 * @return Evcd_Admin
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
			self::$instance->hooks();
		}

		return self::$instance;
	}

	/**
	 * Constructor
	 * @since 0.1.0
	 */
	protected function __construct() {
		// Set our title
// 		$theme_name = wp_get_theme();
// 		$this->title = __( $theme_name . ' Theme Options', 'evcd_textdomain' );
		$this->title = __( 'Theme Options', 'evcd_textdomain' );
	}

	/**
	 * Initiate our hooks
	 * @since 0.1.0
	 */
	public function hooks() {
		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'cmb2_admin_init', array( $this, 'add_options_page_metabox' ) );
	}


	/**
	 * Register our setting to WP
	 * @since  0.1.0
	 */
	public function init() {
		register_setting( $this->key, $this->key );
	}

	/**
	 * Add menu options page
	 * @since 0.1.0
	 */
	public function add_options_page() {
		$this->options_page = add_menu_page( $this->title, $this->title, 'manage_options', $this->key, array( $this, 'admin_page_display' ) );
		add_submenu_page(
			 $this->key,
			'How to Use Contact Info',
			'Instructions',
			'manage_options',
			'contactinfo-instructions-page',
			'contactinfo_submenu_page_callback'
		);
		// Include CMB CSS in the head to avoid FOUC
		add_action( "admin_print_styles-{$this->options_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
	}

	/**
	 * Admin page markup. Mostly handled by CMB2
	 * @since  0.1.0
	 */
	public function admin_page_display() {
		$theme_name = wp_get_theme();
		?>
		<div class="wrap cmb2-options-page <?php echo $this->key; ?>">
			<h2><?php echo esc_html( $theme_name . ' ' . get_admin_page_title() ); ?></h2>
			<?php cmb2_metabox_form( $this->metabox_id, $this->key ); ?>
		</div>
		<?php
	}

	/**
	 * Add the options metabox to the array of metaboxes
	 * @since  0.1.0
	 */
	function add_options_page_metabox() {

		global $theme_prefix;
		
		// hook in our save notices
		add_action( "cmb2_save_options-page_fields_{$this->metabox_id}", array( $this, 'settings_notices' ), 10, 2 );
		
		// Contact Info Box

		$contact_info = new_cmb2_box( array(
			'id'         => $this->metabox_id,
			'hookup'     => false,
			'cmb_styles' => false,
			'show_on'    => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( $this->key, )
			),
		) );

		$contact_info->add_field( array(
			'name' => __( 'Organization Contact Info', 'evcd_textdomain' ),
			'id'   => $theme_prefix . 'contact_title',
			'type' => 'title',
			'before_row'   => '
				<div class="cmb2-tabs">
					<ul class="tabs-nav">
						<li class="current"><a href="#tab-content-1">Contact Info</a></li>
						<li><a href="#tab-content-2">Theme Styling</a></li>
						<li><a href="#tab-content-3">SEO Settings</a></li>
					</ul>
					<div class="tab-content tab-content-1 current">
			',
		) );

		$contact_info->add_field( array(
			'name' => __( 'Business Name', 'evcd_textdomain' ),
			'id'   => $theme_prefix . 'business_name',
			'type' => 'text',
		) );
	
		$contact_info->add_field( array(
			'name' => __( 'Contact Name', 'evcd_textdomain' ),
			'id'   => $theme_prefix . 'contact_name',
			'type' => 'text',
		) );
	
		$contact_info->add_field( array(
			'name' => __( 'Contact\'s Job Title', 'evcd_textdomain' ),
			'id'   => $theme_prefix . 'contact_job_title',
			'type' => 'text',
		) );
	
		$contact_info->add_field( array(
			'name' => __( 'Street Address 1', 'evcd_textdomain' ),
			'id'   => $theme_prefix . 'address_1',
			'type' => 'text',
		) );
	
		$contact_info->add_field( array(
			'name' => __( 'Street Address 2', 'evcd_textdomain' ),
			'id'   => $theme_prefix . 'address_2',
			'type' => 'text',
		) );
	
		$contact_info->add_field( array(
			'name' => __( 'Street Address 3', 'evcd_textdomain' ),
			'id'   => $theme_prefix . 'address_3',
			'type' => 'text',
		) );

		$contact_info->add_field( array(
			'name' => __( 'City', 'evcd_textdomain' ),
			'id'   => $theme_prefix . 'city',
			'type' => 'text',
		) );
	
		$contact_info->add_field( array(
			'name' => __( 'State', 'evcd_textdomain' ),
			'id'   => $theme_prefix . 'state',
			'type' => 'text',
		) );

		$contact_info->add_field( array(
			'name' => __( 'Zip Code', 'evcd_textdomain' ),
			'id'   => $theme_prefix . 'zip',
			'type' => 'text',
		) );

		$contact_info->add_field( array(
			'name' => __( 'Primary Phone', 'evcd_textdomain' ),
			'id'   => $theme_prefix . 'phone_1',
			'type' => 'text',
		) );

		$contact_info->add_field( array(
			'name' => __( 'Mobile Phone', 'evcd_textdomain' ),
			'id'   => $theme_prefix . 'phone_2',
			'type' => 'text',
		) );

		$contact_info->add_field( array(
			'name' => __( 'Fax', 'evcd_textdomain' ),
			'id'   => $theme_prefix . 'fax',
			'type' => 'text',
		) );

		$contact_info->add_field( array(
			'name' => __( 'Public Email Address', 'evcd_textdomain' ),
			'id'   => $theme_prefix . 'email',
			'type' => 'text_email',
			'after_row'    => '
					</div><!-- /.tab-content -->
			',
		) );

		// Theme Style Options Box

		$theme_options = new_cmb2_box( array(
			'id'         => $this->metabox_id,
			'hookup'     => false,
			'cmb_styles' => false,
			'show_on'    => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( $this->key, )
			),
		) );

		$theme_options->add_field( array(
			'name' => __( 'Theme Styling Options', 'evcd_textdomain' ),
			'id'   => $theme_prefix . 'style_options_title',
			'type' => 'title',
			'before_row'   => '
					<div class="tab-content tab-content-2">
			',
		) );

		$theme_options->add_field( array(
			'name' => esc_html__( 'Logo Image', 'evcd_textdomain' ),
			'id'   => $theme_prefix . 'style_options_logo',
			'type' => 'file',
			'options' => array(
				'url' => false, // Hide the text input for the url
			),
			'query_args' => array( 'type' => 'image' ),
			'desc'    => esc_html__( 'Will be used on header. Otherwise, theme will use text from Site Title and Tagline.', 'evcd_textdomain' ),
		) );

		$theme_options->add_field( array(
			'name' => esc_html__( 'Retina Logo Image', 'evcd_textdomain' ),
			'id'   => $theme_prefix . 'style_options_logo2x',
			'type' => 'file',
			'options' => array(
				'url' => false, // Hide the text input for the url
			),
			'query_args' => array( 'type' => 'image' ),
			'desc'    => esc_html__( 'Should be twice the pixel dimensions of the regular logo, but otherwise the same.', 'evcd_textdomain' ),
		) );

		$theme_options->add_field( array(
			'name' => esc_html__( 'Search/404 Page Image', 'evcd_textdomain' ),
			'id'   => $theme_prefix . '404_image',
			'type' => 'file',
			'options' => array(
				'url' => false, // Hide the text input for the url
			),
			'desc'    => esc_html__( 'Photo to show on "Page Not Found" or Empty Search Results page.', 'evcd_textdomain' ),
			'after_row'    => '
					</div><!-- /.tab-content -->
			',
		) );
		
		$misc_options = new_cmb2_box( array(
			'id'         => $this->metabox_id,
			'hookup'     => false,
			'cmb_styles' => false,
			'show_on'    => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( $this->key, )
			),
		) );

		$misc_options->add_field( array(
			'name' => __( 'Misc Settings', 'evcd_textdomain' ),
			'id'   => $theme_prefix . 'seo_title',
			'type' => 'title',
			'before_row'   => '
					<div class="tab-content tab-content-3">
			',
		) );

		$misc_options->add_field( array(
			'name' => __( 'Google Analytics Tracking ID', 'evcd_textdomain' ),
			'id'   => $theme_prefix . 'ga_tracking_id',
			'type' => 'text',
		) );
	
		$misc_options->add_field( array(
			'name' => __( 'SEO Meta Page Description', 'evcd_textdomain' ),
			'id'   => $theme_prefix . 'seo_description',
			'type' => 'textarea_small',
			'row_classes'	=> 'seo-desc-count',
			'desc' => '160 characters max. This sets as a fall-back for the whole site, but it\'s a good idea to set a custom description for each page.',
			'after_field'   => '
				<div class="the-count"><span id="chars">160</span> characters remaining.</div>
			',
			'after_row'    => '
					</div><!-- /.tab-content -->
				</div><!-- /.cmb2-tabs -->
			',
		) );
	}

	/**
	 * Register settings notices for display
	 *
	 * @since  0.1.0
	 * @param  int   $object_id Option key
	 * @param  array $updated   Array of updated fields
	 * @return void
	 */
	public function settings_notices( $object_id, $updated ) {
		if ( $object_id !== $this->key || empty( $updated ) ) {
			return;
		}

		add_settings_error( $this->key . '-notices', '', __( 'Settings updated.', 'evcd_textdomain' ), 'updated' );
		settings_errors( $this->key . '-notices' );
	}

	/**
	 * Public getter method for retrieving protected/private variables
	 * @since  0.1.0
	 * @param  string  $field Field to retrieve
	 * @return mixed          Field value or exception is thrown
	 */
	public function __get( $field ) {
		// Allowed fields to retrieve
		if ( in_array( $field, array( 'key', 'metabox_id', 'title', 'options_page' ), true ) ) {
			return $this->{$field};
		}

		throw new Exception( 'Invalid property: ' . $field );
	}

}

/**
 * Helper function to get/return the Evcd_Admin object
 * @since  0.1.0
 * @return Evcd_Admin object
 */
function evcd_admin() {
	return Evcd_Admin::get_instance();
}

/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 * @param  string $key     Options array key
 * @param  mixed  $default Optional default value
 * @return mixed           Option value
 */
function evcd_get_option( $key = '', $default = null ) {
	if ( function_exists( 'cmb2_get_option' ) ) {
		// Use cmb2_get_option as it passes through some key filters.
		return cmb2_get_option( evcd_admin()->key, $key, $default );
	}

	// Fallback to get_option if CMB2 is not loaded yet.
	$opts = get_option( evcd_admin()->key, $key, $default );

	$val = $default;

	if ( 'all' == $key ) {
		$val = $opts;
	} elseif ( array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
		$val = $opts[ $key ];
	}

	return $val;
}

// Get it started
evcd_admin();

// ADD SHORTCODE FOR CONTACT INFO
function contactinfo_shortcode( $atts ) {
	global $theme_prefix;
	// Attributes
	extract( shortcode_atts(
		array(
			'include' => 'all',
			'style' => 'block',
		), $atts )
	);
	
	if ($include == "all") {
		$include = "business, name, title, email, phone, fax, address";
	}
	$space = '';
	if ($style == "inline") {
		$html_elmt = "span";
	} else {
		$html_elmt = "div";
		$space = ' ';
	}
	$output = "<" . $html_elmt . " class=\"contact-info";
	if ($style == "inline") {
		$output .= " inline";
	}
	$output .= "\">\n";
	$include = preg_replace('/\s+/', '', $include);
	$value = array($include);
	$value = explode(',', $include);
	foreach ($value as $att_id) {
		if ($att_id == 'business') {
			if (evcd_get_option($theme_prefix . 'business_name')) {
				$output .= "<" . $html_elmt . " class=\"business\">" . evcd_get_option($theme_prefix . 'business_name') . "</" . $html_elmt . ">";
			} elseif (get_bloginfo('name')) {
				$output .= "<" . $html_elmt . " class=\"business\">" . esc_attr( get_bloginfo('name')) . "</" . $html_elmt . ">" . $space;
			}
		}
		elseif ($att_id == 'name') {
			if (evcd_get_option($theme_prefix . 'contact_name')) {
				$output .= "<" . $html_elmt . " class=\"name\">" . evcd_get_option($theme_prefix . 'contact_name') . "</" . $html_elmt . ">" . $space;
			}
		}
		elseif ($att_id == 'title') {
			if (evcd_get_option($theme_prefix . 'contact_job_title')) {
				$output .= "<" . $html_elmt . " class=\"job-title\">" . evcd_get_option($theme_prefix . 'contact_job_title') . "</" . $html_elmt . ">" . $space;
			}
		}
		elseif ($att_id == 'email') {
			if (evcd_get_option($theme_prefix . 'email')) {
				$encodedEmail = antispambot(evcd_get_option($theme_prefix . 'email'));
				$output .= "<" . $html_elmt . " class=\"email\"><span class=\"type\">Email:</span> <a href=\"mailto:" . $encodedEmail . "\">" . $encodedEmail."</a></" . $html_elmt . ">" . $space;
			}
		}
		elseif ($att_id == 'phone') {
			if (evcd_get_option($theme_prefix . 'phone_1')) {
				$phone_1 = evcd_get_option($theme_prefix . 'phone_1');
				$strPhone1 = preg_replace('/\D/', '', $phone_1);
				$output .= "<" . $html_elmt . " class=\"tel tel1\"><span class=\"type\">Phone:</span> <a class=\"phone\" href=\"tel:+1".$strPhone1."\">".$phone_1."</a></" . $html_elmt . ">" . $space;
			}
			if (evcd_get_option($theme_prefix . 'phone_2')) {
				$phone_2 = evcd_get_option($theme_prefix . 'phone_2');
				$strPhone2 = preg_replace('/\D/', '', $phone_2);
				$output .= "<" . $html_elmt . " class=\"tel tel2\"><span class=\"type\">Mobile:</span> <a class=\"phone\" href=\"tel:+1".$strPhone2."\">".$phone_2."</a></" . $html_elmt . ">" . $space;
			}
		}
		elseif ($att_id == 'fax') {
			if (evcd_get_option($theme_prefix . 'fax')) {
				$output .= "<" . $html_elmt . " class=\"fax\"><span class=\"type\">Fax:</span> " . evcd_get_option($theme_prefix . 'fax') . "</" . $html_elmt . ">" . $space;
			}
		}
		elseif ($att_id == 'address') {
			$output .= "<" . $html_elmt . " class=\"adr\">";
			if (evcd_get_option($theme_prefix . 'address_1')) {
				$output .= "<" . $html_elmt . " class=\"street-address\">" . evcd_get_option($theme_prefix . 'address_1') . "</" . $html_elmt . ">" . $space;
			}
			if (evcd_get_option($theme_prefix . 'address_2')) {
				$output .= "<" . $html_elmt . " class=\"extended-address\">" . evcd_get_option($theme_prefix . 'address_2') . "</" . $html_elmt . ">" . $space;
			}
			if (evcd_get_option($theme_prefix . 'address_3')) {
				$output .= "<" . $html_elmt . " class=\"extended-address\">" . evcd_get_option($theme_prefix . 'address_3') . "</" . $html_elmt . ">" . $space;
			}
			$output .= "<" . $html_elmt . " class=\"city_state_zip\">";
			if (evcd_get_option($theme_prefix . 'city')) {
				$output .= "<span class=\"locality\">" . evcd_get_option($theme_prefix . 'city') . "</span>";
			}
			if (evcd_get_option($theme_prefix . 'state')) {
				$output .= "<span class=\"region\">" . evcd_get_option($theme_prefix . 'state') . "</span>";
			}
			if (evcd_get_option($theme_prefix . 'zip')) {
				$output .= "<span class=\"postal-code\">" . evcd_get_option($theme_prefix . 'zip') . "</span>";
			}
			$output .= "</" . $html_elmt . ">" . $space;
	
			$output .= "</" . $html_elmt . ">" . $space;
		}
	} // foreach
	$output .= "</" . $html_elmt . "><!-- /.contact-info -->" . $space;

	return $output;
}
add_shortcode( 'contactinfo', 'contactinfo_shortcode' );

// ADD CATEGORIES TO ATTACHEMENTS (IMAGES)
function evcd_add_categories_to_attachments() {
    register_taxonomy_for_object_type( 'category', 'attachment' );
}
add_action( 'init' , 'evcd_add_categories_to_attachments' );

// Add a "styleselect" drop down menu to the editor
add_filter( 'mce_buttons_2', 'my_awesome_buttons' );
function my_awesome_buttons( $buttons ) {
    array_unshift( $buttons, 'styleselect' );
    return $buttons;
}

// Initialize our buttons
add_filter( 'tiny_mce_before_init', 'evcd_add_tinymce_formats' );
function evcd_add_tinymce_formats( $settings ) {

    $style_formats = array(
        array(
        	'title' => 'Column',
        	'block' => 'div',
        	'classes' => 'col',
        	'wrapper' => true // --- * Notice how this is a wrapper * ---
        ),
        array(
        	'title' => 'Read More Button',
        	'selector' => 'a',
        	'classes' => 'read-more',
        	// 'wrapper' => true // --- * Notice not a wrapper by default * ---
        ),
    );

    $settings['style_formats'] = json_encode( $style_formats );

    return $settings;
}
add_filter('tiny_mce_before_init', 'tiny_mce_remove_unused_formats' );
/*
 * Modify TinyMCE editor to remove H1.
 */
function tiny_mce_remove_unused_formats($init) {
	// Add block format elements you want to show in dropdown
	$init['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Address=address;Pre=pre;Div=div';
	return $init;
}

// In your theme's functions.php
function customize_add_button_atts( $attributes ) {
  return array_merge( $attributes, array(
    'text' => 'Add Another Photo',
  ) );
}
add_filter( 'wpcf7_field_group_add_button_atts', 'customize_add_button_atts' );
