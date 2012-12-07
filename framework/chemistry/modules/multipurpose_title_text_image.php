<?php

	if( !class_exists( 'chemistry_potion_multipurpose_title_text_image' ) )
	{

		class chemistry_potion_multipurpose_title_text_image extends chemistry_slider_ready_widget
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

				parent::__construct( 'services', __( 'Image, Title, Content' , 'chemistry' ) );
				$this->label = __( 'Display a combination of images, titles and content' , 'chemistry' );

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

			public function item( $widget, $data )
			{

				$output = '';
				//Build our config markup
				$output .= '<div' . $this->_class( 'col' ) . '>';
				$output .= '<section' . $this->_class( 'services-item' ) . '>';

				//Start clean
				$title = '';
				$image = '';

				//If we're *not* hiding the title, let's output that
				if( !isset( $widget['title_hide'] ) || $widget['title_hide'] != 'on' )
				{

					if( !empty( $data['subtitle'] ) )
					{

						$title .= '<hgroup>';
						$title .= '<h2' . $this->_class( 'title' ) . '>' . (  !empty( $data['url'] ) ? '<a href="' . $data['url'] . '">' : '' ).$data['title'].(  !empty( $data['url'] ) ? '</a>' : '' ) . '</h2>';
						$title .= '<h3' . $this->_class( 'subtitle' ) . '>' . $data['subtitle'] . '</h3>';
						$title .= '</hgroup>';


					}
					else
					{

						$title .= '<h2' . $this->_class( 'title' ) . '>' . (  !empty( $data['url'] ) ? '<a href="' . $data['url'] . '">' : '' ).$data['title'].(  !empty( $data['url'] ) ? '</a>' : '' ) . '</h2>';

					}

				}

				//If we are needing images (i.e. not text only)
				if( $widget['type'] != 'text-only' )
				{

					//No image yet, so let's show a placeholder
					if( empty( $data['image_url'] ) )
					{

						$data['image_url'] = Chemistry::path( 'assets/images/logo-transparent-black.png', true );

						$image = '<img src="' . Chemistry::chemistry_image( $data['image_url'], 'services' ) . '" alt="' . $data['image_alt'] . '" width="50" />';

					}
					else
					{

						//Show a thumb of the image we're using
						$image_url = $data['image_url'];

						if( $widget['image_crop_width'] > 0 || $widget['image_crop_height'] > 0 )
							$image_url = Chemistry::get_or_make_thumbnail( Chemistry::get_raw_image_url( $image_url ), $widget['image_crop_width'], $widget['image_crop_height'] );

						$image = (  !empty( $data['url'] ) ? '<a href="' . $data['url'] . '">' : '' ) . '<img src="'.Chemistry::chemistry_image( $image_url, 'services' ) . '" alt="' . $data['image_alt'] . '"' . ( $widget['image_width'] > 0 ? ' width="' . $widget['image_width'] . '"' : '' ) . ( $widget['image_height'] > 0 ? ' height="' . $widget['image_height'] . '"' : '' ) . ' />' . (  !empty( $data['url'] ) ? '</a>' : '' );

					}

				}

				//Test which way round we want the content: title -> image, or image -> title
				if( isset( $widget['title_above_image'] ) && $widget['title_above_image'] == 'on' )
				{

					$output .= $title;
					$output .= $image;

				}
				else
				{

					$output .= $image;
					$output .= $title;

				}

				//If it's not just image-only then we need to output our content
				if( $widget['type'] != 'image-only' )
					$output .= '<div' . $this->_class( 'content' ) . '>' . wpautop( $data['content'] ) . '</div>';

				//Cleanup
				$output .= '</section>';
				$output .= '</div>';

				//Count it!
				return $output;

			}/* item() */


			/* =========================================================================== */
			
			/**
			 * Build the markup for this potion
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (array) $widget - The widget
			 * @return Markup
			 */
			
			public function widget( $widget )
			{

				//Compare passed options to our defaults
				$widget = chemistry_molecule_widget::extend( array( 

					'align' => '',
					'content_align' => 'left',
					'width' => '',
					'style' => 1,
					'disable_spacing' => '',
					'classes' => ''

				 ), $widget );

				//Start clean
				$output = '';

				//Some classes
				$classes = array_merge( array( 'widget', 'services' ), $this->get_classes( $widget ) );

				$classes[] = 'services-' . $widget['style'];

				if( $widget['type'] == 'image-only' || $widget['type'] == 'text-only' )
					$classes[] = 'image';
				else
					$classes[] = $widget['type'] . '-' . $widget['content_align'];

				//If we just want icons, set the height and width to 75
				if( $widget['type'] == 'icon' )
				{

					$widget['image_width'] = 75;
					$widget['image_height'] = 75;

				}
				else if( $widget['type'] == 'number' && $widget['content_align'] == 'center' )
				{

					//Or ensure the alignment is up top
					$widget['content_align'] = 'top';

				}

				//Get are config
				$widget['image_width'] = intval( $widget['image_width'] );
				$widget['image_height'] = intval( $widget['image_height'] );
				$widget['image_crop_width'] = intval( $widget['image_crop_width'] );
				$widget['image_crop_height'] = intval( $widget['image_crop_height'] );

				//Check units
				if( !empty( $widget['width'] ) )
					$widget['width'] = Chemistry::check_units_on_end_of_string( $widget['width'], 'px' );

				if( !empty( $widget['align'] ) )
					$classes[] = 'align' . $widget['align'];

				//Start clean and loop through our output
				$counter = 1;

				$output .= '<div' . $this->_class( $classes, $widget['classes'] ) . 'style="' . (  !empty( $widget['width'] ) ? 'width: ' . $widget['width'] : '' ) . '">';
				$output .= '	<div' . $this->_class( array( 'cols', 'cols-' . $widget['columns'], 'rows-' . $widget['rows'], 'spacing-' . ( $widget['disable_spacing'] == 'on' ? 0 : 1 ) ) ) . '>';

				if( isset( $widget['service_content'] ) && !empty( $widget['service_content'] ) )
				{

					$count = count( $widget['service_content'] );

					for( $i = 0; $i < $count; $i++ )
					{

						if( !empty( $widget['service_title'][$i] ) || !empty( $widget['service_content'][$i] ) || !empty( $widget['image_url'][$i] ) )
						{

							$output .= $this->item( $widget, array( 

								'title' => $widget['service_title'][$i],
								'subtitle' => '',
								'content' => $widget['service_content'][$i],
								'url' => $widget['service_link'][$i],
								'image_url' => $widget['image_url'][$i],
								'image_alt' => $widget['image_alt'][$i],
								'counter' => $counter

							 ) );

							$counter++;

						}

					}

				}

				//Cleanup
				$output .= '</div>';
				$output .= '</div>';

				//Shiny disco balls
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

				return '<div class="col"' . ( empty( $widget ) ? ' style="display: none;"' : '' ) . '><div class="group-item gallery-item">
					<div class="group-item-title">' . __( 'Item' , 'chemistry' ) . '</div>
					<div class="group-item-content">
						<div class="preview-img-wrap">
							<img src="' . ( $i >= 0 ? $widget['image_url'][$i] : '' ) . '" class="chemistry-preview upload_image" />
						</div>
						<label><span class="label-title">' . __( 'Image URL' , 'chemistry' ) . '</span> ' . $this->group_field( 'text', 'image_url', $i, $widget, array( 'class' => 'upload_image' ) ) . '</label>
						<label><span class="label-title">' . __( 'Image alt' , 'chemistry' ) . '</span> ' . $this->group_field( 'text', 'image_alt', $i, $widget ) . '</label>
						<label><span class="label-title">' . __( 'Link URL' , 'chemistry' ) . '</span> ' . $this->group_field( 'text', 'service_link', $i, $widget ) . '</label>
						<label><span class="label-title">' . __( 'Title' , 'chemistry' ) . '</span> ' . $this->group_field( 'text', 'service_title', $i, $widget ) . '</label>
						<label><span class="label-title">' . __( 'Content' , 'chemistry' ) . '</span> ' . $this->group_field( 'textarea', 'service_content', $i, $widget ) . '</label>
					</div>
					<div class="group-item-actions">
						<button name="molecule-widget-tab-rich" class="molecule-widget-group-item-rich">' . __( 'Rich Text Editor' , 'chemistry' ) . '</button>
						<button type="submit"' . $this->get_field_atts( 'change_item' ) . ' name="' . $this->get_field_name( 'change_item' ) . '" class="molecule-widget-gallery-change upload_image single callback-molecule_gallery_widget_change molecule-widget-group-item-edit-image">' . __( 'Edit' , 'chemistry' ) . '</button>
						<button name="molecule-widget-tab-remove" class="molecule-widget-group-item-remove">' . __( 'Remove' , 'chemistry' ) . '</button>
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

				//We have 4 different types, and 2 different styles by default
				$types = array( 

					'icon' => __( 'Icons (75x75px)', 'chemistry' ),
					'image' => __( 'Images' , 'chemistry' ),
					'image-only' => __( 'Images only' , 'chemistry' ),
					'text-only' => __( 'Text only' , 'chemistry' )

				 );

				//Alignments
				$aligns = array( 

					'left' => __( 'Left' , 'chemistry' ),
					'right' => __( 'Right' , 'chemistry' ),
					'center' => __( 'Centre' , 'chemistry' )

				 );

				//Filtered styles so a theme can have its wicked way
				$styles = apply_filters( 'chemistry_services_styles', array( 

					'1' => __( 'Style 1' , 'chemistry' ),
					'2' => __( 'Style 2' , 'chemistry' )

				 ) );

				//Star fresh
				$services = '';

				//We *do* actually have some content don't we?
				if( isset( $widget['service_content'] ) )
				{

					$column = 0;

					for( $i = 0; $i < count( $widget['service_content'] ); $i++ )
						if( !empty( $widget['service_title'][$i] ) || !empty( $widget['service_content'][$i] ) || !empty( $widget['image_url'][$i] ) )
							$services .= $this->group_item( $widget, $i );

				}

				//Build the markup
				return '<fieldset class="chemistry-form">
					<h2 class="chemistry-tab-title">' . __( 'General' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						' . $this->form_widget_general( $widget ) . '
						<div class="cols cols-1">
							<div class="col"><label><span class="label-title">' . __( 'Style' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'style', $widget, array( 'options' => $styles ) ) . '</label></div>
						</div>
						' . $this->form_posts( $widget, 'tile', 'tileset' ) . '
					</div>
					<h2 class="chemistry-tab-title">' . __( 'Add New' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<div class="chemistry-form sortable-content  group-content-wrap">
							<div class="buttonset-1">
								<button name="molecule-widget-group-item-add" class="molecule-widget-group-item-add button button-secondary alignright">' . __( 'Add New' , 'chemistry' ) . '</button>
							</div>
							<div class="group-prototype">' . $this->group_item( array(), -1 ) . '</div>
							<div class="group-content">
								<div class="cols-3 cols">
									' . $services.'
								</div>
							</div>
						</div>
					</div>
					<h2 class="chemistry-tab-title">' . __( 'Item Layout' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<div class="cols-3">
							<div class="col"><label><span class="label-title">' . __( 'Type' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'type', $widget, array( 'options' => $types ) ) . '</label></div>
							<div class="col">
								<label class="label-alt-1">' . $this->field( 'checkbox', 'title_hide', $widget ) . ' <span class="label-title">' . __( 'Hide title' , 'chemistry' ) . '</span></label>
							</div>
							<div class="col">
								<label>' . $this->field( 'checkbox', 'title_above_image', $widget ) . ' <span class="label-title">' . __( 'Title Above Image' , 'chemistry' ) . '</span></label>
							</div>
						</div>
						<div class="cols cols-1">
							<div class="col"><label><span class="label-title">' . __( 'Align' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'content_align', $widget, array( 'options' => $aligns ) ) . '</label></div>
						</div>
					</div>
					<h2 class="chemistry-tab-title">' . __( 'Image Settings' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
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

		}/* class chemistry_potion_multipurpose_title_text_image */

	}/* !class_exists( 'chemistry_potion_multipurpose_title_text_image' ) */

?>