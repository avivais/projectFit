<?php get_header(); ?>
			
			<div id="content" class="clearfix">
			
				<?php if(of_get_option('sc_blurbhome') == '1') { ?>
				<!-- begin .blurb -->
				<div class="container">
					<div class="intro-page">
						<?php if(!of_get_option('sc_blurb') == '')  { ?>
						<h2><?php echo of_get_option('sc_blurb') ?></h2>
						<?php }?>
					</div>
				</div>
				<!-- end .blurb -->
				<?php } ?>
				

				


				<?php if(of_get_option('sc_displayslider') == '1') { ?>
					<?php if(of_get_option('sc_slidertype') == 'flex') { ?>
						<?php get_template_part( 'homepage', 'slider' ); ?>
					<?php } ?>
				<?php } ?>


				
				
				<?php if(of_get_option('sc_homecontent') == '1') { ?>
				
				<!--begin cols content -->
				<div class="container clearfix">
					<?php if( of_get_option('sc_homecontent1img') ||  of_get_option('sc_homecontent1title') || of_get_option('sc_homecontent1') ) : ?>
					<div class="one-half">
						<?php if( of_get_option('sc_homecontent1img')): ?><img src="<?php echo of_get_option('sc_homecontent1img') ?>" class="img-align-left" alt="<?php echo of_get_option('sc_homecontent1title') ?>" /><?php endif ?>
						<h3><?php echo of_get_option('sc_homecontent1title') ?></h3>
						<p><?php echo of_get_option('sc_homecontent1') ?></p>
						<?php if (of_get_option('sc_homecontent1url')!='') { ?>
						<p class="readmore">
							<a href="<?php echo of_get_option('sc_homecontent1url') ?>"><?php _e('Read more…', 'site5framework') ?></a>
						</p>
						<?php } ?>
					</div>
					<?php endif ?>
					
					<?php if( of_get_option('sc_homecontent2img') ||  of_get_option('sc_homecontent2title') || of_get_option('sc_homecontent2') ) : ?>
					<div class="one-half last">
						<?php if( of_get_option('sc_homecontent2img')): ?><img src="<?php echo of_get_option('sc_homecontent2img') ?>" class="img-align-left" alt="<?php echo of_get_option('sc_homecontent2title') ?>" /><?php endif ?>
						<h3><?php echo of_get_option('sc_homecontent2title') ?></h3>
						<p><?php echo of_get_option('sc_homecontent2') ?></p>
						<?php if (of_get_option('sc_homecontent2url')!='') { ?>
						<p class="readmore">
							<a href="<?php echo of_get_option('sc_homecontent2url') ?>"><?php _e('Read more…', 'site5framework') ?></a>
						</p>
						<?php } ?>
					</div>
					<?php endif ?>
				</div> 
				
				<div class="container clearfix">
					<?php if( of_get_option('sc_homecontent3img') ||  of_get_option('sc_homecontent3title') || of_get_option('sc_homecontent3') ) : ?>
					<div class="one-half">
						<?php if( of_get_option('sc_homecontent3img')): ?><img src="<?php echo of_get_option('sc_homecontent3img') ?>" class="img-align-left" alt="<?php echo of_get_option('sc_homecontent3title') ?>" /><?php endif ?>
						<h3><?php echo of_get_option('sc_homecontent3title') ?></h3>
						<p><?php echo of_get_option('sc_homecontent3') ?></p>
						<?php if (of_get_option('sc_homecontent3url')!='') { ?>
						<p class="readmore">
							<a href="<?php echo of_get_option('sc_homecontent3url') ?>"><?php _e('Read more…', 'site5framework') ?></a>
						</p>
						<?php } ?>
					</div>
					<?php endif ?>
					
					<?php if( of_get_option('sc_homecontent4img') ||  of_get_option('sc_homecontent4title') || of_get_option('sc_homecontent4') ) : ?>
					<div class="one-half last">
						<?php if( of_get_option('sc_homecontent4img')): ?><img src="<?php echo of_get_option('sc_homecontent4img') ?>" class="img-align-left" alt="<?php echo of_get_option('sc_homecontent4title') ?>" /><?php endif ?>
						<h3><?php echo of_get_option('sc_homecontent4title') ?></h3>
						<p><?php echo of_get_option('sc_homecontent4') ?></p>
						<?php if (of_get_option('sc_homecontent4url')!='') { ?>
						<p class="readmore">
							<a href="<?php echo of_get_option('sc_homecontent4url') ?>"><?php _e('Read more…', 'site5framework') ?></a>
						</p>
						<?php } ?>
					</div>
					<?php endif ?>
					
					<div class="horizontal-line"> </div>
					
				</div>
				
				

				<!-- end cols content -->

				<?php } ?>
				
				
				
				<?php if(of_get_option('sc_portfoliohome') == '1') : ?>
				<div class="container clearfix">

					<h3><?php echo of_get_option('sc_portfoliohometitle') ?></h3>
					
					<?php
					$args=array('post_type'=> 'portfolio', 'post_status'=> 'publish','orderby'=> 'menu_order','posts_per_page'=>8,'showposts'=>8,'caller_get_posts'=>1,'paged'=>$paged,); query_posts($args); 
					if ( have_posts() ) :
					?>
					<ul id="projects-carousel" class="loading">
						<?php 
						while (have_posts()): the_post(); 
							$categories = wp_get_object_terms( get_the_ID(), 'types');
						?>
						<!-- PROJECT ITEM STARTS -->
						<li>
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
										
										<img src="<?php echo $thumb[0] ?>" alt="<?php the_title(); ?>" width="220" class="portfolio-img" />
										<?php } else { ?>
										<img src="<?php echo get_template_directory_uri(); ?>/library/images/sampleimages/portfolio-img.jpg" alt="<?php the_title(); ?>" width="220"  class="portfolio-img" />	
										<?php }?>
										
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
						</li>
						<!-- PROJECT ITEM ENDS -->
						<?php 
						endwhile;
						wp_reset_query();
						?>
					</ul>
					<!-- // optional "view full portfolio" button on homepage featured projects -->
					<a href="<?php echo of_get_option('sc_portfoliohomebuttonurl') ?>" class="colored" title="<?php echo of_get_option('sc_portfoliohomebuttontitle') ?>"><?php echo of_get_option('sc_portfoliohomebuttontitle') ?></a> 
					<?php 
					else :
					?>
					
					<?php 
					endif;
					?>
					

					
				</div>
				<?php endif ?>
		
				
    
			</div> <!-- end #content -->
<?php get_footer(); ?>

