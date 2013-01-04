<?php get_header(); ?>
			
			<div id="content" class="clearfix">

				<!-- page header -->
				<div class="container clearfix ">

					<?php if(of_get_option('sc_showpageheader') == '1' &&  get_post_meta($post->ID, 'snbpd_ph_disabled', true) != 'on' ) : ?>

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
				
				<div id="main" role="main" class="container clearfix">

					<article id="post-not-found">
						

						<header>
							
							<h1 class="not-found-text"> · <span class='colored'>404</span> <?php _e("Error", "site5framework"); ?> · </h1>
						
						</header> <!-- end article header -->
					
						<section class="post_content">
							
							<p><?php _e("The article you were looking for was not found, but maybe try looking again!", "site5framework"); ?></p>
					
						</section> <!-- end article section -->
					
					</article> <!-- end article -->
			
				</div> <!-- end #main -->
    
			</div> <!-- end #content -->

<?php get_footer(); ?>
