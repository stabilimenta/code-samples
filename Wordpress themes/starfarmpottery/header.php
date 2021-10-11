<?php
	/**
	* @package WordPress
	* @subpackage starfarm
	*/

	global $theme_prefix;
	$theme_path = get_bloginfo( 'stylesheet_directory' );
	// if ( is_home() ) :
	if (( get_post_type() == 'post' ) || (is_category() )) :
		$page_ID = get_option('page_for_posts', true);
	else :
		$page_ID = get_the_ID();
	endif;

	// First, try to see if page has description set...
	$seo_desc = get_post_meta( $page_id, $theme_prefix . 'seo-page-description', true );
	// If not, try theme settings SEO description...
	if ( empty( $seo_desc ) ) {
		$seo_desc = evcd_get_option($theme_prefix . 'seo_description');
	}
	// If still nothing, try blog description, aka "tagline"...
	if ( empty( $seo_desc ) ) {
		$seo_desc = get_bloginfo( 'description' );
	}

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php
		if ( !empty($seo_desc) ) {
			echo "<meta name=\"description\" content=\"" . esc_html($seo_desc) . "\">\n";
		}
	?>
	<meta name="format-detection" content="telephone=no">
	<link rel="preconnect" href="https://fonts.googleapis.com"> 
	<link rel="preconnect" href="https://cdn.jsdelivr.net"> 
	
<!-- 
	<style>
		.page-wrap { visibility: hidden; } /* prevent flash of unstyled content (FOUC) */
		/* this is when menus have to collapse, and we want the mobile menus to be hidden if javascript is working and our jquery will show them */
		@media only screen and (max-width : 950px) { 
			.js #access ul.menu { display: none; }
		}
	</style>	
 -->
	<?php wp_head(); ?>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo $theme_path; ?>/style.css?<?php echo rand(); ?>">
	<link rel="apple-touch-icon-precomposed" sizes="57x57" href="/apple-touch-icon-57x57.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/apple-touch-icon-114x114.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="/apple-touch-icon-144x144.png">
	<link rel="apple-touch-icon-precomposed" sizes="60x60" href="/apple-touch-icon-60x60.png">
	<link rel="apple-touch-icon-precomposed" sizes="120x120" href="/apple-touch-icon-120x120.png">
	<link rel="apple-touch-icon-precomposed" sizes="76x76" href="/apple-touch-icon-76x76.png">
	<link rel="apple-touch-icon-precomposed" sizes="152x152" href="/apple-touch-icon-152x152.png">
	<link rel="icon" type="image/png" href="/favicon-196x196.png" sizes="196x196">
	<link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">
	<link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
	<link rel="icon" type="image/png" href="/favicon-128.png" sizes="128x128">
	<meta name="application-name" content="Star Farm Pottery">
	<meta name="msapplication-TileColor" content="#FFFFFF">
	<meta name="msapplication-TileImage" content="/mstile-144x144.png">
	<meta name="msapplication-square70x70logo" content="/mstile-70x70.png">
	<meta name="msapplication-square150x150logo" content="/mstile-150x150.png">
	<meta name="msapplication-wide310x150logo" content="/mstile-310x150.png">
	<meta name="msapplication-square310x310logo" content="/mstile-310x310.png">
	<meta name="theme-color" content="#ffffff">
	
	<meta property="og:type" content="article">
	<meta name="twitter:card" content="summary">
	<?php 
		if (is_home()){
			$page_id = get_option('page_for_posts', true);
		} else {
			$page_id = get_the_id();
		}
		$tw_handle = evcd_get_option($theme_prefix . 'twitter');
		if (!empty($tw_handle)){
			if (strpos($tw_handle, '@') !== false) {		 // if string has a @...
				$tw_handle_array = explode('@', $tw_handle); // chop at @
				$tw_handle = end($tw_handle_array);			 // take left side string and make it the var
			} elseif (strpos($tw_handle, '/') !== false) {		 // if string has a /...
				$tw_handle_array = explode('/', $tw_handle); // chop at LAST / 
				$tw_handle = end($tw_handle_array);			 // take left side string and make it the var
			}
			$tw_handle = '@' . $tw_handle; 					// re-add @	
			echo '<meta name="twitter:site" content="' . $tw_handle . '">' . "\n";
		}
	?>
	<?php
		$meta_img_id = get_featured_image_id($page_id);
		if (!empty($meta_img_id)) {
			$meta_image = wp_get_attachment_image_url( $meta_img_id, array('550', '450') );
			// echo '<meta name="twitter:image" content="' . $meta_image . '">' . "\n";
			echo '<meta property="og:image" content="' . $meta_image . '">' . "\n";
			echo '<meta name="twitter:image:alt" content="' . get_bloginfo('description') .'">' . "\n";
		} 
	?>
	<?php 
		$meta_summary = get_page_summary($page_id, '100'); //100 words max
		$meta_summary = substr($meta_summary, 0, 200); // trim string to 200 characters for facebook and twitter
		if(!empty($meta_summary)){
			// echo '<meta name="twitter:description" content="' . $meta_summary . '">' . "\n";
			echo '<meta property="og:description" content="' . $meta_summary . '">' . "\n";
		}
	?>	
	<meta name="twitter:title" content="<?php echo get_bloginfo('name') . ' : ' . get_the_title($page_id); ?>">
	<meta property="og:title" content="<?php echo get_bloginfo('name') . ' : ' . get_the_title($page_id); ?>">
	<meta property="og:url" content="<?php the_permalink($page_id); ?>">
	
	<?php
		$tracking_code = evcd_get_option($theme_prefix . 'ga_tracking_id');
		if (isset($tracking_code)){
			$tracking_code = esc_html($tracking_code);
			echo 
				"<script async src=\"https://www.googletagmanager.com/gtag/js?id=" . $tracking_code . "\"></script>
				<script>
				  window.dataLayer = window.dataLayer || [];
				  function gtag(){dataLayer.push(arguments);}
				  gtag('js', new Date());

				  gtag('config', '" . $tracking_code . "');
				</script>\n"
			;
		}
	?>
</head>
<?php 
	if ( is_archive() ) {
		if ( is_tax() || is_tag() ) {
			$body_id = get_queried_object()->slug . '-archive-page';
		} else {
			$body_id = get_post_type() . '-archive-page';
		}
	} elseif ( is_page() || is_single() ) {
		$body_id = $post->post_name . '-page';
	} else {
		$body_id = 'something-else-page';
	}
?>
<body <?php body_class("js-no"); ?> id="<?php echo $body_id; ?>">
	<header id="org-header">
		<div class="inner">
			<div class="page-width">
				<a class="speech" href="#page-content">Skip to content</a>

				<div class="branding" id="hdr-branding">

					<?php 
						$theme_logo = $theme_logo_id = $retina_logo = '';
						$theme_logo = evcd_get_option($theme_prefix . 'style_options_logo');
						if ( ! empty( $theme_logo ) ) {
							$theme_logo_id = evcd_get_option($theme_prefix . 'style_options_logo_id');
							$retina_logo = evcd_get_option($theme_prefix . 'style_options_logo2x');
							if ( ! empty( $retina_logo ) ) {
								$retina_logo = ", " . $retina_logo . " 2x";
							}
							// create attributes array to feed into image
							$logo_attr = array ("srcset" => $theme_logo . $retina_logo);
					
							// check if alt tag is filled in, and make one otherwise
							if ( empty(get_post_meta($theme_logo_id, '_wp_attachment_image_alt', true))) {
								$logo_attr["alt"] = get_bloginfo( 'name' ); 
							}
							?>
							<h2 class="branding">
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="return to homepage" rel="home"><?php echo wp_get_attachment_image( $theme_logo_id, "win_width", "", $logo_attr ); ?></a>
							</h2>
						<?php } 
						else { ?>
							<div class="inner">
								<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
								<h2 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'description' ); ?></a></h2>
							</div>
						<?php } ?>
				</div>

			
				<nav id="main-nav" aria-label="Main Menu">
					<button class="menu-control" id="main-nav-trigger" type="button" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
						<span class="lines"></span>
						<span class="visually-hidden">Open Main Menu</span>
					</button>
					<?php 
						wp_nav_menu( array( 'theme_location' => 'top-menu', 'menu_class' => 'closed menu', 'menu_id' => 'main-menu', 'container'  => false ) ); 
					?>
				</nav>
			</div>
		</div>
	</header>
	<main id="page-content">
