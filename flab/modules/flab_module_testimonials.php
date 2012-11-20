<?php

	/* ================================================================================ */
	
	if( !class_exists( 'flab_testimonials_widget' ) )
	{
	
		class flab_testimonials_widget extends flab_builder_widget
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
				
				parent::__construct( 'testimonials', __( 'Testimonials' ) );
				$this->label = __( 'Testimonials widget.' );
				
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
				
				$output = '';
				//$classes = $this->get_classes( $widget );
	
				if( !isset( $widget['style'] ) || empty( $widget['style'] ) )
				{
					$widget['style'] = '1';
				}
	
				$output .= "<div class='row display flab-testimonials'>";

				$output .= '<div class="flab-widget flab-quotes '.( ( isset( $widget['style'] ) && !empty( $widget['style'] ) ) ? ' flab-quotes-'.$widget['style'] : '' ).( ( isset( $widget['classes'] ) && !empty( $widget['classes'] ) ) ? ' '.$widget['classes'] : '' ).'">'._n;
				
				$output .= '	<div class="flab-cols flab-spacing-'.( ( isset( $widget['disable_spacing'] ) && $widget['disable_spacing'] == 'on' ) ? '0' : '1' ).'">'._n;
	
				if( isset( $widget['testimonial_content'] ) && !empty( $widget['testimonial_content'] ) )
				{
					
					$count = count( $widget['testimonial_content'] );
					$num_of_cols_per_image = $this->convert_num_to_words( 12 / ( $count - 1 ) );
	
					for ( $i = 0; $i < $count; $i++ )
					{
						if( !empty( $widget['testimonial_author'][$i] ) || !empty( $widget['testimonial_url'][$i] ) || !empty( $widget['testimonial_content'][$i] ) )
						{
							
							$output .= '<div class="' . $num_of_cols_per_image . ' columns">';
							$output .= '			<div class="flab-quotes-item">'._n;
							$output .= '				<blockquote>'.wpautop( $widget['testimonial_content'][$i] ).'</blockquote>'._n;
	
							if( isset( $widget['testimonial_author'][$i] ) && !empty( $widget['testimonial_author'][$i] ) )
							{
								$output .= '				<p class="meta">'.$widget['testimonial_author'][$i].'</p>'._n;
							}
	
							$output .= '			</div>'._n;
							$output .= '		</div>'._n;
							
						}
						
					}
					
				}
	
				$output .= '	</div>'._n;
				$output .= '</div>'._n;
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
				
				$styles = array(
				
					'1' => __( 'Style 1' ),
					'2' => __( 'Style 2' )
					
				 );
	
				$style_options = '';
	
				foreach ( $styles as $value => $label )
				{
					
					$style_options .= '<option value="'.$value.'"'.( ( isset( $widget['style'] ) && $widget['style'] == $value ) ? ' selected="selected"' : '' ).'>'.$label.'</option>';
					
				}
	
				$testimonials = '';
	
				$testimonials .= '<div class="col" style="display: none;">
					<div class="group-item">
						<label>'.__( 'Who Said It?' ).' <input type="text"'.$this->get_field_atts( 'testimonial_author][' ).' name="'.$this->get_field_name( 'testimonial_author][' ).'" value="" /></label>
						<label>'.__( 'Quote' ).' <textarea'.$this->get_field_atts( 'testimonial_content][' ).' name="'.$this->get_field_name( 'testimonial_content][' ).'" rows="5"></textarea></label>
						<div class="buttonset-1">
							<button name="builder-widget-tab-remove" class="button-1 button-1-1 alignright builder-widget-group-item-remove">'.__( 'Remove' ).'</button>
						</div>
					</div>
				</div>';
	
				if( isset( $widget['testimonial_content'] ) )
				{
					
					$column = 0;
	
					for ( $i = 0; $i < count( $widget['testimonial_content'] ); $i++ )
					{
						
						if( !empty( $widget['testimonial_author'][$i] ) || !empty( $widget['testimonial_url'][$i] ) || !empty( $widget['testimonial_content'][$i] ) )
						{
							
							$testimonials .= '<div class="col">
								<div class="group-item">
									<label>'.__( 'Who Said It?' ).' <input type="text"'.$this->get_field_atts( 'testimonial_author][' ).' name="'.$this->get_field_name( 'testimonial_author][' ).'" value="'.$widget['testimonial_author'][$i].'" /></label>
									<label>'.__( 'Quote' ).' <textarea'.$this->get_field_atts( 'testimonial_content][' ).' name="'.$this->get_field_name( 'testimonial_content][' ).'" rows="5">'.$widget['testimonial_content'][$i].'</textarea></label>
									<div class="buttonset-1">
										<button name="builder-widget-testimonial-remove" class="button-1 button-1-1 alignright builder-widget-group-item-remove">'.__( 'Remove' ).'</button>
									</div>
								</div>
							</div>';
							
						}
						
					}
					
				}
	
				return '<fieldset class="flab-form">
					<label>'.__( 'Style' ).' <select'.$this->get_field_atts( 'style' ).' name="'.$this->get_field_name( 'style' ).'" value="'.( isset( $widget['style'] ) ? $widget['style'] : '' ).'">'.$style_options.'</select></label>
					<label>'.__( 'Additional classes' ).' <input type="text"'.$this->get_field_atts( 'classes' ).' name="'.$this->get_field_name( 'classes' ).'" value="'.( isset( $widget['classes'] ) ? $widget['classes'] : '' ).'" /></label>
					</fieldset>
					<fieldset class="flab-form">
					<div class="buttonset-1">
						<button name="builder-widget-group-item-add" class="button-1 button-1-2 alignright builder-widget-group-item-add">'.__( 'Add testimonial' ).'</button>
					</div>
					<div class="cols-3 group-content">
					'.$testimonials.'
					</div>
					<div class="buttonset-1" style="display: none;">
						<button name="builder-widget-group-item-add" class="button-1 button-1-2 alignright builder-widget-group-item-add">'.__( 'Add testimonial' ).'</button>
					</div>
				</fieldset>';
				
			}/* form() */
			
			/* ============================================================================ */
			
		}/* class flab_testimonials_widget */
		
	}
	
	/* ================================================================================ */

?>