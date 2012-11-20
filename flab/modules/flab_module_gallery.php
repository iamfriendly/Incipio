<?php

	/* ================================================================================ */
	
	if( !class_exists( 'flab_gallery_widget' ) )
	{
	
		class flab_gallery_widget extends flab_slider_ready_widget
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
				
				parent::__construct( 'gallery', __( 'Gallery' ) );
				$this->label = __( 'Got several images you want to display? Got you covered!' );
				
			}/* __construct() */
			
			/* ============================================================================ */
			
			/**
			 * Convert the 'number' of columns to words
			 *
			 * long desc
			 * @package
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public function convert_num_to_words( $number )
			{
				
				if( ( $number < 0 ) || ( $number > 999999999 ) )
			    {
			       throw new Exception( "Number is out of range" );
			    }
			
			    $Gn = floor( $number / 1000000 );  /* Millions ( giga ) */
			    $number -= $Gn * 1000000;
			    $kn = floor( $number / 1000 );     /* Thousands ( kilo ) */
			    $number -= $kn * 1000;
			    $Hn = floor( $number / 100 );      /* Hundreds ( hecto ) */
			    $number -= $Hn * 100;
			    $Dn = floor( $number / 10 );       /* Tens ( deca ) */
			    $n = $number % 10;               /* Ones */ 
			
			    $result = ""; 
			
			    if( $Gn ){  $result .= number_to_words( $Gn ) . " Million";  } 
			
			    if( $kn ){  $result .= ( empty( $result ) ? "" : " " ) . number_to_words( $kn ) . " Thousand"; } 
			
			    if( $Hn ){  $result .= ( empty( $result ) ? "" : " " ) . number_to_words( $Hn ) . " Hundred";  } 
			
			    $ones = array( "", "One", "Two", "Three", "Four", "Five", "Six",
			        "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
			        "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen",
			        "Nineteen" );
			    
			    $tens = array( "", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty",
			        "Seventy", "Eigthy", "Ninety" ); 
			
			    if( $Dn || $n )
			    {
			      
			       if( !empty( $result ) ){ $result .= " and "; } 
			
			       if( $Dn < 2 ){ $result .= $ones[$Dn * 10 + $n]; }
			       else
			       {
			       
						$result .= $tens[$Dn];
						
						if( $n ){  $result .= "-" . $ones[$n]; }
			          
			       }
			       
			    }
			
			    if( empty( $result ) ){  $result = "zero"; } 
			
			    return strtolower( $result ) ;
				
			}/* convert_num_to_words */
	
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
				
				
				$widget['image_height']	= intval( $widget['image_height'] );
				$widget['image_width']	= intval( $widget['image_width'] );
				
	
				$classes = $this->get_classes( $widget );
				
				
				$output = '<div class="row display flab_gallery">';
				
					if( isset( $widget['image_url'] ) && !empty( $widget['image_url'] ) )
					{
					
						$count = count( $widget['image_url'] ) ;
						$num_of_cols_per_image = $this->convert_num_to_words( 12 / ( $count - 1 ) );
	
						for ( $i = 0; $i < $count; $i++ )
						{
						
							if( !empty( $widget['image_url'][$i] ) || !empty( $widget['image_alt'][$i] ) )
							{
								
								$output .= '<div class="' . $num_of_cols_per_image . ' columns">';
								
									$image = isset( $widget['image_url'][$i] ) ? $widget['image_url'][$i] : '';
									$alt = isset( $widget['image_alt'][$i] ) ? $widget['image_alt'][$i] : '';
									
									if( !isset( $widget['disable_lightbox'] ) || $widget['disable_lightbox'] != 'on' )
										$output .= '<a href="'.$image.'" class="" rel="lightbox[album-'.$count.']">';
									else
										$output .= '<div class="nocbox">';
									
									
									$output .= '<img src="'.flab::img( $image, 'gallery' ).'" alt="'.$alt.'"'.( $widget['image_width'] > 0 ? ' width="'.$widget['image_width'].'"' : '' ).( $widget['image_height'] > 0 ? ' height="'.$widget['image_height'].'"' : '' ).' />';
									
									
									if( !isset( $widget['disable_lightbox'] ) || $widget['disable_lightbox'] != 'on' )
										$output .= '</a>';
									else
										$output .= '</div>';
									
								$output .= '</div>';
								
							}
						
						}
						
					}
				
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
				
				$items = '';
	
				$items .= '<div class="col" style="display: none"><div class="gallery-item">';
				$items .= '<div class="preview-img-wrap"><img src="'.flab::path( 'flab/assets/images/placeholder.png', TRUE ).'" class="flab-preview upload_image" /></div>';
				$items .= '<div class="gallery-item-meta"><div class="cols-2">';
				$items .= '<div class="col"><label>'.__( 'URL' ).' <input type="text"'.$this->get_field_atts( 'image_url][' ).' name="'.$this->get_field_name( 'image_url][' ).'" class="upload_image" value="" /></label></div> ';
				$items .= '<div class="col"><label>'.__( 'Alt' ).' <input type="text"'.$this->get_field_atts( 'image_alt][' ).' name="'.$this->get_field_name( 'image_alt][' ).'" value="" /></label></div>';
				$items .= '</div></div>';
				$items .= '<div class="buttonset-1"><button type="submit"'.$this->get_field_atts( 'change_item' ).' name="'.$this->get_field_name( 'change_item' ).'" class="button-1 button-1-1 builder-widget-gallery-change upload_image single callback-builder_gallery_widget_change">'.__( 'Edit' ).'</button>';
				$items .= '<button type="submit"'.$this->get_field_atts( 'remove_item' ).' name="'.$this->get_field_name( 'remove_item' ).'" class="button-1 button-1-1 builder-widget-gallery-remove">'.__( 'Remove' ).'</button></div>';
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
							$items .= '<div class="buttonset-1"><button type="submit"'.$this->get_field_atts( 'change_item' ).' name="'.$this->get_field_name( 'change_item' ).'" class="button-1 button-1-1 builder-widget-gallery-change upload_image single callback-builder_gallery_widget_change">'.__( 'Edit' ).'</button>';
							$items .= '<button type="submit"'.$this->get_field_atts( 'remove_item' ).' name="'.$this->get_field_name( 'remove_item' ).'" class="button-1 button-1-1 builder-widget-gallery-remove">'.__( 'Remove' ).'</button></div>';
							$items .= '</div></div>';
							
						}
						
					}
					
				}
	
				return '<fieldset class="flab-form">
					<div class="cols-1">
	
						<div class="col">
							<label class="label-alt-1"><input type="checkbox"'.$this->get_field_atts( 'disable_lightbox' ).' name="'.$this->get_field_name( 'disable_lightbox' ).'"'.( ( isset( $widget['disable_lightbox'] ) && $widget['disable_lightbox'] == 'on' ) ? ' checked="checked"' : '' ).'" /> '.__( 'Disable lightbox' ).'</label>
						</div>
					</div>
					<div class="cols-2">
						<div class="col">
							<label>'.__( 'Image width' ).' <input type="text"'.$this->get_field_atts( 'image_width' ).' name="'.$this->get_field_name( 'image_width' ).'" value="'.( isset( $widget['image_width'] ) ? intval( $widget['image_width'] ) : '' ).'" /></label>
						</div>
						<div class="col">
							<label>'.__( 'Image height' ).' <input type="text"'.$this->get_field_atts( 'image_height' ).' name="'.$this->get_field_name( 'image_height' ).'" value="'.( isset( $widget['image_height'] ) ? intval( $widget['image_height'] ) : '' ).'" /></label>
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
			
		}/* class flab_gallery_widget */
		
	}
	
	/* ================================================================================ */

?>