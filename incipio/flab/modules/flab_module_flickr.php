<?php

	/* ================================================================================ */
	
	if( !class_exists( 'flab_flickr_feed_widget' ) )
	{
	
		class flab_flickr_feed_widget extends flab_slider_ready_widget
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
				
				parent::__construct( 'flickr', __( 'Flickr feed' ) );
				$this->label = __( 'Creates simple gallery from a flickr feed.' );
				
			}/* __construct() */
			
			
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
			
				$count = 10;
	
				if( isset( $widget['count'] ) && $widget['count'] > 0 )
				{
					$count = $widget['count'];
				}
	
				$tags = '';
	
				if( isset( $widget['tags'] ) && !empty( $widget['tags'] ) )
				{
					$tags = $widget['tags'];
				}
	
				$flickr_feed = flab::flab_flickr_feed( $widget['flickr_id'], $count, $tags );
	
				$classes = $this->get_classes( $widget );
	
				/*$output = _t( 5 ).'<div class="flab-widget flab-flickr flab-gallery flab-grid-height-'.$widget['height'].' flab-image-stretch-mode-'.$widget['image_mode'].' '.implode( ' ', $classes ).( ( isset( $widget['classes'] ) && !empty( $widget['classes'] ) ) ? ' '.$widget['classes'] : '' ).'">'._n;
				
				$output .= _t( 5 ).'	<div class="flab-cols flab-cols-'.$widget['columns'].' flab-rows-'.$widget['rows'].' flab-spacing-'.( ( isset( $widget['disable_spacing'] ) && $widget['disable_spacing'] == 'on' ) ? '0' : '1' ).'">'._n;
	
				$album = time( );
	
				if( is_array( $flickr_feed ) && !empty( $flickr_feed ) )
				{
				
					foreach( $flickr_feed as $item )
					{
						
						$image = $item['thumbnail'];
						$alt = $item['title'];
	
						if( !empty( $image ) )
						{
							
							$output .= _t( 5 ).'		<div class="flab-col">'._n;
							$output .= _t( 5 ).'			<div class="flab-gallery-item">'._n;
	
							$output .= _t( 5 ).'				';
	
							if( !isset( $widget['disable_lightbox'] ) || $widget['disable_lightbox'] != 'on' )
							{
								$output .= '<a href="'.$item['image'].'" class="flab-media-img" rel="lightbox[album-'.$album.']">';
							}
							else
							{
								$output .= '<div class="flab-media-img">';
							}
	
							$output .= '<img src="'.flab::img( $image, 'flickr_feed' ).'" alt="'.$alt.'"'.( $widget['image_width'] > 0 ? ' width="'.$widget['image_width'].'"' : '' ).( $widget['image_height'] > 0 ? ' height="'.$widget['image_height'].'"' : '' ).' />';
	
							if( !isset( $widget['disable_lightbox'] ) || $widget['disable_lightbox'] != 'on' )
							{
								$output .= '</a>';
							}
							else
							{
								$output .= '</div>';
							}
	
							$output .= _n;
	
							$output .= _t( 5 ).'			</div>'._n;
							$output .= _t( 5 ).'		</div>'._n;
							
						}
						
					}
					
				}
	
				$output .= _t( 5 ).'	</div>'._n;
				$output .= _t( 5 ).'</div>'._n;*/
				
				$output = '<div class="row display flab_gallery flickr_gallery">';
				
					$album = time( );
	
					if( is_array( $flickr_feed ) && !empty( $flickr_feed ) )
					{
					
						foreach( $flickr_feed as $item )
						{
							
							$image = $item['image'];
							$alt = $item['title'];
							$num_of_cols_per_image = $this->convert_num_to_words( 12 / ( $count ) );
							
							//wp_die( print_r( $item, true ) );
						
							if( !empty( $image ) )
							{
							
								$output .= '<div class="' . $num_of_cols_per_image . ' columns">';
							
									if( !isset( $widget['disable_lightbox'] ) || $widget['disable_lightbox'] != 'on' )
										$output .= '<a href="'.$item['image'].'" class="flab-media-img" rel="lightbox[album-'.$album.']">';
									else
										$output .= '<div class="flab-media-img">';
			
									$output .= '<img src="'.flab::img( $image, 'flickr_feed' ).'" alt="'.$alt.'"'.( $widget['image_width'] > 0 ? ' width="'.$widget['image_width'].'"' : '' ).( $widget['image_height'] > 0 ? ' height="'.$widget['image_height'].'"' : '' ).' />';
			
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
	
				if( !isset( $count_options ) || $count_options === FALSE )
					$count_options = "";

				for( $i = 1; $i <= 20; $i++ )
				{
					
					$count_options .= '<option value="'.$i.'"'.( ( isset( $widget['count'] ) && $widget['count'] == $i ) ? ' selected="selected"' : '' ).'>'.$i.'</option>';
					
				}
	
				return '<fieldset class="flab-form">
					<div class="cols-3">
						<div class="col">
							<label>'.__( 'Flickr ID (Use <a href="http://idgettr.com/" title="" target="_blank">idgettr.com</a> to find your ID)' ).' <input type="text"'.$this->get_field_atts( 'flickr_id' ).' name="'.$this->get_field_name( 'flickr_id' ).'" value="'.( isset( $widget['flickr_id'] ) ? $widget['flickr_id'] : '' ).'" /></label>
						</div>
						<div class="col">
							<label>'.__( 'Tags' ).' <input type="text"'.$this->get_field_atts( 'tags' ).' name="'.$this->get_field_name( 'tags' ).'" value="'.( isset( $widget['tags'] ) ? $widget['tags'] : '' ).'" /></label>
						</div>
						<div class="col">
							<label>'.__( 'Count' ).' <select'.$this->get_field_atts( 'count' ).' name="'.$this->get_field_name( 'count' ).'" value="'.( isset( $widget['count'] ) ? $widget['count'] : '' ).'">'.$count_options.'</select></label>
						</div>
					</div>
					<div class="cols-3">
						<div class="col">
							<label class="label-alt-1"><input type="checkbox"'.$this->get_field_atts( 'disable_lightbox' ).' name="'.$this->get_field_name( 'disable_lightbox' ).'"'.( ( isset( $widget['disable_lightbox'] ) && $widget['disable_lightbox'] == 'on' ) ? ' checked="checked"' : '' ).'" /> '.__( 'Disable lightbox' ).'</label>
						</div>
					</div>
					<label>'.__( 'Additional classes' ).' <input type="text"'.$this->get_field_atts( 'classes' ).' name="'.$this->get_field_name( 'classes' ).'" value="'.( isset( $widget['classes'] ) ? $widget['classes'] : '' ).'" /></label>
				</fieldset>';
				
			}/* form() */
			
			/* ============================================================================ */
			
		}/* class flab_flickr_feed_widget */
		
	}
	
	/* ================================================================================ */

?>