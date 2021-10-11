<?php
	/**
	* @package WordPress
	* @subpackage WGWC
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
	$seo_desc = get_post_meta( get_the_ID(), $theme_prefix . 'seo-page-description', true );
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
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<?php
		if ( !empty($seo_desc) ) {
			echo "<meta name=\"description\" content=\"" . esc_html($seo_desc) . "\">\n";
		}
	?>
	<meta name="format-detection" content="telephone=no">
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
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans:600|Montserrat:400,400i,700" rel="stylesheet">
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo $theme_path; ?>/style.css?v9" />
	<!--[if lt IE 9]>
		<script src="<?php echo $theme_path; ?>/js/html5.js" type="text/javascript"></script>
		<link href="<?php echo $theme_path; ?>/lt_ie9.css?reload123" rel="stylesheet" type="text/css" />
	<![endif]-->
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png?v=m2LqbjR6L3">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?v=m2LqbjR6L3">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png?v=m2LqbjR6L3">
	<link rel="manifest" href="/site.webmanifest?v=m2LqbjR6L3">
	<link rel="mask-icon" href="/safari-pinned-tab.svg?v=m2LqbjR6L3" color="#5bbad5">
	<link rel="shortcut icon" href="/favicon.ico?v=m2LqbjR6L3">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="theme-color" content="#ffffff">
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
							<h2 class="branding" aria-label="Wadsworth Garber Warner Conrardy, P.C.: Colorado's Bankruptcy Firm">
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
					<input type="checkbox" id="nav-trigger" class="trigger">
					<label for="nav-trigger" title="Open/close menu" aria-label="Click to open or close main menu"><span></span></label>
					<?php 
						wp_nav_menu( array( 'theme_location' => 'top-menu', 'menu_class' => 'menu', 'menu_id' => 'page-menu', 'container'  => false ) ); 
					?>
				</nav>
			</div>
		</div>
	</header>
	<main id="page-content">
