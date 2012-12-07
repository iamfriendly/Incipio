<?php

	if( !class_exists( 'chemistry_potion_message_box' ) )
	{

		class chemistry_potion_message_box extends chemistry_molecule_widget
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

				parent::__construct( 'message', __( 'Message' , 'chemistry' ) );
				$this->label = __( 'Need to highlight something? Here you go!' , 'chemistry' );

			}/* __construct() */


			/* =========================================================================== */
			
			/**
			 * Simple content boxes (a div with some classes)
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.3
			 * @param (array) $widget - Widget config
			 * @return Markup
			 */
			
			public function widget( $widget )
			{

				//Compare passed options to our defaults
				$widget = chemistry_molecule_widget::extend( array( 

					'align' => '',
					'width' => '',
					'type' => 'info',
					'classes' => ''

				 ), $widget );

				//Build our classes
				$classes = array( 'widget', 'msg', 'msg-' . $widget['type'] );

				if( !empty( $widget['width'] ) )
					$widget['width'] = Chemistry::check_units_on_end_of_string( $widget['width'], 'px' );

				if( !empty( $widget['align'] ) )
					$classes[] = 'align' . $widget['align'];

				//She's a sweet black angel!
				return '<div' . $this->_class( $classes, $widget['classes'] ) . 'style="' . (  !empty( $widget['width'] ) ? 'width: ' . $widget['width'] : '' ) . '"><span' . $this->_class( 'msg-icon' ) . '></span>' . wpautop( $widget['text'] ) . '</div>';

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

				//Different styles of our boxes
				$types = apply_filters( 'chemistry_message_box_styles', array( 

					'info' => __( 'Info' , 'chemistry' ),
					'warning' => __( 'Warning' , 'chemistry' ),
					'error' => __( 'Error' , 'chemistry' ),
					'download' => __( 'Download' , 'chemistry' ),
					'important-1' => __( 'Important' , 'chemistry' )

				 ) );

				//Form markup
				return '<fieldset class="chemistry-form">
					<h2 class="chemistry-tab-title">' . __( 'General' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						' . $this->form_widget_general( $widget ) . '
						<label><span class="label-title">' . __( 'Type' , 'chemistry' ) . ' ' . $this->field( 'select', 'type', $widget, array( 'options' => $types ) ) . '</label>
						<label><span class="label-title">' . __( 'Message' , 'chemistry' ) . ' ' . $this->field( 'textarea', 'text', $widget ) . '<small>' . __( 'You can use any plain-text or shortcodes that you like. WordPress text formatting will automatically be applied.' , 'chemistry' ) . '</small></label>
					</div>
					<h2 class="chemistry-tab-title">' . __( 'Other' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<label><span class="label-title">' . __( 'Additional classes' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'classes', $widget ) . '</label>
					</div>
				</fieldset>';

			}/* form() */

		}/* class chemistry_potion_message_box */

	}/* !class_exists( 'chemistry_potion_message_box' ) */

?>