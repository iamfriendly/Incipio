<?php

	/* ================================================================================ */
	
	if( !class_exists( 'flab_nivo_widget' ) )
	{
	
		class flab_nivo_widget extends flab_slider_ready_widget
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
			
				parent::__construct( 'nivo', __( 'Nivo slider' ) );
				$this->label = __( 'Nivo slider' );
				
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
			
				if( flab::is_not_desktop( ) )
				{
					return '';
				}
	
				flab::load_stylesheet( 'jquery.nivo.slider.css', 'flab/assets/css/nivo-slider.css' );
				flab::load_script( 'jquery.nivo.slider', 'flab/assets/js/jquery.nivo.slider.pack.js', array( 'jquery' ) );
	
				$output = '';
	
				$widget['image_width'] = intval( $widget['image_width'] );
				$widget['image_height'] = intval( $widget['image_height'] );
	
				if( !isset( $widget['effect'] ) || empty( $widget['effect'] ) )
				{
					$widget['effect'] = 'random';
				}
	
				if( !isset( $widget['anim_speed'] ) || empty( $widget['anim_speed'] ) )
				{
					$widget['anim_speed'] = 500;
				}
	
				if( !isset( $widget['pause_time'] ) || empty( $widget['pause_time'] ) )
				{
					$widget['pause_time'] = 3000;
				}
	
				$id = substr( uniqid( ), -6 );
	
				$output .= '<script>jQuery( window ).load( function( ) { jQuery( "#nivo-slider-'.$id.'" ).nivoSlider( { effect: \''.$widget['effect'].'\', animSpeed: '.intval( $widget['anim_speed'] ).', pauseTime: '.intval( $widget['pause_time'] ).'} ); } );</script>'._n;
	
				$output .= ' <div class="flab-widget flab_nivo_slider slider-wrapper theme-default'.( ( isset( $widget['classes'] ) && !empty( $widget['classes'] ) ) ? ' '.$widget['classes'] : '' ).'">'._n;
				$output .= '	<div id="nivo-slider-'.$id.'" class="nivoSlider"'.( $widget['image_width'] > 0 ? ' style="width: 100%;"' : '' ).'>'._n;
	
				$album = time( );
	
				if( isset( $widget['image_url'] ) && !empty( $widget['image_url'] ) )
				{
					
					$count = count( $widget['image_url'] );
	
					for ( $i = 0; $i < $count; $i++ )
					{
						
						if( !empty( $widget['image_url'][$i] ) || !empty( $widget['image_alt'][$i] ) )
						{
							
							$image = isset( $widget['image_url'][$i] ) ? $widget['image_url'][$i] : '';
							$alt = isset( $widget['image_alt'][$i] ) ? $widget['image_alt'][$i] : '';
	
							if( !empty( $image ) )
							{
	
								$output .= '<img src="'.flab::img( $image, 'nivo' ).'" alt="'.$alt.'"'.( $widget['image_width'] > 0 ? ' width="'.$widget['image_width'].'"' : '' ).( $widget['image_height'] > 0 ? ' height="'.$widget['image_height'].'"' : '' ).' title="'.$alt.'" />'._n;
								
							}
							
						}
						
					}
					
				}
	
				$output .= '	</div>'._n;
				$output .= '</div>'._n;
	
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
				$effects = array(
				
					'random' => __( 'Random' ),
					'sliceDown' => __( 'Slice down' ),
					'sliceDownLeft' => __( 'Slice down left' ),
					'sliceUp' => __( 'Slice up' ),
					'sliceUpLeft' => __( 'Slice left' ),
					'sliceUpDown' => __( 'Slice up/down' ),
					'sliceUpDownLeft' => __( 'Slice up/down left' ),
					'fold' => __( 'Fold' ),
					'fade' => __( 'Fade' ),
					'slideInRight' => __( 'Slide in right' ),
					'slideInLeft' => __( 'Slide in left' ),
					'boxRandom' => __( 'Box random' ),
					'boxRain' => __( 'Box rain' ),
					'boxRainReverse' => __( 'Box rain reverse' ),
					'boxRainGrow' => __( 'Box rain grow' ),
					'boxRainGrowReverse' => __( 'Box rain grow reverse' )
					
				 );
	
				$effect_options = '';
	
				foreach ( $effects as $value => $label )
				{
					$effect_options .= '<option value="'.$value.'"'.( ( isset( $widget['effect'] ) && $widget['effect'] == $value ) ? ' selected="selected"' : '' ).'>'.$label.'</option>';
				}
	
				$anim_speed = array(
				
					100,
					200,
					300,
					400,
					500,
					600,
					700,
					800,
					900,
					1000
					
				 );
	
				$anim_speed_options = '';
	
				foreach ( $anim_speed as $value )
				{
					$anim_speed_options .= '<option value="'.$value.'"'.( ( isset( $widget['anim_speed'] ) && $widget['anim_speed'] == $value ) ? ' selected="selected"' : '' ).'>'.( $value / 1000 ).'s</option>';
				}
	
				$pause_time = array(
				
					1000,
					1500,
					2000,
					2500,
					3000,
					3500,
					4000,
					4500,
					5000,
					6000,
					7000,
					8000,
					9000,
					10000
					
				 );
	
				$pause_time_options = '';
	
				foreach ( $pause_time as $value )
				{
					$pause_time_options .= '<option value="'.$value.'"'.( ( isset( $widget['pause_time'] ) && $widget['pause_time'] == $value ) ? ' selected="selected"' : '' ).'>'.( $value/1000 ).'s</option>';
				}
	
				$items = '';
	
				$items .= '<div class="col" style="display: none"><div class="gallery-item">';
				$items .= '<div class="preview-img-wrap"><img src="'.flab::path( 'flab/assets/images/placeholder.png', TRUE ).'" class="flab-preview upload_image" /></div>';
				$items .= '<div class="gallery-item-meta"><div class="cols-2">';
				$items .= '<div class="col"><label>'.__( 'URL' ).' <input type="text"'.$this->get_field_atts( 'image_url][' ).' name="'.$this->get_field_name( 'image_url][' ).'" class="upload_image" value="" /></label></div>';
				$items .= '<div class="col"><label>'.__( 'Alt' ).' <input type="text"'.$this->get_field_atts( 'image_alt][' ).' name="'.$this->get_field_name( 'image_alt][' ).'" value="" /></label></div>';
				$items .= '</div></div>';
				$items .= '<div class="buttonset-1"><button type="submit"'.$this->get_field_atts( 'change_item' ).' name="'.$this->get_field_name( 'change_item' ).'" class="button-1 button-1-1 alignright builder-widget-gallery-change upload_image single callback-builder_gallery_widget_change">'.__( 'Edit' ).'</button>';
				$items .= '<button type="submit"'.$this->get_field_atts( 'remove_item' ).' name="'.$this->get_field_name( 'remove_item' ).'" class="button-1 button-1-1 alignright builder-widget-gallery-remove">'.__( 'Remove' ).'</button></div>';
				$items .= '</div></div>';
	
				if( isset( $widget['image_url'] ) && !empty( $widget['image_url'] ) )
				{
					
					$count = count( $widget['image_url'] );
	
					for ( $i = 0; $i < $count; $i++ )
					{
						
						if( !empty( $widget['image_url'][$i] ) || !empty( $widget['image_alt'][$i] ) )
						{
							
							$items .= '<div class="col"><div class="gallery-item">';
							$items .= '<div class="preview-img-wrap"><img src="'.$widget['image_url'][$i].'" class="flab-preview upload_image" /></div>';
							$items .= '<div class="gallery-item-meta"><div class="cols-2">';
							$items .= '<div class="col"><label>'.__( 'URL' ).' <input type="text"'.$this->get_field_atts( 'image_url][' ).' name="'.$this->get_field_name( 'image_url][' ).'" class="upload_image" value="'.( isset( $widget['image_url'][$i] ) ? $widget['image_url'][$i] : '' ).'" /></label></div>';
							$items .= '<div class="col"><label>'.__( 'Alt' ).' <input type="text"'.$this->get_field_atts( 'image_alt][' ).' name="'.$this->get_field_name( 'image_alt][' ).'" value="'.( isset( $widget['image_alt'][$i] ) ? $widget['image_alt'][$i] : '' ).'" /></label></div>';
							$items .= '</div></div>';
							$items .= '<div class="buttonset-1"><button type="submit"'.$this->get_field_atts( 'change_item' ).' name="'.$this->get_field_name( 'change_item' ).'" class="button-1 button-1-1 alignright builder-widget-gallery-change upload_image single callback-builder_gallery_widget_change">'.__( 'Edit' ).'</button>';
							$items .= '<button type="submit"'.$this->get_field_atts( 'remove_item' ).' name="'.$this->get_field_name( 'remove_item' ).'" class="button-1 button-1-1 alignright builder-widget-gallery-remove">'.__( 'Remove' ).'</button></div>';
							$items .= '</div></div>';
							
						}
						
					}
					
				}
	
				return '<fieldset class="flab-form">
					<div class="cols-2"><div class="col"><label>'.__( 'Image width' ).' <input type="text"'.$this->get_field_atts( 'image_width' ).' name="'.$this->get_field_name( 'image_width' ).'" value="'.( isset( $widget['image_width'] ) ? intval( $widget['image_width'] ) : '' ).'" /></label></div>
					<div class="col"><label>'.__( 'Image height' ).' <input type="text"'.$this->get_field_atts( 'image_height' ).' name="'.$this->get_field_name( 'image_height' ).'" value="'.( isset( $widget['image_height'] ) ? intval( $widget['image_height'] ) : '' ).'" /></label></div>
					</div>
					<div class="cols-3">
						<div class="col">
							<label>'.__( 'Animation' ).' <select'.$this->get_field_atts( 'effect' ).' name="'.$this->get_field_name( 'effect' ).'" value="'.( isset( $widget['effect'] ) ? $widget['effect'] : '' ).'">'.$effect_options.'</select></label>
						</div>
						<div class="col">
							<label>'.__( 'Speed of animation' ).' <select'.$this->get_field_atts( 'anim_speed' ).' name="'.$this->get_field_name( 'anim_speed' ).'" value="'.( isset( $widget['anim_speed'] ) ? $widget['anim_speed'] : '' ).'">'.$anim_speed_options.'</select></label>
						</div>
						<div class="col">
							<label>'.__( 'Delay between slides' ).' <select'.$this->get_field_atts( 'pause_time' ).' name="'.$this->get_field_name( 'pause_time' ).'" value="'.( isset( $widget['pause_time'] ) ? $widget['pause_time'] : '' ).'">'.$pause_time_options.'</select></label>
						</div>
					</div>
					<label>'.__( 'Additional classes' ).' <input type="text"'.$this->get_field_atts( 'classes' ).' name="'.$this->get_field_name( 'classes' ).'" value="'.( isset( $widget['classes'] ) ? $widget['classes'] : '' ).'" /></label>
					<div class="buttonset-1">
						<button type="submit"'.$this->get_field_atts( 'add_item' ).' name="'.$this->get_field_name( 'add_item' ).'" class="button-1 button-1-2 builder-widget-gallery-add">'.__( 'Add item' ).'</button>
						<button type="submit"'.$this->get_field_atts( 'insert_images' ).' name="'.$this->get_field_name( 'insert_images' ).'" class="button-1 button-1-2 builder-widget-gallery-insert upload_image callback-builder_gallery_widget_insert">'.__( 'Insert images' ).'</button>
					</div>
					<div class="cols-3 gallery-content">
					'.$items.'
					</div>
					<div class="buttonset-1" style="display: none;">
						<button type="submit"'.$this->get_field_atts( 'add_item' ).' name="'.$this->get_field_name( 'add_item' ).'" class="button-1 button-1-2 builder-widget-gallery-add">'.__( 'Add item' ).'</button>
						<button type="submit"'.$this->get_field_atts( 'insert_images' ).' name="'.$this->get_field_name( 'insert_images' ).'" class="button-1 button-1-2 builder-widget-gallery-insert upload_image callback-builder_gallery_widget_insert">'.__( 'Insert images' ).'</button>
					</div>
				</fieldset>';
				
			}/* form() */
		
		/* ============================================================================ */
			
		}/* class flab_nivo_widget */

	}
	
	/* ================================================================================ */

?>