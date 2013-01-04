<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">
	
	<div class="resize">

	<?php if(of_get_option('sc_showfeaturedimage') == '1') { ?>
		<?php if ( has_post_thumbnail()) { ?>
		<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', 'site5framework' ); ?> <?php the_title(); ?>">
			<?php  the_post_thumbnail('archive-thumbnail', array('title'=>'')); ?>
		</a>
		<?php } ?>
	<?php } ?>

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

	
		<?php the_excerpt(); ?>

	</div> <!-- end article section -->
						
</article> <!-- end article -->