<?php

	/* ================================================================================ */

	if ( !class_exists( 'flab_content_as_row_widget' ) )
	{
	
		class flab_content_as_row_widget extends flab_builder_widget
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
			
				parent::__construct( 'content-as-row', __( 'Content As Row' ) );
				$this->label = __( 'If you would like to include the content of another page use this' );
				
			}/* __construct() */
			
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
				
				$page_title = $widget['of_pages'];
				$bg_properties = $widget['bg_properties'];
			
				$post_id = friendly_get_id_from_slug( $page_title, 'page' );
				$post_data = get_post( $post_id );
				
				if( $bg_properties == "on" )
				{
					
					$styles = friendly_background_styles_output( $post_id );
	
				}
				else
				{
					$styles = "";
				}
				
				$out = "<div class='friendly_content_as_row row_".$post_id."' $styles><div>";
				
					$flab_content = flab_builder::parse( flab::meta( 'builder_data', true, $post_id ), 'main', false );
					if( $flab_content && $flab_content != "" )
						$out .= $flab_content;
						
					$wp_content = $post_data->post_content;
					$out .= apply_filters( 'the_content', $wp_content );
					
				$out .= "</div></div>";
				
				return $out;
				
				
			}/* widget() */
			
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
			
				$of_pages = array();
				$of_pages_obj = get_pages('sort_column=post_parent,menu_order');    
				
				foreach ($of_pages_obj as $of_page)
				{
				    $of_pages[$of_page->ID] = $of_page->post_name;
				}
				
				$of_pages_tmp = array_unshift($of_pages, __( "Select a page:", THEMENAME ));
				$of_pages_options = "";
				
				foreach ( $of_pages as $value => $label )
				{
					$of_pages_options .= '<option value="'.$label.'"'.( ( isset( $widget['of_pages'] ) && $widget['of_pages'] == $label ) ? ' selected="selected"' : '' ).'>'.$label.'</option>';
				}
	
				return '<fieldset class="flab-form">
					<label>'.__( 'Page' ).' <select'.$this->get_field_atts( 'of_pages' ).' name="'.$this->get_field_name( 'of_pages' ).'" value="'.( isset( $widget['of_pages'] ) ? $widget['of_pages'] : '' ).'">'.$of_pages_options.'</select></label>
					<label><input type="checkbox"'.$this->get_field_atts( 'bg_properties' ).' name="'.$this->get_field_name( 'bg_properties' ).'"'.( ( isset( $widget['bg_properties'] ) && $widget['bg_properties'] == 'on' ) ? ' checked="checked"' : '' ).' /> '.__( 'Use Background Properties' ).'</label>
				</fieldset>';
				
			}/* form() */
			
			/* ============================================================================ */
			
		}/* class flab_content_as_row_widget */
		
	}

	/* ================================================================================ */

?>