<?php

	/* ================================================================================ */
	
	if( !class_exists( 'flab_table_widget' ) )
	{
	
		class flab_table_widget extends flab_builder_widget
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
				
				parent::__construct( 'table', __( 'Table' ) );
				$this->label = __( 'Need a table for some data? You got it.' );
				
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
			
				if( isset( $widget['style'] ) )
				{
					$widget['style'] = str_replace( 'flab-style-', '', $widget['style'] );
				}
				
				$output = '<table cellspacing="0" class="table flab-widget '.( ( isset( $widget['style'] ) && !empty( $widget['style'] ) ) ? ''.$widget['style'] : '' ).( ( isset( $widget['classes'] ) && !empty( $widget['classes'] ) ) ? ' '.$widget['classes'] : '' ).'">'._n;
	
				$column = 0;
				$row = 0;
				$th_top = ( isset( $widget['header_top'] ) && $widget['header_top'] == 'on' );
				$th_left = ( isset( $widget['header_left'] ) && $widget['header_left'] == 'on' );
	
				$count = count( $widget['table_data'] );
	
				for ( $i = 0; $i < $count; $i++ )
				{
				
					if( $column == 0 )
					{
						$output .= '	<tr>'._n;
					}
	
					$column++;
					$output .= '		<t'.( ( ( $row == 0 && $th_top ) || ( $column == 1 && $th_left ) ) ? 'h' : 'd' ).'>'._n;
					$output .= '			'.do_shortcode( $widget['table_data'][$i] )._n;
					$output .= '		</t'.( ( ( $row == 0 && $th_top ) || ( $column == 1 && $th_left ) ) ? 'h' : 'd' ).'>'._n;
	
					if( $column == $widget['columns'] )
					{
						
						$output .= '	</tr>'._n;
						$column = 0;
						$row++;
						
					}
					
				}
	
				$output .= '</table>'._n;
	
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
			
				$styles = array( 
					
					'table-bordered' => __( 'Default' ),
					'table-striped' => __( 'Plain Jane, Basic Stripes' ),
					'raw' => __( 'Raw - Add your own styles with custom classes' )
					
				 );
	
				$style_options = '';
	
				foreach ( $styles as $value => $label )
				{
					$style_options .= '<option value="'.$value.'"'.( ( isset( $widget['style'] ) && $widget['style'] == $value ) ? ' selected="selected"' : '' ).'>'.$label.'</option>';
				}
	
				if( !isset( $widget['rows'] ) )
				{
					$widget['rows'] = 1;
				}
	
				if( !isset( $widget['columns'] ) )
				{
					$widget['columns'] = 1;
				}
	
				$widget['rows'] = intval( $widget['rows'] );
				$widget['columns'] = intval( $widget['columns'] );
	
				if( $widget['rows'] < 1 )
				{
					$widget['rows'] = 1;
				}
	
				if( $widget['rows'] > 60 )
				{
					$widget['rows'] = 60;
				}
	
				if( $widget['columns'] < 1 )
				{
					$widget['columns'] = 1;
				}
	
				if( $widget['columns'] > 30 )
				{
					$widget['columns'] = 30;
				}
	
				$table_data = '<table class="table">';
	
				if( !isset( $widget['table_data'] ) || empty( $widget['table_data'] ) )
				{
					$table_data .= '<tr><td><textarea cols="10" rows="3"'.$this->get_field_atts( 'table_data][' ).' name="'.$this->get_field_name( 'table_data][' ).'"></textarea></td></tr>';
				}
				else
				{
					
					$column = 0;
	
					for ( $i = 0; $i < count( $widget['table_data'] ); $i++ )
					{
					
						if( $column == 0 )
						{
							$table_data .= '<tr>';
						}
	
						$column++;
						$table_data .= '<td><textarea cols="10" rows="3"'.$this->get_field_atts( 'table_data][' ).' name="'.$this->get_field_name( 'table_data][' ).'">'.$widget['table_data'][$i].'</textarea></td>';
	
						if( $column == $widget['columns'] )
						{
							
							$table_data .= '</tr>';
							$column = 0;
							
						}
						
					}
					
				}
	
				$table_data .= '</table>';
	
				return '<fieldset class="flab-form">
					<div class="cols-3">
					<div class="col"><label>'.__( 'Rows' ).' <input type="text"'.$this->get_field_atts( 'rows' ).' name="'.$this->get_field_name( 'rows' ).'" value="'.( isset( $widget['rows'] ) ? $widget['rows'] : '' ).'" /></label>
					<label><input type="checkbox"'.$this->get_field_atts( 'header_top' ).' name="'.$this->get_field_name( 'header_top' ).'"'.( ( isset( $widget['header_top'] ) && $widget['header_top'] == 'on' ) ? ' checked="checked"' : '' ).' /> '.__( 'Highlight first row' ).'</label>
					</div>
					<div class="col"><label>'.__( 'Columns' ).' <input type="text"'.$this->get_field_atts( 'columns' ).' name="'.$this->get_field_name( 'columns' ).'" value="'.( isset( $widget['columns'] ) ? $widget['columns'] : '' ).'" /></label>
					<label><input type="checkbox"'.$this->get_field_atts( 'header_left' ).' name="'.$this->get_field_name( 'header_left' ).'"'.( ( isset( $widget['header_left'] ) && $widget['header_left'] == 'on' ) ? ' checked="checked"' : '' ).' /> '.__( 'Highlight first column' ).'</label>
					</div>
					<div class="col"><label>'.__( 'Style' ).' <select'.$this->get_field_atts( 'style' ).' name="'.$this->get_field_name( 'style' ).'" value="'.( isset( $widget['style'] ) ? $widget['style'] : '' ).'">'.$style_options.'</select></label></div></div>
					<input type="hidden"'.$this->get_field_atts( 'table' ).' name="'.$this->get_field_name( 'table' ).'" value="'.( isset( $widget['table'] ) ? $widget['table'] : '' ).'" />
					<label>'.__( 'Additional classes' ).' <input type="text"'.$this->get_field_atts( 'classes' ).' name="'.$this->get_field_name( 'classes' ).'" value="'.( isset( $widget['classes'] ) ? $widget['classes'] : '' ).'" /></label>
				</fieldset>
				<fieldset class="flab-form">
					'.$table_data.'
				</fieldset>';
				
			}/* form() */
			
			/* ============================================================================ */
			
		}/* class flab_table_widget */
		
	}
	
	/* ================================================================================ */

?>