<?php
/**
 * @subpackage WGWC
 */
	global $theme_prefix;
?>

		</main>
		<footer id="page-footer">
			<section class="page-width">
				<div>
					<?php echo do_shortcode('[contactinfo include="phone, fax, email"]'); ?>
				</div>
				<div>
					<?php echo do_shortcode('[contactinfo include="address"]'); ?>
				</div>
				<div>
					© <?php echo date('Y'); ?>
					<?php echo do_shortcode('[contactinfo include="business" style="inline"]'); ?><br>
					All Rights Reserved.<br>
					<a href="<?php echo site_url(); ?>/disclaimer">Disclaimer</a>
				</div>			
			</section>
		</footer>
		<a href="#results" class="down-arrow" aria-label="click to scroll to attorneys" title="click to scroll to attorneys" style="display:none;">Click to Scroll Down</a>

		<?php wp_footer(); ?>
	</body>
</html>