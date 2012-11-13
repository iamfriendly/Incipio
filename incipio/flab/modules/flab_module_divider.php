<?php

	/* ================================================================================ */
	
	if( !class_exists( 'flab_divider_widget' ) )
	{
	
		class flab_divider_widget extends flab_builder_widget
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
				
				parent::__construct( 'divider', __( 'Horizontal Rule' ) );
				$this->label = __( 'A horizontal rule' );
				
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
			
				$extra_class = ( isset( $widget['classes'] ) && !empty( $widget['classes'] ) ) ? $widget['classes'] : '';

				return '<hr'.( ( isset( $widget['clear'] ) && $widget['clear'] == 'on' ) ? ' class="flab-clear ' . $extra_class .  '"' : ' class="' . $extra_class . '"' ).' />';
				
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
			
				return '<p>'.__( 'Horizontal rule for dividing separate sections.' ).'</p>
				<fieldset class="flab-form">
					<label><input type="checkbox"'.$this->get_field_atts( 'clear' ).' name="'.$this->get_field_name( 'clear' ).'"'.( ( isset( $widget['clear'] ) && $widget['clear'] == 'on' ) ? ' checked="checked"' : '' ).' /> '.__( 'Clear divider' ).' </label>
					<label>'.__( 'Additional classes' ).' <input type="text"'.$this->get_field_atts( 'classes' ).' name="'.$this->get_field_name( 'classes' ).'" value="'.( isset( $widget['classes'] ) ? $widget['classes'] : '' ).'" /></label>
				</fieldset>';
				
			}/* form */
			
			/* ============================================================================ */
			
		}/* class flab_divider_widget */
		
	}
	
	/* ================================================================================ */

?>