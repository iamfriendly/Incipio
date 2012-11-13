<?php

	/* ================================================================================ */

	if ( !class_exists( 'flab_plain_text_widget' ) )
	{
	
		class flab_plain_text_widget extends flab_builder_widget
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
			
				parent::__construct( 'plain-text', __( 'Plain text' ) );
				$this->label = __( 'Need to say something nice and quick? Some plain text will be what you want.' );
				
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
			
				if ( empty( $widget['align'] ) )
				{
					$widget['align'] = 'left';
				}
	
				$text = isset( $widget['text'] ) ? do_shortcode( $widget['text'] ) : '';
	
				if ( !isset( $widget['disable_formatting'] ) || $widget['disable_formatting'] != 'on' )
				{
					$text = wpautop( $text );
				}
	
				if ( isset( $widget['align'] ) && $widget['align'] != 'left' )
				{
					$text = '<div class="text-align'.$widget['align'].'">'.$text.'</div>';
				}
	
				return $text;
				
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
			
				$align = array( 'left' => __( 'Left' ), 'right' => __( 'Right' ), 'center' => __( 'Center' ) );
	
				$align_options = '';
	
				foreach ( $align as $value => $label )
				{
					$align_options .= '<option value="'.$value.'"'.( ( isset( $widget['align'] ) && $widget['align'] == $value ) ? ' selected="selected"' : '' ).'>'.$label.'</option>';
				}
	
				return '<fieldset class="flab-form">
					<label>'.__( 'Alignment' ).' <select'.$this->get_field_atts( 'align' ).' name="'.$this->get_field_name( 'align' ).'" value="'.( isset( $widget['align'] ) ? $widget['align'] : '' ).'">'.$align_options.'</select></label>
					<label><input type="checkbox"'.$this->get_field_atts( 'disable_formatting' ).' name="'.$this->get_field_name( 'disable_formatting' ).'"'.( ( isset( $widget['disable_formatting'] ) && $widget['disable_formatting'] == 'on' ) ? ' checked="checked"' : '' ).' /> '.__( 'Remove default WordPress formating?' ).'</label>
					<label>'.__( 'Your Content' ).' <textarea'.$this->get_field_atts( 'text' ).' name="'.$this->get_field_name( 'text' ).'" rows="5">'.htmlspecialchars( isset( $widget['text'] ) ? $widget['text'] : '' ).'</textarea></label>
				</fieldset>';
				
			}/* form() */
			
			/* ============================================================================ */
			
		}/* class flab_plain_text_widget */
		
	}

	/* ================================================================================ */

?>