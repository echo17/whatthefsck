<?php // Do not delete these lines
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

        if (post_password_required()) { 
	?>
			
		<p class="center"><?php _e("This post is password protected. Enter the password to view comments."); ?><p>
				
<?php	return; } 


	/* Function for seperating comments from track- and pingbacks. */
	function k2_comment_type_detection($commenttxt = 'Comment', $trackbacktxt = 'Trackback', $pingbacktxt = 'Pingback') {
		global $comment;
		if (preg_match('|trackback|', $comment->comment_type))
			return $trackbacktxt;
		elseif (preg_match('|pingback|', $comment->comment_type))
			return $pingbacktxt;
		else
			return $commenttxt;
	}

function unsleepable_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	global $count_pings;
	extract($args, EXTR_SKIP);
?>
<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<div id="div-comment-<?php comment_ID() ?>">
	<div class="comment-author vcard">
		<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
		<a href="#comment-<?php comment_ID() ?>" class="counter" title="Permanent Link to this Comment"><?php echo $count_pings; $count_pings++; ?></a>
		<span class="commentauthor fn" style="font-weight: bold;"><?php comment_author_link() ?></span><small class="commentmetadata"> on <a href="#comment-<?php comment_ID() ?>" title="<?php if (function_exists('time_since')) { $comment_datetime = strtotime($comment->comment_date); echo time_since($comment_datetime) ?> ago<?php } else { ?>Permalink to Comment<?php } ?>"><?php comment_date() ?></a> said:</small>
		<?php if ( $user_ID ) { edit_comment_link('<img src="'.get_bloginfo(template_directory).'/images/pencil.png" alt="Edit Link" />','<span class="commentseditlink">','</span>'); } ?>
	</div>

	<div class="itemtext2">
		<?php comment_text() ?> 
	</div>

	<?php if ($comment->comment_approved == '0') : ?>
	<p class="alert"><strong>Your comment is awaiting moderation.</strong></p>
	<?php endif; ?>

	<div class="reply">
		<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	</div>
<?php
}

function unsleepable_ping($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	global $count_pings;
	extract($args, EXTR_SKIP);
?>
<li <?php comment_class(empty( $args['has_children'] ) ? 'item' : 'item parent') ?> id="comment-<?php comment_ID() ?>"> 
	<a href="#comment-<?php comment_ID() ?>" title="Permanent Link to this Comment" class="counter"><?php echo $count_pings; $count_pings++; ?></a>
	<span class="commentauthor"><?php comment_author_link() ?></span>
</li>
<?php
}

if ((have_comments()) or (comments_open())) { ?>

<hr />

<div class="comments" id="comments">

	<h4><a href="#comments"><?php comments_number('No Responses Yet', 'One Response', '% Responses' );?> to &#8220;<?php the_title(); ?>&#8221; &nbsp;</a></h4>

	<div class="metalinks">
		<span class="commentsrsslink"><?php post_comments_feed_link( 'Feed for this Entry' ); ?></span>
		<?php if (pings_open()) { ?><span class="trackbacklink"><a href="<?php trackback_url() ?>" title="Copy this URI to trackback this entry.">Trackback Address</a></span><?php } ?>
	</div>
	
	<ol class="commentlist" id='commentlist'>
	<?php if (have_comments()) { 
		global $count_pings;
		$count_pings = 1;
		wp_list_comments(array('callback'=>'unsleepable_comment', 'avatar_size'=>48, 'type'=>'comment')); 
		?>
		</ol>

		<div class="navigation">
			<div class="alignleft"><?php previous_comments_link() ?></div>
			<div class="alignright"><?php next_comments_link() ?></div>
		</div>
		<br />

		<ol class="pinglist">
		<?php 
		$count_pings = 1; 
		wp_list_comments(array('callback'=>'unsleepable_ping', 'type'=>'pings')); 
		?>
		</ol>
		
	<?php } else { // this is displayed if there are no comments so far ?>

		<?php if (comments_open()) { ?> 
			<!-- If comments are open, but there are no comments. -->
				<li id="leavecomment">Leave a Comment</li>

		<?php } else { // comments are closed ?>

			<!-- If comments are closed. -->

			<?php if (is_single()) { // To hide comments entirely on Pages without comments ?>
				<li>Comments are currently closed.</li>
			<?php } ?>
	
		<?php } ?>

		</ol>

	<?php } ?>

	<?php if (have_comments()) { ?>
		<?php include (TEMPLATEPATH . '/navigation.php'); ?>
	<?php } ?>


	<?php comment_form(); ?>

</div> <!-- Close .comments container -->
<?php } ?>
