<?php

	/* ======================================================================================

	This file acts as a 'loader' for the rest of the framework. The vast majority, you won't
	want to touch however, for quick and easy adjustments as to what the theme can do on the 
	back-end then you can simply (un)comment the theme support calls below.

	theme-options
	layout-builder
	maintenance-page
	holding-page

	====================================================================================== */

	add_theme_support( 'theme-options' );
	add_theme_support( 'layout-builder' );

	//This is immediately removed if the WP SEO plugin is installed (see loader.php)
	add_theme_support( 'incipio-seo' );

	//We register theme support for post types. These are the ones that are shown by the themeistsposttype
	//plugin when that is activated
	add_theme_support( 'custom-post-types', array( 'project' ) );

	//Also add support for taxonomies, similar to above
	add_theme_support( 'custom-taxonomies', array( 'project' => array( 'clients' ) ) );

	add_theme_support( 'post-formats', array( 'aside', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat', 'gallery' ) );
	add_theme_support( 'post-thumbnails', array( 'post', 'page' ) );
	add_theme_support( 'automatic-feed-links' );

	add_theme_support( 'help-in-options-panel' );

	//add_image_size( 'test-image-size', 300, 9999 ); //300 pixels wide ( and unlimited height )
	//add_image_size( 'test-size-2', 220, 180, true ); //( cropped )


	/* =================================================================================== */


	/**
	 * We need to run this through after_setup_theme so that we can register support for widgets
	 *
	 * @author Richard Tape
	 * @package 
	 * @since 1.0
	 * @param 
	 * @return 
	 */

	if( !function_exists( 'incipio_theme_supports' ) ) :
	
		function incipio_theme_supports()
		{

			//Also add support for our custom widgets
			add_theme_support(	'custom-widgets', 
			                  	array(
			                  		'call-to-action-row', 
			                  		'image-widget', 
			                  		'latest-blog-posts', 
			                  		'quick-flickr-widget'
			                  	)
			);

		}/* incipio_theme_supports */

	endif;

	add_action( 'after_setup_theme', 'incipio_theme_supports' );


	/* ======================================================================================

	It's unlikely you'll need to edit below. Any amendments you make should be made in a child
	theme. If you're unsure how to do that, check out http://codex.wordpress.org/Child_Themes

	====================================================================================== */

	/**
	 * Set up some constants that are used throughout the theme
	 *
	 * @author Richard Tape
	 * @package Incipio
	 * @since 1.0
	 */

	$theme_data = wp_get_theme();
	define( 'THEMENAME', $theme_data->Template );
	define( 'THEMEVERSION', $theme_data->Version );


	/**
	 * Now we have set what this theme supports we need to load the core framework files
	 *
	 * @author Richard Tape
	 * @package Incipio
	 * @since 1.0
	 */
	

	require_once locate_template( '/loader.php' );


	/**
	 * Handle internationalisation
	 *
	 * @author Richard Tape
	 * @package Incipio
	 * @since 1.0
	 */

	load_theme_textdomain( THEMENAME, get_template_directory() . '/languages' );
	
	$locale = get_locale();
	$locale_file = "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once locate_template( $locale_file );



?>