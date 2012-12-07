<?php

	if( !class_exists( 'chemistry_potion_single_image' ) )
	{

		class chemistry_potion_single_image extends chemistry_molecule_widget
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

				parent::__construct( 'image', __( 'Image' , 'chemistry' ) );
				$this->label = __( 'A single image with optional title, link and frame' , 'chemistry' );

			}/* __construct() */


			/* =========================================================================== */
			
			/**
			 * Markup for our single image.
			 *
			 * Note: This is way, way too complicated for what it actually is
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (array) $widget - Widget config
			 * @return Markup
			 * @todo Tidy this the hell up 
			 */
			
			public function widget( $widget )
			{

				//Compare passed options to our defaults
				$widget = chemistry_molecule_widget::extend( array( 

					'align' => '',
					'frame' => '',
					'show_img_title' => '',
					'use_lightbox' => '',
					'url' => '',
					'image' => '',
					'description' => '',
					'classes' => ''

				 ), $widget );

				//Build classes
				$classes = array( 'widget', 'img' );

				if( !empty( $widget['align'] ) )
					$classes[] = 'align' . $widget['align'];

				//Check our units
				preg_match( '/( \d* )( .* )/', $widget['image_width'], $width_unit );
				$width_unit = 'px';

				preg_match( '/( \d* )( .* )/', $widget['image_height'], $height_unit );
				$height_unit = 'px';

				//Build our dimensions
				$widget['image_width'] = intval( $widget['image_width'] );
				$widget['image_height'] = intval( $widget['image_height'] );
				$widget['image_crop_width'] = intval( $widget['image_crop_width'] );
				$widget['image_crop_height'] = intval( $widget['image_crop_height'] );

				//If we are cropping, pass to our thumbnail method
				if( $widget['image_crop_width'] > 0 || $widget['image_crop_height'] > 0 )
					$widget['image'] = Chemistry::get_or_make_thumbnail( Chemistry::get_raw_image_url( $widget['image'] ), $widget['image_crop_width'], $widget['image_crop_height'] );

				//If we want a frame, put the appropriate classes
				if( !empty( $widget['frame'] ) )
				{

					$classes[] = 'frame';
					$classes[] = 'frame-' . $widget['frame'];

				}

				//Same with title
				if( $widget['show_img_title'] == 'on' )
					$classes[] = 'show-img-title';

				//If we want a lightbox, se the link to that or the img src
				if( $widget['use_lightbox'] == 'on' )
					if( empty( $widget['url'] ) )
						$widget['url'] = $widget['image'];

				//Begin output
				$output = '';

				//If we need a lightbox, output an anchor
				if( !empty( $widget['url'] ) )
				{

					$output .= '<a href="' . $widget['url'] . '"' . $this->_class( $classes, $widget['classes'] );
					$output .= ( $widget['use_lightbox'] == 'on' ? ' rel="lightbox"' : '' );
					$output .= ' style="' . ( $widget['image_width'] > 0 ? 'width: ' . $widget['image_width'].$width_unit.';' : '' ) . ' ' . ( $widget['image_height'] > 0 ? 'height: ' . $widget['image_height'].$height_unit.';' : '' );
					$output .= '">';

				}

				//Build the image markup
				$output .= '<img src="' . (  !empty( $widget['image'] ) ? Chemistry::chemistry_image( $widget['image'], 'image' ) : '' ) . '" alt="' . (  !empty( $widget['description'] ) ? $widget['description'] : '' ) . '"' . ( empty( $widget['url'] ) ? $this->_class( $classes, $widget['classes'] ) : '' ) . ( $widget['image_width'] > 0 && $width_unit == 'px' ? ' width="' . $widget['image_width'] . '"' : '' ) . ( $widget['image_height'] > 0 && $height_unit == 'px' ? ' height="' . $widget['image_height'] . '"' : '' ) . ' style="' . ( ( isset( $widget['url'] ) && empty( $widget['url'] ) ) ? ( $widget['image_width'] > 0 && $width_unit != 'px' ? 'width: ' . $widget['image_width'].$width_unit : '' ) . ' ' . ( ( $widget['image_height'] && $height_unit != 'px' ) ? 'height: ' . $widget['image_height'].$height_unit : '' ) : '' ) . '" />';

				//Close the anchor if we have a lightbox
				if( !empty( $widget['url'] ) )
					$output .= '</a>';

				//You should answer that
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

				//Floats
				$aligns = array( 

					'' => __( 'Default' , 'chemistry' ),
					'left' => __( 'Left' , 'chemistry' ),
					'right' => __( 'Right' , 'chemistry' ),
					'center' => __( 'Centre' , 'chemistry' )

				 );

				//Do we want a frame?
				$frames = apply_filters( 'chemistry_image_frames', array( 

					'' => __( 'Theme default' , 'chemistry' ),
					'1' => __( 'Image Frame 1' , 'chemistry' ),
					'2' => __( 'Image Frame 2' , 'chemistry' )

				 ) );

				//Form markup
				return '<fieldset class="chemistry-form">
					<h2 class="chemistry-tab-title">' . __( 'General' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<label><span class="label-title">' . __( 'Align' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'align', $widget, array( 'options' => $aligns ) ) . '</label>
						<label><span class="label-title">' . __( 'Frame Style' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'frame', $widget, array( 'options' => $frames ) ) . '</label>
					</div>
					<h2 class="chemistry-tab-title">' . __( 'Select Image' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<div class="cols-2">
							<div class="col">
								<label><span class="label-title">' . __( 'Image Location' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'image', $widget, array( 'class' => 'chemistry-preview upload_image' ) ) . '</label>
								<div class="buttonset-1">
									<button type="submit"' . $this->get_field_atts( 'upload_image' ) . ' name="' . $this->get_field_name( 'upload_image' ) . '" class="button button-secondary alignright upload_image single callback-molecule_image_widget_change">' . __( 'Select Image' , 'chemistry' ) . '</button>
								</div>
							</div>
							<div class="col">
								<div class="preview-img-wrap">
									<img src="' . ( ( isset( $widget['image'] ) && !empty( $widget['image'] ) ) ? $widget['image'] : Chemistry::path( 'assets/images/logo-transparent-black.png', true ) ) . '" alt="" class="upload_image" />
								</div>
							</div>
						</div>
					</div>
					<h2 class="chemistry-tab-title">' . __( 'Image Settings' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<div class="cols-1">
							<div class="col">
								<label><span class="label-title">' . __( 'Image Title' , 'chemistry' ) . '  ' . $this->field( 'text', 'description', $widget ) . '</label>
							</div>
						</div>
						<div class="cols-2">
							<div class="col"><label><span class="label-title">' . __( 'Link URL' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'url', $widget ) . '</label></div>
							<div class="col"><label class="label-alt-1">' . $this->field( 'checkbox', 'use_lightbox', $widget ) . ' <span class="label-title">' . __( 'Open "Link URL" in lightbox' , 'chemistry' ) . '</span></label></div>
						</div>
						' . $this->form_image_dimensions( $widget ) . '
					</div>
					<h2 class="chemistry-tab-title">' . __( 'Other' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<label><span class="label-title">' . __( 'Additional classes' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'classes', $widget ) . '</label>
					</div>
				</fieldset>';

			}/* form() */

		}/* class chemistry_potion_single_image */

	}/* !class_exists( 'chemistry_potion_single_image' ) */

?>