<?php

	/* ======================================================================================

	Extend - for now - our potion class to create our metabox abstract class. This allows us
	to easily load scripts and styles as well as the output and save routines for each
	metabox (just like the instances of our potions). This will soon be removed to use the
	CMB class used for the rest of our theme.

	====================================================================================== */


	if( !class_exists( 'chemistry_metabox_abstract_class' ) && class_exists( 'chemistry_potion' ) )
	{

		class chemistry_metabox_abstract_class extends chemistry_potion
		{

			/**
			 * No changes to default potion. Initialise ourselves.
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.3
			 */
			
			public function init(){}


			/* =========================================================================== */


			/**
			 * No changes to default potion, allow us to hook into the header.
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.3
			 */

			public function potion_header()
			{

				$class = self::get_class();

				echo call_user_func( array( $class, 'header' ) );

			}/* potion_header() */


			/* =========================================================================== */


			/**
			 * No changes to default potion, no header scripts needed for metaboxes
			 * This has all been taken out to the CMB class (needs removing)
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.3
			 * @todo Remove
			 */

			public function header(){}


			/* =========================================================================== */


			/**
			 * No changes to default potion, just instantiate ourselves.
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.3
			 */

			public function potion_body()
			{

				$class = self::get_class();

				echo call_user_func( array( $class, 'body' ) );

			}/* potion_body() */


			/* =========================================================================== */


			/**
			 * No changes to default potion, allow our metaboxes to output the markup
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.3
			 */

			public function body(){}


			/* =========================================================================== */


			/**
			 * No changes to default potion, load our scripts.
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.3
			 */

			public function potion_header_scripts()
			{

				$class = self::get_class();

				call_user_func( array( $class, 'scripts' ) );

			}/* potion_header_scripts */


			/* =========================================================================== */


			/**
			 * No changes to default potion, no defaults.
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.3
			 */

			public function scripts(){}


			/* =========================================================================== */


			/**
			 * No changes to default potion, output our styles
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.3
			 */

			public function potion_header_styles()
			{

				$class = self::get_class();

				call_user_func( array( $class, 'styles' ) );

			}/* potion_header_styles() */


			/* =========================================================================== */


			/**
			 * No changes to default potion, default styles
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.3
			 */

			public function styles(){}


			/* =========================================================================== */


			/**
			 * No changes to default potion, handle the reset.
			 * Not really appropriate for metaboxes.
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.3
			 * @todo remove
			 */

			public function reset(){}


			/* =========================================================================== */


			/**
			 * No changes to default potion, deal with the save routine
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.3
			 */

			public function postion_save_routine( $post_id = -1 )
			{

				$class = self::get_class();

				call_user_func_array( array( $class, 'save' ), array( $post_id ) );

			}/* postion_save_routine() */

			public function save( $post_id ){}


			/* =========================================================================== */


			/**
			 * No changes to default potion, the actual markup.
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.3
			 */

			public function potion(){}

		}/* class chemistry_metabox_abstract_class */

	}/* !class_exists( 'chemistry_metabox_abstract_class' ) && class_exists( 'chemistry_potion' ) */

?>