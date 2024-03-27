<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta http-equiv="content-type" content="<?php bloginfo('html_type') ?>; charset=<?php bloginfo('charset') ?>" />
	<meta name="viewport" content="width=device-width" />
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="pingback" href="<?php bloginfo('pingback_url') ?>" />
	<?php wp_head() ?>
</head>
<body <?php body_class(); ?> >
	<div id="wrapperimage" class="headerimage">
	</div>
    <div id="wrapperpub" class="header">
		<div id="header" class="container-head">
			
			<div id="mainlogo" class="">
				<div id="blog-title" class="blogtitle"><a href="<?php echo esc_url(home_url( '/' )); ?>" title="<?php bloginfo('name') ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="lednews.ru"></a></div>
				<!-- <div class="description"><?php bloginfo('description'); ?> </div> -->
			</div>

            <div class="mobileoff">
            <nav id="primary-navigation-mobileoff" class="site-navigation primary-navigation-mobileoff" role="navigation">
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu-mobileoff mleft' ) ); ?>

				<ul class="user_link nav-menu-mobileoff mright">
					<li class="login_zone">										
							<a href="http://lednews.lighting/">Форум</a>
					</li>
					<li>
	                  <span class="searchform">
	                      <span class="search-text"></span>
	                  </span>
					</li>
				</ul>

			</nav>
            </div>    
			
			<div class="mobileon">
            <nav id="primary-navigation" class="site-navigation primary-navigation" role="navigation">
				<button class="menu-toggle"><?php _e( 'Primary Menu', 'codium-now' ); ?></button>
                <div class="clear"></div>
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?>

		

			</nav>
            </div>    
		</div>
		<!--  #header -->	
	</div>
	
	<div class="clear"></div>

	<!--  banner -->
	<?php 
	//get_template_part('banner-header');
	dynamic_sidebar( 'banner_sidebar_up' );
    ?>


	<!--  #wrapperpub -->	
	<div id="wrapper" class="container">
		<div class="clear"></div>