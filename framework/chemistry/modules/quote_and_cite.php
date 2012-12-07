<?php

	if( !class_exists( 'chemistry_potion_quote_and_cite' ) )
	{

		class chemistry_potion_quote_and_cite extends chemistry_molecule_widget
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

				parent::__construct( 'blockquote', __( 'Blockquote' , 'chemistry' ) );
				$this->label = __( 'Got a quote from someone? Show it here.' , 'chemistry' );

			}/* __construct() */


			/* =========================================================================== */
			
			/**
			 * Output for our quote
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param 
			 * @return 
			 */
			
			public function widget( $widget )
			{

				//Compare passed options to our defaults
				$widget = chemistry_molecule_widget::extend( array( 

					'align' => '',
					'width' => '',
					'style' => 1,
					'classes' => ''

				 ), $widget );

				//Default classes
				$classes = array( 'widget', 'blockquote', 'blockquote-' . $widget['style'] );

				if( !empty( $widget['width'] ) )
					$widget['width'] = Chemistry::check_units_on_end_of_string( $widget['width'], 'px' );

				if( !empty( $widget['align'] ) )
					$classes[] = 'align' . $widget['align'];

				//Pretty simple, really
				return '<blockquote' . $this->_class( $classes, $widget['classes'] ) . 'style="' . (  !empty( $widget['width'] ) ? 'width: ' . $widget['width'] : '' ) . '">' . wpautop( $widget['text'] ) . '</blockquote>';

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

				$styles = apply_filters( 'chemistry_blockquote_styles', array( 

					'' => __( 'Theme default' , 'chemistry' ),
					'1' => __( 'Quote Style 1' , 'chemistry' ),
					'2' => __( 'Quote Style 2' , 'chemistry' ),

				 ) );

				//Build our form markup
				return '<fieldset class="chemistry-form">
					<h2 class="chemistry-tab-title">' . __( 'General' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						' . $this->form_widget_general( $widget ) . '
						<label><span class="label-title">' . __( 'Style' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'style', $widget, array( 'options' => $styles ) ) . '</label>
						<label><span class="label-title">' . __( 'Text' , 'chemistry' ) . ' ' . $this->field( 'textarea', 'text', $widget ) . '<small>' . __( 'You can use any plain-text or shortcodes that you like. WordPress text formatting will automatically be applied.' , 'chemistry' ) . '</small></label>
					</div>
					<h2 class="chemistry-tab-title">' . __( 'Other' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<label><span class="label-title">' . __( 'Additional classes' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'classes', $widget ) . '</label>
					</div>
				</fieldset>';

			}/* form() */

		}/* class chemistry_potion_quote_and_cite */

	}/* !class_exists( 'chemistry_potion_quote_and_cite' ) */

?>