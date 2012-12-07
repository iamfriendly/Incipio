<?php


	/* ======================================================================================

	We have a separate layout (template) save and build routine which allows people to add
	potions and modules in the Chemistry builder and then save that layout and load it in
	a different post or page. First we need to extend our chemistry_metabox class to allow us
	to output the desired fields. Then we need to build our save and layout routine. It's
	called a molecule templte and is saved as post meta.

	====================================================================================== */

	//Load our metabox class so that we can extend it
	require_once locate_template( '/framework/chemistry/admin/metabox/molecule.php' );



	if(  !class_exists( 'chemistry_layout_editor' ) )
	{


		/**
		 * Build our layout metabox and template loader/save routine
		 *
		 * @author Richard Tape
		 * @package Chemistry Layout
		 * @since 0.7
		 */
		
		class chemistry_layout_editor extends chemistry_metabox
		{

			/**
			 * Initialise ourselves as a metabox (and hence get all the lovely loveliness thence entailed)
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param None
			 * @return None
			 */
			
			public function init()
			{

				chemistry_metabox_molecule::init();

			}/* init() */


			/* =========================================================================== */


			/**
			 * Run the header() method from our molecule class as a default - we don't need anything new here
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param None
			 * @return None
			 */
			
			public function header()
			{

				chemistry_metabox_molecule::header();

			}/* header() */


			/* =========================================================================== */


			/**
			 * Helper function to add post meta for each of our molecule templates. add_post_meta
			 * serializes the array passed into it.
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (string) $name - The name of our molecule template
			 * @param (string) $layout - The layout of our molecules
			 * @return None
			 */
			
			public function layout_save( $name, $layout )
			{

				global $post;

				add_post_meta( $post->ID, Chemistry::chemistry_option( 'prefix' ) . 'molecule_template', array( 'name' => $name, 'layout' => $layout ), false );

			}/* layout_save() */


			/* =========================================================================== */


			/**
			 * The load routine for our molecule templates. When someone wishes to reuse a layout they've made
			 * elsehwere they save them (above) and they can then reuse them. Our layout-editor metabox calls
			 * this method when a user wishes to load a specific template. 
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (string) $id - The ID of the layout requested
			 * @return  None
			 */
			
			public function layout_load( $id )
			{

				//Get some objects we'll need including the database class
				global $post, $wpdb;

				//Load a list of all of our saved layouts
				$layouts = self::layout_list();

				//If the layout requested exists
				if( isset( $layouts[$id] ) )
				{

					//It's a serialized array so lets make it useful
					$layout = $wpdb->get_var( $wpdb->prepare( 'SELECT meta_value FROM ' . $wpdb->postmeta . ' WHERE meta_id=\'' . $id . '\'', '' ) );
					$layout = unserialize( $layout );

					//Load and send the layout
					$_POST['chemistry_molecule_widget'] = $layout['layout'];
					Chemistry::get_or_set_meta( 'molecule_data', $layout['layout'], $post->ID, true );

				}

			}/* layout_load() */


			/* =========================================================================== */


			/**
			 * Offer the option to delete a saved layout. Simply get rid from the database
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (string) $id - The ID of the layout requested
			 * @return Nope
			 */
			
			public function layout_remove( $id )
			{

				//Get some objects we'll need including the database class
				global $post, $wpdb;

				//Load a list of all of our saved layouts
				$layouts = self::layout_list();

				//If the layout requested exists, get rid
				if( isset( $layouts[$id] ) )
					$wpdb->query( $wpdb->prepare( 'DELETE FROM ' . $wpdb->postmeta . ' WHERE meta_id=\'' . $id . '\'', '' ) );

			}/* layout_remove */


			/* =========================================================================== */


			/**
			 * Helper method to grab a list of all the saved layouts
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param None
			 * @return (array) $output - All of the layouts
			 */
			
			public function layout_list()
			{

				//We need the db class
	  			global $wpdb;

	  			//Grab them all from the datavase, they're saved as postmeta as chemistry_molecule_template
	  			$layouts = $wpdb->get_results(  $wpdb->prepare(  'SELECT post_id, meta_id, meta_value FROM ' . $wpdb->postmeta . ' WHERE meta_key=\'' . Chemistry::chemistry_option(  'prefix'  ) . 'molecule_template\'', ''  )  );
	  			
	  			//Just in case
	  			$output = array();

	  			foreach( $layouts as $layout )
	  			{

	  				//Unserialize our fruits from above
	  				$data = unserialize( $layout->meta_value );

	  				//Add to our output array
	  				$output[$layout->meta_id] = array( 'name' => $data['name'] . ' ( Post ID: ' . $layout->post_id . ' )', 'post_id' => $layout->post_id );

	  			}

	  			//Bingo bango
	  			return $output;

			}/* layout_list() */


			/* =========================================================================== */


			/**
			 * We've made a new layout. Let's save it. Someone has clicked the 'save' button, we
			 * intercept that request and do what we need to do
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (int) $post_id - The post that we're on when we're saving (needed for postmeta)
			 * @return None
			 */
			
			public function save( $post_id )
			{

				//Make sure we know where we are and have access to everything we need
				global $post, $post_type;

				//As long as we're not on a post revision
				if( $post != NULL && ! wp_is_post_revision( $post_id ) )
				{

					//If we're saving
					if( Chemistry::process_request_check_empty( 'molecule_template_save', 'POST' ) )
					{

						$request = Chemistry::get_and_process_option_save( 'molecule_template_name', 'POST' );

						if(  ! empty( $request ) )
							self::layout_save( $request, $_POST['chemistry_molecule_widget'] );

					}

					//If we're loading
					if( Chemistry::process_request_check_empty( 'molecule_template_load', 'POST' ) )
					{

						$request = Chemistry::get_and_process_option_save( 'molecule_template', 'POST' );

						if(  ! empty( $request ) )
							self::layout_load( $request );

					}

					//If we're deleting
					if( Chemistry::process_request_check_empty( 'molecule_template_remove', 'POST' ) )
					{

						$request = Chemistry::get_and_process_option_save( 'molecule_template', 'POST' );

						if(  ! empty( $request ) )
							self::layout_remove( $request );

					}

				}

				//Finish up and push our changes to the editor if necessary
				if(  ! Chemistry::process_request_check_empty( 'molecule_template_load', 'POST' ) )
					chemistry_metabox_molecule::save( $post_id );

			}/* save() */


			/* =========================================================================== */


			/**
			 * The markup for our Layout Editor metabox
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param None
			 * @return The markup
			 */
			
			public function body()
			{

				$layouts = self::layout_list();

				return '<div class="molecule-options">
					<p class="molecule-template">' . '<a class="button button-secondary" href="#template-load">'.__( 'Load Template', 'chemistry' ).'</a> <a class="button button-secondary" href="#template-save">'.__( 'Save New', 'chemistry' ).'</a></p>
					<fieldset class="chemistry-form" style="display: none;">
						<label>' . Chemistry::chemistry_metabox_field( 'molecule_template', array( 'type' => 'select', 'options' => $layouts ) ) . '</label>
						<div class="load_remove_template">
							'.Chemistry::chemistry_metabox_field( 'molecule_template_load', array( 'type' => 'submit', 'value' => __( 'Load', 'chemistry' ), 'class' => 'alignright button-primary' ) ).'
							'.Chemistry::chemistry_metabox_field( 'molecule_template_remove', array( 'type' => 'submit', 'value' => __( 'Delete', 'chemistry' ), 'class' => 'remove-template alignleft confirm' ) ).'
						</div>
					</fieldset>
					<fieldset class="chemistry-form hide-if-no-js" style="display: none;">
						<label>' . Chemistry::chemistry_metabox_field( 'molecule_template_name', array( 'type' => 'text', 'class' => 'widefat save_new_template', 'placeholder' => __( 'Template Name' ) ) ).'</label>
						<div class="buttonset-1">
							'.Chemistry::chemistry_metabox_field( 'molecule_template_save', array( 'type' => 'submit', 'value' => __( 'Save', 'chemistry' ), 'class' => 'alignright button-primary' ) ).'
						</div>
					</fieldset>
				</div>';

			}/* body() */

		}/* class chemistry_layout_editor */

	}/* !class_exists( 'chemistry_layout_editor' ) */

?>