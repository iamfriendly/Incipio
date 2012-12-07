<?php

	if( !class_exists( 'chemistry_potion_nivo_slider' ) )
	{

		class chemistry_potion_nivo_slider extends chemistry_slider_ready_widget
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

				parent::__construct( 'nivo', __( 'Nivo slider' , 'chemistry' ) );
				$this->label = __( 'The world-famous Nivo Slider to beautifully showcase your images' , 'chemistry' );

			}/* __construct() */


			/* =========================================================================== */
			
			/**
			 * When we add a variable number of "items" (such as images to sliders or groups
			 * of fields to Services or Roundabout sliders) then we have this method which 
			 * allows us to contain all of the markup for each single 'item' which is added
			 * when someone presses 'Add New'
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (array) $widget - Which widget we're in
			 * @param (array) $data - The data array
			 * @return Markup for each item
			 */

			public function item( $widget, $data )
			{

				$output = '';

				$image = $data['image_url'];
				$alt = $data['image_alt'];

				if( !empty( $image ) )
				{

					if( $widget['image_crop_width'] > 0 || $widget['image_crop_height'] > 0 )
						$image = Chemistry::get_or_make_thumbnail( Chemistry::get_raw_image_url( $image ), $widget['image_crop_width'], $widget['image_crop_height'] );

					$output .= '<img src="'.Chemistry::chemistry_image( $image, 'nivo' ) . '" alt="' . $alt . '"' . ( $widget['image_width'] > 0 ? ' width="' . $widget['image_width'] . '"' : '' ) . ( $widget['image_height'] > 0 ? ' height="' . $widget['image_height'] . '"' : '' ) . ' title="' . $alt . '" />';

				}

				return $output;

			}/* item() */


			/* =========================================================================== */
			

			public function widget( $widget )
			{

				//Compare passed options to our defaults
				$widget = chemistry_molecule_widget::extend( array( 

					'effect' => 'fade',
					'anim_speed' => 100,
					'pause_time' => 1000,
					'align' => '',
					'classes' => ''

				 ), $widget );

				//Some default classes
				$classes = array( 'widget', 'slider-wrapper', 'nivo-slider-wrapper', 'chemestry-nivo' );

				if( !empty( $widget['align'] ) )
					$classes[] = 'align' . $widget['align'];

				//Load our CSS and JS
				wp_enqueue_style( 'jquery.nivo.slider.css', Chemistry::path( 'assets/css/utilitylibraries/nivo-slider/nivo-slider.css', true ), '', Chemistry::chemistry_option( 'chemistry_version' ) );

				wp_enqueue_style( 'jquery.nivo.default.theme', Chemistry::path( 'assets/css/utilitylibraries/nivo-slider/default.css', true ), '', Chemistry::chemistry_option( 'chemistry_version' ) );

				wp_enqueue_script( 'jquery.nivo.slider', Chemistry::path( 'assets/js/utilitylibraries/jquery.nivo.slider.js', true ), array( 'jquery' ), Chemistry::chemistry_option( 'chemistry_version' ) );

				//Start afresh
				$output = '';

				//Make sure we don't double up (or have empty) units 
				preg_match( '/( \d* )( .* )/', $widget['image_width'], $width_unit );
				if( !array_key_exists( 2, $width_unit ) )
					$width_unit[2] = '';
				
				$width_unit = $width_unit[2] === '' ? 'px' : $width_unit[2];

				preg_match( '/( \d* )( .* )/', $widget['image_height'], $height_unit );
				if( !array_key_exists( 2, $height_unit ) )
					$height_unit[2] = '';
				
				$height_unit = $height_unit[2] === '' ? 'px' : $height_unit[2];

				//Ensure we're numerical for our dimensions
				$widget['image_width'] = intval( $widget['image_width'] );
				$widget['image_height'] = intval( $widget['image_height'] );
				$widget['image_crop_width'] = intval( $widget['image_crop_width'] );
				$widget['image_crop_height'] = intval( $widget['image_crop_height'] );

				//Generate a random ID
				$id = substr( uniqid(), -6 );

				//Output our slider-specific markup
				$output .= '<script>jQuery( window ).load(  function() { jQuery( "#nivo-slider-' . $id.'" ).nivoSlider( { effect: \'' . $widget['effect'] . '\', animSpeed: ' . intval( $widget['anim_speed'] ) . ', pauseTime: ' . intval( $widget['pause_time'] ) . '} ); } );</script>';

				$output .= ' <div' . $this->_class( $classes, $widget['classes'] . ' theme-default' ) . ' style="' . ( $widget['image_width'] > 0 ? ' width: ' . $widget['image_width'] . 'px;' : ( $widget['image_crop_width'] > 0 ? 'width: ' . $widget['image_crop_width'] . 'px;' : '' ) ) . '">';
				$output .= '	<div id="nivo-slider-' . $id.'" class="nivoSlider"' . ( $widget['image_width'] > 0 ? ' style="width: 100%;"' : '' ) . '>';

				$album = time();

				//Build the markup from each image section
				if( isset( $widget['image_url'] ) && !empty( $widget['image_url'] ) )
				{
					$count = count( $widget['image_url'] );

					for( $i = 0; $i < $count; $i++ )
					{

						if( !empty( $widget['image_url'][$i] ) || !empty( $widget['image_alt'][$i] ) )
						{

							$output .= $this->item( $widget, array(

								'image_url' => $widget['image_url'][$i],
								'image_alt' => $widget['image_alt'][$i]

							 ) );

						}

					}

				}

				//Clean up
				$output .= '	</div>';
				$output .= '</div>';

				//Ship it!
				return $output;

			}/* widget() */


			/* =========================================================================== */
			

			/**
			 * When we add a variable number of "items" (such as images to sliders or groups
			 * of fields to Services or Roundabout sliders) then we have this method which 
			 * allows us to contain all of the markup for each single 'item' which is added
			 * when someone presses 'Add New'
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (array) $widget - Which widget we're in
			 * @param (int) $i - The count of items added
			 * @return Markup for each item
			 */

			public function group_item( $widget, $i )
			{

				return '<div class="col"' . ( empty( $widget ) ? ' style="display: none;"' : '' ) . '>
					<div class="group-item">
						<div class="group-item-title">' . __( 'Item' , 'chemistry' ) . '</div>
						<div class="group-item-content">
							<div class="preview-img-wrap"><img src="' . ( $i >= 0 ? $widget['image_url'][$i] : '' ) . '" class="chemistry-preview upload_image" /></div>

							<label><span class="label-title">' . __( 'Image URL' , 'chemistry' ) . '</span> ' . $this->group_field( 'text', 'image_url', $i, $widget, array( 'class' => 'upload_image' ) ) . '</label>
							<label><span class="label-title">' . __( 'Image alt' , 'chemistry' ) . '</span> ' . $this->group_field( 'text', 'image_alt', $i, $widget ) . '</label>
						</div>
						<div class="group-item-actions">
							<button type="submit"' . $this->get_field_atts( 'change_item' ) . ' name="' . $this->get_field_name( 'change_item' ) . '" class="molecule-widget-gallery-change upload_image single callback-molecule_gallery_widget_change molecule-widget-group-item-edit-image">' . __( 'Edit' , 'chemistry' ) . '</button>
							<button type="submit"' . $this->get_field_atts( 'remove_item' ) . ' name="' . $this->get_field_name( 'remove_item' ) . '" class="molecule-widget-group-item-remove">' . __( 'Remove' , 'chemistry' ) . '</button>
						</div>
					</div>
				</div>';

			}/* group_item() */


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

				//Nivo has a lot of effects, give the user the option of which one to use
				$effects = array( 

				    'fade' => 				__( 'Fade' , 'chemistry' ),
					'fold' => 				__( 'Fold' , 'chemistry' ),
					'sliceDown' => 			__( 'Slice down' , 'chemistry' ),
					'sliceDownLeft' => 		__( 'Slice down left' , 'chemistry' ),
					'sliceUp' => 			__( 'Slice up' , 'chemistry' ),
					'sliceUpLeft' => 		__( 'Slice left' , 'chemistry' ),
					'sliceUpDown' => 		__( 'Slice up/down' , 'chemistry' ),
					'sliceUpDownLeft' => 	__( 'Slice up/down left' , 'chemistry' ),
					'slideInRight' => 		__( 'Slide in right' , 'chemistry' ),
					'slideInLeft' => 		__( 'Slide in left' , 'chemistry' ),
					'boxRandom' => 			__( 'Box random' , 'chemistry' ),
					'boxRain' => 			__( 'Box rain' , 'chemistry' ),
					'boxRainReverse' => 	__( 'Box rain reverse' , 'chemistry' ),
					'boxRainGrow' => 		__( 'Box rain grow' , 'chemistry' ),
					'boxRainGrowReverse' => __( 'Box rain grow reverse' , 'chemistry' ),
					'random' => 			__( 'Random' , 'chemistry' ),

				 );

				//We can align (if we're not responsivising) the slider
				$aligns = array( 

					'' => 			__( 'Default' , 'chemistry' ),
					'left' => 		__( 'Left' , 'chemistry' ),
					'right' => 		__( 'Right' , 'chemistry' ),
					'center' => 	__( 'Centre' , 'chemistry' ) //Let's spell it properly, shall we? Yay for being British

				 );

				//0 - 2s in steps of 0.1
				$anim_speed = array();
				for( $i = 1; $i <= 20; $i++ )
					$anim_speed[$i * 100] = ( $i / 10 ) . 's';

				//How long should each slide be on for?
				$pause_time = array();
				foreach( array( 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 ) as $value )
					$pause_time[$value * 1000] = $value.'s';

				//Grab our images
				$items = '';

				if( isset( $widget['image_url'] ) && !empty( $widget['image_url'] ) )
				{

					$count = count( $widget['image_url'] );

					for( $i = 0; $i < $count; $i++ )
						if( !empty( $widget['image_url'][$i] ) || !empty( $widget['image_alt'][$i] ) )
							$items .= $this->group_item( $widget, $i );

				}

				//Our markup
				return '<fieldset class="chemistry-form">
					<h2 class="chemistry-tab-title">' . __( 'General' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<div class="cols-1">
							<div class="col">
								<label><span class="label-title">' . __( 'Alignment' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'align', $widget, array( 'options' => $aligns ) ) . '</label>
							</div>
						</div>
					</div>
					<h2 class="chemistry-tab-title">' . __( 'Add New' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<div class="chemistry-form sortable-content  group-content-wrap">
							<div class="buttonset-1">
								<button type="submit"' . $this->get_field_atts( 'add_item' ) . ' name="' . $this->get_field_name( 'add_item' ) . '" class="button button-primary molecule-widget-group-item-add">' . __( 'Add' , 'chemistry' ) . '</button>
								
							</div>
							<div class="group-prototype">' . $this->group_item( array(), -1 ) . '</div>
							<div class="group-content">
								<div class="cols-3 cols">' . $items . '</div>
							</div>
						</div>
					</div>
					<h2 class="chemistry-tab-title">' . __( 'Image Settings' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						' . $this->form_image_dimensions( $widget ) . '
					</div>
					<h2 class="chemistry-tab-title">' . __( 'Slider Settings' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<div class="cols-3">
							<div class="col">
								<label><span class="label-title">' . __( 'Transition' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'effect', $widget, array( 'options' => $effects ) ) . '</label>
							</div>
							<div class="col">
								<label><span class="label-title">' . __( 'Animation speed' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'anim_speed', $widget, array( 'options' => $anim_speed ) ) . '</label>
							</div>
							<div class="col">
								<label><span class="label-title">' . __( 'Pause time' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'pause_time', $widget, array( 'options' => $pause_time ) ) . '</label>
							</div>
						</div>
					</div>
					<h2 class="chemistry-tab-title">' . __( 'Other' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<label><span class="label-title">' . __( 'Additional classes' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'classes', $widget ) . '</label>
						' . $this->form_posts( $widget, 'tile', 'tileset' ) . '
					</div>
				</fieldset>';

			}/* form() */

		}/* class chemistry_potion_nivo_slider */

	}/* !class_exists( 'chemistry_potion_nivo_slider' ) */

?>