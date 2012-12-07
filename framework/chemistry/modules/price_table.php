<?php

	if( !class_exists( 'chemistry_potion_price_table' ) )
	{

		class chemistry_potion_price_table extends chemistry_slider_ready_widget
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

				parent::__construct( 'pricing-box', __( 'Pricing box' , 'chemistry' ) );
				$this->label = __( 'Beautiful pricing boxes to persuade your customers to buy' , 'chemistry' );

			}/* __construct() */


			/* =========================================================================== */
			

			public function widget( $widget )
			{

				//Compare passed options to our defaults
				$widget = chemistry_molecule_widget::extend( array( 

					'cols' => 1,
					'rows' => 1,
					'classes' => '',
					'currency' => '',
					'disable_spacing' => ''

				 ), $widget );

				//Default classes
				$classes = array_merge( array( 'widget', 'prcbox', 'prcbox-1', 'grid' ), $this->get_classes( $widget ) );

				//Begin fresh
				$output = '';

				//Start of markup
				$output .= '<div' . $this->_class( $classes, $widget['classes'] ) . '>';
				$output .= '<div' . $this->_class( array( 'cols', 'cols-' . $widget['columns'], 'rows-' . $widget['rows'], 'spacing-' . ( $widget['disable_spacing'] == 'on' ? 0 : 1 ) ) ) . '>';

				//How many are we?
				$count = count( $widget['box_content'] );

				for( $i = 0; $i < $count; $i++ )
				{

					//Markup for each item
					if( !empty( $widget['box_title'][$i] ) || !empty( $widget['box_content'][$i] ) )
					{

						$output .= '<div' . $this->_class( 'col' ) . '>';
						$output .= '<div' . $this->_class( 'prcbox-item' ) . '>';
						$output .= '<h2' . $this->_class( 'prcbox-title' ). 'style="' . ( ( isset( $widget['box_background_color'][$i] ) && !empty( $widget['box_background_color'][$i] ) ) ? ' background-color: ' . $widget['box_background_color'][$i] . ';' : '' ) . ' ' . ( ( isset( $widget['box_text_color'][$i] ) && !empty( $widget['box_text_color'][$i] ) ) ? ' color: ' . $widget['box_text_color'][$i] . ';' : '' ) . '">' . $widget['box_title'][$i] . '</h2>';
						$output .= '<p' . $this->_class( 'prcbox-price' ) . '><span' . $this->_class( 'prcbox-currency' ) . '>' . $widget['currency'] . '</span><span' . $this->_class( 'prcbox-val-main' ) . '>' . $widget['box_price_main'][$i] . '</span><sup' . $this->_class( 'prcbox-val-tail' ) . '>' . $widget['box_price_tail'][$i] . '</sup></span></p>';
						$output .= '<div' . $this->_class( 'prcbox-desc' ) . '>';
						$output .= wpautop( $widget['box_content'][$i] );
						$output .= '</div>';
						$output .= '<a href="' . $widget['box_button_url'][$i] . '"' . $this->_class( 'prcbox-button' ). 'style="' . ( ( isset( $widget['box_background_color'][$i] ) && !empty( $widget['box_background_color'][$i] ) ) ? ' background-color: ' . $widget['box_background_color'][$i] . ';' : '' ) . ' ' . ( ( isset( $widget['box_text_color'][$i] ) && !empty( $widget['box_text_color'][$i] ) ) ? ' color: ' . $widget['box_text_color'][$i] . ';' : '' ) . '">' . $widget['box_button_label'][$i] . '</a>';
						$output .= '</div>';
						$output .= '</div>';

					}

				}

				//Cleanup
				$output .= '	</div>';
				$output .= '</div>';

				//So fresh and so clean, clean
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
							<label><span class="label-title">' . __( 'Title' , 'chemistry' ) . '</span> ' . $this->group_field( 'text', 'box_title', $i, $widget ) . '</label>
							<label class="chemistry-color"><span class="label-title">' . __( 'BG Color' , 'chemistry' ) . '</span> ' . $this->group_field( 'text', 'box_background_color', $i, $widget ) . '</label>
							<label class="chemistry-color"><span class="label-title">' . __( 'Text Color' , 'chemistry' ) . '</span> ' . $this->group_field( 'text', 'box_text_color', $i, $widget ) . '</label>
							<div class="cols cols-2">
								<div class="col">
									<label><span class="label-title">' . __( 'Full', 'chemistry' ) . '</span> ' . $this->group_field( 'text', 'box_price_main', $i, $widget ) . '</label>
								</div>
								<div class="col">
									<label><span class="label-title">' . __( 'Pennies', 'chemistry' ) . '</span> ' . $this->group_field( 'text', 'box_price_tail', $i, $widget ) . '</label>
								</div>
							</div>
							<label><span class="label-title">' . __( 'Content' , 'chemistry' ) . '</span> ' . $this->group_field( 'textarea', 'box_content', $i, $widget ) . '</label>
							<label><span class="label-title">' . __( 'Button label' , 'chemistry' ) . '</span> ' . $this->group_field( 'text', 'box_button_label', $i, $widget ) . '</label>
							<label><span class="label-title">' . __( 'Button url' , 'chemistry' ) . '</span> ' . $this->group_field( 'text', 'box_button_url', $i, $widget ) . '</label>
						</div>
						<div class="group-item-actions">
							<button name="molecule-widget-tab-rich" class="molecule-widget-group-item-rich">' . __( 'Rich Text Editor' , 'chemistry' ) . '</button>
							<button name="molecule-widget-tab-remove" class="molecule-widget-group-item-remove">' . __( 'Remove' , 'chemistry' ) . '</button>
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

				//Max 6
				$columns = array();

				for( $i = 1; $i <= 6; $i++ )
					$columns[$i] = $i;

				$count = 1;

				//If we have stuff in boxes, carry on
				if( isset( $widget['box_content'] ) && count( $widget['box_content'] ) > 0 )
				{

					$count = 0;

					for( $i = 0; $i < count( $widget['box_content'] ); $i++ )
						if( !empty( $widget['box_title'][$i] ) || !empty( $widget['box_content'][$i] ) )
							$count++;

				}

				//Start clean
				$boxes = '';

				//Add the individual cols
				if( isset( $widget['box_content'] ) )
				{

					$column = 0;

					for( $i = 0; $i < count( $widget['box_content'] ); $i++ )
						if( !empty( $widget['box_title'][$i] ) || !empty( $widget['box_content'][$i] ) )
							$boxes .= $this->group_item( $widget, $i );

				}

				//Markup, yo
				return '<fieldset class="chemistry-form">
					<h2 class="chemistry-tab-title">' . __( 'General' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<label><span class="label-title">' . __( 'Currency' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'currency', $widget ) . '</label>
					</div>
					<h2 class="chemistry-tab-title">' . __( 'Add New' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<div class="sortable-content group-content-wrap">
							<div class="buttonset-1">
								<button name="molecule-widget-group-item-add" class="molecule-widget-group-item-add button button-secondary alignright">' . __( 'Add New' , 'chemistry' ) . '</button>
							</div>
							<div class="group-prototype">' . $this->group_item( array(), -1 ) . '</div>
							<div class="group-content">
								<div class="cols-3 cols">
									' . $boxes.'
								</div>
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

		}/* class chemistry_potion_price_table */

	}/* !class_exists( 'chemistry_potion_price_table' ) */

?>