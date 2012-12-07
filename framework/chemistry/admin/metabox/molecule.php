<?php

	/* ======================================================================================

	The layout for our actual Chemistry editor (the one people see when they click on the button
	in the toolbar). Technically, it's a metabox. A pretty complicated one that has drag-and-drop
	within it as well as lots of cues to load the overlay with the relevant options. The body is
	called in modules/molecule.php

	====================================================================================== */

	if( !class_exists( 'chemistry_metabox_molecule' ) )
	{

		class chemistry_metabox_molecule extends chemistry_metabox
		{

			/**
			 * Load our required scripts and styles and set some options when we're initialised
			 * Hide both the visual and html tab when active
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.1
			 * @param None
			 * @return None
			 */
			
			public function init()
			{
				
				//Only do this if we're in the dashboard
				if( is_admin() )
				{

					//Run a method to ensure we're only loading our js/css on the correct screens
					add_action( 'admin_print_scripts', array( 'chemistry_metabox_molecule', 'load_admin_scripts' ) );
					add_action( 'admin_print_styles', array( 'chemistry_metabox_molecule', 'load_admin_css' ) );

					//Make sure the visual and html editors are hidden
					Chemistry::chemistry_optionjs( 'hide_visual_tab', Chemistry::get_or_set_option( 'hide_visual_tab' ) == 'on' );
					Chemistry::chemistry_optionjs( 'hide_html_tab', Chemistry::get_or_set_option( 'hide_html_tab' ) == 'on' );

				}

			}/* init() */


			/* =========================================================================== */


			/**
			 * Load our Admin js when we need it
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param 
			 * @return 
			 */
			
			public function load_admin_scripts()
			{

				global $hook_suffix;

				/*
					We only want to load our custom js on Add New posts/pages and custom post types screens
					For Add new post/page/CPT, $hook_suffix is post-new.php
					For Edit post/page/CPT, it's post.php
				*/

				if( $hook_suffix == "post.php" || $hook_suffix == "post-new.php" )
				{

					wp_enqueue_script( 'chemistry_admin_js', Chemistry::path( 'admin/assets/js/chemistry_admin.js', true ), array( 'jquery', 'jquery-ui-sortable', 'jquery-ui-draggable', 'jquery-ui-droppable' ), Chemistry::chemistry_option( 'version' ) );

				}

			}/* load_admin_scripts() */



			/* =========================================================================== */


			/**
			 * Load our Admin CSS when we need it
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param 
			 * @return 
			 */
			
			public function load_admin_css()
			{

				global $hook_suffix;
				if( $hook_suffix == "post.php" || $hook_suffix == "post-new.php" )
				{

					wp_enqueue_style( 'chemistry_admin_css', Chemistry::path( 'admin/assets/css/chemistry_admin.css', true ), '', Chemistry::chemistry_option( 'version' ) );

				}

			}/* load_admin_css() */


			/* =========================================================================== */


			/**
			 * No need to edit this one as all the nittygritty is done in body()
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.1
			 * @param None
			 * @return None
			 */
			
			public function header(){}


			/* =========================================================================== */


			/**
			 * We're saving, so let's ensure we keep a record of where all of our meta is
			 * and what position it's in.
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (int) $post_id - The ID of the post we're saving to.
			 * @return None
			 */
			
			public function save( $post_id )
			{

				//Make sure we have access to the global objects
				global $post, $post_type;

				//As long as we actually have a post_id (i.e. a $post object)
				if( $post != null )
				{

					//Make sure we start fresh
					if( isset( $_POST['chemistry_molecule_widget']['___POSITION___'] ) )
						unset( $_POST['chemistry_molecule_widget']['___POSITION___'] );

					if( isset( $_POST['chemistry_molecule_widget']['___CHEMISTRYID___'] ) )
						unset( $_POST['chemistry_molecule_widget']['___CHEMISTRYID___'] );

					//Get what we're saving
					if( array_key_exists( 'chemistry_molecule_widget', $_POST ) )
						Chemistry::get_or_set_meta( 'molecule_data', $_POST['chemistry_molecule_widget'], $post->ID, true );

				}

				//If we have the Chemistry tab active, then let's ensure it's active when we next load the page
				Chemistry::chemistry_metabox_save( $_POST, array( 

					'checkbox' => array( array( 'name' => 'editor_tab', 'relation' => 'meta', 'value' => '' ) ) )

				);
			
			}/* save() */


			/* =========================================================================== */


			/**
			 * The markup for our content builder
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (array) $molecule_data - Our actual chemistry molecule data
			 * @param (int) $parent_id - The parent post ID 
			 * @param (bool) $read_only - Is this to be read-only..err, only
			 * @return The markup for Chemistry
			 */
			
			public function body( $molecule_data = array(), $parent_id = null, $read_only = false )
			{

				//We need to know where we are w.r.t what post and post type
				global $post, $post_type;

				//Start with a clean slate
				$body = '';
				$widgets_output = '';

				//Are we hiding any widgets from our overlay, if so we need to know about them so we don't show 'em
				$molecule_hidden_widgets = Chemistry::get_or_set_option( 'molecule_hidden_widgets' );

				if( isset( $molecule_hidden_widgets[0] ) && !empty( $molecule_hidden_widgets[0] ) )
					$molecule_hidden_widgets = $molecule_hidden_widgets[0];

				//Ensure it's array so we don't get in trouble when we do a foreach
				if(  !is_array( $molecule_hidden_widgets ) )
					$molecule_hidden_widgets = array();

				//Get all of our potions
				$widgets = chemistry_molecule::get_molecules();

				//For those that we're hiding, let's run hide() on that widget so it doesn't show. Then grab our output
				foreach( $widgets as $widget )
				{

					if( isset( $molecule_hidden_widgets[$widget->get_slug()] ) && $molecule_hidden_widgets[$widget->get_slug()] == 'on' )
						$widget->hide();

					$widgets_output .= $widget->admin_form();

				}

				//Output our markup
				$body .= '<div id="molecule-widgets" style="display: none;">
					<div class="molecule-widgets-wrap">
					' . $widgets_output . '
					</div>
					<div id="close_and_filter">
						<button name="molecule-modal-close" class="molecule-modal-close">close</button><span class="or_esc">' . __( 'or escape to close', 'chemistry' ) . '</span>
						<fieldset class="chemistry-form filter-molecule-widgets">
							<label class="filter"><input type="text" placeholder="' . __( 'Filter modules', 'chemistry' ) . '" name="molecule-widget-filter" value="" /></label>
						</fieldset>
					</div>
				</div>';

				$body .= '<div class="molecule-location-wrapper' . ( $read_only ? ' read-only' : '' ) . '"><fieldset class="chemistry-form">';

				$widgets_output = '';
				$widgets = chemistry_molecule::get_molecules();
				$laboratories = chemistry_molecule::get_laboratories();
				$molecule_widgets = array();

				if(  !is_array( $molecule_data ) )
					$molecule_data = array();

				$tmp_post = null;

				if( isset( $_GET['post'] ) && $_GET['post'] != $post->ID )
				{

					$tmp_post = $post;

					$post = get_post( $_GET['post'] );

				}

				$id = $post->ID;

				if( $parent_id != null && $parent_id > 0 )
					$id = $parent_id;

				$molecule_widgets = Chemistry::get_or_set_meta( 'molecule_data', true, $id );

				//Again, for WPML We need to do some faffing around
				if( defined( 'ICL_LANGUAGE_CODE' ) && (  ! is_array( $molecule_widgets ) || empty( $molecule_widgets ) ) )
				{

					global $sitepress;

					if( ICL_LANGUAGE_CODE != $sitepress->get_default_language() )
					{

						if( $post->post_status == 'auto-draft' && isset( $_GET['trid'] ) && isset( $_GET['source_lang'] ) )
						{

							global $wpdb, $table_prefix;

							$prefix = $table_prefix;

							$id = $wpdb->get_var( 'SELECT element_id FROM `' . $prefix . 'icl_translations` WHERE `trid`=\'' . $_GET['trid'] . '\' && `language_code`=\'' . $_GET['source_lang'] . '\'' );

						}
						else
						{

							$id = icl_object_id( $post->ID, $post->post_type, true, ( isset( $_GET['source_lang'] ) ? $_GET['source_lang'] : $sitepress->get_default_language() ) );

						}
							
						$molecule_widgets = Chemistry::get_or_set_meta( 'molecule_data', true, $id );

					}

				}

				//WPML Plugin dislikes serialized data. If we're using that plugin then we need to unserialize for it
				if( defined( 'ICL_LANGUAGE_CODE' ) && is_string( $molecule_widgets ) )
					$molecule_widgets = chemistry_molecule::unserialize( $molecule_widgets );

				//Let's set up each lab, with the container and parsed potions
				foreach( $laboratories as $location => $name )
				{

					$molecule_widgets_output = '';

					$body .= '<div id="molecule-location-' . $location . '" class="molecule-location">';
					$body .= chemistry_molecule::parse( $molecule_widgets, $location, true, $molecule_data );
					$body .= '</div>';
					$body .= '<div class=""><button name="molecule-widget-add" id="add_module_button" class="molecule-widget-add"><span class="add-widget-bottom-of-editor">'.__( 'Add Module', 'chemistry' ).'</span></button></div>';

				}

				//Let's not interfere too much by default
				$molecule_tab_default = false;

				//Unless we choose to in the config OR we've just submitted stuff from Chemistry
				if(  ! isset( $_GET['post'] ) && Chemistry::get_or_set_option( 'molecule_tab_default' ) )
					$molecule_tab_default = true;

				//Add our tab and save the status of it in a custom field
				$body .= Chemistry::chemistry_metabox_field( 'editor_tab', array( 'type' => 'hidden', 'relation' => 'meta', 'use_default' => $molecule_tab_default, 'value' => ( $molecule_tab_default ? 'molecule' : '' ) ) );

				//Reset
				if( $tmp_post != null )
					$post = $tmp_post;

				//Cleanup
				$body .= '</fieldset></div>';

				//Bingo bango
				return $body;

			}/* body() */

		}/* class chemistry_metabox_molecule */

	}/* !class_exists( 'chemistry_metabox_molecule' ) */

?>