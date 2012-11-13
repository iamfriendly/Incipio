<?php

	/* ================================================================================ */
	
	if( !class_exists( 'flab_html_widget' ) )
	{
	
		class flab_html_widget extends flab_builder_widget
		{
		
			/**
			 * Set up the title and description
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
		
			public function __construct( )
			{
				
				parent::__construct( 'html', __( 'HTML' ) );
				$this->label = __( 'Need to output some specific html? Use this, then!' );
				
			}
			
			/* ============================================================================ */
	
			/**
			 * Output the contents of this widget
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public function widget( $widget )
			{
				return ( isset( $widget['html'] ) ? do_shortcode( $widget['html'] ) : '' );
			}
			
			/* ============================================================================ */
	
			/**
			 * The options for this widget
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public function form( $widget )
			{
				
				return '<fieldset class="flab-form">
						<label>'.__( 'Your raw HTML' ).' <textarea'.$this->get_field_atts( 'html' ).' name="'.$this->get_field_name( 'html' ).'" rows="10">'.htmlspecialchars( isset( $widget['html'] ) ? $widget['html'] : '' ).'</textarea></label>
				</fieldset>';
				
			}
			
			/* ============================================================================ */
			
		}
		
	}
	
	/* ================================================================================ */

?>