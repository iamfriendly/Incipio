<?php

	/* ================================================================================ */
	
	/*
	
		Set up all of our widgets
	
	*/

	/* ================================================================================ */

	/**
	 * Include the helper funtions for our modules
	 *
	 * @package FLAB
	 * @author iamfriendly
	 * @version 1.0
	 * @since 1.0
	 */
	 
	include( 'flab_module_helper_functions.php' );
	
	/* ================================================================================ */

	/**
	 * Include the widgets we need for this theme
	 *
	 * @package FLAB
	 * @author iamfriendly
	 * @version 1.0
	 * @since 1.0
	 */
	 
	 $array_of_modules_for_theme = array(
	 
	 	'plain_text',
	 	'rich_text',
	 	'html',
	 	'heading',
	 	'image',
	 	'content',
	 	'content_as_row',
	 	'divider',
	 	'message_box',
	 	'blockquote',
	 	'lists',
	 	'button',
	 	'contact_form',
	 	'table',
	 	'tabs',
	 	'accordion',
	 	'gallery',
	 	'testimonials',
	 	'twitter',
	 	'flickr',
	 	'nivo',
	 	'googlemap',
	 	'row_setup',
	 	'rows'
	 
	 );
	 
	 foreach( $array_of_modules_for_theme as $module )
	 {
	 	
	 	$file_name = "flab_module_" . $module . ".php";
	 	include( $file_name );
	 	
	 }
	 
	 /* ============================================================================ */
	
	
	/**
	 * Now actually register those modules. First, do the columns
	 *
	 * @package FLAB
	 * @author iamfriendly
	 * @version 1.0
	 * @since 1.0
	 */
	 
	 $class_names_of_modules_to_register = array(
	 
	 	'flab_row2_widget',
	 	'flab_row3_widget',
	 	'flab_row4_widget',
	 	'flab_row5_widget',
	 	'flab_row6_widget',
	 	'flab_row2d3_1_widget',
	 	'flab_row2d3_2_widget',
	 	'flab_row3d4_1_widget',
	 	'flab_row3d4_2_widget',
	 	'flab_row2d4_1_widget',
	 	'flab_row2d4_2_widget',
	 	'flab_row2d4_3_widget',
	 	'flab_post_content_widget',
	 	'flab_divider_widget',
	 	'flab_image_widget',
	 	'flab_plain_text_widget',
	 	'flab_rich_text_widget',
	 	'flab_html_widget',
	 	'flab_heading_widget',
	 	'flab_message_widget',
	 	'flab_blockquote_widget',
	 	'flab_list_widget',
	 	'flab_contact_form_widget',
	 	'flab_button_widget',
	 	'flab_gallery_widget',
	 	'flab_testimonials_widget',
	 	'flab_table_widget',
	 	'flab_twitter_feed_widget',
	 	'flab_flickr_feed_widget',
	 	'flab_tabs_widget',
	 	'flab_accordion_widget',
	 	'flab_nivo_widget',
	 	'flab_googlemap_widget',
	 	'flab_content_as_row_widget'
	 
	 );
	 
	 foreach( $class_names_of_modules_to_register as $module_class )
	 {
	 	flab_builder::register_widget( $module_class );
	 }
	 
	  /* ============================================================================ */
	

?>