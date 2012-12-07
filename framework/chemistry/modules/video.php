<?php

	if( !class_exists( 'chemistry_potion_video' ) )
	{

		class chemistry_potion_video extends chemistry_molecule_widget
		{

			/**
			 * Register this potion. It basically gets shunted off to our video method
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param None
			 * @return None
			 */
			
			public function __construct()
			{

				parent::__construct( 'video', __( 'Video' , 'chemistry' ) );
				$this->label = __( 'YouTube, Vimeo or Blip.tv all covered' , 'chemistry' );

			}/* __construct() */


			/* =========================================================================== */
			

			public function widget( $widget )
			{

				//Compare passed options to our defaults
				$widget = chemistry_molecule_widget::extend( array( 

					'align' => '',
					'url' => '',
					'width' => '',
					'height' => '',
					'classes' => ''

				 ), $widget );

				$classes = array( 'widget', 'media-wrap' );

				$classes[] = 'align' . $widget['align'];

				$output = '<div' . $this->_class( $classes, $widget['classes'] ) . ' style="' . (  !empty( $widget['width'] ) ? 'width: ' . $widget['width'] . 'px;' : NULL ) . ' ' . (  !empty( $widget['height'] ) ? 'height: ' . $widget['height'] . 'px;' : NULL ) . '">';
				$output .= Chemistry::video( $widget['url'], (  !empty( $widget['width'] ) ? $widget['width'] : NULL ), (  !empty( $widget['height'] ) ? $widget['height'] : NULL ) );
				$output .= '</div>';

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

				$aligns = array( 

					'' => __( 'Default' , 'chemistry' ),
					'left' => __( 'Left' , 'chemistry' ),
					'right' => __( 'Right' , 'chemistry' ),
					'center' => __( 'Center' , 'chemistry' )

				 );

				return '<fieldset class="chemistry-form">
					<h2 class="chemistry-tab-title">' . __( 'General' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">'
					.$this->form_widget_general( $widget, true ) . '
						<div class="cols cols-1">
							<div class="col">
								<label><span class="label-title">' . __( 'URL' , 'chemistry' ) . ' ' . $this->field( 'text', 'url', $widget ) . ' <small>' . __( 'YouTube, Vimeo or Blip.tv url.' , 'chemistry' ) . '</small></label>
							</div>
						</div>
					</div>
					<h2 class="chemistry-tab-title">' . __( 'Other' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<label><span class="label-title">' . __( 'Additional classes' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'classes', $widget ) . '</label>
					</div>
				</fieldset>';

			}/* form() */

		}/* class chemistry_potion_video */

	}/* !class_exists( 'chemistry_potion_video' ) */

?>