<?php
/*
 * Template Name: Contact Form Page
*/
if(isset($_POST['submitted'])) {
		//Check to make sure that the name field is not empty
		if(trim($_POST['contactName']) === '') {
			$nameError = __("You forgot to enter your name.", "site5framework");
			$hasError = true;
		} else {
			$name = trim($_POST['contactName']);
		}

		//Check to make sure sure that a valid email address is submitted
		if(trim($_POST['email']) === '')  {
			$emailError = __("You forgot to enter your email address.", "site5framework");
			$hasError = true;
		} else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['email']))) {
			$emailError = __("You entered an invalid email address.", "site5framework");
			$hasError = true;
		} else {
			$email = trim($_POST['email']);
		}

		//Check to make sure comments were entered
		if(trim($_POST['comments']) === '') {
			$commentError = __("You forgot to enter your comments.", "site5framework");
			$hasError = true;
		} else {
			if(function_exists('stripslashes')) {
				$comments = stripslashes(trim($_POST['comments']));
			} else {
				$comments = trim($_POST['comments']);
			}
		}

		//If there is no error, send the email
		if(!isset($hasError)) {
			$msg .= "------------User Info------------ \r\n"; //Title
			$msg .= "User IP : ".$_SERVER["REMOTE_ADDR"]."\r\n"; //Sender's IP
			$msg .= "Browser Info : ".$_SERVER["HTTP_USER_AGENT"]."\r\n"; //User agent
			$msg .= "User Come From : ".$_SERVER["HTTP_REFERER"]; //Referrer
			
			$emailTo = ''.of_get_option('sc_contact_email').'';
			$subject = 'Contact Form Submission From '.$name;
			$body = "Name: $name \n\nEmail: $email \n\nMessage: $comments \n\n $msg";
			$headers = 'From: '.get_bloginfo('name').' <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $email;

			if(mail($emailTo, $subject, $body, $headers)) $emailSent = true;

	}
	 
}
get_header(); 
?>
			
			<div id="content" class="container clearfix"> 

				<!-- page header -->
				<div class="container clearfix ">

					

					<?php if(of_get_option('sc_contact_map') != '') { ?> 
						<!-- contact map -->  
						<div id="contact-map">
						<?php echo of_get_option('sc_contact_map') ?>
						</div>
						<!-- end contact map -->
					<?php } else if(of_get_option('sc_showpageheader') == '1' &&  get_post_meta($post->ID, 'snbpd_ph_disabled', true) != 'on' ) : ?>

						<?php if(get_post_meta($post->ID, 'snbpd_phitemlink', true)!= '') : ?>

						<?php
						$thumbId = get_image_id_by_link ( get_post_meta($post->ID, 'snbpd_phitemlink', true) );
						$thumb = wp_get_attachment_image_src($thumbId, 'page-header', false);
						?>
						<img class="intro-img" alt=" " src="<?php echo $thumb[0] ?>" alt="<?php the_title(); ?>"  />

						<?php else : ?>
						<img class="intro-img" alt=" " src="<?php echo get_template_directory_uri(); ?>/library/images/inner-page-bg.jpg" />
						<?php endif ?>
					<?php endif ?>

				</div>


				<!-- content -->
				<div class="container">
				
					<h1><?php the_title(); ?> <?php if ( !get_post_meta($post->ID, 'snbpd_pagedesc', true)== '') { ?>/<?php }?> <span><?php echo get_post_meta($post->ID, 'snbpd_pagedesc', true); ?></span></h1>
				
					<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">

						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						
							<div class="page-body clearfix"> 
								<?php the_content(); ?>
							</div> 


							<div class="one-third">
								<div class="caddress"><strong><?php _e('Address:', 'site5framework') ?></strong> <?php echo of_get_option('sc_contact_address') ?></div>
				                <div class="cphone"><strong><?php _e('Phone:', 'site5framework') ?></strong> <?php echo of_get_option('sc_contact_phone') ?></div>
				                <div class="cphone"><strong><?php _e('Fax:', 'site5framework') ?></strong> <?php echo of_get_option('sc_contact_fax') ?></div>
				                <div class="cemail"><strong><?php _e('E-mail:', 'site5framework') ?></strong> <?php echo of_get_option('sc_contact_email') ?></div>
								
							</div>

							<div class="two-third last">
								<div id="messages">
									<p class="simple-error error" <?php if($hasError != '') echo 'style="display:block;"'; ?>><?php _e('There was an error submitting the form.', 'site5framework'); ?></p>
				                    
				                    <p class="simple-success thanks"><?php _e('<strong>Thanks!</strong> Your email was successfully sent. We should be in touch soon.', 'site5framework'); ?></p>
			                	</div>
								
								<form id="contactForm" method="POST">
				                    <div class="one-third">
										<label for="nameinput"><?php _e("Your name", "site5framework"); ?></label>
										<input type="text" id="nameinput" name="contactName" value="<?php if(isset($_POST['contactName'])) echo $_POST['contactName'];?>" class="requiredField"/>
										<span class="error" <?php if($nameError != '') echo 'style="display:block;"'; ?>><?php _e("You forgot to enter your name.", "site5framework");?></span>
				                    </div>
				                    <div class="one-third last">
										<label for="emailinput"><?php _e("Your email", "site5framework"); ?></label>			
											<input type="text" id="emailinput" name="email" value="<?php if(isset($_POST['email']))  echo $_POST['email'];?>" class="requiredField email"/>
										  <span class="error" <?php if($emailError != '') echo 'style="display:block;"'; ?>><?php _e("You forgot to enter your email address.", "site5framework");?></span>
				                    </div>
				                    <div class="two-third">
									<label for="Mymessage"><?php _e("Your message", "site5framework"); ?></label>
										<textarea cols="20" rows="20" id="Mymessage" name="comments" class="requiredField"><?php if(isset($_POST['comments'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['comments']); } else { echo $_POST['comments']; } } ?></textarea>
										  <span class="error" <?php if($commentError != '') echo 'style="display:block;"'; ?>><?php _e("You forgot to enter your comments.", "site5framework");?></span>
				                    </div>
				                    <br class="clear" />
				                    <input type="hidden" name="submitted" id="submitted" value="true" />
									<button type="submit" id="submitbutton" class="button small round blue"><?php _e(' &nbsp;SEND MESSAGE&nbsp; ', 'site5framework'); ?></button>
			               
								</form>
								
							</div>
							
							
						
						<?php endwhile; ?>		
					</article>

					<?php else : ?>
						
					<article id="post-not-found">
						<header>
							<h1><?php _e("Not Found", "site5framework"); ?></h1>
						</header>
						<section class="post_content">
							<p><?php _e("Sorry, but the requested resource was not found on this site.", "site5framework"); ?></p>
						</section>
						<footer>
						</footer>
					</article>
					
					<?php endif; ?>
				
					
				</div>
		

			</div> <!-- end content -->

			<?php get_footer(); ?>