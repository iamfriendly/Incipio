<?php

	if( !class_exists( 'chemistry_potion_plain_text' ) )
	{

		class chemistry_potion_plain_text extends chemistry_molecule_widget
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

				parent::__construct( 'plain-text', __( 'Plain text' , 'chemistry' ) );
				$this->label = __( 'Plain text module for when you just want text w/o styling' , 'chemistry' );

			}/* __construct() */


			/* =========================================================================== */
			

			public function widget( $widget )
			{

				//Compare passed options to our defaults
				$widget = chemistry_molecule_widget::extend( array( 

					'align' => 'left',
					'text_align' => 'left',
					'width' => '',
					'disable_formatting' => ''

				 ), $widget );

				//What is set?
				$text = isset( $widget['text'] ) ? $widget['text'] : '';

				//Default WP formating?
				if( $widget['disable_formatting'] != 'on' )
					$text = wpautop( $text );

				//Build our classes
				$classes = array();

				if( $widget['text_align'] != 'left' )
					$classes[] = 'text-align' . $widget['align'];

				if( !empty( $widget['align'] ) )
					$classes[] = 'align' . $widget['align'];

				//Dimensions and units
				if( !empty( $widget['width'] ) )
					$widget['width'] = Chemistry::check_units_on_end_of_string( $widget['width'], 'px' );

				//Built it up
				$text = '<div' . $this->_class( $classes ) . ' style="' . (  !empty( $widget['width'] ) ? $widget['width'] : '' ) . '">' . $text . ' </div>';

				//Let loose
				return $text;

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

				//Simple text align
				$text_align = array( 

					'left' => __( 'Left' , 'chemistry' ),
					'right' => __( 'Right' , 'chemistry' ),
					'center' => __( 'Centre' , 'chemistry' 

				) );

				//Form markup
				return '<fieldset class="chemistry-form">
					<h2 class="chemistry-tab-title">' . __( 'General' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
					' . $this->form_widget_general( $widget ) . '
					<label><span class="label-title">' . __( 'Text Align' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'text_align', $widget, array( 'options' => $text_align ) ) . '</label>
					<label>' . $this->field( 'checkbox', 'disable_formatting', $widget ) . ' <span class="label-title">' . __( 'Disable formatting' , 'chemistry' ) . '</span></label>
					<label><span class="label-title">' . __( 'Plain text' , 'chemistry' ) . '</span> ' . $this->field( 'textarea', 'text', $widget ) . '<small>' . __( 'You can use any plain-text or shortcodes that you like. WordPress text formatting will automatically be applied.' , 'chemistry' ) . '</small></label>
					</div>
				</fieldset>';

			}/* form() */

		}/* class chemistry_potion_plain_text */

	}/* !class_exists( 'chemistry_potion_plain_text' ) */

?>