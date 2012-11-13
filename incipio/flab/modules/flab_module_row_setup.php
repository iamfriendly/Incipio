<?php

	/* ================================================================================ */
	
	if( !class_exists( 'flab_row_base_widget' ) )
	{
	
		/**
		 * As we have several different column layouts, we can use a class to 'start' each one as they'll all have the same
		 * basic setup. Then, each column layout can simply extend this class with a __construct( ) function
		 *
		 * @package FLAB
		 * @author iamfriendly
		 * @version 1.0
		 * @since 1.0
		 */
	
		class flab_row_base_widget extends flab_builder_widget
		{
		
			protected $cols;
			protected $col_count;
			protected $splits;
	
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
	
				$output = '<div class="row display">';
	
				if( is_array( $this->splits ) )
				{
					
					foreach( $this->splits as $i =>$num_cols )
					{
						
						$output .= '<div class="' . $this->convert_num_to_words( $num_cols ) . ' columns">';
					
							$output .= ( isset( $widget['col-'.$i] ) ? $widget['col-'.$i] : '' );
					
						$output .= '</div>';
						
					}
					
				}
				else
				{
				
					for ( $i = 1; $i <= $this->col_count; $i++ )
					{
						
						$output .= '<div class="' . $this->convert_num_to_words( ( 12 / $this->cols ) ) . ' columns">'.( isset( $widget['col-'.$i] ) ? $widget['col-'.$i] : '' ).'</div>'._n;	
						
					}
					
				}
	
				$output .= '</div>'._n;
	
				return $output;
				
			}/* widget( ) */
			
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
				
				$cols = '<div class="builder-widget-row cols-'.$this->cols.'">';
				$options = '<div class="builder-widget-row-options cols-'.$this->cols.'">';
	
				for ( $i = 1; $i <= $this->col_count; $i++ )
				{
					
					$cols .= '<div class="col builder-widget-column">'.( isset( $widget['col-'.$i] ) ? $widget['col-'.$i] : '' ).'</div>';
					$options .= ' <div class="col builder-widget-column-options"><button name="builder-widget-add" class="button-1 button-1-1 builder-widget-add"><span>'.__( 'Add Module' ).'</span></button></div>';
					
				}
	
				$cols .= '</div>';
				$options .= '</div>';
	
				return $cols.$options;
				
			}/* form( ) */
			
			/* ============================================================================ */
			
		}/* class flab_row_base_widget */
		
	}
	
	/* ================================================================================ */

?>