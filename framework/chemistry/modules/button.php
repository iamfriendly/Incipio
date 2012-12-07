<?php

	if( !class_exists( 'chemistry_potion_button' ) )
	{

		class chemistry_potion_button extends chemistry_molecule_widget
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

				parent::__construct( 'button', __( 'Button' , 'chemistry' ) );
				$this->label = __( 'Everyone loves buttons. Loads of styles.' , 'chemistry' );

			}/* __construct() */


			/* =========================================================================== */
			
			/**
			 * Really simple button builder, ust outputs an anchor with appropriate classes and styles
			 * can also use additional classes for bootstrap buttons
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

					'align' => 'left',
					'label' => '',
					'style' => 1,
					'background' => '',
					'color' => '',
					'width' => '',
					'classes' => ''

				 ), $widget );

				//Default sizes
				$styles = array( '1' => 'small', '2' => 'medium', '3' => 'large' );

				//Default classes
				$classes = array( 'widget', 'button', 'button-' . $styles[$widget['style']], 'align' . $widget['align'] );

				//Check our units
				if( !empty( $widget['width'] ) )
					$widget['width'] = Chemistry::check_units_on_end_of_string( $widget['width'], 'px' );

				//Do we need custom style?
				$has_style = implode( '', array( $widget['background'], $widget['color'], $widget['width'] ) ) != '';

				return '<a href="' . (  !empty( $widget['url'] ) ? $widget['url'] : '' ) . '"' . $this->_class( $classes, $widget['classes'] ) . ( $has_style ? ( ' style="' . (  !empty( $widget['width'] ) ? 'width: ' . $widget['width'] . ';' : '' ) . (  !empty( $widget['background'] ) ? 'background-color: ' . $widget['background'] . ';' : '' ) . (  !empty( $widget['color'] ) ? 'color: ' . $widget['color'] . ';' : '' ) . '"' ) : '' ) . '>' . $widget['label'] . '</a>';

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

				//3 button sizes
				$styles = array( 

					'1' => __( 'Small' , 'chemistry' ),
					'2' => __( 'Medium' , 'chemistry' ),
					'3' => __( 'Large' , 'chemistry' )

				 );

				//Build form markup
				return '<fieldset class="chemistry-form">
					<h2 class="chemistry-tab-title">' . __( 'General' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
					' . $this->form_widget_general( $widget ) . '
						<div class="cols-2">
							<div class="col">
								<label><span class="label-title">' . __( 'Button Text' , 'chemistry' ) . ' ' . $this->field( 'text', 'label', $widget ) . '</label>
							</div>
							<div class="col">
								<label><span class="label-title">' . __( 'URL' , 'chemistry' ) . ' ' . $this->field( 'text', 'url', $widget ) . '</label>
							</div>
						</div>

						<div class="cols-1">
							<div class="col">
								<label><span class="label-title">' . __( 'Style' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'style', $widget, array( 'options' => $styles ) ) . '</label>
							</div>
						</div>
					</div>

					<h2 class="chemistry-tab-title">' . __( 'Set Colors' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<div class="cols-2">
							<div class="col">
								<label class="chemistry-color"><span class="label-title">' . __( 'Background color' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'background', $widget ) . '</label>
							</div>
							<div class="col">
								<label class="chemistry-color"><span class="label-title">' . __( 'Text color' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'color', $widget ) . '</label>
							</div>
						</div>
					</div>
					<h2 class="chemistry-tab-title">' . __( 'Other' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<div class="cols-1">
							<div class="col">
								<label><span class="label-title">' . __( 'Additional classes' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'classes', $widget ) . '</label>
							</div>
						</div>
					</div>
				</fieldset>';

			}/* form() */

		}/* class chemistry_potion_button */

	}/* !class_exists( 'chemistry_potion_button' ) */

?>