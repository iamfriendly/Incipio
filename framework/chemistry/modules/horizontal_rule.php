<?php

	if( !class_exists( 'chemistry_potion_horizontal_rule' ) )
	{

		class chemistry_potion_horizontal_rule extends chemistry_molecule_widget
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

				parent::__construct( 'divider', __( 'Divider' , 'chemistry' ) );
				$this->label = __( 'Need to split up your page horizontally? Done!' , 'chemistry' );

			}/* __construct() */


			/* =========================================================================== */
			

			/**
			 * We have a few different types of <hr>
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
				$widget = chemistry_molecule_widget::extend( array
				( 
					'back_to_top' => '',
					'clear' => ''
				 ), $widget );

				//Our output for back to top
				$back_to_top = array( 

				    //Where do we want our 'top' notice?
					'alignment' => array( 

						'left' => __( 'Left' , 'chemistry' ),
						'right' => __( 'Right' , 'chemistry' ),
						'center' => __( 'Centre' , 'chemistry' )
					 ),

					//And what is it to say?
					'title' => array( 

						'0' => __( 'Top' , 'chemistry' ),
						'1' => __( '&#92; top' , 'chemistry' ),
						'2' => __( '&uarr; top' , 'chemistry' ),
						'3' => __( 'Custom' , 'chemistry' )
					 )

				);

				//Default classes and build others
				$classes = array( 'divider', 'style-1' );

				if( $widget['clear'] == 'on' )
					$classes[] = 'clear';

				//If we are showing the 'To Top' link
				if( $widget['back_to_top'] == 'on' )
				{
					
					$classes[] = 'clear';
					$classes[] = 'title-align' . $widget['back_to_top_alignment'];

					if( $widget['back_to_top_title'] == '3' )
						$back_to_top_title = $widget['back_to_top_custom_title'];
					else
						$back_to_top_title = $back_to_top['title'][$widget['back_to_top_title']];

				}

				//Start afresh
				$output = '';

				//If we do have a to top link, build that markup
				if( $widget['back_to_top'] == 'on' )
				{

					$href = '';

					if( isset( $widget['back_to_top_custom_link'] ) && !empty( $widget['back_to_top_custom_link'] ) )
					{
						
						if( substr( $widget['back_to_top_custom_link'], 0, 4 ) == 'http' )
							$href = $widget['back_to_top_custom_link'];
						else
							$href = '#' . $widget['back_to_top_custom_link'];

					}

					$output.= '<a href="' . (  !empty( $href ) ? $href : apply_filters( 'chemistry_to_top_hash', '#content' ) ) . '"' . $this->_class( $classes ) . '><hr /><span' . $this->_class( 'back-to-top' ) . '>' . $back_to_top_title . ' </span></a>';
				}
				else
				{

					//Otherwise just stick the <hr> in there
					$output.= '<hr' . $this->_class( $classes ) . '" />';

				}

				//Exile on Main Street
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

				$back_to_top = array( 

				    //Where do we want our 'top' notice?
					'alignment' => array( 

						'left' => __( 'Left' , 'chemistry' ),
						'right' => __( 'Right' , 'chemistry' ),
						'center' => __( 'Centre' , 'chemistry' )
					 ),

					//And what is it to say?
					'title' => array( 

						'0' => __( 'Top' , 'chemistry' ),
						'1' => __( '&#92; top' , 'chemistry' ),
						'2' => __( '&uarr; top' , 'chemistry' ),
						'3' => __( 'Custom' , 'chemistry' )
					 )

				);

				//Form markup
				return '<fieldset class="chemistry-form">
					<h2 class="chemistry-tab-title">' . __( 'General' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<label>' . $this->field( 'checkbox', 'clear', $widget ) . '<span class="label-title"> ' . __( 'Clear divider' , 'chemistry' ) . '</span></label>
						<label>' . $this->field( 'checkbox', 'back_to_top', $widget, array( 'class' => 'chemistry-cond chemistry-group-1' ) ) . ' <span class="label-title">' . __( 'Include back to top link' , 'chemistry' ) . '</span></label>
						<div class="cols cols-2 chemistry-cond-on chemistry-group-1">
							<div class="col">
								<label><span class="label-title">' . __( 'Back to top link alignment' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'back_to_top_alignment', $widget, array( 'options' => $back_to_top['alignment'] ) ) . '</label>
							</div>
							<div class="col">
								<label><span class="label-title">' . __( 'Back to top link title' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'back_to_top_title', $widget, array( 'options' => $back_to_top['title'], 'class' => 'chemistry-cond chemistry-group-2' ) ) . '</label>
								<label class="chemistry-cond-3 chemistry-group-2"><span class="label-title">' . __( 'Custom title' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'back_to_top_custom_title', $widget ) . '</label>
								<label class="chemistry-cond-3 chemistry-group-2"><span class="label-title">' . __( 'Custom link' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'back_to_top_custom_link', $widget ) . '</label>
							</div>
						</div>
					</div>

					<h2 class="chemistry-tab-title">' . __( 'Other' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<label><span class="label-title">' . __( 'Additional classes' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'classes', $widget ) . '</label>
					</div>
				</fieldset>';

			}/* form() */

		}/* class chemistry_potion_horizontal_rule */

	}/* !class_exists( 'chemistry_potion_horizontal_rule' ) */

?>