<?php

/* ================================================================================ */

	/*
		Main loader file for our Friendly Layout Builder (FLAB)
	*/
	
	include( 'flab_functions.php' );

/* ================================================================================ */

	/**
	 * Include the main class if we haven't already been initialised
	 *
	 * @package FLAB
	 * @author iamfriendly
	 * @version 1.0
	 * @since 1.0
	 */

	if ( !class_exists( 'flab_generic' ) )
	{
	
		include( 'flab.php' );
		
	}
	
/* ================================================================================ */
	
	/**
	 * Initialise
	 *
	 * @package FLAB
	 * @author iamfriendly
	 * @version 1.0
	 * @since 1.0
	 */
	
	if ( !class_exists( 'flab' ) )
	{
		
		class flab extends flab_generic
		{
		
		}/* class flab */
		
	}
	
/* ================================================================================ */
	
	/**
	 * Set up some config
	 *
	 * @package FLAB
	 * @author iamfriendly
	 * @version 1.0
	 * @since 1.0
	 */
	
	flab::init();
	
	flab::flab_load_module( 'builder' );
	
/* ================================================================================ */

	/**
	 * Add the necessary filters and actions to we can actually do what we need to do
	 *
	 * @package FLAB
	 * @author iamfriendly
	 * @version 1.0
	 * @since 1.0
	 */
	 
	flab_add_filters_and_actions();
	
/* ================================================================================ */

?>
