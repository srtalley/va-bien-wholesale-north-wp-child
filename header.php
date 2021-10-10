<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_site_icon(); ?>
	<?php 
		/* Always have wp_head() just before the closing </head>
		 * tag of your theme, or you will break many plugins, which
		 * generally use this hook to add elements to <head> such
		 * as styles, scripts, and meta tags.
		 */
		wp_head(); 
	?>
</head>
<body <?php body_class(); ?>>
<div id="wrapper" class="open">
	
	<?php get_template_part( 'inc/templates/header/mobile-menu' ); ?>
	
	<!-- Start Side Cart -->
	<?php do_action( 'thb_side_cart' ); ?>
	<!-- End Side Cart -->
	
	<!-- Start Shop Filters -->
	<?php do_action( 'thb_shop_filters' ); ?>
	<!-- End Shop Filters -->
	
	<!-- Start Content Click Capture -->
	<div class="click-capture"></div>
	<!-- End Content Click Capture -->
	
	<!-- Start Global Notification -->
	<?php get_template_part( 'inc/templates/header/global-notification' ); ?>
	<!-- End Global Notification -->
	
	<!-- Start Header -->
	<?php
	
	if ( is_home() || is_singular('post') ) { 
	
	$row_classes[] = "row align-center";
	$row_classes[] = ot_get_option('header_fullwidth', 'off') == 'on' ? 'full-width-row' : '';
	?>
	<header class="header style1">
		<div class="<?php echo implode(' ', $row_classes); ?>">
			<div class="small-6 columns hide-for-large toggle-holder">
				<a href="#" class="mobile-toggle"><i class="fa fa-bars"></i></a>
			</div>
			<div class="large-6 columns show-for-large">
				<div class="menu-holder">
					<nav id="nav">
						<?php if (has_nav_menu('nav-menu')) { ?>
						  <?php wp_nav_menu( array( 'theme_location' => 'nav-menu', 'depth' => 4, 'container' => false, 'menu_class' => 'thb-full-menu', 'walker' => new thb_MegaMenu  ) ); ?>
						<?php } ?>
					</nav>
				</div>
			</div>
			<div class="logo-holder blog-logo">
				<a href="<?php echo esc_url(home_url()); ?>" class="logolink">
					<img src="https://staging.vabienusa.com/wp-content/uploads/2018/03/Blog-Logo.png" class="logoimg bg--light" alt="<?php bloginfo('name'); ?>"/>
				</a>
			</div>
			<div class="small-6 columns account-holder">
				<?php if (has_nav_menu('acc-menu-in') && is_user_logged_in()) { ?>
				  <?php wp_nav_menu( array( 'theme_location' => 'acc-menu-in', 'depth' => 1, 'container' => false, 'menu_class' => 'secondary-menu' ) ); ?>
				<?php } else if (has_nav_menu('acc-menu-out') && !is_user_logged_in()) { ?>
					<?php wp_nav_menu( array( 'theme_location' => 'acc-menu-out', 'depth' => 1, 'container' => false, 'menu_class' => 'secondary-menu' ) ); ?>
				<?php } ?> 
				<?php do_action( 'thb_quick_search' ); ?>
				<?php do_action( 'thb_quick_cart' ); ?>
			</div>
		</div>
	</header>
	<?php
	} else {
		 get_template_part( 'inc/templates/header/header-'.ot_get_option('header_style','style1') ); 
	}
	
	?>
	<!-- End Header -->

	<div role="main">