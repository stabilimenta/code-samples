<?php 
	if ( is_404() ) {
		$attachment_id = evcd_get_option($theme_prefix . '404_image_id');
	} elseif ( has_post_thumbnail() ) {
		$attachment_id = get_post_thumbnail_id();							
	}
	if ( ! empty( $attachment_id ) ) {
		$photo_credit = get_post_meta($attachment_id, 'pp_photo_credit', true);
		echo "<div class=\"featured-photo\">\n";
		echo wp_get_attachment_image( $attachment_id, array('550', '450') );
		if (!empty($photo_credit))
			echo "\n<div class=\"credit\">Photo by " . esc_html( $photo_credit ) . "</div>\n";
		echo "</div><!-- /.featured-photo -->\n";
	}
?>
