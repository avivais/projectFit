<?php
/*
The comments page for Site5 Framework
*/

// Do not delete these lines
  if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
    die ('Please do not load this page directly. Thanks!');

  if ( post_password_required() ) { ?>
  	<div class="help">
    	<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.','site5framework'); ?></p>
  	</div>
  <?php
    return;
  }
?>

<!-- You can start editing here. -->

<?php if ( have_comments() ) : ?>
	
	<h3 id="comments"><?php comments_number('<span>No</span> Comments', '<span>One</span> Comment', '<span>%</span> Comments' );?></h3>

	<nav id="comment-nav">
		<ul>
	  		<li><?php previous_comments_link() ?></li>
	  		<li><?php next_comments_link() ?></li>
	 	</ul>
	</nav>
	
	<ol class="commentlist">
		<?php wp_list_comments('type=comment&callback=site5framework_comments'); ?>
	</ol>
	
	<nav id="comment-nav">
		<ul>
	  		<li><?php previous_comments_link() ?></li>
	  		<li><?php next_comments_link() ?></li>
		</ul>
	</nav>
  
	<?php else : // this is displayed if there are no comments so far ?>

	<?php if ( comments_open() ) : ?>
    	<!-- If comments are open, but there are no comments. -->

	<?php else : // comments are closed ?>
	<!-- If comments are closed. -->
	<p class="nocomments"><?php _e('Comments are closed.','site5framework'); ?></p>

	<?php endif; ?>

<?php endif; ?>


<?php if ( comments_open() ) : ?>

<section id="respond" class="respond-form">

	<h3 id="comment-form-title" class="h2"><?php _e( 'So, what do you think ?', 'site5framework' ); ?></h3>
	
	<div id="cancel-comment-reply">
		<p class="small"><?php cancel_comment_reply_link(); ?></p>
	</div>

	<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
  	<div class="help">
  		<p><?php _e("You must be  ", "site5framework"); ?> <a href="<?php echo wp_login_url( get_permalink() ); ?>"><?php _e("logged in ", "site5framework"); ?></a> <?php _e("to post a comment. ", "site5framework"); ?></p>
  	</div>
	<?php else : ?>

	<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
	
	<div id="comment-form-elements">

	<?php if ( is_user_logged_in() ) : ?>

	<div class="comments-logged-in-as"><?php _e("Logged in as  ", "site5framework"); ?><a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account"><?php _e("Log out &raquo; ", "site5framework"); ?></a></li>
	
	
	<?php else : ?>
	
		<div class="one-fourth">
		  <label for="author"><?php _e("Your Name ", "site5framework"); ?></label>
		  <input class="text" type="text" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
		</div>
		
		<div class="one-fourth">
		  <label for="email"><?php _e("Your Mail ", "site5framework"); ?></label>
		  <input class="text" type="email" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
		</div>
		
		<div class="one-fourth last">
		  <label for="url"><?php _e("Your Website ", "site5framework"); ?></label>
		  <input class="text" type="url" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" tabindex="3" />
		</div>
	
	<?php endif; ?>
	
		<div class=""> 
			<label for="comment"><?php _e("Message ", "site5framework"); ?></label> 
			<textarea name="comment" id="comment" tabindex="4"></textarea>
		</li>
	
	
	  <input name="submit" type="submit" id="submitcomment" class="button small steel_blue round" tabindex="5" value="<?php _e("POST COMMENT", "site5framework"); ?>" />
	  <?php comment_id_fields(); ?>
	
	<?php do_action('comment_form', $post->ID); ?>
	</div>
	</form>
	
	<?php endif; // If registration required and not logged in ?>
</section>

<?php endif; // if you delete this the sky will fall on your head ?>
