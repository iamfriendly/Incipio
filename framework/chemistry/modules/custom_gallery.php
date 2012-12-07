<?php

	if( !class_exists( 'chemistry_potion_custom_gallery' ) )
	{

		class chemistry_potion_custom_gallery extends chemistry_slider_ready_widget
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

				parent::__construct( 'gallery', __( 'Gallery' , 'chemistry' ) );
				$this->label = __( 'For when one image just isn\'t enough.'  , 'chemistry' );

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
			 * @param (int) $i - The count of items added
			 * @return Markup for each item
			 */

			public function item( $widget, $data, $hidden = false )
			{

				//Start clean
				$output = '';

				//Grab our attributes
				$image = $data['image_url'];
				$alt = $data['image_alt'];
				$link = $data['image_link'];

				//As long as we have something to output...
				if( !empty( $image ) )
				{

					//Are we cropping? If so, pass to our thumbnail machine
					if( $widget['image_crop_width'] > 0 || $widget['image_crop_height'] > 0 )
						$image = Chemistry::get_or_make_thumbnail( Chemistry::get_raw_image_url( $image ), $widget['image_crop_width'], $widget['image_crop_height'] );

					//Some starter-output
					$output .= '<div' . $this->_class( 'col' ) . ( $hidden ? ' style="display: none;"' : '' ) . '>';
					$output .= '<div' . $this->_class( 'gallery-item' ) . '>';

					//Are we showing a video? Unlikely but possible. If so pass to our video method
					$is_video = Chemistry::video( $image );

					//If we are showing a video, output the video markup
					if( !empty( $is_video ) )
					{

						$output .= '<div' . $this->_class( 'media-video' ) . '>' . $is_video . ' </div>';

					}
					else
					{

						//We're an image. Do we want a lightbox?
						if( $widget['disable_lightbox'] != 'on' || !empty( $link ) )
							$output .= '<a href="' . (  !empty( $link ) ? $link : $image ) . '"' . $this->_class( 'media-img' ) . ' rel="lightbox[album-' . $data['album'] . ']">';
						else
							$output .= '<div' . $this->_class( 'media-img' ) . '>';

						//Build the actual image tag
						$output .= '<img src="'.Chemistry::chemistry_image( $image, 'gallery' ) . '" alt="' . $alt . '"' . ( $widget['image_width'] > 0 ? ' width="' . $widget['image_width'] . '"' : '' ) . ( $widget['image_height'] > 0 ? ' height="' . $widget['image_height'] . '"' : '' ) . ' />';

						//Style-specifics
						if( $widget['frame'] == 2 )
							$output .= '<div' . $this->_class( 'media-helper' ) . '></div>';

						//Close ourselves if we're on a lightbox
						if( $widget['disable_lightbox'] != 'on' || !empty( $link ) )
							$output .= '</a>';
						else
							$output .= '</div>';
						
					}

					//Cleanup
					$output .= '</div>';
					$output .= '</div>';

				}

				//Bad robot
				return $output;

			}/* item() */


			/* =========================================================================== */
			

			public function widget( $widget )
			{

				//Compare passed options to our defaults
				$widget = chemistry_molecule_widget::extend( array( 

					'align' => '',
					'width' => '',
					'ratio' => 100,
					'image_mode' => 'auto',
					'frame' => '',
					'enable_title' => '',
					'disable_lightbox' => '',
					'term' => '',
					'disable_spacing' => '',
					'front_only' => '',
					'classes' => ''

				 ), $widget );

				//Check we're all numbers up in this house
				$widget['image_width'] = intval( $widget['image_width'] );
				$widget['image_height'] = intval( $widget['image_height'] );
				$widget['image_crop_width'] = intval( $widget['image_crop_width'] );
				$widget['image_crop_height'] = intval( $widget['image_crop_height'] );

				//Check our units endings
				if( !empty( $widget['width'] ) )
					$widget['width'] = Chemistry::check_units_on_end_of_string( $widget['width'], 'px' );

				//Build our classes
				$classes = array_merge( array( 'widget', 'gallery' ), $this->get_classes( $widget ) );

				if( $widget['front_only'] == 'on' )
					$widget['slider'] = '';

				if( !empty( $widget['align'] ) )
					$classes[] = 'align' . $widget['align'];

				if( !empty( $widget['frame'] ) )
					$classes[] = 'frame-style-' . $widget['frame'];

				if( $widget['enable_title'] == 'on' )
					$classes[] = 'gallery-img-title-1';

				$classes[] = 'media-height-ratio-' . $widget['ratio'];
				$classes[] = 'media-height-' . $widget['height'];
				$classes[] = 'image-stretch-mode-' . $widget['image_mode'];
				$classes[] = 'hide-grid-cell-overflow-0';

				//Begin our output
				$output = '<div' . $this->_class( $classes, $widget['classes'] ) . 'style="' . (  !empty( $widget['width'] ) ? 'width: ' . $widget['width'] : '' ) . '">';
				$output .= '<div' . $this->_class( array( 'cols', 'cols-' . $widget['columns'], 'rows-' . $widget['rows'], 'spacing-' . ( $widget['disable_spacing'] == 'on' ? 0 : 1 ) ) ) . '>';

				$album = time();

				//Build our per-item markup
				if( isset( $widget['image_url'] ) && !empty( $widget['image_url'] ) )
				{

					$count = count( $widget['image_url'] );

					for( $i = 0; $i < $count; $i++ )
					{

						if( !empty( $widget['image_url'][$i] ) || !empty( $widget['image_alt'][$i] ) )
						{

							$hidden = $i > 1 ? true : false;

							if( empty( $widget['front_only'] ) )
								$hidden = false;

							$output .= $this->item( $widget, array( 

								'image_url' => $widget['image_url'][$i],
								'image_alt' => $widget['image_alt'][$i],
								'image_link' => $widget['image_link'][$i],
								'album' => $album

							 ), $hidden );

						}

					}

				}

				//Tidy up
				$output .= '</div>';
				$output .= '</div>';

				//For great victory
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

				return '<div class="col"' . ( empty( $widget ) ? ' style="display: none;"' : '' ) . '><div class="group-item">
					<div class="group-item-title">' . __( 'Item' , 'chemistry' ) . '</div>
					<div class="group-item-content">
						<div class="preview-img-wrap"><img src="' . ( $i >= 0 ? $widget['image_url'][$i] : '' ) . '" class="chemistry-preview upload_image" /></div>
						<label><span class="label-title">' . __( 'Image URL' , 'chemistry' ) . '</span> ' . $this->group_field( 'text', 'image_url', $i, $widget, array( 'class' => 'upload_image' ) ) . '</label>
						<label><span class="label-title">' . __( 'Image alt' , 'chemistry' ) . '</span> ' . $this->group_field( 'text', 'image_alt', $i, $widget ) . '</label>
						<label><span class="label-title">' . __( 'Link URL' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'image_link][', ( isset( $widget['image_link'][$i] )  ? $widget['image_link'][$i] : '' ) ) . '</label>
					</div>
					<div class="group-item-actions">
						<button type="submit"' . $this->get_field_atts( 'change_item' ) . ' name="' . $this->get_field_name( 'change_item' ) . '" class="molecule-widget-gallery-change upload_image single callback-molecule_gallery_widget_change molecule-widget-group-item-edit-image">' . __( 'Edit' , 'chemistry' ) . '</button>
						<button type="submit"' . $this->get_field_atts( 'remove_item' ) . ' name="' . $this->get_field_name( 'remove_item' ) . '" class="molecule-widget-group-item-remove">' . __( 'Remove' , 'chemistry' ) . '</button>
					</div>
				</div></div>';

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

				//Compare passed options to our defaults
				$widget = chemistry_molecule_widget::extend( array( 

					'ratio' => 100

				 ), $widget );

				//Start clean
				$items = '';

				//If we have stuff to show...
				if( isset( $widget['image_url'] ) && !empty( $widget['image_url'] ) )
				{

					$count = count( $widget['image_url'] );

					for( $i = 0; $i < $count; $i++ )
					{

						if( !isset( $widget['image_link'] ) )
							$widget['image_link'] = array();

						if( !empty( $widget['image_url'][$i] ) || !empty( $widget['image_alt'][$i] ) )
							$items .= $this->group_item( $widget, $i );

					}

				}

				//Markup for our form
				return '<fieldset class="chemistry-form">
					<h2 class="chemistry-tab-title">' . __( 'General' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						' . $this->form_widget_general( $widget ) . '
						' . $this->form_posts( $widget, 'tile', 'tileset' ) . '
						<div class="cols cols-2">
							<div class="col">
								<label>' . $this->field( 'checkbox', 'front_only', $widget ) . ' <span class="label-title">' . __( 'Front image only' , 'chemistry' ) . '</span></label>
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
								<div class="cols-3 cols">
									' . $items.'
								</div>
							</div>
						</div>
					</div>
					<h2 class="chemistry-tab-title">' . __( 'Item Settings' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						' . $this->form_media_frame( $widget ) . '
						' . $this->form_image_dimensions( $widget ) . '
					</div>
					' . $this->form_common( $widget ) . '
					' . $this->form_slider( $widget ) . '
					<h2 class="chemistry-tab-title">' . __( 'Other' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<label><span class="label-title">' . __( 'Additional classes' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'classes', $widget ) . '</label>
					</div>
				</fieldset>';

			}/* form() */

		}/* class chemistry_potion_custom_gallery */

	}/* !class_exists( 'chemistry_potion_custom_gallery' ) */

?>