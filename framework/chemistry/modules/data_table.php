<?php

	if( !class_exists( 'chemistry_potion_data_table' ) )
	{

		class chemistry_potion_data_table extends chemistry_molecule_widget
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

				parent::__construct( 'table', __( 'Table' , 'chemistry' ) );
				$this->label = __( 'Got data? This is a module to create a table to display that data.' , 'chemistry' );

			}/* __construct() */


			/* =========================================================================== */
			
			/**
			 * Our data table widget markup
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.4
			 * @param (array) $widget - Our widget data
			 * @return Markup
			 */
			
			public function widget( $widget )
			{

				//Compare passed options to our defaults
				$widget = chemistry_molecule_widget::extend( array( 

					'style' => '',
					'header_top' => '',
					'header_left' => '',
					'classes' => ''

				 ), $widget );

				//Set up our classes
				$classes = array( 'widget', 'table', 'chemistry-data-table' );

				if( !empty( $widget['style'] ) )
					$classes[] = 'style-' . $widget['style'];

				//Begin our output
				$output = '<table cellspacing="0"' . $this->_class( $classes, $widget['classes'] ) . '">';

				//Some starting points
				$column = 0;
				$row = 0;
				$th_top = ( $widget['header_top'] == 'on' );
				$th_left = ( $widget['header_left'] == 'on' );

				$count = count( $widget['table_data'] );

				//Build our markup. The idea for the smart markup came from
				//http://www.phpbuilder.com/columns/patterson20050620_5.php3
				for( $i = 0; $i < $count; $i++ )
				{

					if( $column == 0 )
						$output .= '<tr>';

					$column++;
					$output .= '<t' . ( ( ( $row == 0 && $th_top ) || ( $column == 1 && $th_left ) ) ? 'h' : 'd' ) . '>';
					$output .= $widget['table_data'][$i];
					$output .= '</t' . ( ( ( $row == 0 && $th_top ) || ( $column == 1 && $th_left ) ) ? 'h' : 'd' ) . '>';

					if( $column == $widget['columns'] )
					{

						$output .= '</tr>';
						$column = 0;
						$row++;

					}

				}

				$output .= '</table>';

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

				//Some simple styles for our data tables
				$styles = apply_filters( 'chemistry_table_styles', array( 

					'' => __( 'Theme default' , 'chemistry' ),
					'1' => __( 'Style 1' , 'chemistry' ),
					'2' => __( 'Style 2' , 'chemistry' )

				 ) );

				//If num rows is blank, we need 1 min, obviously
				if( !isset( $widget['rows'] ) )
					$widget['rows'] = 1;

				//Same with columns
				if( !isset( $widget['columns'] ) )
					$widget['columns'] = 1;

				//Make sure we're all numbers up in this hizzle
				$widget['rows'] = intval( $widget['rows'] );
				$widget['columns'] = intval( $widget['columns'] );

				//Sanity checking if people are trying to be clever
				if( $widget['rows'] < 1 )
					$widget['rows'] = 1;

				//Max of 50. If you need more than 50 rows, this isn't the thing for you
				if( $widget['rows'] > 50 )
					$widget['rows'] = 50;

				//Stop being stupid
				if( $widget['columns'] < 1 )
					$widget['columns'] = 1;

				//20 columns is plenty - they'll each be about [_]  <-- this wide
				if( $widget['columns'] > 20 )
					$widget['columns'] = 20;

				//Begin our putput
				$table_data = '<table class="table chemistry-table">';

				if( !isset( $widget['table_data'] ) || empty( $widget['table_data'] ) )
				{

					$table_data .= '<tr><td>' . $this->field( 'textarea', 'table_data][', NULL, array( 'cols' => 10, 'rows' => 3 ) ) . '</td></tr>';

				}
				else
				{

					$column = 0;

					for( $i = 0; $i < count( $widget['table_data'] ); $i++ )
					{

						if( $column == 0 )
							$table_data .= '<tr>';

						$column++;
						$table_data .= '<td>' . $this->field( 'textarea', 'table_data][', $widget['table_data'][$i], array( 'cols' => 10, 'rows' => 3 ) ) . '</td>';

						if( $column == $widget['columns'] )
						{

							$table_data .= '</tr>';
							$column = 0;

						}

					}

				}

				//Tidy up
				$table_data .= '</table>';

				//Form markup
				return '<fieldset class="chemistry-form">
					<h2 class="chemistry-tab-title">' . __( 'General' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<label><span class="label-title">' . __( 'Style' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'style', $widget, array( 'options' => $styles ) ) . '</label>
						<div class="cols-2">
							<div class="col">
								<label><span class="label-title">' . __( 'Rows' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'rows', $widget ) . '</label>
								<label>' . $this->field( 'checkbox', 'header_top', $widget ) . ' <span class="label-title">' . __( 'Highlight first row' , 'chemistry' ) . '</span></label>
							</div>
							<div class="col">
								<label><span class="label-title">' . __( 'Columns' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'columns', $widget ) . '</label>
								<label>' . $this->field( 'checkbox', 'header_left', $widget ) . ' <span class="label-title">' . __( 'Highlight first column' , 'chemistry' ) . '</span></label>

							</div>
						</div>

						' . $this->field( 'hidden', 'table', $widget ) . '
					</div>
					<h2 class="chemistry-tab-title">' . __( 'Other' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<label><span class="label-title">' . __( 'Additional classes' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'classes', $widget ) . '</label>
					</div>
				</fieldset>
				<fieldset class="chemistry-form">
					' . $table_data.'
				</fieldset>';

			}/* form() */

		}/* class chemistry_potion_data_table */

	}/* !class_exists( 'chemistry_potion_data_table' ) */

?>