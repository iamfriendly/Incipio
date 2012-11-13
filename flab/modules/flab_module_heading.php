<?php

	/* ================================================================================ */
	
	if( !class_exists( 'flab_heading_widget' ) )
	{
	
		class flab_heading_widget extends flab_builder_widget
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
				
				parent::__construct( 'heading', __( 'Heading' ) );
				$this->label = __( 'Need a header? Get them here!' );
				
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
				
				if( isset( $widget['title'] ) && !empty( $widget['title'] ) )
				{
					$title = $widget['title'];
				}
				else
				{
					$title = get_the_title( flab::flab_get_current_id( ) );
					flab::config( 'hide_title', TRUE );
				}
	
				$type = 'h2';
	
				if( isset( $widget['type'] ) && !empty( $widget['type'] ) )
				{
					$type = $widget['type'];
				}
	
				return '<'.$type.( ( isset( $widget['classes'] ) && !empty( $widget['classes'] ) ) ? ' class="'.$widget['classes'].'"' : '' ).'>'.( isset( $widget['title'] ) ? $widget['title'] : '' ).'</'.$type.'>';
				
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
				
				$headings = array( 
				
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6'
					
				 );
	
				$type_options = '';
	
				foreach ( $headings as $value => $label )
				{
					$type_options .= '<option value="'.$value.'"'.( ( isset( $widget['type'] ) && $widget['type'] == $value ) ? ' selected="selected"' : '' ).'>'.$label.'</option>';
				}
	
				return '<fieldset class="flab-form">
					<label>'.__( 'Heading Text' ).' <input type="text"'.$this->get_field_atts( 'title' ).' name="'.$this->get_field_name( 'title' ).'" value="'.( isset( $widget['title'] ) ? $widget['title'] : '' ).'" /></label>
					<label>'.__( 'Type' ).' <select'.$this->get_field_atts( 'type' ).' name="'.$this->get_field_name( 'type' ).'" value="'.( isset( $widget['type'] ) ? $widget['type'] : '' ).'">'.$type_options.'</select></label>
					<label>'.__( 'Additional classes' ).' <input type="text"'.$this->get_field_atts( 'classes' ).' name="'.$this->get_field_name( 'classes' ).'" value="'.( isset( $widget['classes'] ) ? $widget['classes'] : '' ).'" /></label>
				</fieldset>';
				
			}/* form() */
			
			/* ============================================================================ */
			
		}/* class flab_heading_widget */
		
	}
	
	/* ================================================================================ */

?>