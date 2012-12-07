<?php

	/* ======================================================================================

	This file, which is a filtered location so it's easily overridden on a theme-by-theme basis
	allows us to override the generic Chemistry class config options and javascript config 
	settings.

	Abstracted so that it will be much easier in the future - when we have more user-defined
	options, to be able to adjust these from a theme location.

	There are 2 types of options, standard and javascript-related. To overwrite (or set) an
	option, do something like:

	Chemistry::chemistry_option( 'option_name_goes_here', true );

	or

	Chemistry::chemistry_optionjs( 'js_option_nname_here', 'value_for_js_option' );

	====================================================================================== */

	//Set up some internationalization for our javascript dialogies
	Chemistry::chemistry_optionjs( 'molecule_lang', array( 

		'quit' => __( 'Close window without saving?', 'chemistry' ),
		'changes' => __( 'Lose editor changes?', 'chemistry' ),
		'sure' => __( 'Are you sure?', 'chemistry' )

	 ) );


	/* =================================================================================== */


	/**
	 * Pluggable function to initialise our template metabox, decide which CPTs to show on
	 * and to see if we specifically have any hidden types
	 *
	 * @author Richard Tape
	 * @package Chemistry
	 * @since 0.71
	 */
	
	if( !function_exists( 'chemistry_molecule_init' ) )
	{
		
		function chemistry_molecule_init()
		{

			//Here are our standard post types for WordPress
			$regular_types = array( 'post', 'page' );

			//Get a list of all registered, custom post types
			$custom_types = array_keys( get_post_types( array( '_builtin' => false, 'public' => true, 'show_ui' => true ) ) );

			//Combine the 2 arrays above to get a list
			$molecule_post_types = array_unique( array_merge( $regular_types, $custom_types ) );

			//Fetch a list of any CPTs we're told to hide from
			$molecule_hidden_types = Chemistry::get_or_set_option( 'molecule_hidden_types' );

			//Let's go ahead and work out which post types to show ourselves on
			if( isset( $molecule_hidden_types[0] ) && !empty( $molecule_hidden_types[0] ) )
			{

				$molecule_hidden_types = $molecule_hidden_types[0];

				$molecule_post_types = array_diff( array_merge( $molecule_post_types, array_keys( $molecule_hidden_types ) ), array_intersect( $molecule_post_types, array_keys( $molecule_hidden_types ) ) );

			}

			//Add our 'Content Attributes' metabox on the relevant post types
			Chemistry::add_admin_chemistry_metabox( 'layout editor', array( 'title' => __( 'Content Attributes', 'chemistry' ), 'permissions' => $molecule_post_types, 'context' => 'side' ) );

		}/* chemistry_molecule_init() */

	}/* !function_exists( 'chemistry_molecule_init' ) */

	//Let's run this during our Chemistry setup routine
	add_action( 'chemistry_setup', 'chemistry_molecule_init' );


	/* =================================================================================== */


	/**
	 * Pluggable function to load our necessary stylesheets and javascript for the front-end.
	 *
	 * @author Richard Tape
	 * @package Chemistry
	 * @since 0.7
	 */
	
	if( !function_exists( 'chemistry_molecule_header' ) )
	{

		function chemistry_molecule_header()
		{

			wp_enqueue_style( 'chemistry-molecule', Chemistry::path( 'assets/css/chemistry.css', true ), null, Chemistry::chemistry_option( 'chemistry_version' ) );

			wp_enqueue_style( 'jquery.colorbox', Chemistry::path( 'assets/css/utilitylibraries/colorbox/colorbox.css', true ), null, Chemistry::chemistry_option( 'chemistry_version' ) );

			wp_enqueue_script( 'jquery.colorbox', Chemistry::path( 'assets/js/utilitylibraries/jquery.colorbox.js', true ), array( 'jquery' ), Chemistry::chemistry_option( 'chemistry_version' ) );

			wp_enqueue_script( 'chemistry-molecule', Chemistry::path( 'assets/js/chemistry-molecule.js', true ), array( 'jquery', 'jquery.colorbox' ), Chemistry::chemistry_option( 'chemistry_version' ) );

		}/* chemistry_molecule_header() */

	}/* !function_exists( 'chemistry_molecule_header' ) */

	add_action( 'chemistry_molecule_header', 'chemistry_molecule_header' );


	/* =================================================================================== */

?>