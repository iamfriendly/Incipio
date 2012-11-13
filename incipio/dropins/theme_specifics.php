<?php

	/* ======================================================================================

	Our theme framework is as abstracted as possible. This means that we're able to drop it 
	in to most themes with ease. To allow that, all of the theme-specific items such as
	sidebars, menus etc. are loaded separately from the framework (which makes sense if you
	think about it).

	This file does exactly that - it loads the theme-specific items. It is called by the
	loader.php file (which checks for its existence first).

	====================================================================================== */

	/**
	 * Load our custom theme options, if that file exists
	 *
	 * @author Richard Tape
	 * @package Autify
	 * @since 1.0
	 */

	if( file_exists( locate_template( '/dropins/options.php', false  ) ) ) :
		
		require_once locate_template( '/dropins/options.php' );
	
	endif;



	/**
	 * Load our custom theme sidebars, if that file exists
	 *
	 * @author Richard Tape
	 * @package Autify
	 * @since 1.0
	 */

	if( file_exists( locate_template( '/dropins/sidebars.php', false  ) ) ) :
		
		require_once locate_template( '/dropins/sidebars.php' );
	
	endif;



	/**
	 * Load our custom theme menus, if that file exists
	 *
	 * @author Richard Tape
	 * @package Autify
	 * @since 1.0
	 */

	if( file_exists( locate_template( '/dropins/menus.php', false  ) ) ) :
		
		require_once locate_template( '/dropins/menus.php' );
	
	endif;


?>