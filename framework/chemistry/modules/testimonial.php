<?php

	if( !class_exists( 'chemistry_potion_testimonial' ) )
	{

		class chemistry_potion_testimonial extends chemistry_slider_ready_widget
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

				parent::__construct( 'testimonials', __( 'Testimonials' , 'chemistry' ) );
				$this->label = __( 'Clients said great things? Show your visitors who said what.' , 'chemistry' );

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

				$output .= '<div' . $this->_class( 'col' ) . '>';
				$output .= '<div' . $this->_class( 'quotes-item' ) . '>';
				$output .= '<blockquote>' . wpautop( $data['content'] ) . '</blockquote>';

				if( !empty( $data['author'] ) )
					$output .= '<p' . $this->_class( 'meta' ) . '><a href="' . $data['url'] . '">' . $data['author'] . '</a></p>';

				$output .= '</div>';
				$output .= '</div>';

				return $output;

			}/* item() */


			/* =========================================================================== */
			

			public function widget( $widget )
			{

				//Compare passed options to our defaults
				$widget = chemistry_molecule_widget::extend( array( 

					'align' => '',
					'width' => '',
					'style' => 1,
					'disable_spacing' => '',
					'classes' => ''

				 ), $widget );

				//Start afresh with some default classes
				$output = '';
				$classes = array_merge( array( 'widget', 'quotes' ), $this->get_classes( $widget ) );

				$classes[] = 'quotes-' . $widget['style'];

				//Check our unit endings
				if( !empty( $widget['width'] ) )
					$widget['width'] = Chemistry::check_units_on_end_of_string( $widget['width'], 'px' );

				if( !empty( $widget['align'] ) )
					$classes[] = 'align' . $widget['align'];

				//Build our output
				$output .= '<div' . $this->_class( $classes, $widget['classes'] ) . 'style="' . (  !empty( $widget['width'] ) ? 'width: ' . $widget['width'] : '' ) . '">';
				$output .= '<div' . $this->_class( array( 'cols', 'cols-' . $widget['columns'], 'rows-' . $widget['rows'], 'spacing-' . ( $widget['disable_spacing'] == 'on' ? 0 : 1 ) ) ) . '>';

				if( isset( $widget['testimonial_content'] ) && !empty( $widget['testimonial_content'] ) )
				{

					$count = count( $widget['testimonial_content'] );

					for( $i = 0; $i < $count; $i++ )
					{

						if( !empty( $widget['testimonial_author'][$i] ) || !empty( $widget['testimonial_url'][$i] ) || !empty( $widget['testimonial_content'][$i] ) )
						{

							$output .= $this->item( $widget, array( 

								'content' => $widget['testimonial_content'][$i],
								'author' => $widget['testimonial_author'][$i],
								'url' => $widget['testimonial_url'][$i]

							 ) );

						}

					}

				}

				//Cleanup
				$output .= '</div>';
				$output .= '</div>';

				//Planet Earth is blue, there's nothing I can do
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
						<label><span class="label-title">' . __( 'Author' , 'chemistry' ) . '</span> ' . $this->group_field( 'text', 'testimonial_author', $i, $widget ) . '</label>
						<label><span class="label-title">' . __( 'Author URL' , 'chemistry' ) . '</span> ' . $this->group_field( 'text', 'testimonial_url', $i, $widget ) . '</label>
						<label><span class="label-title">' . __( 'Content' , 'chemistry' ) . '</span> ' . $this->group_field( 'textarea', 'testimonial_content', $i, $widget ) . '</label>
					</div>
					<div class="group-item-actions">
						<button name="molecule-widget-tab-rich" class="molecule-widget-group-item-rich">' . __( 'Rich Text Editor' , 'chemistry' ) . '</button>
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

				//Some default styles, run through a filter so themes can specify
				$styles = apply_filters( 'chemistry_testimonials_styles', array( 

					'1' => __( 'Style 1' , 'chemistry' ),
					'2' => __( 'Style 2' , 'chemistry' )

				 ) );

				//Start clean
				$testimonials = '';

				//Ensure we've actually got something to output
				if( isset( $widget['testimonial_content'] ) )
				{

					$column = 0;

					for( $i = 0; $i < count( $widget['testimonial_content'] ); $i++ )
					{

						if( !empty( $widget['testimonial_author'][$i] ) || !empty( $widget['testimonial_url'][$i] ) || !empty( $widget['testimonial_content'][$i] ) )
							$testimonials .= $this->group_item( $widget, $i );

					}

				}

				//build our markup
				return '<fieldset class="chemistry-form">
					<h2 class="chemistry-tab-title">' . __( 'General' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						' . $this->form_widget_general( $widget ) . '
						<div class="cols cols-1">
							<div class="col">
								<label><span class="label-title">' . __( 'Style' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'style', $widget, array( 'options' => $styles ) ) . '</label>
							</div>
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
								<div class="cols-3 cols">' . $testimonials . '</div>
							</div>
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

		}/* class chemistry_potion_testimonial */

	}/* !class_exists( 'chemistry_potion_testimonial' ) */

?>