<?php

	 /* ================================================================================ */

	flab::import( 'admin.flab.metabox.builder' );
	
	/* ================================================================================ */
	
	if ( !class_exists( 'flab_metabox_builder_post' ) )
	{
	
		class flab_metabox_builder_post extends flab_metabox
		{
		
			/**
			 * Initialise our metaboxes
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
		
			public static function init()
			{
				flab_metabox_builder::init();
			}/* init() */
			
			/* =========================================================================== */
	
			/**
			 * Run our headers
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function header()
			{
				flab_metabox_builder::header();
			}/* header() */
			
			/* =========================================================================== */
	
			/**
			 * Run our save actions
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function save()
			{
				flab_metabox_builder::save();
			}/* save() */
			
			/* =========================================================================== */
	
			/**
			 * Output our body content
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function body()
			{
	
				return '<p></p>';
				
			}/* body() */
			
			/* =========================================================================== */
			
		}/* class flab_metabox_builder_post */
		
	}
	
	 /* ================================================================================ */

?>
