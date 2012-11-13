<?php

	/* ================================================================================ */

	if( !class_exists( 'flab_metabox_builder' ) )
	{
	
		class flab_metabox_builder extends flab_metabox
		{
		
			/**
			 * Initialise the FLAB and make sure we have our js loaded on the back end and the CSS 
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
		
			public static function init()
			{
			
				if( is_admin() )
				{
				
					flab::load_script( array( 
						
						array( 
						
							'path' => 'admin/flab/assets/js/builder.js',
							'deps' => array( 'jquery', 'jquery-ui-sortable', 'jquery-ui-draggable', 'jquery-ui-droppable' )
							
						 )
						 
					 ) );
	
					flab::load_stylesheet( array( 
					
						array( 
						
							'path' => 'admin/flab/assets/css/builder.css'
							
						 )
						 
					 ) );
					 
				}
				
			}/* init() */
			
			/* ============================================================================ */
	
			/**
			 * We don't need any specific inline styles, but just in case
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function header()
			{
			
				echo '<style type="text/css"></style>';
				
			}/* header() */
			
			/* ============================================================================ */
	
			/**
			 * Handle our saves for each row and each module
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function save()
			{
			
				global $post;
				global $post_type;
	
				if( $post != null )
				{
				
					if( isset( $_POST['flab_builder_widget']['__LOCATION__'] ) )
					{
						unset( $_POST['flab_builder_widget']['__LOCATION__'] );
					}
	
					if( isset( $_POST['flab_builder_widget']['__ID__'] ) )
					{
						unset( $_POST['flab_builder_widget']['__ID__'] );
					}
	
					flab::meta( 'builder_data', $_POST['flab_builder_widget'], $post->ID, true );
	
				}
	
				flab::flab_update_meta_option_from_field( $_POST, array(
				
					'checkbox' => array(
					
						array(
						
							'name' => 'editor_tab',
							'relation' => 'meta',
							'value' => ''
							
						 )
						 
					 )
					 
				 ) );
				 
			}/* save() */
			
			/* ============================================================================ */
	
			/**
			 * If we have modules saved then handle the outputting of them
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function body( $builder_data = array(), $parent_id = null, $read_only = false )
			{
			
				global $post;
				global $post_type;
				global $_D;
	
				$body = '';

				$widgets_output = '';

				$builder_hidden_widgets = flab::option( 'builder_hidden_widgets' );

				if( isset( $builder_hidden_widgets[0] ) && !empty( $builder_hidden_widgets[0] ) )
				{
					$builder_hidden_widgets = $builder_hidden_widgets[0];
				}

				if( !is_array( $builder_hidden_widgets ) )
				{
					$builder_hidden_widgets = array();
				}

				$widgets = flab_builder::get_widgets();

				foreach( $widgets as $widget )
				{
					
					if( isset( $builder_hidden_widgets[$widget->get_slug()] ) && $builder_hidden_widgets[$widget->get_slug()] == 'on' )
					{
						$widget->hide();
					}

					$widgets_output .= $widget->admin_form();
					
				}

				$body .= '<div id="builder-widgets" style="display: none;">
					<div class="builder-widgets-wrap">
					'.$widgets_output.'
					</div>
					<fieldset class="flab-form filter-builder-widgets">
						<span class="filter_esc">'.__( "Press Escape To Close Window", THEMENAME ).'</span>
						<label class="filter"><input type="text" placeholder="'.flab::langr( 'Filter modules' ).'" name="builder-widget-filter" value="" /></label>
					</fieldset>
				</div>';

				$body .= '<div class="builder-location-wrapper'.( $read_only ? ' read-only' : '' ).'"><fieldset class="flab-form">';

				$widgets_output = '';
				$widgets = flab_builder::get_widgets();
				$locations = flab_builder::get_locations();
				$builder_widgets = array();

				if( !is_array( $builder_data ) )
				{
					$builder_data = array();
				}

				$tmp_post = null;

				if( isset( $_GET['post'] ) && $_GET['post'] != $post->ID )
				{
					$tmp_post = $post;

					$post = get_post( $_GET['post'] );
				}

				$id = $post->ID;

				if( $parent_id != null && $parent_id > 0 )
				{
					$id = $parent_id;
				}

				$builder_widgets = flab::meta( 'builder_data', true, $id );

				foreach( $locations as $location => $name )
				{
				
					$builder_widgets_output = '';

					$body .= '<div class="buttonset-1"><button name="builder-widget-add" class="button-1 button-1-2 builder-widget-add" ><span>'.flab::langr( 'Add Modile' ).'</span></button></div>';
					$body .= '<div id="builder-location-'.$location.'" class="builder-location">';
					$body .= flab_builder::parse( $builder_widgets, $location, true, $builder_data );
					$body .= '</div>';
					$body .= '<div class="buttonset-1"><button name="builder-widget-add" class="button-1 button-1-2 builder-widget-add"><span>'.flab::langr( 'Add Module' ).'</span></button></div>';
					
				}

				$builder_tab_default = false;

				if( !isset( $_GET['post'] ) && flab::option( 'builder_tab_default' ) )
				{
					$builder_tab_default = true;
				}

				$body .= flab::flab_create_field_layout( 'editor_tab', array( 'type' => 'hidden', 'relation' => 'meta', 'use_default' => $builder_tab_default, 'value' => ( $builder_tab_default ? 'builder' : '' ) ) );

				if( $tmp_post != null )
				{
					$post = $tmp_post;
				}

				$body .= '</fieldset></div>';
				
				return $body;
				
			}/* body() */
			
			/* ============================================================================ */

		}/* class flab_metabox_builder */
		
	}

	/* ================================================================================ */

?>
