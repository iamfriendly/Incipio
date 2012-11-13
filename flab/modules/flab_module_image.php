<?php

	/* ================================================================================ */
	
	if( !class_exists( 'flab_image_widget' ) )
	{
	
		class flab_image_widget extends flab_builder_widget
		{
		
			/**
			 * Set up the title and description
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
		
			public function __construct()
			{
				
				parent::__construct( 'image', __( 'Image' ) );
				$this->label = __( 'Insert a single image into this post' );
				
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
				
				$classes = array();
	
				if( isset( $widget['align'] ) && !empty( $widget['align'] ) )
				{
					$classes[] = 'align'.$widget['align'];
				}
	
				$widget['image_width'] 				= intval( $widget['image_width'] );
				$widget['image_height'] 				= intval( $widget['image_height'] );
				$widget['image_crop_width'] 		= intval( $widget['image_crop_width'] );
				$widget['image_crop_height'] 		= intval( $widget['image_crop_height'] );
	
				if( $widget['image_crop_width'] > 0 || $widget['image_crop_height'] > 0 )
				{
				
					$widget['image'] = flab::flab_return_img_thumb( flab::flab_return_actual_img_path( $widget['image'] ), $widget['image_crop_width'], $widget['image_crop_height'] );
					
				}
	
				if( isset( $widget['frame'] ) && !empty( $widget['frame'] ) )
				{
					
					$widget['frame'] = str_replace( 'flab-frame-', '', $widget['frame'] );
	
					$classes[] = 'flab-frame';
					$classes[] = 'flab-frame-'.$widget['frame'];
					
				}
	
				$classes = implode( ' ', $classes );
	
				return ( ( isset( $widget['url'] ) && !empty( $widget['url'] ) ) ? '<a href="'.$widget['url'].'" class="'.$classes.( ( isset( $widget['classes'] ) && !empty( $widget['classes'] ) ) ? ' '.$widget['classes'] : '' ).'">' : '' ).'<img src="'.( isset( $widget['image'] ) ? flab::img( $widget['image'], 'image' ) : '' ).'" alt="'.( isset( $widget['description'] ) ? $widget['description'] : '' ).'"'.( ( !isset( $widget['url'] ) || empty( $widget['url'] ) ) ? ' class="'.$classes.( ( isset( $widget['classes'] ) && !empty( $widget['classes'] ) ) ? ' '.$widget['classes'] : '' ).'"' : '' ).( $widget['image_width'] > 0 ? ' width="'.$widget['image_width'].'"' : '' ).( $widget['image_height'] > 0 ? ' height="'.$widget['image_height'].'"' : '' ).' />'.( ( isset( $widget['url'] ) && !empty( $widget['url'] ) ) ? '</a>' : '' );
				
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
			
				$aligns = array( 
				
					'' => __( 'Default' ),
					'left' => __( 'Left' ),
					'right' => __( 'Right' ),
					'center' => __( 'Centre' )
					
				 );
	
				$align_options = '';
	
				foreach ( $aligns as $value => $label )
				{
				
					$align_options .= '<option value="'.$value.'"'.( ( isset( $widget['align'] ) && $widget['align'] == $value ) ? ' selected="selected"' : '' ).'>'.$label.'</option>';
					
				}
	
				$frames = array( 
				
					'' => __( 'Default' ),
					'1' => __( 'Frame 1' ),
					'2' => __( 'Frame 2' )
					
				 );
	
				$frame_options = '';
	
				foreach ( $frames as $value => $label )
				{
				
					$frame_options .= '<option value="'.$value.'"'.( ( isset( $widget['frame'] ) && $widget['frame'] == $value ) ? ' selected="selected"' : '' ).'>'.$label.'</option>';
					
				}
	
				return '<fieldset class="flab-form">
					<div class="cols-2">
						<div class="col">
							<label>'.__( 'Image Path' ).' <input type="text"'.$this->get_field_atts( 'image' ).' name="'.$this->get_field_name( 'image' ).'" class="flab-preview upload_image" value="'.( isset( $widget['image'] ) ? $widget['image'] : '' ).'" /></label> <div class="buttonset-1"><button type="submit"'.$this->get_field_atts( 'upload_image' ).' name="'.$this->get_field_name( 'upload_image' ).'" class="button-1 button-1-2 alignright upload_image single callback-builder_image_widget_change">'.__( 'Choose Image' ).'</button></div>
							<label>'.__( 'Description' ).' <input type="text"'.$this->get_field_atts( 'description' ).' name="'.$this->get_field_name( 'description' ).'" value="'.( isset( $widget['description'] ) ? $widget['description'] : '' ).'" /></label>
							<label>'.__( 'Frame' ).' <select'.$this->get_field_atts( 'frame' ).' name="'.$this->get_field_name( 'frame' ).'" value="'.( isset( $widget['frame'] ) ? $widget['frame'] : '' ).'">'.$frame_options.'</select></label>
						</div>
						<div class="col"><div class="preview-img-wrap"><img src="'.( ( isset( $widget['image'] ) && !empty( $widget['image'] ) ) ? $widget['image'] : flab::path( 'flab/assets/images/placeholder.png', TRUE ) ).'" alt="" class="upload_image" /></div></div>
					</div>
					<label>'.__( 'Link URL' ).' <input type="text"'.$this->get_field_atts( 'url' ).' name="'.$this->get_field_name( 'url' ).'" value="'.( isset( $widget['url'] ) ? $widget['url'] : '' ).'" /></label>
					<div class="cols-3">
						<div class="col"><label>'.__( 'Align' ).' <select'.$this->get_field_atts( 'align' ).' name="'.$this->get_field_name( 'align' ).'" value="'.( isset( $widget['align'] ) ? $widget['align'] : '' ).'">'.$align_options.'</select></label></div>
						<div class="col"><label>'.__( 'Image width' ).' <input type="text"'.$this->get_field_atts( 'image_width' ).' name="'.$this->get_field_name( 'image_width' ).'" value="'.( isset( $widget['image_width'] ) ? intval( $widget['image_width'] ) : '' ).'" /></label></div>
						<div class="col"><label>'.__( 'Image height' ).' <input type="text"'.$this->get_field_atts( 'image_height' ).' name="'.$this->get_field_name( 'image_height' ).'" value="'.( isset( $widget['image_height'] ) ? intval( $widget['image_height'] ) : '' ).'" /></label></div>
					</div>
					<div class="cols-2">
						<div class="col"><label>'.__( 'Image crop width' ).' <input type="text"'.$this->get_field_atts( 'image_crop_width' ).' name="'.$this->get_field_name( 'image_crop_width' ).'" value="'.( isset( $widget['image_crop_width'] ) ? intval( $widget['image_crop_width'] ) : '' ).'" /></label></div>
						<div class="col"><label>'.__( 'Image crop height' ).' <input type="text"'.$this->get_field_atts( 'image_crop_height' ).' name="'.$this->get_field_name( 'image_crop_height' ).'" value="'.( isset( $widget['image_crop_height'] ) ? intval( $widget['image_crop_height'] ) : '' ).'" /></label></div>
					</div>
					<label>'.__( 'Additional classes' ).' <input type="text"'.$this->get_field_atts( 'classes' ).' name="'.$this->get_field_name( 'classes' ).'" value="'.( isset( $widget['classes'] ) ? $widget['classes'] : '' ).'" /></label>
				</fieldset>';
				
			}/* form() */
			
			/* ============================================================================ */
			
		}/* class flab_image_widget */
		
	}
	
	/* ================================================================================ */

?>