<?php

	if( !class_exists( 'chemistry_potion_wysiwyg' ) )
	{

		class chemistry_potion_wysiwyg extends chemistry_molecule_widget
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

				parent::__construct( 'rich-text', __( 'Rich text' , 'chemistry' ) );
				$this->label = __( 'Advanced text editor powered by TinyMCE' , 'chemistry' );

			}/* __construct() */


			/* =========================================================================== */
			
			/**
			 * Just output the markup from the editor and run it through wpautop
			 * What could possibly go wrong?
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (array) $widget - Config
			 * @return Markup
			 */
			
			public function widget( $widget )
			{

				return wpautop( $widget['text'] );

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

				//Yes, yes, I know, output buffering, not ideal, but it's needed for STUPID TinyMCE
				//When will WP get a proper editor?
				ob_start();
				media_buttons( $this->get_field_name( 'text' ) );
				$mediabuttons = ob_get_clean();

				//Form markup
				return '<fieldset class="chemistry-form">
					<h2 class="chemistry-tab-title">' . __( 'General' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						' . (  ! user_can_richedit() ? '<p class="chemistry-error">' . __( 'Rich text editor has been disabled. Check your account settings.' , 'chemistry' ) . '</p>' : '' ) . '
						<div class="wp-editor-tools">
							' . $mediabuttons.'
						</div>
						<div class="wp-editor-wrap">
							<div class="wp-editor-container">
								<textarea' . $this->get_field_atts( 'text' ) . ' name="' . $this->get_field_name( 'text' ) . '" id="' . $this->get_field_name( 'text' ) . '" cols="15" class="tinymce">' . ( isset( $widget['text'] ) ? wpautop( $widget['text'] ) : '' ) . '</textarea>
							</div>
						</div>
					</div>
				</fieldset>';

			}/* form() */

		}/* class chemistry_potion_wysiwyg */

	}/* !class_exists( 'chemistry_potion_wysiwyg' ) */

?>