<?php

	if( !class_exists( 'chemistry_molecule' ) )
	{

		class chemistry_molecule extends chemistry_potion
		{

			/**
			 * A trcukload of variables we use throughout Chemistry. Mainly to do with
			 * the data our potions store, which potions we're using and the content
			 * we're outputting. Petri dishes are data controllers. Laboratories are
			 * where we have our editors or metaboxes.
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.1
			 */
			
			protected static $are_we_setup = false;
			protected static $chemistry_data_holder = array();
			protected static $chemistry_return_data = array();

			protected static $molecules = array();
			protected static $molecule_slugs = array();
			protected static $molecule_data = array();
			protected static $molecule_classes = array();

			protected static $petri_dish = null;
			protected static $laboratories = array();
			protected static $disabled_fields = array();
			

			/* =========================================================================== */

			/**
			 * Registers our main Chemistry editor so that we can actually *do* something :)
			 * So, yeah, pretty important.
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.1
			 * @param None
			 * @return None
			 */
			
			public function init()
			{

				self::register_laboratory( 'main', __( 'Chemistry Editor' , 'chemistry' ) );

			}/* init() */


			/* =========================================================================== */

			/**
			 * When we kick things off we need to register our scripts and styles as well as
			 * actually register our potion classes.
			 *
			 * This method actually registers all of our potions
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param 
			 * @return 
			 */
			
			public function chemistry_register_potions()
			{

				//Our scripts and styles
				if( is_admin() )
				{

					wp_enqueue_script( 'tiny_mce' );
					wp_enqueue_style( 'editor-buttons' );

				}

				//Run through our available potions and pass through a filter so we can edit in a theme
				self::$molecule_classes = apply_filters( 'chemistry_molecules', self::$molecule_classes );

				//Loop through each one and go ahead and do the dirty
				$count = count( self::$molecule_classes );

				for( $i = 0; $i < $count; $i++ )
				{

					$class = self::$molecule_classes[$i];

					//If we actually exist
					if( class_exists( $class ) )
					{

						$object = new $class();

						if( !isset( self::$molecule_slugs[$object->get_slug()] ) )
						{

							self::$molecules[] = $object;
							self::$molecule_slugs[$object->get_slug()] = $class;

						}
						else
						{

							//Wachutalkin' bout Willis?
							unset( $obejct );

						}

					}

				}

			}/* chemistry_register_potions() */

			/* =========================================================================== */

			/**
			 * Add the TinyMCE Editor and overlay
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.1
			 * @param None
			 * @return None
			 */
			
			public function header()
			{

				//Only do this in the admin
				if( is_admin() )
				{

					global $post_type;
					
					if( empty( $post_type ) )
					{

						if( function_exists( 'add_thickbox' ) )
							add_thickbox();

						if(  function_exists(  'wp_tiny_mce'  )  )
			    		{

			    			//Shut up warning, you annoying SoB
			    			if(  defined(  'WP_DEBUG'  ) && WP_DEBUG  )
			    				add_filter(  'deprecated_function_trigger_error', '__return_false'  );

			    			wp_tiny_mce();

			    		}

					}

				}
				
			}/* header() */

			/* =========================================================================== */

			/**
			 * Actually go ahead and say 'yup' I want to use this potion in the overlay
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.2
			 * @param (array) $class - Which potion?
			 * @return None
			 */
			
			public function use_potion( $class )
			{

				self::$molecule_classes[] = $class;

			}/* use_potion() */


			/* =========================================================================== */


			/**
			 * Go ahead and actually get the data from our potion. Adds it to the $molecule_data array
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.2
			 * @param (string) $slug - The ID of this potion
			 * @param (string) $title - Title for this potion
			 * @return None
			 */
			
			public function use_potion_data( $slug, $title )
			{

				$slug = Chemistry::uglify( $slug );
				self::$molecule_data[] = array( 'slug' => $slug, 'title' => $title );

			}/* use_potion_data() */


			/* =========================================================================== */


			/**
			 * If we want to disable a field, a fieldset or the entire overlay, we can call this
			 * method to see which fields are disabled. Makes them uneditible by the user.
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.3
			 * @param None
			 * @return The disabled fields array
			 */
			
			public function get_disabled_fields()
			{

				return apply_filters( 'chemistry_molecule_disabled_fields', self::$disabled_fields );

			}/* get_disabled_fields() */


			/* =========================================================================== */


			/**
			 * If we want to disbale a field, we call this method. It checks if it isn't already
			 * disbaled, if not it registers it as disabled in the $disabled_fields array
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.3
			 * @param (string) $name - The name of the field to disable
			 * @return None
			 */
			
			public function disable_field( $name )
			{

				if( ! in_array( $name, self::$disabled_fields ) )
					self::$disabled_fields[] = $name;

			}/* disable_field() */


			/* =========================================================================== */


			/**
			 * Helper method to return a list of all of our registered potions
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.3
			 * @param None
			 * @return Array of potions
			 */
			
			public function get_molecules()
			{

				return self::$molecules;

			}/* get_molecules() */


			/* =========================================================================== */


			/**
			 * Get the class name of a potion based on a slug
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (string) $slug - Which potion to get
			 * @return (string) the class name
			 */
			
			public function get_widget_class( $slug )
			{

				$slug = Chemistry::uglify( $slug );

				//You got it!
				if( isset( self::$molecule_slugs[$slug] ) )
					return self::$molecule_slugs[$slug];

				//Otherwise empty string
				return '';

			}/* get_widget_class() */


			/* =========================================================================== */

			/**
			 * Abstracted method to register a 'laboratory' for chemistry. Removed this
			 * from a single instance so that - in the future - we can add our editor elsewhere.
			 * Simply checks if the location doesn't already exists, if it doesn't, registers it.
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.2
			 * @param (string) $slug - ID of location to register
			 * @param (string) $name - user-readable name of area
			 * @return (bool)
			 */
			
			public function register_laboratory( $slug, $name )
			{

				$slug = Chemistry::uglify( $slug );

				if( !isset( self::$laboratories[$slug] ) )
				{

					self::$laboratories[$slug] = $name;

					return true;

				}

				return false;

			}/* register_laboratory() */

			/* =========================================================================== */

			/**
			 * For when we're adding metaboxes, we need a list of places to add Chemistry
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param None
			 * @return List of laboratories
			 */
			
			public function get_laboratories()
			{

				return self::$laboratories;

			}/* get_laboratories() */


			/* =========================================================================== */


			/**
			 * Sometimes we have vast arrays of arrays of arrays of arr.. you get the picture.
			 * We need a method to actually make some sense of this nonsense and go ahead
			 * and produce something useful. This method goes ahead and produces a usable array
			 * of potions and their ids for our output. Started (and then extended) from
			 * http://davidwalsh.name/flatten-nested-arrays-php
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (array) $molecule - The data of what we're flattening
			 * @param (string) $prefix - Prefix to use for the array key
			 * @param (bool) $parse - Are we to actually parse the data wutg a regex?
			 * @return (array) $output - The flattened data
			 */
			
			public function flatten( $molecule, $prefix = '', $parse = true )
			{

				//Must always been an array. Start clean
				$output = array();

				//*must* be an array of data
				if( is_array( $molecule ) )
				{

					//Begin the flattening process by iterating over each value
					foreach( $molecule as $key => $value )
					{

						$entry = ( $prefix ? $prefix . '][' : '' ) . $key;

						if( is_array( $value ) )
							$output = array_merge( $output, self::flatten( $value, $entry, false ) );
						else
							$output['[' . $entry . ']'] = $value;

					}

					//If we're to parse this data, run through here
					if( $parse )
					{

						$molecule = $output;
						$output = array();

						foreach( $molecule as $k => $v )
						{

							preg_match_all( '/\[( .*? )\]\[( .*? )\]\[( .*? )\]\[( .*? )\]\[( .*? )\]/is', $k, $matches );

							if( isset( $matches[4][0] ) && !empty( $matches[4][0] ) && isset( $matches[5][0] ) && !empty( $matches[5][0] ) )
							{

								$id = $matches[4][0];
								$field = $matches[5][0];

								if( !isset( $output[$id] ) )
									$output[$id] = array();

								$output[$id][$field] = $v;

							}

						}

					}

				}

				//A faff
				return $output;

			}/* flatten() */


			/* =========================================================================== */


			/**
			 * This is the main method which actully makes heads or tails of the data stored in
			 * WP's post meta. We store everything in a series of rows. Each row contains columns
			 * of data. Each row has an ID and may contain arrays of modules
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.1
			 * @param (array) $molecule_molecules - Array of our molecules and their laboratories
			 * @param (string) $location - Where is this?
			 * @param (bool) $admin - If we're in the admin, we parse the admin form, otherwise the markup
			 * @param (array) $data - The data can be passed as an array directly
			 * @return (array) $molecule_molecules_output - The output. Parsed.
			 */
			
			public function parse( $molecule_molecules, $location, $admin = false, $data = array() )
			{

				//Start clean
				$molecule_molecules_output = '';

				//Set up some defaults
				$row_begin = false;
				$first_row_data = array();
				$row_id = '___CHEMISTRYID___';
				$after_petri_dish = array();

				//we actually have something to parse, right?
				if( isset( $molecule_molecules[$location] ) && is_array( $molecule_molecules[$location] ) )
				{

					//Go through each location and split by rows
					foreach( $molecule_molecules[$location] as $row_name => $row_data )
					{

						//If this isn't the actual row container itself
						if( $row_name !== '___CHEMISTRYROW___' )
						{

							//let's now split each row by the potions in columns
							$columns = array( array(), array(), array(), array() );

							foreach( $row_data as $column_name => $column_data )
							{

								//We're rocking a column here
								if( $column_name === '___CHEMISTRYCOL___' )
								{

									//Yay for arrays
									foreach( $column_data as $widget_id => $molecule_data )
									{

										//If this isn't a core potion
										if( !isset( $molecule_data['__CORE__'] ) )
										{

											if( $widget_id != '___CHEMISTRYID___' )
											{

												$class = self::get_widget_class( $molecule_data['___MODULE___'] );

												//Let's run through and set our details for this potion
												if( !empty( $class ) && class_exists( $class ) )
												{

													$object = new $class();
													$object->set_id( $widget_id );
													$object->set_laboratory( $location );
													$object->set_row( $row_name );
													$object->set_column( $column_name );

													//dashboard
													if( $admin )
													{

														$molecule_molecules_output .= $object->admin_form( array_merge( $molecule_data, isset( $data[$widget_id] ) ? $data[$widget_id] : array(), array( '___CHEMISTRYID___' => $widget_id ) ) );

													}
													else
													{

														//This is for the front-end
														$molecule_data = array_merge( $molecule_data, isset( $data[$widget_id] ) ? $data[$widget_id] : array(), array( '___CHEMISTRYID___' => $widget_id ) );
														$output = '';

														if( $object->is_after() )
														{

															$after_petri_dish[] = array( 'widget' => $object, 'molecule_data' => $molecule_data, 'widget_id' => $widget_id );

														}
														else
														{

															$molecule_molecules_output = $object->content_filter( $molecule_data, $molecule_molecules_output );

															$output = $object->widget( $molecule_data );
															$output = apply_filters( 'chemistry_molecule_widget', $output, $widget_id, $molecule_data, $object->get_slug() );
															$output = apply_filters( 'chemistry_molecule_' . $object->get_slug() . '_widget', $output, $widget_id, $molecule_data, $object->get_slug() );

															$molecule_molecules_output .= $output;

														}

													}

													$row_begin = false;

												}

											}

										}
										else
										{

											//Indiv. potion
											$row_id = $widget_id;
											$row_begin = true;
											$first_row_data = array_merge( $molecule_data, array( '___CHEMISTRYID___' => $widget_id ) );

										}

									}

								}
								else
								{

									//Compare to defaults
									foreach( $column_data as $widget_id => $molecule_data )
										$columns[$column_name][] = array_merge( $molecule_data, array( '___CHEMISTRYID___' => $widget_id ) );

								}

							}

							//OK so now we know if this item is on its own or not. If it's in a row then we need to 
							//handle it separately
							if( $row_begin )
							{

								$row_columns = array();

								foreach( $columns as $column_index => $column_data )
								{

									foreach( $column_data as $widget )
									{

										$class = self::get_widget_class( $widget['___MODULE___'] );

										if( !empty( $class ) && class_exists( $class ) )
										{

											$object = new $class();
											$object->set_id( $widget['___CHEMISTRYID___'] );
											$object->set_laboratory( $location );
											$object->set_row( $row_name );
											$object->set_column( $column_index );

											if( !isset( $row_columns['col-' . ( $column_index + 1 )] ) )
												$row_columns['col-' . ( $column_index + 1 )] = '';

											if( $admin )
											{

												$row_columns['col-' . ( $column_index + 1 )] .= $object->admin_form( array_merge( $widget, isset( $data[$widget['___CHEMISTRYID___']] ) ? $data[$widget['___CHEMISTRYID___']] : array() ) );

											}
											else
											{

												$widget_id = $widget['___CHEMISTRYID___'];
												$molecule_data = array_merge( $widget, isset( $data[$widget['___CHEMISTRYID___']] ) ? $data[$widget['___CHEMISTRYID___']] : array() );
												$output = '';

												if( $object->is_after() )
												{

													$after_petri_dish[] = array( 'widget' => $object, 'molecule_data' => $molecule_data, 'widget_id' => $widget_id );


												}
												else
												{

													$molecule_molecules_output = $object->content_filter( $molecule_data, $molecule_molecules_output );

													$output = $object->widget( $molecule_data );

													$output = apply_filters( 'chemistry_molecule_widget', $output, $widget_id, $molecule_data, $object->get_slug() );
													$output = apply_filters( 'chemistry_molecule_' . $object->get_slug() . '_widget', $output, $widget_id, $molecule_data, $object->get_slug() );

													$row_columns['col-' . ( $column_index + 1 )] .= $output;

												}

											}

										}

									}

								}

								//What's our classname for this potion
								$class = self::get_widget_class( $first_row_data['___MODULE___'] );

								//Let's instantiate
								if( !empty( $class ) && class_exists( $class ) )
								{

									$object = new $class();

									$object->set_id( $row_id );
									$object->set_laboratory( $location );
									$object->set_row( $row_name );

									//Dashboard?
									if( $admin )
									{

										$molecule_molecules_output .= $object->admin_form( array_merge( array( '___CHEMISTRYID___' => $row_id ), $row_columns ) );

									}
									else
									{

										$output = $object->widget( array_merge( array( '___CHEMISTRYID___' => $row_id ), $row_columns ) );
										$output = apply_filters( 'chemistry_molecule_widget', $output, $row_id, array(), $object->get_slug() );
										$output = apply_filters( 'chemistry_molecule_' . $object->get_slug() . '_widget', $output, $row_id, array(), $object->get_slug() );

										//Aaaaand our output
										$molecule_molecules_output .= $output;

									}

								}

							}

						}

					}

				}

				//Front-end stuff?
				if( !$admin )
				{

					foreach( $after_petri_dish as $after )
					{

						$molecule_molecules_output = $after['widget']->content_filter( $after['molecule_data'], $molecule_molecules_output );

						$output = $after['widget']->widget( $after['molecule_data'] );

						$output = apply_filters( 'chemistry_molecule_widget', $output, $after['widget_id'], $after['molecule_data'], $after['widget']->get_slug() );
						$output = apply_filters( 'chemistry_molecule_' . $after['widget']->get_slug() . '_widget', $output, $after['widget_id'], $after['molecule_data'], $after['widget']->get_slug() );

					}

					//I should just drop qtrans. It's...annoying
					if( function_exists( 'qtrans_init' ) )
						return __( $molecule_molecules_output , 'chemistry' );
					else
						return $molecule_molecules_output;

				}

				return $molecule_molecules_output;

			}/* parse() */

			/* =========================================================================== */


			/**
			 * get our data from a location and parse it then spit it out
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.1
			 * @param (string) $location - Where are we getting data from
			 * @return The data
			 */
			
			public function get( $location )
			{

				global $post;

				if( self::$petri_dish != null )
				{

					$petri_dish = Chemistry::get_or_set_meta( 'molecule_data', true, $post->ID );

					echo self::parse( $petri_dish, $location, false );

				}

			}/* get() */

			/* =========================================================================== */

			
			/**
			 * Should we need to use genuine custom loops with our chemistry data, we'll need
			 * to use a custom version of WordPress's in-built get_the_content() function. This
			 * replacement actually chucks out the data correctly.
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.35
			 * @param (int) ID - Which post?
			 * @param (bool) $skip_the_content - Do we want the WP content?
			 * @return the parse()'d data
			 */
			
			public function get_the_content( $id = null, $skip_the_content = false )
			{

				//Get the post object
				global $post;

				//Have we run the header? If not, do it
				if( did_action( 'chemistry_molecule_header' ) == 0 )
					do_action( 'chemistry_molecule_header' );

				//Get our chemistry metadata
				$data = Chemistry::get_or_set_meta( 'molecule_data', true, ( $id !== null ? $id : $post->ID ) );

				//Make it usable
				$data_flatten = self::flatten( $data, '', false );
				$prepend_content = true;

				//Make sure we're getting the right data
				foreach( $data_flatten as $k => $v )
				{
					

					if( $v == 'post-content' && strpos( $k, '___MODULE___' ) !== false )
					{

						$prepend_content = false;
						break;

					}

				}

				//Awesome
				return ( ( $prepend_content && !$skip_the_content ) ? apply_filters( 'the_content', get_the_content() ) : '' ).self::parse( $data, 'main', false );

			}/* get_the_content */

			
			/* =========================================================================== */


			/**
			 * Let's chuck out our primary editor
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.1
			 * @param (string) $content - Which'n?
			 * @return Modified content (with markup) for the main chemistry editor
			 */
			
			public function molecule_tab( $content )
			{

				global $post, $post_type;

				//Which metabox?
				$molecule_metabox = Chemistry::admin_metabox_get( 'layout editor' );

				//If we're on the proper post editor, let's add our container
				if( !empty( $molecule_metabox ) && in_array( $post_type, $molecule_metabox['permissions'] ) )
					if( strpos( $content, 'editorcontainer' ) !== false || strpos( $content, 'wp-content-editor-container' ) !== false )
						echo '<div id="editor-molecule-tab" class="hide-if-no-js hide">'.chemistry_metabox_molecule::body() . '</div>';

				//Disco
				return $content;

			}/* molecule_tab() */


			/* =========================================================================== */


			/**
			 * Let's make sure our content runs through shortcodes without kablooey
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.2
			 * @param (string) @content
			 * @return Modified content
			 */
			
			public function molecule_content( $content )
			{

				global $post, $post_type;

				//Have we already initialised ourselves?
				if( !self::$are_we_setup )
					self::molecule_setup();

				//Have we got an edited post on our hands?
				if( $post )
				{

					if( !empty( $post->ID ) && !isset( self::$chemistry_data_holder[$post->ID] ) )
						if( isset( self::$chemistry_return_data[$post->ID] ) )
							$content .= do_shortcode( self::$chemistry_return_data[$post->ID] );
					else
						if( isset( self::$chemistry_return_data[$post->ID] ) )
							$content = do_shortcode( self::$chemistry_return_data[$post->ID] );

				}

				//Sorted
				return $content;

			}/* molecule_content() */


			/* =========================================================================== */


			/**
			 * Let's run through and make sure we're all set up on the correct screens
			 * Helper method run on template redirect to make sure we're rocking and rolling
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param None
			 * @return None
			 */
			
			public function molecule_setup()
			{

				global $post, $post_type;

				self::$are_we_setup = true;

				global $wp_query;

				//Let's just make sure we don't interfere where we're not wanted
				$tmp_post = $post;

				$posts = array();

				//Make sure we're not empty
				if( isset( $wp_query->posts ) )
					$posts = $wp_query->posts;
				else
					$posts[] = $post;

				//Run through each post
				foreach( $posts as $post )
				{

					if( $post )
					{

						//Have we not been through this already?
						if( !empty( $post->ID ) && !isset( self::$chemistry_return_data[$post->ID] ) )
						{

							$id = $post->ID;
							$data = array();

							//Get our chemsitry data
							$petri_dish = Chemistry::get_or_set_meta( 'molecule_data', true, $id );

							//Cool, parse it
							if( !empty( $petri_dish ) )
							{

								$molecule_content = self::parse( $petri_dish, 'main', false, $data );

								$petri_dish_flatten = self::flatten( $petri_dish, '', false );

								foreach( $petri_dish_flatten as $k => $v )
								{
									
									if( $v == 'post-content' && strpos( $k, '___MODULE___' ) !== false )
									{

										self::$chemistry_data_holder[$post->ID] = true;

										break;

									}

								}

								self::$chemistry_return_data[$post->ID] = $molecule_content;

								if( !empty( self::$chemistry_return_data[$post->ID] ) )
									if( !is_admin() && did_action( 'chemistry_molecule_header' ) == 0 )
										do_action( 'chemistry_molecule_header' );

							}

						}

					}

				}

				//Reset
				$post = $tmp_post;

			}/* molecule_setup() */


			/* =========================================================================== */


			/**
			 * Let's make sure our chemistry data is available when searching
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.5
			 * @param 
			 * @return 
			 */
			
			public function molecule_search( $whereforeartthou )
			{

				global $wp_query, $wpdb, $wp;

				//Just check we're not searching in the dashboard
				if( !is_admin() && $wp_query->is_search )
				{

					//Grrrr
					$query = str_replace( '/', '\/', $wp->query_vars['s'] );

					//Return positive results if the searcg term is in our metadata
					$whereforeartthou = preg_replace( "/( posts.post_title ( LIKE '%{$query}%' ) )/i", "$0 ) || ( $wpdb->postmeta.meta_key = 'chemistry_molecule_data' && $wpdb->postmeta.meta_value LIKE '%{$query}%'", $whereforeartthou );

				}

				//Bang!
				return $whereforeartthou;

			}/* molecule_search() */


			/* =========================================================================== */


			/**
			 * Make sure our extra search query is tacked onto the normal WP one
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.6
			 * @param (string) $join - the orig. query
			 * @return (string) $join - the modified query
			 */
			
			public function molecule_search_join( $join )
			{

				global $wp_query, $wpdb, $wp;

				//Add the search for our metadata
				if( !is_admin() && $wp_query->is_search )
					return $join .= " LEFT JOIN $wpdb->postmeta ON ( $wpdb->posts.ID = $wpdb->postmeta.post_id ) ";

				//And the dirt is gone!
				return $join;

			}/* molecule_search_join() */


			/* =========================================================================== */


			/**
			 * We need to make sure - sometimes - that our searches are MySQL distinct. Easy!
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param 
			 * @return 
			 */
			
			public function molecule_search_distinct( $distinct )
			{

				$distinct = 'DISTINCT';

				return $distinct;

			}/* molecule_search_distinct() */


			/* =========================================================================== */


			/**
			 * Timestamps can *sometimes* be stored as integers, we want them as a string
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.2
			 * @param (int) $data
			 * @return string
			 */
			
			public function unserialize( $data )
			{
				
				return Chemistry::unserialize( preg_replace( '!i:( [0-9]{13,20}? );!e', "'s:'.strlen( '$1' ) . ':\"$1\";'", $data ) );

			}/* unserialize() */


			/* =========================================================================== */


			/**
			 * SERIALIZED DATA CAN EAT ME. Basically, sometimes, our chemistry data can be
			 * cached and that means our output is old. Sort that out. (pass job to 
			 * update_meta_cache())
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param 
			 * @return 
			 */
			
			public function cached_metadata_unserialize_fix( $null, $object_id, $meta_key, $single )
			{

				$meta_type = 'post';

				if( $meta_key == 'chemistry_molecule_data' )
				{

					$meta_cache = wp_cache_get( $object_id, $meta_type.'_meta' );

					if( !$meta_cache )
					{

						$meta_cache = update_meta_cache( $meta_type, array( $object_id ) );
						$meta_cache = $meta_cache[$object_id];

					}

					if( isset( $meta_cache[$meta_key] ) )
					{

						if( $single )
							if( is_serialized( $meta_cache[$meta_key][0] ) && !maybe_unserialize( $meta_cache[$meta_key][0] ) )
								return array( self::unserialize( $meta_cache[$meta_key][0] ) );
						else
							foreach( $meta_cache[$meta_key] as $k => $v )
								if( is_serialized( $v ) && ! maybe_unserialize( $v ) )
									return array_map( array( 'chemistry_molecule', 'unserialize' ), $meta_cache[$meta_key] );

					}

				}

			}/* cached_metadata_unserialize_fix() */

		}/* class chemistry_molecule */

	}/* !class_exists( 'chemistry_molecule' ) */



	/* =================================================================================== */



	/**
	 * Load our class which is extended to make our potions
	 *
	 * @author Richard Tape
	 * @package Chemistry
	 * @since 0.7
	 */

	require_once locate_template( '/framework/chemistry/class.chemistry_molecule_widget.php' );



	/* =================================================================================== */
	

	
	/**
	 * Now that we've loaded let's instantiate our layout editor
	 *
	 * @author Richard Tape
	 * @package Chemistry
	 * @since 0.7
	 */

	require_once locate_template( '/framework/chemistry/modules/molecule-widget.php' );



	/* =================================================================================== */



	/**
	 * Add our methods to the apprpriate actions and filters to actually run our setup and front-end
	 * parsing
	 *
	 * @author Richard Tape
	 * @package Chemistry
	 * @since 0.4
	 */

	//Add our chemistry editor
	add_filter( 'the_editor', 				array( 'chemistry_molecule', 'molecule_tab' ), 100 );
	
	//Make sure we display our chemistry data properly
	add_filter( 'the_content', 				array( 'chemistry_molecule', 'molecule_content' ), 100 );
	
	//Make search work
	add_filter( 'posts_where_request', 		array( 'chemistry_molecule', 'molecule_search' ) );
	add_filter( 'posts_join_request', 		array( 'chemistry_molecule', 'molecule_search_join' ) );
	add_filter( 'posts_distinct_request',	array( 'chemistry_molecule', 'molecule_search_distinct' ) );
	
	//Unserialized data + cache = world of hell
	add_filter( 'get_post_metadata', 		array( 'chemistry_molecule', 'cached_metadata_unserialize_fix' ), 10, 4 );
	
	//Make sure we're all loaded
	add_action( 'template_redirect', 		array( 'chemistry_molecule', 'molecule_setup' ), 100 );
	add_action( 'admin_head', 				array( 'chemistry_molecule', 'header' ) );
	add_action( 'wp_head', 					array( 'chemistry_molecule', 'header' ), 100 );
	
	//Custom action to ensure we have our potions (correct ones) registered
	add_action( 'chemistry_setup', 			array( 'chemistry_molecule', 'chemistry_register_potions' ), 2 );
	


?>