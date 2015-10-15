	<div id="comments">
	<?php if (post_password_required()): ?>
		<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'rockharbor' ); ?></p>
	</div>
	<?php
			return;
		endif;
		$comments = get_comments(array(
			'post_id' => get_the_ID(),
			'type__in' => array(
				'comment',
				'social-twitter-rt',
				'social-twitter',
				'social-facebook-like',
				'social-facebook'
			)
		));
	?>

	<?php if (count($comments) > 0) : ?>
		<h2 id="comments-title">
			<?php
				printf(_n('One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', count($comments), 'rockharbor'),
					number_format_i18n(count($comments)), '<span>' . get_the_title() . '</span>' );
			?>
		</h2>

		<div class="commentlist">
			<?php wp_list_comments(array('style' => 'div'), $comments); ?>
		</div>

		<?php if (get_comment_pages_count() > 1 && get_option( 'page_comments')):  ?>
		<nav id="comment-nav-above">
			<h1 class="assistive-text"><?php _e('Comment navigation', 'twentyeleven'); ?></h1>
			<div class="nav-previous"><?php previous_comments_link(__('&larr; Older Comments', 'twentyeleven')); ?></div>
			<div class="nav-next"><?php next_comments_link(__('Newer Comments &rarr;', 'twentyeleven')); ?></div>
		</nav>
		<?php endif;  ?>

	<?php
		elseif (!comments_open() && ! is_page() && post_type_supports(get_post_type(), 'comments')):
	?>
		<p class="nocomments"><?php _e( 'Comments are closed.', 'twentyeleven' ); ?></p>
	<?php endif; ?>

	<?php comment_form(array(
		'title_reply_to' => 'asdfa'
	)); ?>

	</div>
