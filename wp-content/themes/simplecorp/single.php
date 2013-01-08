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
						<img class="intro-img" alt=" " src="<?php echo $thumb[0] ?>" alt="<?php the_title(); ?>"  />
						<?php else : ?>
						<img class="intro-img" alt=" " src="<?php echo get_template_directory_uri(); ?>/library/images/inner-page-bg.jpg" />
						<?php endif ?>
					<?php endif ?>
					
				</div>


				<div class="three-fourth">


					<h1><?php _e("WODs", "site5framework"); ?> 
					<?php
					$singledescpage = of_get_option('sc_singledesc');
					$singledesc = get_post_meta($singledescpage, 'snbpd_pagedesc');
					?>
					
					<?php if (!empty($singledesc)) { 
					echo ' / <span>';
					echo $singledesc[0].'</span>';
					}?>
					</h1>
					


					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>


					<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">
						
						<div class="resize">
						<?php 
						 if ( has_post_thumbnail()) {
						   $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large');
						   echo '<a data-rel="prettyPhoto" href="' . $large_image_url[0] . '" title="' . the_title_attribute('echo=0') . '" >';
						   the_post_thumbnail('full');
						   echo '</a>';
						 }
						 ?>
						</div>

						<div class="entry-meta">
							<time class="post-date" datetime="<?php echo the_time('Y-m-d'); ?>">
								<span class="post-month"><?php the_time('F'); ?></span>
								<strong class="post-day"><?php the_time('d'); ?></strong>
								<span class="post-year "><?php the_time('Y'); ?></span>
							</time>
							
							<ul>
								<li><span class="title"><?php _e("In", "site5framework"); ?>:</span> <?php the_category(', '); ?></li>
								<li><span class="title"><?php _e("Posted By", "site5framework"); ?>:</span> <?php the_author_posts_link(); ?></li>
								<?php $post_tags = wp_get_post_tags($post->ID);
									if(!empty($post_tags)) { ?>
								<li>
									<span class="title"><?php _e("Tags", "site5framework"); ?>:</span>
									<span class="tags">
										<?php the_tags('', ', ', ''); ?>
									</span>
								</li>
								<?php } ?>
								<li><span class="title"><?php _e("Comments", "site5framework"); ?>:</span> <?php comments_popup_link(__('0', 'site5framework'), __('1', 'site5framework'), __('%', 'site5framework')); ?></li>
							</ul>
						</div>

						<div class="entry-body"> 
							

							<header>

								<h3 class="permalink"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', 'site5framework' ); ?> <?php the_title(); ?>"><?php the_title(); ?></a></h3>
								
							</header> <!-- end article header -->

						
							<?php the_content(); ?>

						</div> <!-- end entry-body -->

						
											
					</article> <!-- end article -->
						
					<?php if(of_get_option('sc_authorbox') == '1') : ?>
					<div class="author clearfix">
				        <div class="author-gravatar">
				            <?php echo get_avatar( $post->post_author, 64 ); ?>       
				        </div>
				        <div class="author-about">
				        	<h4>
				        	<?php if(get_the_author_meta( 'first_name') != ''  &&  get_the_author_meta( 'last_name') != '' ) : ?>
				            	<?php the_author_meta( 'first_name'); ?> <?php the_author_meta( 'last_name'); ?> 
				       		<?php else: ?>
				           		<?php the_author_meta( 'user_nicename'); ?> 
				       		<?php endif; ?>
				       		</h4>
				            <p class="author-description"><?php the_author_meta( 'description' ); ?></p>
				        </div>
				    </div>
					<?php endif ?>
						
						<?php comments_template(); ?>
						
						<?php endwhile; ?>			
						
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
						
					
				</div><!-- three-fourth -->

				<div class="one-fourth last">
					<?php get_template_part( 'blog', 'sidebar' ); ?>
				</div>
    
			</div> <!-- end #content -->
<?php get_footer(); ?>