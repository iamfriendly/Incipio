<?php

	/* ================================================================================ */
	
	if( !class_exists( 'flab_twitter_feed_widget' ) )
	{
	
		class flab_twitter_feed_widget extends flab_slider_ready_widget
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
				
				parent::__construct( 'twitter-feed', __( 'Twitter feed' ) );
				$this->label = __( 'Twitter feed' );
				
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
				
				$output = '';
				$classes = $this->get_classes( $widget );
	
				$output .= '<div class="row display flab-widget flab-twitter-feed">'._n;
	
				$twitters_api_is_being_stupid_by_this_much = 2;
				$count = $widget['count'] + $twitters_api_is_being_stupid_by_this_much;
	
				$tweets = flab::twitter_feed( $widget['username'], $count );
	
				foreach( $tweets as $tweet )
				{
					
					$output .= '		<div class="' . $this->convert_num_to_words( (12 / $widget['count'] ) ) . ' columns">'._n;
					$output .= '			<div class="flab-tweet">'._n;
					$output .= '				<span>'.$tweet['tweet'].'</span> <a href="'.$tweet['link'].'">'.$tweet['time'].'</a>'._n;
					$output .= '			</div>'._n;
					$output .= '		</div>'._n;
					
				}
	
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
				
				return '<fieldset class="flab-form">
					<div class="cols-3">
						<div class="col">
							<label>
								'.__( 'Username (without \'@\')' ).' <input type="text"'.$this->get_field_atts( 'username' ).' name="'.$this->get_field_name( 'username' ).'" value="'.( isset( $widget['username'] ) ? $widget['username'] : '' ).'" />
							</label>
						</div>
						<div class="col">
							<label>
								'.__( 'Number Of Tweets' ).' <input type="text"'.$this->get_field_atts( 'count' ).' name="'.$this->get_field_name( 'count' ).'" value="'.( isset( $widget['count'] ) ? $widget['count'] : '' ).'" />
							</label>
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
			
		}/* class flab_twitter_feed_widget */
		
	}
	
	/* ================================================================================ */

?>