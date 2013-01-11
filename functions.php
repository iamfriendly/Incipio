<?php

	/* ======================================================================================

	All of our theme use our Incipio framework. We instantiate that class below. We load
	our framework functions in the loader() function and we also add default theme support.

	You can override what your theme supports in a file called theme_supports.php in a
	dropins/ directory (in your child theme, naturally). 

	====================================================================================== */


	if( !class_exists( 'Incipio' ) ) :


		class Incipio
		{


			/**
			 * Initialise ourselves with actions and filters to load all the parts to the
			 * Incipio framework
			 *
			 * @author Richard Tape
			 * @package Incipio
			 * @since 1.0
			 * @param None
			 * @return None
			 */
			
			function __construct()
			{

				//Define constants for our theme, framework, paths etc.
				add_action( 'after_setup_theme', array( &$this, 'constants' ), 1 );

				//Define what this theme supports
				add_action( 'after_setup_theme', array( &$this, 'theme_supports' ), 2 );

				//Define theme image sizes
				add_action( 'after_setup_theme', array( &$this, 'image_sizes' ), 3 );

				//Load our loading script which then goes on to load all our necessary files and functions
				add_action( 'after_setup_theme', array( &$this, 'loader' ), 4 );

				//Handle internationalisation
				add_action( 'after_setup_theme', array( &$this, 'internationalisation' ), 5 );

				//Theme update routine
				add_action( 'after_setup_theme', array( &$this, 'theme_update' ), 6 );

			}/* __construct() */


			/* =================================================================================== */


			/**
			 * Define all of the constants for our theme, framework including 
			 * version numbers, paths, etc.
			 *
			 * @author Richard Tape
			 * @package Incipio
			 * @since 1.0
			 * @param None
			 * @return None
			 */
			
			function constants()
			{

				//Define our framework version
				define( 'INCIPIO_VERSION', '1.0' );

				//Define theme-related constants with support for WP < 3.4
				if( function_exists( 'wp_get_theme' ) )
				{

					//We're using WordPress version 3.4+
					$theme_data = wp_get_theme();

					if( !defined( 'THEMENAME' ) )
						define( 'THEMENAME', $theme_data->Template );
					
					if( !defined( 'THEMEVERSION' ) )
						define( 'THEMEVERSION', $theme_data->Version );

				}
				else
				{

					//We're using WP < 3.4 so don't have access to the wp_get_theme class
					$theme_data = get_theme_data( get_template_directory() . '/style.css' );

					if( !defined( 'THEMENAME' ) )
						define( 'THEMENAME', $theme_data['Template'] );
					
					if( !defined( 'THEMEVERSION' ) )
						define( 'THEMEVERSION', $theme_data['Version'] );

				}

			}/* constants() */


			/* =================================================================================== */


			/**
			 * Define what our theme supports. First we check for the existence of a file in the
			 * dropins/ folder called 'theme_supports.php'. If that doesn't exist, we use the
			 * defaults
			 *
			 * @author Richard Tape
			 * @package Incipio
			 * @since 1.0
			 * @param None
			 * @return None
			 */
			
			function theme_supports()
			{

				if( file_exists( locate_template( '/dropins/theme_supports.php', false  ) ) )
				{

					//There's a file for this theme which dictates what this theme supports. Use it.
					include locate_template( '/dropins/theme_supports.php' );

				}
				else
				{

					//There's no specific file, so use defaults
					add_theme_support( 'theme-options' );

					add_theme_support( 'post-formats', array( 'aside', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat', 'gallery' ) );

					add_theme_support( 'post-thumbnails', array( 'post', 'page' ) );

					add_theme_support( 'automatic-feed-links' );

				}

			}/* theme_supports() */


			/* =================================================================================== */


			/**
			 * Set up the image sizes for this theme. To do that you load a file in /dropins/ called
			 * theme_image_sizes.php
			 *
			 * @author Richard Tape
			 * @package Incipio
			 * @since 1.0
			 * @param None
			 * @return None
			 */
			
			function image_sizes()
			{

				if( file_exists( locate_template( '/dropins/theme_image_sizes.php', false  ) ) )
				{

					//There's a file for this theme which dictates theme image sizes.
					include locate_template( '/dropins/theme_image_sizes.php' );

				}

			}/* image_sizes() */


			/**
			 * Load our necessary functions, files, etc.
			 *
			 * @author Richard Tape
			 * @package Incipio
			 * @since 1.0
			 * @param None
			 * @return None
			 */

			function loader()
			{

				require_once locate_template( '/framework/loader.php' );

			}/* loader() */


			/* =================================================================================== */


			/**
			 * Handle internationalisation for this theme by loadinf the locale.php file in the
			 * /languages/ directory if it exists.
			 *
			 * @author Richard Tape
			 * @package Incipio
			 * @since 1.0
			 * @param None
			 * @return None
			 */
			
			function internationalisation()
			{

				load_theme_textdomain( THEMENAME, get_template_directory() . '/languages' );
	
				$locale = get_locale();
				$locale_file = "/languages/$locale.php";
				if ( is_readable( $locale_file ) )
					require_once locate_template( $locale_file );

			}/* internationalisation() */


			/* =================================================================================== */


			/**
			 * Theme update routine from Envato
			 *
			 * @author Richard Tape
			 * @package Incipio
			 * @since 1.0
			 * @param None
			 * @return None
			 */
			
			function theme_update()
			{

				require_once locate_template( '/framework/admin/update/update-check.php' );

			}/* theme_update() */
			


		}/* class Incipio */


	endif;

	$Incipio = new Incipio;


?>