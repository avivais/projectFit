<?php get_header(); ?>
			
			<div id="content" class="container clearfix">
				
				<!-- page header -->
				<div class="container clearfix ">
					<?php
					// display the blog page image header or fallback to the default one.
					$blogPageID = of_get_option('sc_singledesc');
					$thumbId = get_image_id_by_link ( get_post_meta($blogPageID, 'snbpd_phitemlink', true) );
					$thumb = wp_get_attachment_image_src($thumbId, 'page-header', false);
					?>
					<?php if(of_get_option('sc_showpageheader') == '1'  && get_post_meta($blogPageID, 'snbpd_ph_disabled', true) != 'on' ) : ?>
						<?php if(get_post_meta($blogPageID, 'snbpd_phitemlink', true)!= ''  ) : ?>
						<img class="intro-img" src="<?php echo $thumb[0] ?>" alt="<?php the_title(); ?>"  />
						<?php else : ?>
						<img class="intro-img" alt=" " src="<?php echo get_template_directory_uri(); ?>/library/images/inner-page-bg.jpg" />
						<?php endif ?>
					<?php endif ?>
					
				</div>

				<div class="three-fourth">
					<h1>
					<?php if (is_category()) { ?>
								<?php _e("Posts Categorized", "site5framework"); ?> / <span><?php single_cat_title(); ?></span> 
						<?php } elseif (is_tag()) { ?> 
								<?php _e("Posts Tagged", "site5framework"); ?> / <span><?php single_cat_title(); ?></span>
						<?php } elseif (is_author()) { ?>
								<?php _e("Posts By", "site5framework"); ?> / <span><?php the_author_meta('display_name', $post->post_author) ?> </span> 
						<?php } elseif (is_day()) { ?>
								<?php _e("Daily Archives", "site5framework"); ?> / <span><?php the_time('l, F j, Y'); ?></span>
						<?php } elseif (is_month()) { ?>
						    	<?php _e("Monthly Archives", "site5framework"); ?> / <span><?php the_time('F Y'); ?></span>
						<?php } elseif (is_year()) { ?>
						    	<?php _e("Yearly Archives", "site5framework"); ?> / <span><?php the_time('Y'); ?></span> 
						<?php } elseif (is_Search()) { ?>
						    	<?php _e("Search Results", "site5framework"); ?> / <span><?php echo esc_attr(get_search_query()); ?></span> 
						<?php } ?>
					 </h1>

				
					<div id="main" role="main">

						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

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
						    <footer>
						    </footer>
						</article>
						
						<?php endif; ?>
				
					</div> <!-- end #main -->
				</div><!-- three-fourth -->

				<div class="one-fourth last">
					<?php get_template_part( 'blog', 'sidebar' ); ?>
				</div>
    
			</div> <!-- end #content -->
<?php get_footer(); ?>