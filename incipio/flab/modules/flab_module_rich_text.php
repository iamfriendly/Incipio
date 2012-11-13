<?php

	/* ================================================================================ */
	
	if( !class_exists( 'flab_rich_text_widget' ) )
	{
	
		class flab_rich_text_widget extends flab_builder_widget
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
				
				parent::__construct( 'rich-text', __( 'Rich text' ) );
				$this->label = __( 'Need rich text with font styling, colours etc? Just like the visual editor' );
				
			}
			
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
				return wpautop( do_shortcode( $widget['text'] ) );
			}
			
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
			
				return '<fieldset class="flab-form"><div class="wp-editor-wrap"><div class="wp-editor-container">
<textarea'.$this->get_field_atts( 'text' ).' name="'.$this->get_field_name( 'text' ).'" id="'.$this->get_field_name( 'text' ).'" cols="15" class="tinymce flabtinymce">'.( isset( $widget['text'] ) ? wpautop( $widget['text'] ) : '' ).'</textarea></div></div></fieldset>';
				
			}
			
			/* ============================================================================ */
			
		}
		
	}
	
	/* ================================================================================ */

?>