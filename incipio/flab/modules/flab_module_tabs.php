<?php

	/* ================================================================================ */
	
	if( !class_exists( 'flab_tabs_widget' ) )
	{
	
		class flab_tabs_widget extends flab_builder_widget
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
				
				parent::__construct( 'tabs', __( 'Tabs' ) );
				$this->label = __( 'Some jQuery tabs to allow you to split your content.' );
				
			}/* __construct() */
			
			/* ============================================================================ */
			
			/**
			 * Uglifies a string. Remove underscores and lower strings
			 *
			 * @param string $string
			 * @return string
			 *
			 * @author Gijs Jorissen
			 * @since 0.1
			 *
			 */
			 
			static function uglify( $string )
			{
				return strtolower( preg_replace( '/[^A-z0-9]/', '_', $string ) );
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
				
				if( empty( $widget['type'] ) )
				{
					$widget['type'] = 'horizontal';
				}
				
				$count = count( $widget['tabs_content'] );
				
				$output = '<dl class="tabs contained ' . $widget['type'] . '">';
				
					for ( $i = 0; $i < $count; $i++ )
					{
					
						if( !empty( $widget['tabs_title'][$i] ) || !empty( $widget['tabs_content'][$i] ) )
						{
							
							$output .= '<dd class="' . ( ( !empty( $widget['current'] ) && $widget['current'] == $i ) ? 'active' : '' ) . '">';
								$output .= '<a href="#' . $this->uglify( $widget['tabs_title'][$i] ) . '">' . $widget['tabs_title'][$i] . '</a>';
							$output .= '</dd>';
							
						}
						
					}
				
				$output .= '</dl>';
				
				
				
				$output .= '<ul class="tabs-content contained">';
				
					for ( $i = 0; $i < $count; $i++ )
					{
					
						if( !empty( $widget['tabs_title'][$i] ) || !empty( $widget['tabs_content'][$i] ) )
						{
							
							$output .= '<li id="' . $this->uglify( $widget['tabs_title'][$i] ) . 'Tab">';
								$output .= wpautop( do_shortcode( $widget['tabs_content'][$i] ) );
							$output .= '</li>';
							
						}
						
					}
				
				$output .= '</ul>';
	
				return $output;
				
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
					
					'horizontal' => __( 'Horizontal' ),
					'vertical' => __( 'Vertical' )
					
				 );
	
				$current = array( 
				
					'' => __( 'None' )
					
				 );
	
				$type_options = '';
				$current_options = '';
	
				$type_options = '';
	
				foreach ( $types as $value => $label )
				{
					$type_options .= '<option value="'.$value.'"'.( ( isset( $widget['type'] ) && $widget['type'] == $value ) ? ' selected="selected"' : '' ).'>'.$label.'</option>';
				}
	
				foreach ( $current as $value => $label )
				{
					$current_options = '<option value="'.$value.'"'.( ( isset( $widget['current'] ) && $widget['current'] == $value ) ? ' selected="selected"' : '' ).'>'.$label.'</option>';
				}
	
				$count = 1;
	
				if( isset( $widget['tabs_content'] ) && count( $widget['tabs_content'] ) > 0 )
				{
					
					$count = 0;
	
					for ( $i = 0; $i < count( $widget['tabs_content'] ); $i++ )
					{
						if( !empty( $widget['tabs_title'][$i] ) || !empty( $widget['tabs_content'][$i] ) )
						{
							$count++;
						}
						
					}
					
				}
	
	
				if( $count > 1 )
				{
					
					for ( $i = 1; $i <= $count; $i++ )
					{
						$current_options .= '<option value="'.$i.'"'.( ( isset( $widget['current'] ) && $widget['current'] == $i ) ? ' selected="selected"' : '' ).'>'.$i.'</option>';
					}
					
				}
	
				$tabs = '';
	
				$tabs .= '<div class="col" style="display: none;"><div class="group-item">
					<label>'.__( 'Title' ).' <input type="text"'.$this->get_field_atts( 'tabs_title][' ).' name="'.$this->get_field_name( 'tabs_title][' ).'" value="" /></label>
					<label>'.__( 'Content' ).' <textarea'.$this->get_field_atts( 'tabs_content][' ).' name="'.$this->get_field_name( 'tabs_content][' ).'" rows="5"></textarea></label>
					<div class="buttonset-1"><button name="builder-widget-tab-remove" class="button-1 button-1-1 alignright builder-widget-group-item-remove">'.__( 'Remove' ).'</button></div>
				</div></div>';
	
				if( isset( $widget['tabs_content'] ) )
				{
					
					$column = 0;
	
					for ( $i = 0; $i < count( $widget['tabs_content'] ); $i++ )
					{
						
						if( !empty( $widget['tabs_title'][$i] ) || !empty( $widget['tabs_content'][$i] ) )
						{
							
							$tabs .= '<div class="col"><div class="group-item">
								<label>'.__( 'Title' ).' <input type="text"'.$this->get_field_atts( 'tabs_title][' ).' name="'.$this->get_field_name( 'tabs_title][' ).'" value="'.$widget['tabs_title'][$i].'" /></label>
								<label>'.__( 'Content' ).' <textarea'.$this->get_field_atts( 'tabs_content][' ).' name="'.$this->get_field_name( 'tabs_content][' ).'" rows="5">'.$widget['tabs_content'][$i].'</textarea></label>
								<div class="buttonset-1"><button name="builder-widget-tab-remove" class="button-1 button-1-1 alignright builder-widget-group-item-remove">'.__( 'Remove' ).'</button></div>
							</div></div>';
							
						}
						
					}
					
				}
	
				return '<fieldset class="flab-form">
					<div class="cols-2">
						<div class="col">
							<label>'.__( 'Type' ).' <select'.$this->get_field_atts( 'type' ).' name="'.$this->get_field_name( 'type' ).'">'.$type_options.'</select></label>
						</div>
						<div class="col">
							<label>'.__( 'Current tab' ).' <select'.$this->get_field_atts( 'current' ).' name="'.$this->get_field_name( 'current' ).'">'.$current_options.'</select>
						</div>
					</div>
					<label>'.__( 'Additional classes' ).' <input type="text"'.$this->get_field_atts( 'classes' ).' name="'.$this->get_field_name( 'classes' ).'" value="'.( isset( $widget['classes'] ) ? $widget['classes'] : '' ).'" /></label>
				</fieldset>
				<fieldset class="flab-form">
					<div class="buttonset-1">
						<button name="builder-widget-group-item-add" class="button-1 button-1-2 alignright builder-widget-group-item-add">'.__( 'Add tab' ).'</button>
					</div>
					<div class="cols-3 group-content">
					'.$tabs.'
					</div>
					<div class="buttonset-1" style="display: none;">
						<button name="builder-widget-group-item-add" class="button-1 button-1-2 alignright builder-widget-group-item-add">'.__( 'Add tab' ).'</button>
					</div>
				</fieldset>';
				
			}/* form() */
			
			/* ============================================================================ */
			
		}/* class flab_tabs_widget */
		
	}
	
	/* ================================================================================ */

?>