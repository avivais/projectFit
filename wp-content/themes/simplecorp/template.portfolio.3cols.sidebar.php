<?php 
/*
 * Template Name: Portfolio 3 Cols With Sidebar
 */
get_header(); ?>
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

							
				<div class="three-fourth">
				<?php if (have_posts()) : ?>

					<h1><?php echo get_the_title(of_get_option('sc_portfoliodesc')); ?> <?php if(single_cat_title("", false) !='') { ?> / <span><?php single_cat_title(); ?></span> <?php } ?></h1>
				

					<ul class="filterable" id="<?php echo of_get_option('sc_portfoliofilters')=='javascript' ? 'filterable' : '' ?>">
						<li class="active"><a href="<?php  echo get_permalink( of_get_option('sc_portfoliodesc') ) ?>" data-value="all" data-type="all" class="all"><?php _e('all', 'site5framework'); ?></a></li>
						<?php  
						$categories=  get_categories('taxonomy=types&title_li='); foreach ($categories as $category){ ?>
						<?php //print_r(get_term_link($category->slug, 'types')) ?>
						<li><a href="<?php echo get_term_link($category->slug, 'types') ?>" class="<?php echo $category->category_nicename;?>" data-type="<?php echo $category->category_nicename;?>"><?php echo $category->name;?></a></li>
						<?php }?>
											
					</ul>

					<div class="portfolio-container">

						<ul id="portfolio-items-one-fourth"  class="portfolio-items clearfix">

							<?php 
							global $post;
							$term = get_query_var('term'); 
       						$tax = get_query_var('taxonomy'); 
							$args=array('post_type'=> 'portfolio','post_status'=> 'publish', 'orderby'=> 'menu_order', 'caller_get_posts'=>1, 'paged'=>$paged, 'posts_per_page'=>of_get_option('sc_portfolioitemsperpage')); 
							$taxargs = array($tax=>$term);
							if($term!='' && $tax!='') { $args  = array_merge($args, $taxargs); }

							query_posts($args); 
							
							while ( have_posts()):the_post();
								$categories = wp_get_object_terms( get_the_ID(), 'types');
							?>
							
							<li class="item <?php foreach ($categories as $category) { echo $category->slug. ' '; } ?>" data-id="id-<?php the_ID(); ?>" data-type="<?php foreach ($categories as $category) { echo $category->slug. ' '; } ?>">
								<div class="portfolio-item ">
									<div class="item-content">
										<div class="link-holder">
											<div class="portfolio-item-holder">
												<div class="portfolio-item-hover-content">
													
													<?php
													$thumbId = get_image_id_by_link ( get_post_meta($post->ID, 'snbp_pitemlink', true) );
													$thumb = wp_get_attachment_image_src($thumbId, 'portfolio-thumbnail', false);
													$large = wp_get_attachment_image_src($thumbId, 'large', false);

													if (!$thumb == ''){ ?>
													
													<a href="<?php echo $large[0] ?>" title="<?php the_title(); ?>" data-rel="prettyPhoto" class="zoom">View Image</a>
													
													<img src="<?php echo $thumb[0] ?>" alt="<?php the_title(); ?>"  class="portfolio-img" width="220" />
													<?php } else { ?>
													<img src="<?php echo get_template_directory_uri(); ?>/library/images/portfolio-img.png" alt="<?php the_title(); ?>" width="220"  class="portfolio-img" />	
													<?php } ?>

													<div class="hover-options"></div>
												</div>
											</div>
											<div class="description">
												<p>
													<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"> <?php the_title(); ?> </a>
												</p>
												<span><?php $separator = ''; foreach ($categories as $category) { echo $separator . $category->name; $separator=' / ';} ?></span>
											</div>
										</div>
									</div>
								</div>
							</li>

							<?php endwhile; ?>	

						</ul>

						<!-- begin #pagination -->
						<?php if (function_exists("wpthemess_paginate")) { wpthemess_paginate(); } ?>
						<!-- end #pagination -->

						<?php 
							wp_reset_query();
							wp_reset_postdata();	
						?>
					</div>
				

				

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


			</div><!-- three fourth -->


			<div class="one-fourth last">
				<?php get_template_part( 'blog', 'sidebar' ); ?>
			</div>

		</div>
<?php get_footer(); ?>