<!doctype html>

<!--[if IEMobile 7 ]> <html <?php language_attributes(); ?>class="no-js iem7"> <![endif]-->
<!--[if lt IE 7 ]> <html <?php language_attributes(); ?> class="no-js ie6 oldie"> <![endif]-->
<!--[if IE 7 ]>    <html <?php language_attributes(); ?> class="no-js ie7 oldie"> <![endif]-->
<!--[if IE 8 ]>    <html <?php language_attributes(); ?> class="no-js ie8 oldie"> <![endif]-->
<!--[if (gte IE 9)|(gt IEMobile 7)|!(IEMobile)|!(IE)]><!--><html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->
	
	<head>
		<meta charset="utf-8">
		<!--[if ie]><meta content='IE=edge,chrome=1' http-equiv='X-UA-Compatible'/><![endif]-->
		
		<title><?php wp_title( ' - ', true, 'right' ); ?> <?php bloginfo('name'); ?></title>
		
		<?php if ( of_get_option('sc_enablemeta')== '1') { ?>
		
		<!-- meta -->
		<meta name="description" content="<?php echo of_get_option('sc_metadescription')  ?>">
		<meta name="keywords" content="<?php wp_title(); ?>, <?php echo of_get_option('sc_metakeywords')  ?>" />
		<meta name="revisit-after" content="<?php echo of_get_option('sc_revisitafter')  ?> days" />
		<meta name="author" content="www.site5.com">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<?php } ?>
		
		<?php if ( of_get_option('sc_enablerobot')== '1') { ?>
		
		<!-- robots -->
		<meta name="robots" content="<?php echo of_get_option('sc_metabots')  ?>" />
		<meta name="googlebot" content="<?php echo of_get_option('sc_metagooglebot')  ?>" />
		<?php } ?>
		
				
  		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
		
		<!--[if lt IE 9]>
		<script src="<?php echo get_template_directory_uri(); ?>/library/js/html5.js"></script>
		<![endif]-->
			
		

		
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">

		<!-- wordpress head functions -->
		<?php wp_head(); ?>
		<!-- end of wordpress head -->
		


		<?php if(of_get_option('sc_css_code') != '') { ?> 
		<!-- custom css -->  
			<?php load_template( get_template_directory() . '/custom.css.php' );?>
		<!-- custom css -->
		<?php } ?>

		<?php if(of_get_option('sc_customtypography') == '1') { ?>     
		<!-- custom typography-->   
			<?php if(of_get_option('sc_headingfontlink') != '') { ?>
			<?php echo stripslashes(html_entity_decode(of_get_option('sc_headingfontlink')));?>
		<!-- custom typography -->
			<?php } ?>
			<?php load_template( get_template_directory() . '/custom.typography.css.php' );?>
		<?php } ?>


		<?php if(of_get_option('sc_colorscheme') != '') { ?> 
			<!-- custom color scheme css -->  
			<link rel="stylesheet" href="<?php bloginfo('template_directory');?>/library/css/color-schemes/<?php echo of_get_option('sc_colorscheme') ?>/styles.css">
		<?php } ?>


		
	</head>
	
	<body <?php body_class(); ?>>
	

		<div id="page">
			
			
			<div class="container clearfix">
			
			
				<div id="pre-header">
					<!-- begin #socialIcons -->
					<div id="social-icons">
						<ul id="social-links">
							<?php if(of_get_option('sc_facebook')!='') : ?>
							<li class="facebook-link"><a href="<?php echo of_get_option('sc_facebook') ?>" class="facebook" id="social-01" title="<?php _e( 'Join Us on Facebook!', 'site5framework' ); ?>">Facebook</a></li>
							<?php endif ?>
							<?php if(of_get_option('sc_twitter')!=''): ?>
							<li class="twitter-link"><a href="<?php echo of_get_option('sc_twitter') ?>" class="twitter" id="social-02" title="<?php _e( 'Follow Us on Twitter', 'site5framework' ); ?>">Twitter</a></li>
							<?php endif ?>
							<?php if(of_get_option('sc_googleplus')!=''): ?>
							<li class="google-link"><a href="<?php echo of_get_option('sc_googleplus') ?>" id="social-03" title="<?php _e( 'Google+', 'site5framework' ); ?>" class="google">Google</a></li>
							<?php endif ?>
							<?php if(of_get_option('sc_dribble')!=''): ?>
							<li class="dribbble-link"><a href="<?php echo of_get_option('sc_dribble') ?>" id="social-04" title="<?php _e( 'Dribble', 'site5framework' ); ?>" class="dribbble">Dribble</a></li>
							<?php endif ?>
							<?php if(of_get_option('sc_vimeo')!=''): ?>
							<li class="vimeo-link"><a href="<?php echo of_get_option('sc_vimeo') ?>" id="social-05" title="<?php _e( 'Vimeo', 'site5framework' ); ?>" class="vimeo">Vimeo</a></li>
							<?php endif ?>
							<?php if(of_get_option('sc_skype')!=''): ?>
							<li class="skype-link"><a href="<?php echo of_get_option('sc_skype') ?>" id="social-06" title="<?php _e( 'Skype', 'site5framework' ); ?>" class="skype">Skype</a></li>
							<?php endif ?>
							<?php if(of_get_option('sc_linkedin')!=''): ?>
							<li class="linkedin-link"><a href="<?php echo of_get_option('sc_linkedin') ?>" id="social-07" title="<?php _e( ' LinkedIn', 'site5framework' ); ?>" class="linkedin">Linkedin</a></li>
							<?php endif ?>
							<?php if(of_get_option('sc_pinterest')!=''): ?>
							<li class="pinterest-link"><a href="<?php echo of_get_option('sc_pinterest') ?>" id="social-09" title="<?php _e( 'Pinterest', 'site5framework' ); ?>" class="pinterest">Pinterest</a></li>
							<?php endif ?>
							<?php if(of_get_option('sc_rss')=='1'): ?>
								<li class="rss-link"><a href="<?php echo of_get_option('sc_extrss') ?  of_get_option('sc_extrss') : bloginfo('rss_url'); ?>" id="social-08" title="<?php _e( 'RSS', 'site5framework' ); ?>" class="rss">RSS Feeds</a></li>
						<?php endif ?>
						</ul>
					</div>
					 <select id="responsive-social-menu" onchange = "javascript:window.location.replace(this.value);"><option selected="selected" ><?php _e('Social Links', 'site5framework'); ?></select>
					 <!-- end #socialIcons -->
				</div>
				<!-- end #pre-header -->
			
			
			
				
				
				<header role="banner" id="header" class="clearfix">
						
						<!-- begin #logo -->
						<?php if ( !of_get_option('sc_clogo')== '') { ?>
						<hgroup id="logo-wrapper">
							<h1><a id="logo" href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
								<img src="<?php echo of_get_option('sc_clogo'); ?>" alt="<?php echo bloginfo( 'name' ) ?>" />
							</a></h1>
							
						</hgroup>
						
						<?php } else { ?>
						
						<hgroup id="logo-wrapper">
							<h1><a id="logo" href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
								<?php if( !of_get_option('sc_clogo_text')== '') {
									echo of_get_option('sc_clogo_text'); 
									} else {
									bloginfo( 'name' );
								}
								?>
							</a></h1>
							<h5 id="tagline"><?php bloginfo('description'); ?></h5>
						</hgroup>
						
						<?php }?>
						<!-- end #logo -->
						
				
					
						<!-- begin #topMenu -->
						<div id="navigation-wrapper" class="clearfix">
							<select id = "responsive-main-nav-menu" onchange = "javascript:window.location.replace(this.value);"><option selected="selected" ><?php _e('Menu', 'site5framework'); ?></option></select>
							
							<nav id="main-navigation" class="main-menu">
							<?php
							site5_main_nav( array(
							 'container' =>false,
							 'menu_class' => '',
							 'echo' => true,
							 'before' => '',
							 'after' => '',
							 'link_before' => '',
							 'link_after' => '',
							 'depth' => 0
							 )
							 );
							 // Adjust using Menus in Wordpress Admin ?>
							</nav>
						</div>
						<!-- end #topMenu -->
				
				</header> <!-- end header -->
							
			
			</div>
