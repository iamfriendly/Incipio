<?php

	if( !class_exists( 'chemistry_potion_custom_html' ) )
	{

		class chemistry_potion_custom_html extends chemistry_molecule_widget
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

				parent::__construct( 'html', __( 'HTML' , 'chemistry' ) );
				$this->label = __( 'For when you need to enter custom HTML' , 'chemistry' );

			}/* __construct() */


			/* =========================================================================== */
			
			/**
			 * Easiest thing ever, just return what's in the html box.
			 * it's a bit risky to trust people with such stuff, but the
			 * post_content filters of WP will handle that
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (array) $widget - Widget config
			 * @return The html entered in the box
			 */
			
			public function widget( $widget )
			{

				return ( isset( $widget['html'] ) ? $widget['html'] : '' );

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

				//Form markup
				return '<fieldset class="chemistry-form">
					<h2 class="chemistry-tab-title">' . __( 'General' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<label><span class="label-title">' . __( 'HTML code' , 'chemistry' ) . '</span> ' . $this->field( 'textarea', 'html', $widget ) . '</label>
					</div>
				</fieldset>';

			}/* form() */

		}/* class chemistry_potion_custom_html' */

	}/* !class_exists( 'chemistry_potion_custom_html' ) */

?>