<?php

	if( !class_exists( 'chemistry_potion_heading_tag' ) )
	{

		class chemistry_potion_heading_tag extends chemistry_molecule_widget
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

				parent::__construct( 'heading', __( 'Heading' , 'chemistry' ) );
				$this->label = __( 'A custom heading tag' , 'chemistry' );

			}/* __construct() */


			/* =========================================================================== */
			

			/**
			 * Really simply markup for our <h#> tags
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

					'type' => 'h2',
					'title' => '',
					'classes' => ''

				 ), $widget );

				//If we have a title, set it
				if( !empty( $widget['title'] ) )
				{

					$title = $widget['title'];

				}
				else
				{

					//Otherwise, set it to the title of the current post
					$title = get_the_title( get_the_ID() );

					Chemistry::chemistry_option( 'hide_title', true );
				}

				//Build and they will come
				return '<' . $widget['type'].$this->_class( array(), $widget['classes'] ) . ( ( isset( $widget['id'] ) && !empty( $widget['id'] ) ) ? ' id="' . $widget['id'] . '"' : '' ) . '>' . $widget['title'] . '</' . $widget['type'] . '>';

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

				//There are 6 different <h#> tags
				$types = array( 

					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6'

				 );

				//Form markup
				return '<fieldset class="chemistry-form">
					<h2 class="chemistry-tab-title">' . __( 'General' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<label><span class="label-title">' . __( 'Title' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'title', $widget ) . '</label>
						<label><span class="label-title">' . __( 'Type' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'type', $widget, array( 'options' => $types ) ) . '</label>
						<label><span class="label-title">' . __( 'ID' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'id', $widget ) . '</label>
						<label><span class="label-title">' . __( 'Additional classes' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'classes', $widget ) . '</label>
					</div>
				</fieldset>';

			}/* form() */

		}/* class chemistry_potion_heading_tag */

	}/* !class_exists( 'chemistry_potion_heading_tag' ) */

?>