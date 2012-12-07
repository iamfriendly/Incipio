<?php

	/* ======================================================================================

	This file is simply a loader for all of our other components based on what this theme 
	supports (which is set in functions.php). It's aim is to keep functions.php as neat and 
	tidy as possible and compartmentalise the framework from the actual theme.

	Our framework is made up of 4 components; A theme options framework to enable us and you
	to easily add options to your theme, A metabox class to make it easy for us to give you
	a great UI for your custom and built-in post types; our LESS-based front-end frmaework
	for delivering great cross-browser, semantic code for your website and finally, Chemistry,
	our amazingly powerful content builder which makes creating amazing layouts a cinch.

	====================================================================================== */


	/**
	 * Loads the metabox classes to allow us to make custom metaboxes nice and easily
	 *
	 * @author Richard Tape
	 * @package Incipio
	 * @since 1.0
	 */

	require_once locate_template( '/framework/admin/metaboxes/custom-meta-boxes.php' );


	/**
	 * Loads the Options Panel
	 *
	 * If you're loading from a child theme use stylesheet_directory
	 * instead of template_directory
	 *
	 * @author Richard Tape
	 * @package 
	 * @since 1.0
	 */

	if( current_theme_supports( 'theme-options' ) ) :
	 

		if( !function_exists( 'optionsframework_init' ) )
		{

			define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/framework/admin/inc/' );
			
			require_once locate_template( '/framework/admin/inc/options-framework.php' );

		}


	endif;

	/**
	 * Load the Layout Builder if the current theme supports it
	 *
	 * @author Richard Tape
	 * @package 
	 * @since 1.0
	 */

	if( current_theme_supports( 'layout-builder' ) ) :

		require_once locate_template( '/framework/chemistry/chemistry.php' );

	endif;



	/**
	 * Load our custom theme-specific options, menus, sidebars etc.
	 *
	 * @author Richard Tape
	 * @package Incipio
	 * @since 1.0
	 */

	if( file_exists( locate_template( '/dropins/theme_specifics.php', false  ) ) ) :
		
		require_once locate_template( '/dropins/theme_specifics.php' );
	
	endif;
	


	/**
	 * Load the options panel contact form
	 *
	 * @author Richard Tape
	 * @package 
	 * @since 1.0
	 * @param 
	 * @return 
	 */
	

	require_once locate_template( '/framework/admin/inc/contact-form.php' );



	/**
	 * Load our generic framework functions and capabilities
	 *
	 * @author Richard Tape
	 * @package Incipio
	 * @since 1.0
	 */

	require_once locate_template( '/framework/framework_functions.php' );



	/**
	 * Load our specific theme functions and capabilities
	 *
	 * @author Richard Tape
	 * @package Incipio
	 * @since 1.0
	 */

	require_once locate_template( '/dropins/theme_functions.php' );



	/**
	 * If the WPSEO plugin is installed we remove some options from the options panel
	 *
	 * @author Richard Tape
	 * @package Incipio
	 * @since 1.0
	 */

	if ( defined( 'WPSEO_VERSION' ) )
		remove_theme_support( 'incipio-seo' );
	


?>