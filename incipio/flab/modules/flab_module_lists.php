<?php

	/* ================================================================================ */
	
	if( !class_exists( 'flab_list_widget' ) )
	{
	
		class flab_list_widget extends flab_builder_widget
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
				
				parent::__construct( 'list', __( 'Custom List' ) );
				$this->label = __( 'Several different lists with different icons.' );
				
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
			
				preg_match_all( '/<ul( .* )>/i', $widget['text'], $match );
	
				if( empty( $match[0] ) )
				{
					
					$content_li = explode( "\n", $widget['text'] );
	
					$list = array( );
	
					foreach ( $content_li as $li )
					{
						
						$li = trim( $li );
	
						if( !empty( $li ) )
						{
							$list[] = $li;
						}
						
					}
	
					if( empty( $list ) )
					{
						return $widget['text'];
					}
	
					$count = count( $list );
	
					for ( $i = 0; $i < $count; $i++ )
					{
						$list[$i] = flab::strip_only( $list[$i], '<li>' );
					}
	
					if( isset( $widget['bullet'] ) && !empty( $widget['bullet'] ) )
					{
						$widget['bullet'] = str_replace( 'flab-', '', $widget['bullet'] );
					}
	
					return '<ul class="flab-widget'.( !empty( $widget['bullet'] ) ? ' flab-custom-bullet flab-'.flab::slug( $widget['bullet'] ) : '' ).( ( isset( $widget['classes'] ) && !empty( $widget['classes'] ) ) ? ' '.$widget['classes'] : '' ).'"><li>'.implode( '</li><li>', $list ).'</li></ul>';
					
				}
	
				return flab::flab_set_attribute_on_element( 'ul', 'class', 'flab-widget'.( !empty( $widget['bullet'] ) ? ' flab-custom-bullet flab-'.flab::slug( $widget['bullet'] ) : '' ).( ( isset( $widget['classes'] ) && !empty( $widget['classes'] ) ) ? ' '.$widget['classes'] : '' ), $widget['text'], true );
				
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
			
				$bullets = array(
				
					'' => __( 'Default Bullet' ),
					'large-numbers' => __( 'Large Numbers', THEMENAME ),
					'check-1' => __( 'Tick', THEMENAME ),
					'arrow-1' => __( 'Arrow 1', THEMENAME ),
					'arrow-2' => __( 'Arrow 2', THEMENAME ),
					'warning-1' => __( 'Warning', THEMENAME ),
					'error-1' => __( 'Error', THEMENAME )
					
				 );
	
				$bullet_options = '';
	
				foreach ( $bullets as $value => $label )
				{
					
					$bullet_options .= '<option value="'.$value.'"'.( ( isset( $widget['bullet'] ) && $widget['bullet'] == $value ) ? ' selected="selected"' : '' ).'>'.$label.'</option>';
					
				}
	
				return '<fieldset class="flab-form">
					<label>'.__( 'Bullet' ).' <select'.$this->get_field_atts( 'bullet' ).' name="'.$this->get_field_name( 'bullet' ).'" value="'.( isset( $widget['bullet'] ) ? $widget['bullet'] : '' ).'">'.$bullet_options.'</select></label>
					<label>'.__( 'Content' ).' <textarea'.$this->get_field_atts( 'text' ).' name="'.$this->get_field_name( 'text' ).'" rows="5">'.htmlspecialchars( isset( $widget['text'] ) ? $widget['text'] : '' ).'</textarea></label>
					<label>'.__( 'Additional classes' ).' <input type="text"'.$this->get_field_atts( 'classes' ).' name="'.$this->get_field_name( 'classes' ).'" value="'.( isset( $widget['classes'] ) ? $widget['classes'] : '' ).'" /></label>
				</fieldset>';
				
			}/* form() */
			
			/* ============================================================================ */
			
		}/* class flab_list_widget */
		
	}
	
	/* ================================================================================ */

?>