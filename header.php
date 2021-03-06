<?php

	/* ======================================================================================

	This file is used on every page load of your theme (when someone is visiting the front-end).
	It contains the <head> part of your site (so most things for SEO) as well as loads in the 
	necessary parts of the framework. We load *some* js here through necessity but we load as 
	much as we can in the footer so the page loads more quickly for people. You'll find the 
	loading of the js/css in /framework/framework_functions.php

	====================================================================================== */

?>

<!DOCTYPE html> 

	<!--[if lt IE 7 ]> <html <?php language_attributes(); ?> class="no-js ie6"> <![endif]-->
	<!--[if IE 7 ]>    <html <?php language_attributes(); ?> class="no-js ie7"> <![endif]-->
	<!--[if IE 8 ]>    <html <?php language_attributes(); ?> class="no-js ie8"> <![endif]-->
	<!--[if IE 9 ]>    <html <?php language_attributes(); ?> class="no-js ie9"> <![endif]-->
	<!--[if (gt IE 9)|!(IE)]><!--> <html <?php language_attributes(); ?> class="no-js"> <!--<![endif]-->

	<head> 

		<?php /* ============================================================================= */ ?>

		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		
		<?php /* ============================================================================= */ ?>
		
		<link rel="profile" href="http://gmpg.org/xfn/11" />
		
		<title><?php wp_title(); ?></title>
		
		<?php /* ============================================================================= */ ?>
		
		<?php wp_head(); ?>
		
		<script>window.jQuery || document.write( '<script src="<?php echo get_template_directory_uri(); ?>/assets/js/jquery.js">\x3C/script>' )</script>
		
		<?php /* ============================================================================= */ ?>

	</head>

	<body <?php body_class(); ?>>

		<?php

			/* ==============================================================================

			The <header> tag for our theme is output using the following actions. The opening
			is output is hooked into incipio_after_open_body_tag

			These are in /dropins/theme_functions.php

			============================================================================== */

		?>

		<?php do_action( 'incipio_after_open_body_tag' ); ?>