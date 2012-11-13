<?php

	/* ================================================================================ */
	
	if( !class_exists( 'flab_accordion_widget' ) )
	{
	
		class flab_accordion_widget extends flab_builder_widget
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
				
				parent::__construct( 'accordion', __( 'Accordion' ) );
				$this->label = __( 'A jQuery accordion for some content.' );
				
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
				
				$output = '<div class="flab-widget flab-accordion ' . ( ( isset( $widget['classes'] ) && !empty( $widget['classes'] ) ) ? ' '.$widget['classes'] : '' ) . '">';
				
					$output .= '<dl class="accordion">';
				
						$count = count( $widget['tabs_content'] );
						
						for ( $i = 0; $i < $count; $i++ )
						{
							
							if( !empty( $widget['tabs_title'][$i] ) || !empty( $widget['tabs_content'][$i] ) )
							{
								
								$output .= '<dt class="' . ( ( !empty( $widget['current'] ) && $widget['current'] == $i ) ? ' active' : '' ) . '"><a href="">' . $widget['tabs_title'][$i] . '</a></dt>';
								$output .= '<dd class="' . ( ( !empty( $widget['current'] ) && $widget['current'] == $i ) ? ' active' : '' ) . '">' . wpautop( do_shortcode( $widget['tabs_content'][$i] ) ) .  '</dd>';
								
							}
							
						}
					
					$output .= '</dl>';
				
				$output .= '</div>';
	
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
				
				$current = array( 
				
					'' => __( 'None' )
					
				 );
	
				$current_options = '';
	
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
								<div class="buttonset-1"><button name="builder-widget-tab-remove" class="button-1 button-1-1 alignright">'.__( 'Remove' ).'</button></div>
							</div></div>';
							
						}
						
					}
					
				}
	
				return '<fieldset class="flab-form">
					<div class="cols-2">
						<div class="col">
							<label>'.__( 'Current tab' ).' <select'.$this->get_field_atts( 'current' ).' name="'.$this->get_field_name( 'current' ).'">'.$current_options.'</select></label>
						</div>
					</div>
					<label>'.__( 'Additional classes' ).' <input type="text"'.$this->get_field_atts( 'classes' ).' name="'.$this->get_field_name( 'classes' ).'" value="'.( isset( $widget['classes'] ) ? $widget['classes'] : '' ).'" /></label>
				</fieldset>
				<fieldset class="flab-form">
					<div class="buttonset-1">
						<button name="builder-widget-tab-add" class="button-1 button-1-2 alignright builder-widget-group-item-add">'.__( 'Add tab' ).'</button>
					</div>
					<div class="cols-3 group-content">
						'.$tabs.'
					</div>
					<div class="buttonset-1" style="display: none;">
						<button name="builder-widget-tab-add" class="button-1 button-1-2 alignright builder-widget-group-item-add">'.__( 'Add tab' ).'</button>
					</div>
				</fieldset>';
				
			}/* form() */
			
			/* ============================================================================ */
			
		}/* class flab_accordion_widget */
		
	}
	
	/* ================================================================================ */

?>