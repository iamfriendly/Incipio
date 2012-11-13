<?php

	/* ================================================================================ */
	
	if( !class_exists( 'flab_contact_form_widget' ) )
	{
	
		class flab_contact_form_widget extends flab_builder_widget
		{
		
			/**
			 * Set up the title and description
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
		
			public function __construct( )
			{
				
				parent::__construct( 'contact-form', __( 'Contact Form' ) );
				$this->label = __( 'Insert your contact form. Set everything up in your Theme Options Panel under "Contact Form"' );
				
			}/* __construct */
			
			/* ============================================================================ */
	
			/**
			 * Output the contents of this widget
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public function widget( $widget )
			{
			
				return do_shortcode( "[CONTACT_FORM]" );
				
			}/* widget() */
			
			/* ============================================================================ */
	
			/**
			 * The options for this widget
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public function form( $widget )
			{
			
				return '<p>'.__( 'Insert your contact form.' ).'</p>
				<fieldset class="flab-form">
				</fieldset>';
				
			}/* form */
			
			/* ============================================================================ */
			
		}/* class flab_contact_form_widget */
		
	}
	
	/* ================================================================================ */

?>