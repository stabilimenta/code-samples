<?php
/**
/* Template Name: WGWC Employees Single
*/

	global $evep_prefix;


	if ( have_posts() ) {
		$post_id = get_the_id();
		$post_name = $post->post_name;
		$this_cpt = get_post_type();
		$postType = get_post_type_object($this_cpt);
		$primary_cat = '';
		// if ($term_id == '') {
		$categories = get_the_terms($post_id, 'employees_category');
		if ( ! empty( $categories ) ) {
			// first try getting first category name as fallback
			$primary_cat = esc_html( $categories[0]->name ); 
			// secondly, try getting meta singular name of first category as something better for a fallback name
			$primary_cat_id = $categories[0]->term_id;
			$first_cat_singular = '';
			$first_cat_singular = get_term_meta( $primary_cat_id, $evep_prefix . 'cat_singular_name', true );
			if ($first_cat_singular !== '') {
				$primary_cat = get_term_meta( $primary_cat_id, $evep_prefix . 'cat_singular_name', true ); 
			}
		}
		$emp_name = get_the_title();
		$main_photo_id = get_post_meta( $post_id, $evep_prefix . 'main-image_id', true );
		$dir_phone = get_post_meta( $post_id, $evep_prefix . 'direct-phone', true );
		$off_phone = get_post_meta( $post_id, $evep_prefix . 'phone', true );
		$fax = get_post_meta( $post_id, $evep_prefix . 'fax', true );
		$emp_email = get_post_meta( $post_id, $evep_prefix . 'email', true );
		$emp_bio = get_post_meta( $post_id, $evep_prefix . 'bio', true );

		if( isset($_GET['vcard']) ) {
			header('Content-type: text/x-vcard; charset=utf-8');
			header('Content-Disposition: attachment; filename="' . $custom["EmpName"][0] . ' vcard.vcf"');
			echo "BEGIN:VCARD\n";
			echo "VERSION:3.0\n";
			if ($emp_name) {
				echo "N:" . $emp_name . "\n";
				echo "FN:" . $emp_name . "\n";
			}
			echo "ORG:" . get_bloginfo() . "\n";
			if ( !empty($primary_cat) ) {
				echo "TITLE:" . $primary_cat . "\n";
			} else {
				$emp_position = get_post_meta( $post_id, $evep_prefix . 'position', true );
				if ( !empty($emp_position) ) {
					echo "TITLE:" . $emp_position . "\n";
				}
			}
			if( $main_photo_id ) {
				echo "PHOTO:VALUE=URL;TYPE=JPEG:" . wp_get_attachment_url( $main_photo_id ) . "\n";
			}
			$retina_logo_id = evcd_get_option($theme_prefix . 'style_options_logo2x');
			$logo_id = evcd_get_option($theme_prefix . 'style_options_logo');
			if ( ! empty( $retina_logo_id )) {
				echo "LOGO;VALUE=URL;TYPE=PNG:" . wp_get_attachment_url( $retina_logo_id ) . "\n";
			} else if ( ! empty( $logo_id )){
				echo "LOGO;VALUE=URL;TYPE=PNG:" . wp_get_attachment_url( $logo_id ) . "\n";
			}
			if ( ! empty( $off_phone )) {
				echo "TEL;type=MAIN;type=pref:" . $off_phone . "\n";
			}	
			if ( ! empty( $dir_phone )) {
				echo "item1.TEL:" . $dir_phone . "\n";
				echo "item1.X-ABLabel:direct" . "\n";
			}	
			if ( ! empty( $fax )) {
				echo "TEL;type=WORK;type=FAX:" . $fax . "\n";
			}	
			$adr_str = '';
			if (evcd_get_option($theme_prefix . 'address_1')) {
				$adr_str .= evcd_get_option($theme_prefix . 'address_1') . ";";
			}
			if (evcd_get_option($theme_prefix . 'address_2')) {
				$adr_str .= evcd_get_option($theme_prefix . 'address_2') . ";";
			}
			if (evcd_get_option($theme_prefix . 'address_3')) {
				$adr_str .= evcd_get_option($theme_prefix . 'address_3') . ";";
			}
			if (evcd_get_option($theme_prefix . 'city')) {
				$adr_str .= evcd_get_option($theme_prefix . 'city') . ";";
			}
			if (evcd_get_option($theme_prefix . 'state')) {
				$adr_str .= evcd_get_option($theme_prefix . 'state') . ";";
			}
			if (evcd_get_option($theme_prefix . 'zip')) {
				$adr_str .= evcd_get_option($theme_prefix . 'zip') . ";";
			}
			if ($adr_str !== '') {
				$adr_str .= "United States of America" . "\n";
				echo "ADR;TYPE=WORK:;;" . $adr_str;
			} 
			if ( ! empty( $emp_email )) {
				echo "EMAIL;PREF;INTERNET:" . $emp_email . "\n";
			}	
			echo "END:VCARD\n";
			exit();
		}

		get_header(); 

?>	
		<div class="page-width employees employees-single <?php echo $post_name; ?>">
			<div class="page-title">
				<h2>Attorneys</h2>
				<?php 
					if ( !empty($emp_name) ) {
						echo "\t\t\t\t<h1 class=\"emp-name\">" . $emp_name . "</h1>\n";
					}
				?>
			</div>
			<div class="row bio">
				<div class="col photo-col">
					<?php 
						if ($main_photo_id ) { 
							$caption = get_post($main_photo_id)->post_content;
							$img_alt = get_post_meta($main_photo_id, '_wp_attachment_image_alt', true);
							if ($img_alt == '') {
								$img_alt = get_the_title ($main_photo_id);
								$img_alt = ucwords(str_replace("-", " ", $img_alt));
							}
							$sizes = "(min-width: 1200px) 570px, (min-width: 576px) calc((100vw - 90px) * .5), (min-width: 150px) calc(100vw - 30px), 100vw";
							echo wp_get_attachment_image( $main_photo_id, 'headshot-lg', '', array( 'alt' => $img_alt, 'sizes' => $sizes, 'class' => 'main'  ));
						} // end if $main_photo_id
					?>			
					<?php 
						// Get the list of files
						// file_list returns array, key= ID; value= URL
						$emp_photos = get_post_meta( $post_id, $evep_prefix . "images", true );
						if ($emp_photos) {
							foreach ( (array) $emp_photos as $attachment_id => $attachment_url ) {
								$img = $img_id = $credit = $caption = $supersize_url = $thumb_url = $alt = '';

								if ($attachment_id ) { 
									$caption = get_post($attachment_id)->post_content;
									$img_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
									if ($img_alt == '') {
										$img_alt = get_the_title ($attachment_id);
										$img_alt = ucwords(str_replace("-", " ", $img_alt));
									}
									$sizes = "(min-width: 1200px) 285px, (min-width: 576px) calc((100vw - 90px) * .25), (min-width: 150px) 10px, 100vw";
									echo wp_get_attachment_image( $attachment_id, 'headshot-md', '', array( 'alt' => $img_alt, 'sizes' => $sizes, 'class' => 'grayscale low-brightness' ));
								} // end if $attachment_id
							} // end foreach
						} //end if $employee_photos
					 ?>			
				</div>
				<div class="col text-col">
					<?php 
						if ( !empty($primary_cat) ) {
							echo "\t\t\t\t<h3 class=\"emp-position\">" . esc_html($primary_cat) . "</h3>\n";
						} else {
							$emp_position = get_post_meta( $post_id, $evep_prefix . 'position', true );
							if ( !empty($emp_position) ) {
								echo "\t\t\t\t<h3 class=\"emp-position\">" . esc_html( $emp_position ) . "</h3>\n";
							}
						}
					?>
					<ul class="contact">
						<?php 
							if ( !empty($dir_phone) ) {
								$phone_link = preg_replace("/[^0-9]/", "", $dir_phone );
								if (substr($phone_link, 0, 1) !== '1') {
									$phone_link = '1' . $phone_link;
								}
								echo "\t\t\t\t<li>Direct:  <a href=\"tel:+" . $phone_link . "\">" . esc_html( $dir_phone ) . "</a></li>\n";
							}

							if ( !empty($off_phone) ) {
								$phone_link = preg_replace("/[^0-9]/", "", $off_phone );
								if (substr($phone_link, 0, 1) !== '1') {
									$phone_link = '1' . $phone_link;
								}
								echo "\t\t\t\t<li>Office:  <a href=\"tel:+" . $phone_link . "\">" . esc_html( $off_phone ) . "</a></li>\n";
							}

							if ( !empty($fax) ) {
								echo "\t\t\t\t<li>Fax: " . esc_html( $fax ) . "</li>\n";
							}

							if ( !empty($emp_email) ) {
								$encodedEmail = antispambot($emp_email);
								echo "\t\t\t\t<li><a href=\"mailto:" . $encodedEmail . "\">" . $encodedEmail . "</a></li>\n";
							}
						?>
						<li><a href="<?php echo get_site_url() . "?evg_employees=" . $post->post_name; ?>&amp;vcard=1" title="Download my contact info ready to import into any contact manager.">Download vCard</a></li>
					</ul>

					<?php 
						if ( ! empty($emp_bio) ) {
							echo "\t\t\t\t<div class=\"emp-bio\">" . apply_filters( 'the_content', $emp_bio ) . "</div>\n";
						}
					?>

					<?php 
						$accordion_items = get_post_meta( $post_id, $evep_prefix . 'accordion_group', true );
						if ( ! empty($accordion_items) ) { 
						?>
							<div class="accordion" id="accordion-bio">
						<?php
							$i=0;
							foreach ( (array) $accordion_items as $key => $item ) {
								$title = $desc = '';
								if ( isset( $item['accordion_item_label'] ) ) {
									$title = esc_html( $item['accordion_item_label'] );
								} else {
									break;
								}
								if ( isset( $item['accordion_item_text'] ) ) {
									$desc = wpautop( $item['accordion_item_text'] );
								}
								$i++;
								?>
								<div class="item">
									<div class="control" id="adn-ctr-<?php echo $i; ?>">
										<button type="button" data-toggle="collapse" data-target="#adn-content-<?php echo $i; ?>" aria-expanded="false" aria-controls="adn-content-<?php echo $i; ?>">
											<?php echo $title; ?>
										</button>
									</div>
									<div id="adn-content-<?php echo $i; ?>" class="collapse" aria-labelledby="adn-ctr-<?php echo $i; ?>" data-parent="#accordion-bio">
										<div class="inner">
											<?php echo $desc; ?>
										</div>
									</div>
								</div>
								<?php
							}
						?>
							</div>
						<?php
						} // end if
					?>

				</div>
			</div>
		</div>

	<?php
		// Reset Post Data
		wp_reset_postdata();
	?>
<?php } // end if have_posts ?>

<?php get_footer(); ?>