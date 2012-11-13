<?php

	/* ================================================================================ */
	
	if( !class_exists( 'flab_googlemap_widget' ) )
	{
	
		class flab_googlemap_widget extends flab_builder_widget
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
				
				parent::__construct( 'googlemap', __( 'Google map' ) );
				$this->label = __( 'Insert a google map with a specified title and location' );
				
			}/* __constrcut() */
			
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
			
				if( !isset( $widget['zoom'] ) )
				{
					$widget['zoom'] = 14;
				}
	
				if( !isset( $widget['view'] ) )
				{
					$widget['view'] = 0;
				}
	
				if( !isset( $widget['show_address'] ) )
				{
					$widget['show_address'] = false;
				}
	
				return '<div class="flab-widget flab-google-map'.( ( isset( $widget['classes'] ) && !empty( $widget['classes'] ) ) ? ' '.$widget['classes'] : '' ).'">'.flab::flab_google_map( $widget['address'], '100%', empty( $widget['height'] ) ? NULL : intval( $widget['height'] ), $widget['zoom'], $widget['view'], ( $widget['show_address'] != 'on' ), true ).'</div>';
				
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
				
				$zoom_options = '<option value="">'.__( 'Default' ).'</option>';
	
				for ( $i = 1; $i <= 20; $i++ )
				{
					$zoom_options .= '<option value="'.$i.'"'.( ( isset( $widget['zoom'] ) && $widget['zoom'] == $i ) ? ' selected="selected"' : '' ).'>'.$i.( $i == 14 ? ' ( '.__( 'Default' ).' )' : '' ).'</option>';
				}
	
				$views = array( 
				
					__( 'Map' ),
					__( 'Satellite' ),
					__( 'Map &amp; Terrain' )
					
				 );
	
				$view_options = '';
	
				foreach ( $views as $value => $label )
				{
					$view_options .= '<option value="'.$value.'"'.( ( isset( $widget['view'] ) && $widget['view'] == $value ) ? ' selected="selected"' : '' ).'>'.$label.'</option>';
				}
	
				return '<fieldset class="flab-form">
					<label>'.__( 'Address' ).' <input type="text"'.$this->get_field_atts( 'address' ).' name="'.$this->get_field_name( 'address' ).'" value="'.( isset( $widget['address'] ) ? $widget['address'] : '' ).'" /></label>
					<div class="cols-2">
						<div class="col">
							<label>'.__( 'Height' ).' <input type="text"'.$this->get_field_atts( 'height' ).' name="'.$this->get_field_name( 'height' ).'" value="'.( isset( $widget['height'] ) ? intval( $widget['height'] ) : '' ).'" /></label>
						</div>
						<div class="col">
							<label>'.__( 'Zoom' ).' <select'.$this->get_field_atts( 'zoom' ).' name="'.$this->get_field_name( 'zoom' ).'" value="'.( isset( $widget['zoom'] ) ? $widget['zoom'] : '' ).'">'.$zoom_options.'</select></label>
						</div>
						<div class="col">
							<label>'.__( 'View' ).' <select'.$this->get_field_atts( 'view' ).' name="'.$this->get_field_name( 'view' ).'" value="'.( isset( $widget['view'] ) ? $widget['view'] : '' ).'">'.$view_options.'</select></label>
						</div>
			
					</div>
					<label>'.__( 'Additional classes' ).' <input type="text"'.$this->get_field_atts( 'classes' ).' name="'.$this->get_field_name( 'classes' ).'" value="'.( isset( $widget['classes'] ) ? $widget['classes'] : '' ).'" /></label>
				</fieldset>';
				
			}/* form() */
			
			/* ============================================================================ */
			
		}/* class flab_googlemap_widget */
		
	}
	
	/* ================================================================================ */

?>