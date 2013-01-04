<?php 
/*
 * Template Name: Blog
*/
get_header(); ?>
			
			<div id="content" class="container clearfix">
				
				<!-- page header -->
				<div class="container clearfix ">

					
					<?php if(of_get_option('sc_showpageheader') == '1' && get_post_meta($post->ID, 'snbpd_ph_disabled', true)!= 'on'  ) : ?>

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
				<div class="three-fourth">

					<h1><?php the_title(); ?> <?php if ( !get_post_meta($post->ID, 'snbpd_pagedesc', true)== '') { ?>/<?php }?> <span><?php echo get_post_meta($post->ID, 'snbpd_pagedesc', true); ?></span></h1>
					

					<div id="main" role="main">
							
							<?php
								// WP 3.0 PAGED BUG FIX
								if ( get_query_var('paged') )
								$paged = get_query_var('paged');
								elseif ( get_query_var('page') )
								$paged = get_query_var('page');
								else
								$paged = 1;

								$args = array(
								'post_type' => 'post',
								'paged' => $paged );
								query_posts($args);
							?>
							
							<?php if (have_posts()) : $count = 0; ?>
							<?php while (have_posts()) : the_post(); $count++; global $post; ?>
							

								<?php get_template_part( 'content', 'single' ); ?>

							
							<?php endwhile; ?>

						<!-- begin #pagination -->
								<?php if (function_exists("wpthemess_paginate")) { wpthemess_paginate(); } ?>
						<!-- end #pagination -->
									
						<?php else : ?>
						
						<article id="post-not-found">
						    <header>
						    	<h1><?php _e("No Posts Yet", "site5framework"); ?></h1>
						    </header>
						    <section class="post_content">
						    	<p><?php _e("Sorry, What you were looking for is not here.", "site5framework"); ?></p>
						    </section>
						</article>
						
						<?php endif; ?>
				
					</div> <!-- end #main -->

				</div><!-- three-fourth -->

				<div class="one-fourth last">
					<?php get_template_part( 'blog', 'sidebar' ); ?>
				</div>
    
			</div> <!-- end #content -->
<?php get_footer(); ?>