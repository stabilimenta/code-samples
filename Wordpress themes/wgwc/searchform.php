<?php
/**
 *
 * @package WordPress
 * @subpackage WGWC
 */
?>

<div class="search_box">
	<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" role="search">
		<div class="row joined">
			<label class="speech" for="search-term">Enter search terms</label>
			<input id="search-term" type="search" name="s" class="search-term" placeholder="Enter search terms" value="" />
			<input type="submit" class="submit-btn" name="submit" value="Search" />
		</div>
	</form>
</div>
