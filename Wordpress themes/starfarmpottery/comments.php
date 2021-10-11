<?php
/**
 * The template for displaying comments
/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password,
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}

$evcd__comment_count = get_comments_number();
?>

<div id="comments" class="comments boxed mt-6">

	<?php
	if ( have_comments() ) :
		;
		?>
		<h2 class="comments-title">
			<?php if ( '1' === $evcd__comment_count ) : ?>
				1 Comment
			<?php else : ?>
				<?php echo $evcd__comment_count; ?> Comments
			<?php endif; ?>
		</h2><!-- .comments-title -->

		<ol class="comment-list">
			<?php
				wp_list_comments(
					array(
						'style'       => 'ol',
					)
				);
			?>
		</ol><!-- .comment-list -->

		<?php
		the_comments_pagination(
			array(
				'before_page_number' => 'Page ',
				'mid_size'           => 0,
				'prev_text'          => sprintf(
					'%s <span class="nav-prev-text">%s</span>',
					'<span aria-hidden="true">&laquo; </span>',
					'Older comments'
				),
				'next_text'          => sprintf(
					'<span class="nav-next-text">%s</span> %s',
					'Newer comments',
					'<span aria-hidden="true"> &raquo;</span>'
				),
			)
		);
		?>

		<?php if ( ! comments_open() ) : ?>
			<p class="no-comments">Comments are closed.</p>
		<?php endif; ?>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'logged_in_as'       => null,
			'title_reply'        => 'Leave a Comment',
			'title_reply_before' => '<h5 id="reply-title" class="comment-reply-title"><a class="collapse-control" data-bs-toggle="collapse" href="#commentform" role="button" aria-expanded="false" aria-controls="commentform" title="Toggle comment form open/closed">',
			'title_reply_after'  => '</a></h5>',
			'class_form' => 'comment-form collapse',
			'comment_notes_before' => '<p class="comment-notes"><span id="email-notes">Your email address will not be published.</span> <span class="req-field-note">Required fields are marked <span class="required">*</span></span></p>'
		)
	);
	?>

</div><!-- #comments -->
