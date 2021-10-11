<?php
/**
 * starfarm 2.0 theme functions and definitions.
*/

$theme_prefix = 'evcd_';

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
        body.login div#login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri() ?>/images/login-logo.png);
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

// Custom Page Titles
add_action('wp_head','insert_custom_title');

function insert_custom_title() {
	global $theme_prefix;
	global $title_tag;
	echo "<title>";
	if (!empty($title_tag)) {
		echo $title_tag;
	} elseif (have_posts()) {
		the_post();
		$customTitle = get_post_meta(get_the_ID(), $theme_prefix . 'seo-page-title', true);
		if ($customTitle) {
			echo $customTitle;
		} elseif ( 'evg_art_portfolio' == get_post_type() ) {
			$gallery_name = 'Gallery of Handmade Pottery';
			if (is_tax('art_category')) {
				echo  $gallery_name . ': ' . single_tag_title('', false) . ' | ' . get_bloginfo('name');
			} elseif (is_tag()) {
				echo $gallery_name . ': ' . ucwords(single_tag_title('', false)) . ' | ' . get_bloginfo('name');
			} elseif (is_post_type_archive()) {
				echo  $gallery_name . ' | ' . get_bloginfo('name');
			} elseif (is_single()){
				echo get_the_title() . ' | ' . $gallery_name . ' | ' . get_bloginfo('name');
			}
		} elseif ( is_home() ) {
			$blog_id = get_option('page_for_posts', true);
			echo get_the_title($blog_id) . ' | ' . get_bloginfo('name') . ' | ' . get_bloginfo('description'); 
		// } elseif (is_archive()) {
		// 	wp_title('Mud!'); 
		} elseif ((is_single()) || (is_page()) && (!(is_front_page())) ) { // single post or page but not front
			echo ucwords_title(get_the_title()) . ' | ' . get_bloginfo('name') . ' | ' . get_bloginfo('description');
		} elseif ( is_front_page() ) {
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
    } elseif (is_search()) {
		echo 'Search' . ' | ' . get_bloginfo('name');
	} else {
		echo get_bloginfo('name') . ": Page Not Found";
	}
	echo "</title>\n";
}

	/* 
		next/previous navigation on pages when applicable.
	*/
	function evcd_post_nav( $html_id ) {
		global $wp_query;
		// When we're on page 1, 'paged' is 0, but we're counting from 1,
		// so we're using max() to get 1 instead of 0
		$currentPage = max( 1, get_query_var( 'paged', 1 ) );
		// This creates an array with all available page numbers, if there
		// is only *one* page, max_num_pages will return 0, so here we also
		// use the max() function to make sure we'll always get 1
		$pages = range( 1, max( 1, $wp_query->max_num_pages ) );
		
		// Now, map over $pages and return the page number, the url to that
		// page and a boolean indicating whether that number is the current page
		$link_data = array_map( function( $page ) use ( $currentPage ) {
			return ( object ) array(
				'prev' => $page == $currentPage-1,
				'next'	=> $page == $currentPage+1,
				'isCurrent' => $page == $currentPage,
				'page' => $page,
				'url' => get_pagenum_link( $page )
			);
		}, $pages );

		if (count($pages) > 1 && $link_data) {
			$pages_str = '';
			$prev_str = '
				<li class="page-item disabled previous">
				  <a class="page-link" href="#" tabindex="-1" aria-disabled="true"><span aria-hidden="true">&laquo; </span>Previous</a>
				</li>
			';
			$next_str = '
				<li class="page-item disabled next">
				  <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Next<span aria-hidden="true"> &raquo;</span></a>
				</li>
			';
			$nav_str = '';

			foreach( $link_data as $link ) : 
				if ( $link->prev ):
					$prev_str =  '<li class="page-item previous">';
					$prev_str .=  '<a class="page-link" href="' . esc_attr__( $link->url ) . '"><span aria-hidden="true">&laquo; </span>Previous</a>';
					$prev_str .=  '</li>';
				endif;
				if ( $link->next):
					$next_str =  '<li class="page-item next">';
					$next_str .=  '<a class="page-link" href="' . esc_attr__( $link->url ) . '">Next<span aria-hidden="true"> &raquo;</span></a>';
					$next_str .=  '</li>';
				endif;
				if ( $link->isCurrent ):
					$pages_str .= '<li class="page-item disabled">';
					$pages_str .= '<a class="page-link" href="#" tabindex="-1" aria-disabled="true">';
					$pages_str .=  $link->page;
					$pages_str .= '</a>';
					$pages_str .= '</li>';
				 else :
					$pages_str .= '<li class="page-item">';
					$pages_str .= '<a class="page-link" href="' . esc_attr__( $link->url ) . '">';
					$pages_str .= $link->page;
					$pages_str .= '</a>';
					$pages_str . '</li>';
				endif;
			endforeach;
			?>
			<nav id="<?php echo $html_id; ?>" class="post-nav" aria-label="Post Navigation">
				<ul class="pagination">
					<?php echo $prev_str; ?>
					<?php echo $pages_str; ?>
					<?php echo $next_str; ?>
				</ul>
			</nav>
		<?php	
		}
	}
endif;

// custom admin menu labels
function change_post_menu_label() {
    global $menu;
    $menu[5][0] = 'Blog';
}
add_action( 'admin_menu', 'change_post_menu_label' );

// make widgets execute shortcodes
add_filter('widget_text', 'do_shortcode');

// Gutenberg blocks disable
add_theme_support( 'disable-custom-font-sizes' );
add_theme_support( 'disable-custom-colors' );
add_theme_support( 'editor-color-palette', array() );
add_theme_support( 'editor-font-sizes', array() );
function mu_remove_h1_wp_editor( $init ) {
    $init['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Pre=pre';
    return $init;
}
add_filter( 'tiny_mce_before_init', 'mu_remove_h1_wp_editor' );

function evg_remove_gutenberg_h1() {
	echo '<style>
	#editor .block-library-heading-level-toolbar .components-toolbar-group button:first-child {
		width: 3px;
		min-width: auto;
		padding: 3px 0;
		pointer-events: none;
		visibility: hidden;
	}
	</style>';
}
add_action( 'admin_head', 'evg_remove_gutenberg_h1' );

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

	// Adds RSS feed links to <head> for posts and comments.
	// add_theme_support( 'automatic-feed-links' );

	// Menus
	register_nav_menu( 'top-menu', __( 'Top Menu', 'evcd_textdomain' ) );
	register_nav_menu( 'side-menu', __( 'Side Menu', 'evcd_textdomain' ) );

	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 550, 660, true );
}
add_action( 'after_setup_theme', 'evcd_setup' );

/* Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link. */
function evcd_page_menu_args( $args ) {
	if ( ! isset( $args['show_home'] ) )
		$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'evcd_page_menu_args' );

/* Registers widget area  */
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

	register_sidebar( array(
		'name' => __( 'Alt Sidebar', 'evcd_textdomain' ),
		'id' => 'sidebar-alt',
		'description' => __( 'An alternative sidebar, for your convenience.', 'evcd_textdomain' ),
		'before_widget' => '<aside id="%1$s" class="item widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Blog Sidebar', 'evcd_textdomain' ),
		'id' => 'sidebar-blog',
		'description' => __( 'Used on the blog.', 'evcd_textdomain' ),
		'before_widget' => '<aside id="%1$s" class="item widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Widgets 1', 'evcd_textdomain' ),
		'id' => 'footer-widgets-1',
		'description' => __( 'First footer widget', 'evcd_textdomain' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Widgets 2', 'evcd_textdomain' ),
		'id' => 'footer-widgets-2',
		'description' => __( 'Last footer widget. Excellent spot for a copyright.', 'evcd_textdomain' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
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
    //add_image_size( 'win_width', 1600, 10000 ); 	// for parallax backgrounds
	add_image_size( 'xlarge', 1040, 800, false);
	add_image_size( 'lg-nocrop', 600 ); //( not cropped) This is used for starfarm-slideshow.php template
    add_image_size( 'md-nocrop', 450 ); //( not cropped) This is used for starfarm-slideshow.php template
	add_image_size( 'sm-nocrop', 275 ); //( not cropped) This is used for starfarm-slideshow.php template
    add_image_size( 'thumb-nocrop', 150 ); //( not cropped) This is used for admin pages
	add_image_size( 'supersize-photo', 1200, 1200 ); //( not cropped)
}

function evcd_scripts() {
	wp_dequeue_script( 'portfolio-config' ); // don't need default, I'll set it in my theme-scripts
	wp_register_script('infinite-scroll', get_template_directory_uri() . '/js/infinite-scroll.pkgd.min.js', array ( 'jquery' ), '4.0.1', true);
	wp_register_script( 'jquery.colorbox', get_template_directory_uri() . '/js/jquery.colorbox-min.js', array('jquery'), '1.6.4', true );

	if (is_singular('evg_art_portfolio')) {
		wp_enqueue_script('jquery.colorbox');
	}
	
	if(is_archive('evg_art_portfolio')) {
		wp_enqueue_script('masonry');
		wp_enqueue_script('infinite-scroll');
	}
	
	wp_register_script('theme-scripts', get_template_directory_uri() . '/js/theme-scripts.js', array ( 'jquery' ), '1.15', true);
	wp_enqueue_script('theme-scripts');

	if (!is_admin()) {
		wp_register_script('modernizr', get_template_directory_uri() . '/js/modernizr.js', false, '2.0.6', false);
		wp_enqueue_script('modernizr');
	}
}
add_action( 'wp_enqueue_scripts', 'evcd_scripts', 100 );

function load_evcd_wp_admin_style() {
	wp_register_script('admin-scripts', get_template_directory_uri() . '/js/admin.js', array ( 'jquery' ), '1.1', true);
	wp_enqueue_script('admin-scripts');
	wp_register_style( 'admin-theme', get_template_directory_uri() . '/admin-style.css' );
	wp_enqueue_style ( 'admin-theme' );
}
add_action( 'admin_enqueue_scripts', 'load_evcd_wp_admin_style' );

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
		$photo_credit = get_post_meta($photo_id, 'evcd_photo_credit', true);
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

function email_img_shortcode() {
  $interest_img = '<img src="https://placekitten.com/300/200" alt="a kitten" width="300">';
  return $interest_img;
}
add_shortcode('email_img', 'email_img_shortcode');


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

function evcd_image_meta_fields( $form_fields, $post ) {
    $form_fields['pp-photo-credit'] = array(
        'label' => 'Photo Credit',
        'input' => 'text',
        'value' => get_post_meta( $post->ID, 'evcd_photo_credit', true ),
    );

    return $form_fields;
}

add_filter( 'attachment_fields_to_edit', 'evcd_image_meta_fields', 10, 2 );

/* Save values of Photographer meta in media uploader */

function evcd_image_meta_fields_save( $post, $attachment ) {
    if( isset( $attachment['evcd_photo_credit'] ) )
        update_post_meta( $post['ID'], 'evcd_photo_credit', $attachment['evcd_photo_credit'] );

    return $post;
}

add_filter( 'attachment_fields_to_save', 'evcd_image_meta_fields_save', 10, 2 );

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

function evcd_get_post_list_as_options( $query_args ) {

	$args = wp_parse_args( $query_args, array(
		'post_type'		=> 'evg_art_portfolio',
		'numberposts'	=> -1,
		'order'			=> 'ASC',
		'orderby'		=>	'title',
	) );

	$posts = get_posts( $args );

	$post_options = array();
	if ( $posts ) {
		foreach ( $posts as $post ) {
          $post_options[ $post->ID ] = $post->post_title;
		}
	}

	return $post_options;
}

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
						<li><a href="#tab-content-3">Misc Settings</a></li>
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
		) );

		$contact_info->add_field( array(
			'name' => __( 'Facebook Page URL', 'evcd_textdomain' ),
			'id'   => $theme_prefix . 'facebook',
			'type' => 'text',
		) );
		
		$contact_info->add_field( array(
			'name' => __( 'Twitter Handle', 'evcd_textdomain' ),
			'id'   => $theme_prefix . 'twitter',
			'type' => 'text',
		) );
		
		$contact_info->add_field( array(
			'name' => __( 'Instagram Handle', 'evcd_textdomain' ),
			'id'   => $theme_prefix . 'instagram',
			'type' => 'text',
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
			'name' => esc_html__( 'Header Background Image', 'evcd_textdomain' ),
			'id'   => $theme_prefix . 'style_options_header_image',
			'type' => 'file',
			'options' => array(
				'url' => false, // Hide the text input for the url
			),
			'desc'    => esc_html__( '&#8776;1400 x 420px; Will be used unless overridden at page level.', 'evcd_textdomain' ),
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
		) );

		$misc_options->add_field( array(
			'name' => __( 'Gallery Item Purchase Info', 'evcd_textdomain' ),
			'id'   => $theme_prefix . 'purchase_text',
			'type'    => 'wysiwyg',
			'options' => array( 'textarea_rows' => 10,'media_buttons' => false, ),
			'after_row'    => '
					</div><!-- /.tab-content -->
				</div><!-- /.cmb2-tabs -->
			',
		) );
	}

	/**
	 * Register settings notices for display
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
				$output .= "<" . $html_elmt . " class=\"email\"><a href=\"mailto:" . $encodedEmail . "\">" . $encodedEmail."</a></" . $html_elmt . ">" . $space;
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

function posts_page_title($linked = false) {
	$id = get_option('page_for_posts', true);; 
	$post = get_post($id); 
	if ($linked) {
		$post_page_str = '<a href="' . get_the_permalink($post) . '">' . get_the_title($id) . '</a>';
	} else {
		$post_page_str =  get_the_title($id);
	}
	return($post_page_str);
}

function ucwords_title($string, $is_name = false) {
	// Exceptions to standard case conversion
	if ($is_name) {
		$all_uppercase = '';
		$all_lowercase = 'De La|De Las|Der|Van De|Van Der|Vit De|Von|Or|And';
	} else {
		// Addresses, essay titles ... and anything else
		$all_uppercase = 'Us|Ca|Mx|Po|Rr|Se|Sw|Ne|Nw';
		$all_lowercase = 'A|And|As|By|In|Of|Or|To';
	}
	$prefixes = 'Mac|Mc';
	$suffixes = "'S";

	// Trim whitespace and convert to lowercase
	$str = strtolower(trim($string));

	// Capitalize all first letters
	$str = preg_replace_callback('/\\b(\\w)/', function ($m) {
		return strtoupper($m[1]);
	}, $str);

	if ($all_uppercase) {
		// Capitalize acronyms and initialisms (e.g. PHP)
		$str = preg_replace_callback('/\\b(' . $all_uppercase . ')\\b/', function ($m) {
			return strtoupper($m[1]);
		}, $str);
	}
	if ($all_lowercase) {
		// Decapitalize short words (e.g. and)
		if ($is_name) {
			// All occurrences will be changed to lowercase
			$str = preg_replace_callback('/\\b(' . $all_lowercase . ')\\b/', function ($m) {
				return strtolower($m[1]);
			}, $str);
		} else {
			// First and last word will not be changed to lower case (i.e. titles)
			$str = preg_replace_callback('/(?<=\\W)(' . $all_lowercase . ')(?=\\W)/', function ($m) {
				return strtolower($m[1]);
			}, $str);
		}
	}
	if ($prefixes) {
		// Capitalize letter after certain name prefixes (e.g 'Mc')
		$str = preg_replace_callback('/\\b(' . $prefixes . ')(\\w)/', function ($m) {
			return $m[1] . strtoupper($m[2]);
		}, $str);
	}
	if ($suffixes) {
		// Decapitalize certain word suffixes (e.g. 's)
		$str = preg_replace_callback('/(\\w)(' . $suffixes . ')\\b/', function ($m) {
			return $m[1] . strtolower($m[2]);
		}, $str);
	}
	return $str;
}

function normalize_absolute_urls($link = '', $baseURL = '') {
	$link = trim($link);
	$prepped_link = '';
	if (strpos($link, $baseURL) !== false){
		$link = explode($baseURL, $link);
		$prepped_link = $link[1];
	} else {
		$prepped_link = $link;
	}
	return 'https://www.' . $baseURL . urlencode($prepped_link);
}

function social_media_links() {
	global $theme_prefix;
	$fb_handle = evcd_get_option($theme_prefix . 'facebook');
	$tw_handle = evcd_get_option($theme_prefix . 'twitter');
	$ig_link = evcd_get_option($theme_prefix . 'instagram');
	if ($fb_handle || $tw_handle || $ig_link){
		$output = '<ul class="social-icons">';
		if ($fb_handle) {
			$clean_link = normalize_absolute_urls($fb_handle, 'facebook.com/'); //($link = '', $baseURL = '')
			$output .= '<li><a href="' . $clean_link . '" class="facebook" target="_blank" title="Visit my Facebook page"><span class="visually-hidden">Visit us on Facebook</span></a></li>';
		}
		if ($ig_link) {
			$clean_link = normalize_absolute_urls($ig_link, 'instagram.com/'); //($link = '', $baseURL = '')
			$output .= '<li><a href="' . $clean_link . '" class="instagram" target="_blank" title="See my Instagram feed"><span class="visually-hidden">Visit Us on Instagram</span></a></li>';
		}
		if ($tw_handle) {
			if (!empty($tw_handle)){
				if (strpos($tw_handle, '@') !== false) {		 // if string has a @...
					$tw_handle_array = explode('@', $tw_handle); // chop at @
					$tw_handle = end($tw_handle_array);			 // take left side string and make it the var
				}
			}
			$clean_link = normalize_absolute_urls($tw_handle, 'twitter.com/'); //($link = '', $baseURL = '')
			$output .= '<li><a href="' . $clean_link . '" class="twitter" target="_blank" title="See my Twitter posts"><span class="visually-hidden">Visit Us on Twitter</span></a></li>';
		}
		$output .= '</ul>';
		echo $output;
	}
}

function get_featured_image_id($post_holder_id='') {
	global $theme_prefix;
	global $evdp_prefix;
	global $wp_query;
	if ($post_holder_id != '') {
		$pid = $post_holder_id;
	} else {
		$pid = get_the_ID();
	}
	if ( has_post_thumbnail($pid) ) {
		$feat_img_id = get_post_thumbnail_id($pid);							
	} elseif ( 'evg_art_portfolio' == get_post_type($pid) ) {
		$port_photos = get_post_meta($pid, $evdp_prefix . 'file_list', true );
		if ($port_photos) {
			reset($port_photos);  // get first item in array
			$feat_img_id = key($port_photos); // get first item's key (which is where CMB2 is holding image ID
		}
	} elseif (!empty(evcd_get_option($theme_prefix . 'style_options_logo_id'))) {
		$feat_img_id = evcd_get_option($theme_prefix . 'style_options_logo_id');
	}
	return $feat_img_id;
}

function get_page_summary($post_holder_id='', $word_count='20', $show_more=false) {
	global $theme_prefix;
	global $evdp_prefix;
	global $post;
	if ($post_holder_id != '') {
		$pid = $post_holder_id;
	} else {
		$pid = get_the_ID($post);
	}
	if ( 'evg_art_portfolio' == get_post_type() ) {
		$gallery_name = 'Gallery of Handmade Pottery';
		if (is_tax('art_category')) {
			$page_summary = $gallery_name . ': ' . single_tag_title('', false);
		} elseif (is_tag()) {
			$page_summary = $gallery_name . ': ' . ucwords(single_tag_title('', false));
		} elseif (is_post_type_archive()) {
			$page_summary = $gallery_name;
		} elseif (is_single() && (!empty(get_post_meta($pid, $evdp_prefix . 'desc', true)))){
			$page_summary = get_post_meta($pid, $evdp_prefix . 'desc', true);
			$page_summary = strip_tags($page_summary);
			$page_summary = strip_shortcodes( $page_summary );
			$page_summary = apply_filters('the_content', $page_summary);
		} else {
			$page_summary = get_the_title() . ' | ' . $gallery_name;
		}
	} elseif ( 'post' == get_post_type() ) {
		$page_summary = get_the_excerpt($pid);
	} elseif ( 'page' == get_post_type() && (!empty(get_the_content($pid))) ) {
		$page_summary = get_the_content($pid);
		$page_summary = strip_tags($page_summary);
		$page_summary = strip_shortcodes( $page_summary );
		$page_summary = apply_filters('the_content', $page_summary);
	} elseif (!empty(get_post_meta($pid, $theme_prefix . 'seo-page-description', true))) {
		$page_summary = evcd_get_option($theme_prefix . 'seo_description');
	} elseif (!empty(get_bloginfo( 'description'))) {
		$page_summary = get_bloginfo( 'description' );
	} else {
		$page_summary = '';
	}
	$page_summary = wp_trim_words( $page_summary, $word_count );
	if ($show_more) {
		$item_status = get_post_meta($pid, $evdp_prefix . 'status', true);
		$item_price = get_post_meta($pid, $evdp_prefix . 'price', true);
		if ($item_status == 'available' && !empty($item_price)) {
			$page_summary .= ' ($' . $item_price . ')';
		}
	}
	return $page_summary;
}

function share_this( $atts ) {
	$atts = shortcode_atts( 
		array(
			'shared_page_id' => '',
			'shared_page_title' => ''
		), 
		$atts
	);
	global $theme_prefix;
	global $evdp_prefix;
	global $wp_query;
	$shared_page_id = $atts['shared_page_id'];
	$shared_page_title = $atts['shared_page_title'];
	$shared_image = $shared_tags = '';
	if ($shared_page_id != '') {
		$post_id = $shared_page_id;
	} else {
		$post_id = get_the_ID();
	}
	if ($shared_page_title != ''){
		$page_title = $shared_page_title;
	} else {
		$page_title = get_the_title($post_id);
	}
	
	$nl = "\n";

	$shared_desc = get_page_summary($post_id, $word_count='20', true);
	$shared_link = get_permalink($post_id);
	$attachment_id = get_featured_image_id($post_id);
	
	if (!empty($attachment_id)) {
		$shared_image = urlencode(wp_get_attachment_image_url( $attachment_id, array('550', '450') ));
	} 
	
	$tumblr_caption = '<h1>' . get_bloginfo( 'name') . '</h1>';
	$tumblr_caption .= '<h2>';
	if ( 'post' == get_post_type() ) $tumblr_caption .= posts_page_title() . ' / ';
	$tumblr_caption .= $page_title;
	$tumblr_caption .= '</h2>';
	$tumblr_caption .= '<p>' . $shared_desc . '<br />' . '<a href="' . $shared_link .'">Read More at ' . $shared_link . '</a></p>';
	$tumblr_caption = urlencode($tumblr_caption);
	
	$output = '<div class="social-share">' . $nl;
	//$output .= '<div class="boxed">' . $nl;
	$output .= '<h5>Share This Page</h5>' . $nl;
	$output .= '<ul class="social-icons">' . $nl;
	$output .= '<li><a href="http://www.facebook.com/sharer.php?u=' . urlencode($shared_link) .'" class="facebook btnShare" target="_blank" title="Share this on Facebook" id="fb-share"><span class="visually-hidden">Share on Facebook</span></a></li>' . $nl;
	$output .= '<li><a href="https://twitter.com/intent/tweet?text=' .  urlencode($page_title)  . '&amp;url=' . urlencode($shared_link)  . '" class="twitter btnShare" target="_blank" title="Share this on Twitter" id="tw-share"><span class="visually-hidden">Share on Twitter</span></a></li>' . $nl;
	$output .= '<li><a href="https://www.tumblr.com/share/photo?source=' . $shared_image . '&amp;click_thru' . urlencode($shared_link)  . '&amp;caption=' . $tumblr_caption . '&amp;tags=' .  $shared_tags. '" class="tumblr btnShare" target="_blank" title="Share on Tumblr" id="tblr-share"><span class="visually-hidden">Share on Tumblr</span></a></li>' . $nl;
	$output .= '<li><a href="https://www.pinterest.com/pin/create/button/?description=' . $shared_desc . '&media=' . $shared_image . '&url=' . urlencode($shared_link) . '" class="pinterest btnShare" id="pt-share" title="Share this on Pinterest"><span class="visually-hidden">Share this on Pinterest</span></a></li>' . $nl;
	$output .= '<li><a href="mailto:?subject=I wanted to share this pottery&amp;body=' . $page_title . '%0D%0A' . urlencode($shared_link) . '" class="email" target="_blank" title="Email this page to a friend" id="eml-share"><span class="visually-hidden">Email to friend</span></a></li>' . $nl;
	$output .= '</ul>' . $nl;
	//$output .= '</div>' . $nl;
	$output .= '</div>' . $nl;
	wp_reset_postdata();
	return $output;
}
add_shortcode( 'sharethis', 'share_this' );

function bootstrap_page_link_css( $html ){
	$html = str_replace( '<a ', '<a class="page-link" ', $html );
	return $html;
}
add_filter( 'next_post_link', 'bootstrap_page_link_css' );
add_filter( 'previous_post_link', 'bootstrap_page_link_css' );

function evg_customize_comments_fields( $fields ) {
	unset( $fields['url'], $fields['cookies'] );
	return $fields;
}
 
add_filter( 'comment_form_default_fields', 'evg_customize_comments_fields' );
	
