<?php

	if( !class_exists( 'chemistry_potion_google_map' ) )
	{

		class chemistry_potion_google_map extends chemistry_molecule_widget
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

				parent::__construct( 'googlemap', __( 'Google map' , 'chemistry' ) );
				$this->label = __( 'Need a map? Then we just need an address.' , 'chemistry' );

			}/* __construct() */



			/* =========================================================================== */


			/**
			 * The output of our widget. This one calls our google_map method in our main class (abstracted
			 * as we may want to use gmaps elsewhere in the future). Parses all the passed options and pass
			 * those to our Chemistry::google_map() method
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (array) $widget - array of options
			 * @return Markup after running through our google_map method
			 */
			
			public function widget( $widget )
			{

				//Compare passed options to our defaults
				$widget = chemistry_molecule_widget::extend( array( 

					'zoom' => 14,
					'view' => 0,
					'show_address' => false,
					'classes' => ''

				 ), $widget );

				return '<div' . $this->_class( array( 'widget', 'google-map' ), $widget['classes'] ) . '>' . Chemistry::google_map( $widget['address'], '100%', empty( $widget['height'] ) ? NULL : intval( $widget['height'] ), $widget['zoom'], $widget['view'], ( $widget['show_address'] != 'on' ), true ) . '</div>';

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

				//Set up an array for the different map types
				$zoom = array();
				$zoom[''] = __( 'Default' , 'chemistry' );

				//There are 20 zoom levels
				for( $i = 1; $i <= 20; $i++ )
					$zoom[$i] = $i . ( $i == 14 ? ' ( ' . __( 'Default' , 'chemistry' ) . ' )' : '' );

				//Here are the normal views
				$views = array(

					__( 'Map' , 'chemistry' ),
					__( 'Satellite' , 'chemistry' ),
					__( 'Map & Terrain' , 'chemistry' )

				 );

				//The markup
				return '<fieldset class="chemistry-form">
					<h2 class="chemistry-tab-title">' . __( 'General' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<label><span class="label-title">' . __( 'Address' , 'chemistry' ) . ' ' . $this->field( 'text', 'address', $widget ) . '</label>
						<div class="cols-2">
							<div class="col">
								<label><span class="label-title">' . __( 'Height' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'height', $widget ) . '</label>
							</div>
							<div class="col">
								<label><span class="label-title">' . __( 'Zoom' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'zoom', $widget, array( 'options' => $zoom ) ) . '</label>
							</div>
							<div class="col">
								<label><span class="label-title">' . __( 'View' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'view', $widget, array( 'options' => $views ) ) . '</label></label>
							</div>
							<div class="col">
								<label class="label-alt-1">' . $this->field( 'checkbox', 'show_address', $widget ) . ' <span class="label-title">' . __( 'Show address label on page load?' , 'chemistry' ) . '</span></label>
							</div>
						</div>
					</div>
					
					<h2 class="chemistry-tab-title">' . __( 'Other' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<div class="cols-1">
							<div class="col">
								<label><span class="label-title">' . __( 'Additional classes' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'classes', $widget ) . '</label>
							</div>
						</div>
					</div>
				</fieldset>';

			}/* form() */

		}/* class chemistry_potion_google_map */

	}/* !class_exists( 'chemistry_potion_google_map' ) */

?>