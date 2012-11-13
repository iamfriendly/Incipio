<?php

	/* ================================================================================ */
	
	if( !class_exists( 'flab_blockquote_widget' ) )
	{
	
		class flab_blockquote_widget extends flab_builder_widget
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
				
				parent::__construct( 'blockquote', __( 'Blockquote' ) );
				$this->label = __( 'Got a quote you want to emphasises? Use this!' );
				
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
			
				return '<blockquote class="flab-widget flab-blockquote ' . ( ( isset( $widget['style'] ) && !empty( $widget['style'] ) ) ? 'flab-blockquote-' . $widget['style'] : '' ) . ( ( isset( $widget['classes'] ) && !empty( $widget['classes'] ) ) ? ' ' . $widget['classes'] : '' ) . '">' . wpautop( do_shortcode( $widget['text'] ) ) . '</blockquote>';
				
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
			
				$styles = array(
				
					'' => __( 'Theme default' ),
					'1' => __( 'Style 1' ),
					'2' => __( 'Style 2' )
					
				 );
	
				$style_options = '';
	
				foreach ( $styles as $value => $label )
				{
					
					$style_options  .= '<option value="' . $value . '"' . ( ( isset( $widget['style'] ) && $widget['style'] == $value ) ? ' selected="selected"' : '' ) . '>' . $label . '</option>';
					
				}
	
				return '<fieldset class="flab-form">
					<label>' . __( 'Style' ) . ' <select' . $this->get_field_atts( 'style' ) . ' name="' . $this->get_field_name( 'style' ) . '" value="' . ( isset( $widget['style'] ) ? $widget['style'] : '' ) . '">' . $style_options . '</select></label>
					<label>' . __( 'Text' ) . ' <abbr title="required">*</abbr><textarea' . $this->get_field_atts( 'text' ) . ' name="' . $this->get_field_name( 'text' ) . '" rows="5">' . htmlspecialchars( isset( $widget['text'] ) ? $widget['text'] : '' ) . '</textarea></label>
					<label>' . __( 'Additional classes' ) . ' <input type="text"' . $this->get_field_atts( 'classes' ) . ' name="' . $this->get_field_name( 'classes' ) . '" value="' . ( isset( $widget['classes'] ) ? $widget['classes'] : '' ) . '" /></label>
				</fieldset>';
				
			}/* form() */
			
			/* ============================================================================ */
			
		}/* class flab_blockquote_widget */
		
	}
	
	/* ================================================================================ */

?>