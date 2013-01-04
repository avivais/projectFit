<?php get_header(); ?>
			
			<div id="content" class="container clearfix"> 

				<!-- page header -->
				<div class="container clearfix ">
					<?php
					// display the blog page image header or fallback to the default one.
					$blogPageID = of_get_option('sc_portfoliodesc');
					$thumbId = get_image_id_by_link ( get_post_meta($blogPageID, 'snbpd_phitemlink', true) );
					$thumb = wp_get_attachment_image_src($thumbId, 'page-header', false);
					?>
					<?php if(of_get_option('sc_showpageheader') == '1'  && get_post_meta($blogPageID, 'snbpd_ph_disabled', true) != 'on' ) : ?>
						<?php if(get_post_meta($blogPageID, 'snbpd_phitemlink', true)!= ''  ) : ?>
						<img class="intro-img" src="<?php echo $thumb[0] ?>" alt="<?php the_title(); ?>"  />
						<?php else : ?>
						<img class="intro-img" src="<?php echo get_template_directory_uri(); ?>/library/images/inner-page-bg.jpg" />
						<?php endif ?>
					<?php endif ?>
					
				</div>



				<!-- content -->
				<div class="container">
					
					<div class="three-fourth">
						<h1><?php the_title(); ?> <?php if ( !get_post_meta($post->ID, 'snbpd_pagedesc', true)== '') { ?>/<?php }?> <span><?php echo get_post_meta($post->ID, 'snbpd_pagedesc', true); ?></span></h1>
					</div>
					<div class="one-fourth last">
						<div class="project-pagination">
							<?php previous_post_link('%link', '‹'.__('Previous Project', 'site5framework')) ?>
							<?php next_post_link('%link', __('Next Project', 'site5framework') . '›'); ?> 
						</div>
					</div>
					<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">

						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
							
							<div class="portfolio-image resize">
							<?php
							$thumbId = get_image_id_by_link ( get_post_meta($post->ID, 'snbp_pitemlink', true) );
							$thumb = wp_get_attachment_image_src($thumbId, 'full', false);
							$large = wp_get_attachment_image_src($thumbId, 'full', false);

							if (!$thumb == ''){ ?>
							<a href="<?php echo $large[0] ?>" data-rel="prettyPhoto" title="<?php the_title(); ?>"><img src="<?php echo $thumb[0] ?>" alt="<?php the_title(); ?>"  /></a>
							<?php }  ?>
							
							</div>

							<div class="page-body clearfix">
								<?php the_content(); ?>
							</div> <!-- end article section -->
						
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