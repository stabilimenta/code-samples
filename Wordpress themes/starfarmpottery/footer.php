<?php
/**
 * @subpackage starfarm
 */
	global $theme_prefix;
?>
		</main>
		<footer id="page-footer">
			<div class="page-width">
				<div>
					<?php social_media_links(); ?>
					<div class="copyright">
						© <?php echo date('Y') . do_shortcode('[contactinfo include="business" style="inline"]'); ?>
					</div>
				</div>
				<div class="credit">
					Website by <a href="https://evolutionarygraphics.com">Evolutionary Graphics</a>
				</div>
			</div>
		</footer>

		<?php wp_footer(); ?>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
	</body>
</html>