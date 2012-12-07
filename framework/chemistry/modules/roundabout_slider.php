<?php

	if( !class_exists( 'chemistry_potion_roundabout_slider' ) )
	{

		class chemistry_potion_roundabout_slider extends chemistry_slider_ready_widget
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

				parent::__construct( 'roundabout', __( 'Roundabout Slider' , 'chemistry' ) );
				$this->label = __( 'Create a turntable-like interactive image gallery' , 'chemistry' );

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

				//Start clean
				$output = '';

				//Make sure we have the required fields
				$image = $data['image_url'];
				$alt = $data['image_alt'];

				//We *do* have something, right? i.e. we have an image with a url that exists...
				if( !empty( $image ) )
				{

					//Pass to our thumbnail method if we are cropping
					if( $widget['image_crop_width'] > 0 || $widget['image_crop_height'] > 0 )
						$image = Chemistry::get_or_make_thumbnail( Chemistry::get_raw_image_url( $image ), $widget['image_crop_width'], $widget['image_crop_height'] );

					//Pass to our image method to generate the markup for each image
					$output .= '<div><img src="'.Chemistry::chemistry_image( $image, 'roundabout' ) . '" alt="' . $alt . '"' . ( $widget['image_width'] > 0 ? ' width="' . $widget['image_width'] . '"' : '' ) . ( $widget['image_height'] > 0 ? ' height="' . $widget['image_height'] . '"' : '' ) . ' title="' . $alt . '" /></div>';
				}

				return $output;

			}/* item() */


			/* =========================================================================== */
			

			/**
			 * The output for our potion. This one creates a roundabout jquery slider for
			 * lots of images. It outputs the necessary javascript after it enqueues the
			 * library (and checks jQuery is loaded)
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (array) $widget - Widget config
			 * @return Markup
			 */
			
			public function widget( $widget )
			{

				//Compare passed options to our defaults
				$widget = chemistry_molecule_widget::extend( array( 

					'autoplay' => '',
					'autoplay_duration' => 1000,
					'duration' => 600,
					'min_opacity' => 0.4,
					'max_opacity' => 1.0,
					'min_scale' => 0.4,
					'max_scale' => 1.0,
					'classes' => ''

				), $widget );

				//Set some default classes
				$classes = array( 'widget', 'roundabout' );

				//Enqueue our js
				wp_enqueue_script( 'jquery.roundabout', Chemistry::path( 'assets/js/utilitylibraries/jquery.roundabout.js', true ), array( 'jquery' ), Chemistry::chemistry_option( 'chemistry_version' ) );

				//Start afresh
				$output = '';

				//Grab our widget config
				$widget['image_width'] = intval( $widget['image_width'] );
				$widget['image_height'] = intval( $widget['image_height'] );
				$widget['image_crop_width'] = intval( $widget['image_crop_width'] );
				$widget['image_crop_height'] = intval( $widget['image_crop_height'] );

				if( !empty( $widget['align'] ) )
					$classes[] = 'align' . $widget['align'];

				if( !empty( $widget['width'] ) )
					$widget['width'] = Chemistry::check_units_on_end_of_string( $widget['width'], 'px' );

				//Output our roundabout javascript
				$output .= '<script>jQuery( window ).load(  function() { var roundabout_adjust_height = function( e ) { var roundabout_height = 0; var $children = jQuery( e.target ).children(); $children.each(  function() { if( jQuery( this ).height() > roundabout_height ) roundabout_height = jQuery( this ).outerHeight(); } ); jQuery( e.target ).height( roundabout_height ); }; jQuery( ".chemistry-roundabout" ).bind( "childrenUpdated", roundabout_adjust_height ).bind( "ready", roundabout_adjust_height ).roundabout( { autoplay: ' . ( $widget['autoplay'] == 'on' ? 'true' : 'false' ) . ', autoplayDuration: ' . $widget['autoplay_duration'] . ', autoplayInitialDelay: ' . $widget['autoplay_duration'] . ', duration: ' . $widget['duration'] . ', minOpacity: ' . $widget['min_opacity'] . ', maxOpacity: ' . $widget['max_opacity'] . ', minScale: ' . $widget['min_scale'] . ', maxScale: ' . $widget['max_scale'] . ', childSelector: "div", responsive: true } ).roundabout( "relayoutChildren" ); } );</script>';

				$output .= '<div' . $this->_class( $classes, $widget['classes'] ) . ' style="margin: 0 auto; ' . (  !empty( $widget['width'] ) ? 'width: ' . $widget['width'] : '' ) . '">';

				//Grab the markup for each image
				if( isset( $widget['image_url'] ) && !empty( $widget['image_url'] ) )
				{

					$count = count( $widget['image_url'] );

					for( $i = 0; $i < $count; $i++ )
					{
						
						if( !empty( $widget['image_url'][$i] ) || !empty( $widget['image_alt'][$i] ) )
						{

							//For each item, let's set the url and alt text
							$output .= $this->item( $widget, array( 

								'image_url' => $widget['image_url'][$i],
								'image_alt' => $widget['image_alt'][$i]

							) );

						}

					}

				}

				//Clear up
				$output .= '</div>';

				//Done!
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
			 * Older versions of PHP don't have a proper range() function, let's just ensure
			 * we're all good. Idea from http://www.jonasjohn.de/snippets/php/array-range.htm
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.1
			 * @param (int) $first - Start
			 * @param (int) $items - How many
			 * @param (int) $step - Interval between items
			 * @return (array) $output - Our range
			 */
			
			public function array_range( $first, $items, $step )
			{

				$output = array();

				if( $first > 0 )
					$output[0] = $first;

				for( $i = 0; $i < $items; $i++ )
					$output[] = $first + ( $step * $i );

				return $output;

			}/* array_range() */


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

				//Compare passed options to our defaults
				$widget = chemistry_molecule_widget::extend( array( 

					'autoplay' => '',
					'autoplay_duration' => 1000,
					'duration' => 600,
					'min_opacity' => 0.4,
					'max_opacity' => 1.0,
					'min_scale' => 0.4,
					'max_scale' => 1.0

				 ), $widget );

				//Start clean
				$items = '';

				//If we've actually added some images
				if( isset( $widget['image_url'] ) && !empty( $widget['image_url'] ) )
				{

					$count = count( $widget['image_url'] );

					for( $i = 0; $i < $count; $i++ )
						if( !empty( $widget['image_url'][$i] ) || !empty( $widget['image_alt'][$i] ) )
							$items .= $this->group_item( $widget, $i );

				}

				//Start clean
				$duration = array();
				$scale = array();

				//We need to add some times and scales
				foreach( array_merge( $this->array_range( 100.0, 10, 100.0 ), $this->array_range( 2000.0, 4, 1000.0 ) ) as $value )
				{

					$seconds = ( $value / 1000.0 );

					$duration[$value] = $value > 1000 ? $seconds . __( ' seconds', 'chemistry' ) : $seconds . __( ' second', 'chemistry' );

				}

				//For our scale options we need 0-100 in raps of 10%
				for( $i = 10; $i <= 100; $i += 10 )
					$scale[( string )( $i / 100.0 )] = $i . "%";

				//Our markup
				return '<fieldset class="chemistry-form">
					<h2 class="chemistry-tab-title">' . __( 'General' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						' . $this->form_widget_general( $widget ) . '
						' . $this->form_posts( $widget, 'tile', 'tileset' ) . '
					</div>
					<h2 class="chemistry-tab-title">' . __( 'Add New' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<div class="chemistry-form sortable-content group-content-wrap">
							<div class="buttonset-1">
								<button type="submit"' . $this->get_field_atts( 'add_item' ) . ' name="' . $this->get_field_name( 'add_item' ) . '" class="button button-primary molecule-widget-group-item-add">' . __( 'Add' , 'chemistry' ) . '</button>
								
							</div>
							<div class="group-prototype">' . $this->group_item( array(), -1 ) . '</div>
							<div class="group-content">
								<div class="cols-3 cols">
									' . $items.'
								</div>
							</div>
						</div>
					</div>
					<h2 class="chemistry-tab-title">' . __( 'Image Settings' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">' . $this->form_image_dimensions( $widget ) . '</div>
					<h2 class="chemistry-tab-title">' . __( 'Slider Settings' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<div class="cols cols-3">
							<div class="col">
								<label><span class="label-title">' . __( 'Duration' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'duration', $widget, array( 'options' => $duration ) ) . '</label>
							</div>
							<div class="col">
								<label><span class="label-title">' . __( 'Min scale' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'min_scale', $widget, array( 'options' => $scale ) ) . '</label>
							</div>
							<div class="col">
								<label><span class="label-title">' . __( 'Max scale' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'max_scale', $widget, array( 'options' => $scale ) ) . '</label>
							</div>
						</div>
						<div class="cols cols-2">
							<div class="col">
								<label><span class="label-title">' . __( 'Min opacity' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'min_opacity', $widget, array( 'options' => $scale ) ) . '</label>
							</div>
							<div class="col">
								<label><span class="label-title">' . __( 'Max opacity' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'max_opacity', $widget, array( 'options' => $scale ) ) . '</label>
							</div>
						</div>
						<div class="cols cols-2">
							<div class="col">
								<label>' . $this->field( 'checkbox', 'autoplay', $widget, array( 'class' => 'chemistry-cond chemistry-group-roundabout' ) ) . ' <span class="label-title">' . __( 'Autoplay' , 'chemistry' ) . '</span></label>
							</div>
							<div class="col">
								<label><span class="label-title">' . __( 'Autoplay duration' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'autoplay_duration', $widget, array( 'options' => $duration ) ) . '</label>
							</div>
						</div>
					</div>
					<h2 class="chemistry-tab-title">' . __( 'Other' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<label><span class="label-title">' . __( 'Additional classes' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'classes', $widget ) . '</label>
					<div>
				</fieldset>';

			}/* form() */

		}/* class chemistry_potion_roundabout_slider */

	}/* !class_exists( 'chemistry_potion_roundabout_slider' ) */

?>