<?php

	if( !class_exists( 'chemistry_potion_twitter_feed' ) )
	{

		class chemistry_potion_twitter_feed extends chemistry_slider_ready_widget
		{

			/**
			 * Register this potion
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param None
			 * @return None
			 */
			
			public function __construct()
			{

				parent::__construct( 'twitter-feed', __( 'Twitter feed' , 'chemistry' ) );
				$this->label = __( 'Want to showcase your latest tweets? Optionally in a slider, too.' , 'chemistry' );

			}/* __construct() */


			/* =========================================================================== */
			
			/**
			 * The output for our potion. This is basically just shunted off to our twitter method
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (array) $widget - Where we're at
			 * @return Markup
			 */
			
			public function widget( $widget )
			{

				//Compare passed options to our defaults
				$widget = chemistry_molecule_widget::extend( array( 

					'align' => '',
					'width' => '',
					'classes' => ''

				 ), $widget );

				//Start fresh and add some default classes
				$output = '';
				$classes = array_merge( array( 'widget', 'twitter-feed' ), $this->get_classes( $widget ) );

				//Ensure we're all good with units
				if( !empty( $widget['width'] ) )
					$widget['width'] = Chemistry::check_units_on_end_of_string( $widget['width'], 'px' );

				if( !empty( $widget['align'] ) )
					$classes[] = 'align' . $widget['align'];

				//Begin our markup
				$output .= '<div' . $this->_class( $classes, $widget['classes'] ) . 'style="' . (  !empty( $widget['width'] ) ? 'width: ' . $widget['width'] : '' ) . '">';
				$output .= '	<div' . $this->_class( array( 'cols', 'cols-' . $widget['columns'], 'rows-' . $widget['rows'] ) ) . '>';

				//How many in total? (product of number of rows and number of columns)
				$count = $widget['columns'] * $widget['rows'];

				//If we're in a slider, we should grab 10 max
				if( isset( $widget['slider'] ) && $widget['slider'] == 'on' )
					$count = 10;

				//Get our tweets from our twitter_feed method
				$tweets = Chemistry::twitter_feed( $widget['username'], $count );

				//Markup for each tweet
				foreach( $tweets as $tweet )
				{

					$output .= '<div' . $this->_class( 'col' ) . '>';
					$output .= '<div' . $this->_class( 'twitter-feed-item' ) . '>';
					$output .= '<span>' . $tweet['tweet'] . '</span> <a href="' . $tweet['link'] . '">' . $tweet['time'] . '</a>';
					$output .= '</div>';
					$output .= '</div>';

				}

				//Tidy up
				$output .= '</div>';
				$output .= '</div>';

				//Bingo
				return $output;

			}/* widget() */


			/* =========================================================================== */
			
			/**
			 * The admin form for this widget
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (array) $widget - The specific details for this widget
			 * @return The markup
			 */
			
			public function form( $widget )
			{

				return '<fieldset class="chemistry-form">
					<h2 class="chemistry-tab-title">' . __( 'General' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<div class="cols-1 cols">
							<div class="col"><label><span class="label-title">' . __( 'Username' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'username', $widget ) . '</label></div>
						</div>
					</div>
					' . $this->form_common( $widget ) . '
					' . $this->form_slider( $widget ) . '
					<h2 class="chemistry-tab-title">' . __( 'Other' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<label><span class="label-title">' . __( 'Additional classes' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'classes', $widget ) . '</label>
					</div>
				</fieldset>';

			}/* form() */

		}/* class chemistry_potion_twitter_feed */

	}/* !class_exists( 'chemistry_potion_twitter_feed' ) */

?>