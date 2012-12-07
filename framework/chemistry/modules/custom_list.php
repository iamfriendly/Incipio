<?php

	if( !class_exists( 'chemistry_potion_custom_list' ) )
	{

		class chemistry_potion_custom_list extends chemistry_molecule_widget
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

				parent::__construct( 'list', __( 'List' , 'chemistry' ) );
				$this->label = __( 'Several different styles of custom lists' , 'chemistry' );

			}/* __construct() */


			/* =========================================================================== */
			
			/**
			 * Markup for our custom lists. Probably need a better way to abstract this to be honest
			 * it's currently a manual thing for different icon sets etc.
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

					'bullet' => '',
					'classes' => '',
					'list_items_layout' => '1'

				 ), $widget );

				//Get what's been put in our textarea and split on newlines
				$widget['text'] = Chemistry::strip_only_some_tags( $widget['text'], '<ul><li>' );
				$elems = explode( "\n", $widget['text'] );
				$elem_count = count( $elems );

				//Build our classes based on choices
				$classes = array( 'widget' );

				if( !empty( $widget['bullet'] ) )
				{

					$classes[] = 'custom-bullet';
					$classes[] = $widget['bullet'];

				}

				//Start afresh
				$output = '';

				//If we aren't an inline list
				if( $widget['list_items_layout'] != 'inline' )
				{

					$col_count = $widget['list_items_layout'];

					$cols = array();

					for( $i = 0; $i < $col_count; $i++ )
						$cols[$i] = array();

					$col_id = 0;

					foreach( $elems as $elem )
					{

						$elem = trim( $elem );

						if( !empty( $elem ) )
						{
							$cols[$col_id % $col_count][] = $elem;
							$col_id++;
						}

					}

					$output .= $col_count > 1 ? '<div' . $this->_class( array( 'cols', 'cols-' . $col_count ) ) . '>' : '';

					for( $i = 0; $i < $col_count; $i++ )
					{

						$output .= $col_count > 1 ? '<div' . $this->_class( 'col' ) . '>' : '';
						$output .= '<ul' . $this->_class( $classes, $widget['classes'] ) . '>';

						foreach( $cols[$i] as $elem )
							$output .= '<li>' . $elem . ' </li>';

						$output .= '</ul>';
						$output .= $col_count > 1 ? '</div>' : '';

					}

					$output .= $col_count > 1 ? '</div>' : '';

				}
				else
				{

					//We're an inline list, output the relevant markup
					if( !empty( $widget['bullet'] ) )
						$classes[] = 'inline-bullets';

					$output .= '<ul' . $this->_class( $classes, $widget['classes'] ) . '>';

					for( $i = 0; $i < count( $elems ); $i++ )
						if( !empty( $elems[$i] ) )
							$output .= '<li>' . $elems[$i] . '</li>';

					$output .= '</ul>';

				}

				//Thanks for the wine, California
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

				//Default styles
				$bullets = array( 

					'' => __( 'Default' , 'chemistry' ),
					'check-1' => __( 'Check 1' , 'chemistry' ),
					'check-2' => __( 'Check 2' , 'chemistry' ),
					'arrow-1' => __( 'Arrow 1' , 'chemistry' ),
					'arrow-2' => __( 'Arrow 2' , 'chemistry' ),
					'warning-1' => __( 'Warning 1' , 'chemistry' ),
					'warning-2' => __( 'Warning 2' , 'chemistry' ),
					'error-1' => __( 'Error 1' , 'chemistry' ),
					'error-2' => __( 'Error 2' , 'chemistry' )

				 );

				$list_items_layout = array( 

					'1' => __( '1 Column', 'chemistry' ),
					'2' => __( '2 Columns' , 'chemistry' ),
					'3' => __( '3 Columns' , 'chemistry' ),
					'4' => __( '4 Columns' , 'chemistry' ),
					'5' => __( '5 Columns' , 'chemistry' ),
					'6' => __( '6 Columns' , 'chemistry' ),
					'8' => __( '8 Columns' , 'chemistry' ),
					'10' => __( '10 Columns' , 'chemistry' ),
					'inline' => __( 'Inline' , 'chemistry' )

				 );

				//Build our form markup
				return '<fieldset class="chemistry-form">
					<h2 class="chemistry-tab-title">' . __( 'General' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<label><span class="label-title">' . __( 'Bullet' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'bullet', $widget, array( 'options' => $bullets ) ) . '</label>
						<label><span class="label-title">' . __( 'List Items Layout' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'list_items_layout', $widget, array( 'options' => $list_items_layout ) ) . '</label>
						<label><span class="label-title">' . __( 'Content' , 'chemistry' ) . ' ' . $this->field( 'textarea', 'text', $widget ) . '<small>' . __( 'You can use any plain-text or shortcodes that you like. WordPress text formatting will automatically be applied. Use one line per list item.' , 'chemistry' ) . '</small></label>
					</div>
					<h2 class="chemistry-tab-title">' . __( 'Other' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<label><span class="label-title">' . __( 'Additional classes' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'classes', $widget ) . '</label>
					</div>
				</fieldset>';

			}/* form() */

		}/* class chemistry_potion_custom_list */

	}/* !class_exists( 'chemistry_potion_custom_list' ) */

?>