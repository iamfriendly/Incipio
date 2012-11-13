<?php

	/* ================================================================================ */
	
	if( !class_exists( 'flab_button_widget' ) )
	{
	
		class flab_button_widget extends flab_builder_widget
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
				
				parent::__construct( 'button', __( 'Button' ) );
				$this->label = __( 'Can\'t beat a good button to get people to click.' );
				
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
				
				if( !isset( $widget['style'] ) || empty( $widget['style'] ) )
				{
					$widget['style'] = '1';
				}
	
				$sizes = array( '1' => 'small', '2' => 'medium', '3' => 'big', '4' => 'bootstrap', '5' => 'bootstrap-icon' );
	
				if( isset( $widget['label'] ) && !empty( $widget['label'] ) )
				{
					
					$background = '';
					$color = '';
					$width = '';
					$align = 'left';
					$border = 'transparent';
	
					if( isset( $widget['background'] ) )
					{
						$background = $widget['background'];
					}
					
					if( isset( $widget['border'] ) )
					{
						$border = $widget['border'];
					}
	
					if( isset( $widget['color'] ) )
					{
						$color = $widget['color'];
					}
	
					if( isset( $widget['width'] ) )
					{
						$width = $widget['width'];
					}
	
					if( isset( $widget['align'] ) && !empty( $widget['align'] ) )
					{
						$align = $widget['align'];
					}
					
					//For icons, we need to output an <i> around the label
					$classes = ( isset( $widget['classes'] ) && !empty( $widget['classes'] ) ) ? ' '.$widget['classes'] : '';
					
					//If we have classes, we need to separate them into icon- and btn-
					if( $classes != '' )
					{
					
						$btn_classes = array();
						$icon_classes = array();
					
						$all_classes = explode( " ", $classes );
						foreach( $all_classes as $class_name )
						{
							
							$break_at_dash = explode( "-", $class_name );
							if( $break_at_dash[0] == "btn" )
							{
								array_push( $btn_classes, "btn-".$break_at_dash[1] );
							}
							else
							{
								array_push( $icon_classes, "icon-".$break_at_dash[1] );
							}
							
						}
					
					}
					
					if( is_array( $btn_classes ) && !empty( $btn_classes ) )
					{
						$btn_output = join( " ", $btn_classes );
					}
					else
					{
						$btn_output ="";
					}
					
					if( is_array( $icon_classes ) && !empty( $icon_classes ) )
					{
						$icon_output = join( " ", $icon_classes );
					}
					else
					{
						$icon_output ="";
					}
					
					
					$icon_pre = ( $widget['style'] == '5' ) ? "<i class='".$icon_output."'>" : "";
					$icon_post = ( $widget['style'] == '5' ) ? "</i>&nbsp;" : "";
	
					return '<a href="'.( isset( $widget['url'] ) ? $widget['url'] : '' ).'" class="flab-widget flab-button flab-button-'.$sizes[$widget['style']].' '.$btn_output.' '.' align'.$align.'"'.( ( !empty( $background ) || !empty( $color ) || !empty( $width ) ) ? ( ' style="'.( !empty( $width ) ? 'width: '.$width.'px;' : '' ).( !empty( $background ) ? 'background-color: '.$background.';' : '' ).( !empty( $border ) ? 'border-color: '.$border.';' : '' ).( !empty( $color ) ? 'color: '.$color.';' : '' ).'"' ) : '' ).'>'.$icon_pre.$icon_post.$widget['label'].'</a>';
					
				}
	
				return '';
				
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
			
				$alignments = array(
				
					'left' => __( 'Left' ),
					'right' => __( 'Right' ),
					'center' => __( 'Center' )
					
				 );
	
				$sizes = array( 
				
					'1' => __( 'Small' ),
					'2' => __( 'Medium' ),
					'3' => __( 'Big' ),
					'4' => __( 'Bootstrap' ),
					'5' => __( 'Bootstrap with Icon' )
					
				 );
	
				$align_options = '';
	
				foreach ( $alignments as $value => $label )
				{
					
					$align_options .= '<option value="'.$value.'"'.( ( isset( $widget['align'] ) && $widget['align'] == $value ) ? ' selected="selected"' : '' ).'>'.$label.'</option>';
					
				}
	
				$style_options = '';
	
				foreach ( $sizes as $value => $label )
				{
					
					$style_options .= '<option value="'.$value.'"'.( ( isset( $widget['style'] ) && $widget['style'] == $value ) ? ' selected="selected"' : '' ).'>'.$label.'</option>';
					
				}
	
				return '<fieldset class="flab-form">
					<div class="cols-3">
						<div class="col">
							<label>'.__( 'Label' ).' <abbr title="required">*</abbr><input type="text"'.$this->get_field_atts( 'label' ).' name="'.$this->get_field_name( 'label' ).'" value="'.( isset( $widget['label'] ) ? $widget['label'] : '' ).'" /></label>
						</div>
						<div class="col">
							<label>'.__( 'URL' ).' <abbr title="required">*</abbr><input type="text"'.$this->get_field_atts( 'url' ).' name="'.$this->get_field_name( 'url' ).'" value="'.( isset( $widget['url'] ) ? $widget['url'] : '' ).'" /></label>
						</div>
						<div class="col">
							<label>'.__( 'Alignment' ).' <select'.$this->get_field_atts( 'align' ).' name="'.$this->get_field_name( 'align' ).'" value="'.( isset( $widget['align'] ) ? $widget['align'] : '' ).'">'.$align_options.'</select></label>
						</div>
					</div>
	
					<div class="cols-3">
						<div class="col">
							<label>'.__( 'Style' ).' <select'.$this->get_field_atts( 'style' ).' name="'.$this->get_field_name( 'style' ).'" value="'.( isset( $widget['style'] ) ? $widget['style'] : '' ).'">'.$style_options.'</select></label>
						</div>
					</div>
	
					<div class="cols-3">
						<div class="col">
							<label class="flab-color">'.__( 'Background color' ).' <input type="text"'.$this->get_field_atts( 'background' ).' name="'.$this->get_field_name( 'background' ).'" value="'.( isset( $widget['background'] ) ? $widget['background'] : '' ).'" /></label>
						</div>
						<div class="col">
							<label class="flab-color">'.__( 'Text color' ).' <input type="text"'.$this->get_field_atts( 'color' ).' name="'.$this->get_field_name( 'color' ).'" value="'.( isset( $widget['color'] ) ? $widget['color'] : '' ).'" /></label>
						</div>
						<div class="col">
							<label>'.__( 'Width' ).' <input type="text"'.$this->get_field_atts( 'width' ).' name="'.$this->get_field_name( 'width' ).'" value="'.( isset( $widget['width'] ) ? $widget['width'] : '' ).'" /></label>
						</div>
					</div>
					<div class="cols-3">
						<div class="col">
							<label class="flab-color">'.__( 'Border color' ).' <input type="text"'.$this->get_field_atts( 'border' ).' name="'.$this->get_field_name( 'border' ).'" value="'.( isset( $widget['border'] ) ? $widget['border'] : '' ).'" /></label>
						</div>
					</div>
					<div class="cols-1">
						<div class="col">
							<label>'.__( 'Additional classes' ).' <input type="text"'.$this->get_field_atts( 'classes' ).' name="'.$this->get_field_name( 'classes' ).'" value="'.( isset( $widget['classes'] ) ? $widget['classes'] : '' ).'" /></label>
						</div>
					</div>
				</fieldset>';
				
			}/* form() */
			
			/* ============================================================================ */
			
		}/* class flab_button_widget */
		
	}
	
	/* ================================================================================ */

?>