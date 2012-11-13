<?php

	/* ================================================================================ */
	
	if( !class_exists( 'flab_message_widget' ) )
	{
	
		class flab_message_widget extends flab_builder_widget
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
				
				parent::__construct( 'message', __( 'Message' ) );
				$this->label = __( 'Message Boxes with icons for different types' );
				
			}/* __construct() */
			
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
			
				$type = 'info';
	
				if( isset( $widget['type'] ) && !empty( $widget['type'] ) )
				{
					$type = $widget['type'];
				}
	
				return '<div class="flab-widget flab-msg msg msg-'.$type.( ( isset( $widget['classes'] ) && !empty( $widget['classes'] ) ) ? ' '.$widget['classes'] : '' ).'"><span class="msg-icon"></span>'.wpautop( do_shortcode( $widget['text'] ) ).'</div>';
				
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
				
				$types = array( 
				
					'info' => __( 'Info' ),
					'warning' => __( 'Warning' ),
					'error' => __( 'Error' ),
					'download' => __( 'Download' ),
					'important-1' => __( 'Light Bulb' )
					
				 );
	
				$type_options = '';
	
				foreach ( $types as $value => $label )
				{
				
					$type_options .= '<option value="'.$value.'"'.( ( isset( $widget['type'] ) && $widget['type'] == $value ) ? ' selected="selected"' : '' ).'>'.$label.'</option>';
					
				}
	
				return '<fieldset class="flab-form">
					<label>'.__( 'Style' ).' <select'.$this->get_field_atts( 'type' ).' name="'.$this->get_field_name( 'type' ).'" value="'.( isset( $widget['type'] ) ? $widget['type'] : '' ).'">'.$type_options.'</select></label>
					<label>'.__( 'Content' ).' <abbr title="required">*</abbr><textarea'.$this->get_field_atts( 'text' ).' name="'.$this->get_field_name( 'text' ).'" rows="5">'.htmlspecialchars( isset( $widget['text'] ) ? $widget['text'] : '' ).'</textarea></label>
					<label>'.__( 'Additional classes' ).' <input type="text"'.$this->get_field_atts( 'classes' ).' name="'.$this->get_field_name( 'classes' ).'" value="'.( isset( $widget['classes'] ) ? $widget['classes'] : '' ).'" /></label>
				</fieldset>';
				
			}/* form() */
			
			/* ============================================================================ */
			
		}/* class flab_message_widget */
		
	}
	
	/* ================================================================================ */

?>