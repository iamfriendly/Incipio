<?php

	/* ================================================================================ */

	/**
	 * Our main builder class which actually handles registering modules, shortcodes etc.
	 *
	 * @package FLAB
	 * @author iamfriendly
	 * @version 1.0
	 * @since 1.0
	 */

	 /* ================================================================================ */

	if( !class_exists( 'flab_builder' ) )
	{
	
		/**
		 * Our main admin layout builder which sets up the widgets and shortcodes
		 *
		 * @package FLAB
		 * @author iamfriendly
		 * @version 1.0
		 * @since 1.0
		 */
	
		class flab_builder extends flab_plugin
		{
		
			/* ============================================================================ */
		
			/**
			 * Several vars we can use as dumps later
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */

			/* ============================================================================ */
		
			/**
			 * The actual widget/module classes that we are going to register
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */

			protected static $widget_classes = array();

			/**
			 * The data for the widgets/modules (saved options)
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */

			protected static $widget_data = array();

			/**
			 * The slugs of the modules - we can use this elsewhere to test if we want them displayed or not
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */

			protected static $widget_slugs = array();

			/**
			 * Overall array for our widgets which encompasses all of our data
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */

			protected static $widgets = array();

			/**
			 * For our metaboxes/widgets/modules we may have fields which are displayed that are disabled (i.e. uneditable)
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			protected static $disabled_fields = array();

			/**
			 * If we're registering any menu/widget locations for our modules, or if we're listing exactly where they are in post content
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */

			protected static $locations = array();

			/**
			 * Used when we're passing the details of the widgets to/from the post editor. Basically allows us to grab the data
			 * and store it in the postmeta
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */

			protected static $buffer = NULL;

			/**
			 * Used when we actually pass post content through flab
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			protected static $content_filter_registry = array();

			/**
			 * Well, does exactly what it says on the tin... contains the the_content output
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */

			protected static $content_output = array();
			
			/* ============================================================================ */
	
			/**
			 * Initialise the locations
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function init()
			{
				self::register_location( 'main', flab::langr( 'Main content' ) );
			}/* init() */
			
			/* ============================================================================ */
	
			/**
			 * Initialise the modules that are displayed in the flab overlay. There may be several we don't want to
			 * show, in which case add their ID to $widgets_not_to_use_in_flab
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */

			public static function widgets_init()
			{
			
				if( is_admin() )
				{
					wp_enqueue_script( 'tiny_mce' );
					wp_enqueue_style( 'editor-buttons' );
				}
	
				$count = count( self::$widget_classes );
				//wp_die( print_r( self::$widget_classes, true ) );
	
				for( $i = 0; $i < $count; $i++ )
				{
					
					$class = self::$widget_classes[$i];
					
					$widgets_not_to_use_in_flab = array( 
								
						'friendly_page_post_content_as_row_builder_widget',
						'friendly_nivo_slider_builder_widget',
						'friendly_twitter_widget_builder_widget',
						'friendly_what_we_do_widget_builder_widget',
						'friendly_custom_menu_builder_widget',
						'friendly_portfolio_slider_builder_widget',
						'friendly_recent_posts_with_thumbnails_widget_builder_widget',
						'friendly_flex_slider_builder_widget',
						'friendly_latest_posts_builder_widget',
						'friendly_client_logos_builder_widget',
						'friendly_client_testimonials_builder_widget',
						'friendly_earth_slider_builder_widget',
						'Tribe_Image_Widget_builder_widget',
						'friendly_jmpress_slider_builder_widget',
						'WP_Widget_Text_builder_widget',
						'WP_Widget_Price_Range_builder_widget',
						'flab_content_as_row_widget'
						
					);
	
					if( !in_array( $class, $widgets_not_to_use_in_flab ) )
					{
					
						if( class_exists( $class ) )
						{
							
							$object = new $class();
		
							if( !isset( self::$widget_slugs[$object->get_slug()] ) )
							{
							
								self::$widgets[] = $object;
								self::$widget_slugs[$object->get_slug()] = $class;
								
							}
							else
							{
								unset( $obejct );
							}
							
						}
					
					}
					
				}
				
			}/* widgets_init() */
			
			/* ============================================================================ */
	
			/**
			 * Horrible, horrific hack to initialise the widgets built with FLAB. Uses eval. I can't think of a "proper" way to do
			 * this because it needs to be done during the WP bootstrap process. Nothing nefarious here, but using eval
			 * is never good. WordPress itself does actually use eval() on occassion (in both js and PHP) so I don't feel *that*
			 * bad about it. Still feel a bit dirty.
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function builder_sidebar_widgets_init()
			{
			
				global $wp_widget_factory;
	
				foreach( $wp_widget_factory->widgets as $flab_mod_classobject => $flab_widg_object )
				{
				
					if( substr( $flab_mod_classobject, 0, strlen( flab::config( 'prefix' ) ) ) != flab::config( 'prefix' ) )
					{
					
						if( !isset( $flab_widg_object->widget_options['description'] ) )
							$flab_widg_object->widget_options['description'] = "This space intentionally left blank";

						$to_eval = '';
						$to_eval .= 'class '.$flab_mod_classobject.'_builder_widget extends flab_wp_widget';
						$to_eval .= '{';
						$to_eval .= '	public function __construct()';
						$to_eval .= '	{';
						$to_eval .= '		parent::__construct( \''.flab::slug( 'wp_widget_'.$flab_widg_object->name ).'\', \' '.addslashes( $flab_widg_object->name ).'\' );';
						$to_eval .= '		$this->label = \''.addslashes( $flab_widg_object->widget_options['description'] ).'\';';
						$to_eval .= '		$this->wp_class = \''.$flab_mod_classobject.'\';';
						$to_eval .= '		$this->wp_widget = new $this->wp_class;';
						$to_eval .= '	}';
						$to_eval .= '}';
	
						eval( $to_eval );
						
						flab_builder::register_widget( $flab_mod_classobject.'_builder_widget' );
						
					}
					
				}
				
			}/* builder_sidebar_widgets_init() */
			
			/* ============================================================================ */
	
			/**
			 * Register the widgets that we're going to use in  FLAB. Again needs to use eval. See note above
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function sidebar_builder_widgets_init()
			{
			
				global $wp_widget_factory;

				$flab_widgets = array(
				
					'plain-text',
					'rich-text',
					'heading',
					'image',
					'gallery',
					'blockquote',
					'testimonials',
					'message',
					'list',
					'tabs',
					'accordion',
					'divider',
					'html',
					'button',
					'table',
					'twitter-feed',
					'googlemap'
					
				);
	
				foreach( $flab_widgets as $flab_widget )
				{
					
					$class = flab_builder::get_widget_class( $flab_widget );
					$title = flab_builder::get_widget_title( $flab_widget );
	
					$to_eval = '';
					$to_eval .= 'class '.$class.'_sidebar_widget extends flab_builder_sidebar_widget';
					$to_eval .= '{';
					$to_eval .= '	public function __construct()';
					$to_eval .= '	{';
					$to_eval .= '		parent::__construct( false, \'&nbsp;\'.flab::config( \'name\' ).\' '.addslashes( $title ).'\', array() );';
	
					$to_eval .= '		$this->builder_class = \''.$class.'\';';
					$to_eval .= '		$this->builder_widget = new $this->builder_class;';
					$to_eval .= '	}';
					$to_eval .= '}';
	
					eval( $to_eval );
	
					register_widget( $class.'_sidebar_widget' );
					$wp_widget_factory->widgets[$class.'_sidebar_widget']->_register();
					
				}
				
			}/* sidebar_builder_widgets_init() */
			
			/* ============================================================================ */
	
			/**
			 * We need to add the thickbox and wysiwgy editor related bits and bobs
			 * 
			 * Compatibility for 3.4 w.r.t "add_thickbox"
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.1
			 * @since 1.0
			 */
	
			public static function header()
			{
	    		
	    		if( function_exists( 'add_thickbox' ) ){ add_thickbox(); }
	    		if( function_exists( 'wp_tiny_mce' ) )
	    		{

	    			if( defined( 'WP_DEBUG' ) && WP_DEBUG )
	    			{
	    				add_filter( 'deprecated_function_trigger_error', '__return_false' );
	    			}

	    			wp_tiny_mce();

	    		}
		        
			}/* header() */
			
			/* ============================================================================ */
	
			/**
			 * Class function to see the classes for the widget registration
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function register_widget( $class )
			{
				self::$widget_classes[] = $class;
			}/* register_widget() */
			
			/* ============================================================================ */
	
			/**
			 * Register the default WP Widgets
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function register_wp_widget( $class, $title, $label )
			{
				self::$wp_widget_classes[] = $class;
			}/* register_wp_widget() */
			
			/* ============================================================================ */
	
			/**
			 * Give us the slug and title for the widgets, makes things more extensible
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function register_widget_data( $slug, $title )
			{
				
				$slug = flab::slug( $slug );
	
				self::$widget_data[] = array( 'slug' => $slug, 'title' => $title );
				
			}/* register_widget_data() */
			
			/* ============================================================================ */
	
			/**
			 * Get the fields which we have disabled above so we can check against them
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */

			public static function get_disabled_fields()
			{
				return self::$disabled_fields;
			}/* get_disabled_fields() */
			
			/* ============================================================================ */
	
			/**
			 * If we have any disabled fields for the widget, we'll grab them here
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function disable_field( $name )
			{
				
				if( !in_array( $name, self::$disabled_fields ) )
				{
					self::$disabled_fields[] = $name;
				}
				
			}/* disable_field() */
			
			/* ============================================================================ */
	
			/**
			 * get_widgets, well, it gets the widgets
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function get_widgets()
			{
				return self::$widgets;
			}/* get_widgets() */
			
			/* ============================================================================ */
	
			/**
			 * Get the name of the class that has been used to register the widget
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function get_widget_class( $slug )
			{
			
				$slug = flab::slug( $slug );
	
				if( isset( self::$widget_slugs[$slug] ) )
				{
					return self::$widget_slugs[$slug];
				}
	
				return flab::config( 'prefix' ).'builder_widget';
				
			}/* get_widget_class() */
			
			/* ============================================================================ */
	
			/**
			 * Allows us to easily grab the widget's title
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function get_widget_title( $slug )
			{
				
				$slug = flab::slug( $slug );
	
				foreach( self::$widget_data as $widget_data )
				{
					
					if( $widget_data['slug'] == $slug )
					{
						return $widget_data['title'];
					}
					
				}
	
				return flab::langr( 'Widget' );
				
			}/* get_widget_title() */
			
			/* ============================================================================ */
	
			/**
			 * Widgets need a place to live. We need to register those locations,
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function register_location( $slug, $name )
			{
			
				$slug = flab::slug( $slug );
	
				if( !isset( self::$locations[$slug] ) )
				{
					
					self::$locations[$slug] = $name;
	
					return true;
					
				}
	
				return false;
				
			}/* register_location() */
			
			/* ============================================================================ */
	
			/**
			 * Helper function to get the locayions we've registered
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function get_locations()
			{
				return self::$locations;
			}/* get_locations() */
			
			/* ============================================================================ */
	
			/**
			 * WP Widgets have an extract function which allows us to get the saved values for them. This is a version of it.
			 *
			 * @package
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function extract_widgets( $arr, $skip_fields = array() )
			{
			
				$output = array();
	
				foreach( $arr as $key => $value )
				{
				
					if( isset( $value['__SLUG__'] ) )
					{
					
						if( !isset( $value['__C||E__'] ) )
						{
						
							if( !empty( $skip_fields ) )
							{
							
								foreach( $skip_fields as $field )
								{
								
									if( isset( $output[$field] ) )
									{
										unset( $output[$field] );
									}
									
								}
								
							}
	
							$output[$key] = $value;
							
						}
						
					}
					else
					{
						$output = array_merge( $output, self::extract_widgets( $value, $skip_fields ) );
					}
					
				}
	
				return $output;
				
			}/* extract_widgets() */
			
			/* ============================================================================ */
	
			/**
			 * Pretty much the opposite of the above
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function flatten( $builder, $prefix = '', $parse = true )
			{
			
				$output = array();
	
				foreach( $builder as $key => $value )
				{
				
					$entry = ( $prefix ? $prefix.'][' : '' ).$key;
	
					if( is_array( $value ) )
					{
						$output = array_merge( $output, self::flatten( $value, $entry, false ) );
					}
					else
					{
						$output['['.$entry.']'] = $value;
					}
					
				}
	
				if( $parse )
				{
				
					$builder = $output;
					$output = array();
	
					foreach( $builder as $k => $v )
					{
					
						preg_match_all( '/\[( .*? )\]\[( .*? )\]\[( .*? )\]\[( .*? )\]\[( .*? )\]/is', $k, $matches );
	
						if( isset( $matches[4][0] ) && !empty( $matches[4][0] ) && isset( $matches[5][0] ) && !empty( $matches[5][0] ) )
						{
						
							$id = $matches[4][0];
							$field = $matches[5][0];
	
							if( !isset( $output[$id] ) )
							{
								$output[$id] = array();
							}
	
							$output[$id][$field] = $v;
						}
						
					}
					
				}
	
				return $output;
				
			}/* flatten() */
			
			/* ============================================================================ */
	
			/**
			 * parse pretty much everything to do with the widgets
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function parse( $builder_widgets, $location, $admin = false, $data = array() )
			{
			
				$builder_widgets_output = '';
	
				$row_begin = false;
				$first_row_data = array();
				$row_id = '__ID__';
	
				if( isset( $builder_widgets[$location] ) )
				{
				
					foreach( $builder_widgets[$location] as $row_name => $row_data )
					{
					
						if( $row_name !== '__ROW__' )
						{
						
							$columns = array( array(), array(), array(), array() );
	
							foreach( $row_data as $column_name => $column_data )
							{
							
								if( $column_name === '__COLUMN__' )
								{
								
									foreach( $column_data as $widget_id => $widget_data )
									{
									
										if( !isset( $widget_data['__C||E__'] ) )
										{
										
											if( $widget_id != '__ID__' )
											{
											
												$class = self::get_widget_class( $widget_data['__SLUG__'] );
	
												if( class_exists( $class ) )
												{
												
													$object = new $class();
													$object->set_id( $widget_id );
													$object->set_location( $location );
													$object->set_row( $row_name );
													$object->set_column( $column_name );
	
													if( $admin )
													{
														$builder_widgets_output .= $object->admin_form( array_merge( $widget_data, isset( $data[$widget_id] ) ? $data[$widget_id] : array(), array( '__ID__' => $widget_id ) ) );
													}
													else
													{
														$builder_widgets_output .= $object->widget( array_merge( $widget_data, isset( $data[$widget_id] ) ? $data[$widget_id] : array(), array( '__ID__' => $widget_id ) ) );
													}
	
													$row_begin = false;
													
												}
												
											}
											
										}
										else
										{
											
											$row_id = $widget_id;
											$row_begin = true;
											$first_row_data = array_merge( $widget_data, array( '__ID__' => $widget_id ) );
											
										}
										
									}
									
								}
								else
								{
								
									foreach( $column_data as $widget_id => $widget_data )
									{
										$columns[$column_name][] = array_merge( $widget_data, array( '__ID__' => $widget_id ) );
									}
									
								}
								
							}
	
							if( $row_begin )
							{
							
								$row_columns = array();
	
								foreach( $columns as $column_index => $column_data )
								{
								
									foreach( $column_data as $widget )
									{
									
										$class = self::get_widget_class( $widget['__SLUG__'] );
	
										if( class_exists( $class ) )
										{
										
											$object = new $class();
											$object->set_id( $widget['__ID__'] );
											$object->set_location( $location );
											$object->set_row( $row_name );
											$object->set_column( $column_index );
	
											if( !isset( $row_columns['col-'.( $column_index + 1 )] ) )
											{
												$row_columns['col-'.( $column_index + 1 )] = '';
											}
	
											if( $admin )
											{
												$row_columns['col-'.( $column_index + 1 )] .= $object->admin_form( array_merge( $widget, isset( $data[$widget['__ID__']] ) ? $data[$widget['__ID__']] : array() ) );
											}
											else
											{
												$row_columns['col-'.( $column_index + 1 )] .= $object->widget( array_merge( $widget, isset( $data[$widget['__ID__']] ) ? $data[$widget['__ID__']] : array() ) );
											}
											
										}
										
									}
									
								}
	
								$class = self::get_widget_class( $first_row_data['__SLUG__'] );
	
								if( class_exists( $class ) )
								{
								
									$object = new $class();
	
									$object->set_id( $row_id );
									$object->set_location( $location );
									$object->set_row( $row_name );
	
									if( $admin )
									{
										$builder_widgets_output .= $object->admin_form( array_merge( array( '__ID__' => $row_id ), $row_columns ) );
									}
									else
									{
										$builder_widgets_output .= $object->widget( array_merge( array( '__ID__' => $row_id ), $row_columns ) );
									}
									
								}
								
							}
							
						}
						
					}
					
				}
	
				if( !$admin )
				{
					
					if( function_exists( 'qtrans_init' ) )
					{
						return flab::langr( $builder_widgets_output );
					}
					else
					{
						return $builder_widgets_output;
					}
					
				}
	
				return $builder_widgets_output;
				
			}/* parse() */
			
			/* ============================================================================ */
	
			/**
			 * Get the metadata and parse
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function get( $location )
			{
			
				global $post;
	
				if( self::$buffer != NULL )
				{
				
					$buffer = flab::meta( 'builder_data', true, $post->ID );
					$data = array();
	
					echo self::parse( $buffer, $location, false );
					
				}
				
			}/* get() */
			
			/* ============================================================================ */
	
			/**
			 * Output the actual FLAB editor body in the post editor
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function editor_tab( $content )
			{
				
				global $post;
				global $post_type;
		
				if( strpos( $content, 'editorcontainer' ) !== false || strpos( $content, 'wp-content-editor-container' ) !== false )
				{
					echo '<div id="editor-builder-tab" class="hide-if-no-js hide">' . flab_metabox_builder::body() . '</div>';
				}
			
				return $content;
				
			}/* editor_tab() */
			
			/* ============================================================================ */
	
			/**
			 * Return the content based on the flab layout widgets section
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function editor_content( $content )
			{
			
				global $post;
				global $post_type;
	
				if( $post && $post->post_type != 'section' )
				{
				
					if( !empty( $post->ID ) && !isset( self::$content_filter_registry[$post->ID] ) )
					{
					
						if( isset( self::$content_output[$post->ID] ) )
						{
							$content .= _n.self::$content_output[$post->ID];
						}
						
					}
					else
					{

						if( isset( self::$content_output[$post->ID] ) )
						{
							$content = _n.self::$content_output[$post->ID];
						}
						
					}
					
				}
	
				return $content;
				
			}/* editor_content() */
	
			/* ============================================================================ */

			/**
			 * Setup the actual builder, mainly the metadata
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */

			public static function builder_setup()
			{
			
				global $post;
				global $post_type;
	
				if( $post && $post->post_type != 'section' && $post->post_type != 'portfolio' )
				{
				
					if( !empty( $post->ID ) && !isset( self::$content_output[$post->ID] ) )
					{
	
						$id = $post->ID;
						$data = array();
	
						if( $post->post_type == 'project' && $post->post_parent > 0 )
						{
							
							$id = $post->post_parent;
	
							$data = flab::meta( 'builder_widget_post_data', true, $post->ID );
							
						}
	
						$buffer = flab::meta( 'builder_data', true, $id );
	
						if( !empty( $buffer ) )
						{
						
							$builder_content = self::parse( $buffer, 'main', false, $data );
	
							$buffer_flatten = self::flatten( $buffer, '', false );
	
							foreach( $buffer_flatten as $k => $v )
							{
							
								if( $v == 'post-content' && strpos( $k, '__SLUG__' ) !== false )
								{
								
									self::$content_filter_registry[$post->ID] = true;
	
									break;
									
								}
								
							}
	
							self::$content_output[$post->ID] = $builder_content;
	
							if( !empty( self::$content_output[$post->ID] ) )
							{
							
								if( !is_admin() )
								{
									do_action( 'flab_builder_header' );
								}
								
							}
							
						}
						
					}
					
				}
				
			}/* builder_setup() */
			
			/* ============================================================================ */
	
			/**
			 * Enable the WP search to see the post meta from FLAB
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function builder_search( $where )
			{
			
				global $wp_query;
				global $wpdb;
				global $wp;
	
				if( !is_admin() && $wp_query->is_search )
				{
					$where = preg_replace( "/( posts.post_title ( LIKE '%{$wp->query_vars['s']}%' ) )/i", "$0 ) || ( $wpdb->postmeta.meta_key = 'flab_builder_data' && $wpdb->postmeta.meta_value LIKE '%{$wp->query_vars['s']}%'", $where );
				}
	
				return $where;
				
			}/* builder_search() */
			
			/* ============================================================================ */
	
			/**
			 * Add our postmeta to the search results
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function builder_search_join( $join )
			{
				
				global $wp_query;
				global $wpdb;
				global $wp;
	
				if( !is_admin() && $wp_query->is_search )
				{
					return $join .= " LEFT JOIN $wpdb->postmeta ON ( $wpdb->posts.ID = $wpdb->postmeta.post_id ) ";
				}
	
				return $join;
				
			}/* builder_search_join() */
			
			/* ============================================================================ */
			
		}/* class flab_builder() */
		
	}
	
	if( !class_exists( 'flab_builder_widget' ) )
	{
	
		class flab_builder_widget
		{
		
			/**
			 * All of the vars we'll need for the metadata for the widgets
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
		
			protected $id;
			protected $slug;
			protected $title;
			protected $location;
			protected $row;
			protected $column;
			protected $core;
			protected $label;
			protected $excerpt;
			protected $visible;
			protected $data;
			
			/* ============================================================================ */
	
			/**
			 * And it begins. It aint pretty but it's how we keep track of the 'rows' and what is in each one.
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public function __construct( $slug = "", $title = "", $id = NULL, $location = NULL, $row = NULL, $column = NULL )
			{
			
				$this->core = false;
				$this->slug = flab::slug( $slug );
				$this->title = $title;
	
				$this->id = $id;
				$this->location = $location;
				$this->row = $row;
				$this->column = $column;
				$this->label = '';
				$this->excerpt = '';
				$this->visible = true;
	
				if( $this->id == NULL )
				{
					$this->id = '__ID__';
				}
	
				if( $this->location == NULL )
				{
					$this->location = '__LOCATION__';
				}
	
				if( $this->row == NULL )
				{
					$this->row = '__ROW__';
				}
	
				if( $this->column == NULL )
				{
					$this->column = '__COLUMN__';
				}
	
				flab_builder::register_widget_data( $slug, $title );
				
			}/* __construct() */
			
			/* ============================================================================ */
	
			/**
			 * Is this a core widget?
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public function is_core()
			{
				return $this->core;
			}/* is_core() */
			
			/* ============================================================================ */
	
			/**
			 * Return our field names
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public function get_field_name( $name )
			{
				return flab::config( 'prefix' ).'builder_widget['.$this->location.']['.$this->row.']['.$this->column.']['.$this->id.']['.$name.']';
			}/* get_field_name() */
			
			/* ============================================================================ */
	
			/**
			 * If this element is uneditable, return disabled
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public function get_field_atts( $name )
			{
				
				$disabled_fields = flab_builder::get_disabled_fields();
	
				if( in_array( $name, $disabled_fields ) )
				{
					return ' disabled="disabled"';
				}
	
				return '';
				
			}/* get_field_atts() */
			
			/**
			 * A set of helper functions allowing us to grab, set, get etc
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
			
			/* ============================================================================ */
	
			public function get_slug()
			{
				return $this->slug;
			}
			
			/* ============================================================================ */
	
			public function get_title()
			{
				return $this->title;
			}
			
			/* ============================================================================ */
	
			public function get_id()
			{
				return $this->id;
			}
			
			/* ============================================================================ */
	
			public function set_id( $id )
			{
				$this->id = $id;
			}
			
			/* ============================================================================ */
	
			public function get_location()
			{
				return $this->location;
			}
			
			/* ============================================================================ */
	
			public function set_location( $location )
			{
				$this->location = $location;
			}
			
			/* ============================================================================ */
	
			public function get_row()
			{
				return $this->row;
			}
			
			/* ============================================================================ */
	
			public function set_row( $row )
			{
				$this->row = $row;
			}
			
			/* ============================================================================ */
	
			public function get_column()
			{
				return $this->column;
			}
			
			/* ============================================================================ */
	
			public function set_column( $column )
			{
				$this->column = $column;
			}
			
			/* ============================================================================ */
	
			public function get_label()
			{
				return $this->label;
			}
			
			/* ============================================================================ */
	
			public function get_excerpt()
			{
				return '';
			}
			
			/* ============================================================================ */
	
			public function set_label( $label )
			{
				$this->label = $label;
			}
			
			/* ============================================================================ */
	
			public function show()
			{
				$this->visible = true;
			}
			
			/* ============================================================================ */
	
			public function hide()
			{
				$this->visible = false;
			}
			
			/* ============================================================================ */
	
			public function widget( $widget )
			{
				return '';
			}
			
			/* ============================================================================ */
	
			public function form( $widget )
			{
				return '';
			}
			
			/* ============================================================================ */
	
			/**
			 * Grab all the info for each widget and let the user customise what they want to see
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public function admin_form( $widget = NULL )
			{
			
				return '<div class="builder-widget-wrapper column-1 '.( $this->id != '__ID__' ? 'initialized ' : '' ).( $this->is_core() ? 'builder-widget-core ' : '' ).'builder-widget-type-'.$this->get_slug().( !$this->visible ? ' hide' : '' ).'">
					<div class="builder-widget">
						<div class="builder-widget-bar widget widget-top">
							<div class="builder-widget-title">'.$this->get_title().'</div>
							<div class="builder-widget-excerpt">'.$this->get_excerpt().'</div>
							<div class="builder-widget-label">'.$this->get_label().'</div>
							<div class="builder-widget-actions">
								<a href="#edit" class="edit">'.flab::langr( 'Edit' ).'</a>
								<a href="#remove" class="remove">'.flab::langr( 'Remove' ).'</a>
							</div>
						</div>
						'.( !$this->is_core() ? '<div class="builder-widget-content closed"><div class="builder-widget-bar widget widget-top"><div class="builder-widget-title">'.$this->get_title().' <span class="esc_to_close">'.flab::langr( 'Press Escape to close window' ).'</span></div></div><div class="builder-widget-inner"><div class="builder-widget-content-form">'.$this->form( $widget ).'</div><div class="builder-widget-content-actions">
							<div class="builder-widget-inner">
								<button name="builder-widget-save" class="save">'.flab::langr( 'Save' ).'</button>
							</div>
						</div></div></div>' : '' ).
						'<input type="hidden" name="'.flab::config( 'prefix' ).'builder_widget['.$this->location.']['.$this->row.']['.$this->column.']['.$this->id.'][__SLUG__]" value="'.$this->get_slug().'" />'.( $this->is_core() ? $this->form( $widget ).'<input type="hidden" name="'.flab::config( 'prefix' ).'builder_widget['.$this->location.']['.$this->row.']['.$this->column.']['.$this->id.'][__C||E__]" value="true" />' : '' ).'
					</div>
				</div>'._n;
				
			}/* admin_form() */
			
			/* ============================================================================ */
	
			/**
			 * The widget options for each flab module
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public function wp_admin_form( $widget, $id_base, $number )
			{
			
				$form = $this->admin_form( $widget );
				$atts = array( 'name', 'id' );
	
				foreach( $atts as $attr )
				{
					
					preg_match_all( '| '.$attr.'=\"'.flab::config( 'prefix' ).'builder_widget\[( .* )\]\[( .* )\]\[( .* )\]\[( .* )\]\[( .* )\]( .* )\"|iU', $form, $fields );
	
					if( isset( $fields[5] ) && count( $fields[5] ) > 0 )
					{
						
						$count = count( $fields[3] );
	
						for( $i = 0; $i < $count; $i++ )
						{
							$form = str_replace( $fields[0][$i], ' '.$attr.'="widget-'.$id_base.'['.$number.']['.$fields[5][$i].']'.$fields[6][$i].'"', $form );
						}
						
					}
					
				}
	
				return $form;
				
			}/* wp_admin_form() */
			
			/* ============================================================================ */
			
		}/* class flab_builder_widget */
		
	}
	
	if( !class_exists( 'flab_wp_widget' ) )
	{
		
		/**
		 * An extension of the builder widget - basically just a rip of the WP_Widget class again but with output buffering
		 *
		 * @package FLAB
		 * @author iamfriendly
		 * @version 1.0
		 * @since 1.0
		 */
		
		class flab_wp_widget extends flab_builder_widget
		{
			
			/**
			 * Set up our vars
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
			
			protected $wp_class;
			protected $wp_widget;
			
			/* ============================================================================ */
	
			/**
			 * Use output buffering for the output of the widgets
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public function widget( $widget )
			{
				
				ob_start();
					
					//var_dump( $widget );
					echo "<div class='in_content_widget widget ".$widget["__SLUG__"]."'>";
					$this->wp_widget->widget( array(), $widget );
					echo "</div>";
					
					$widget_out = ob_get_contents();
					
				ob_end_clean();
					
				return $widget_out;
				
			}/* widget() */
			
			/* ============================================================================ */
	
			public function form( $widget )
			{
				
				if( is_array( $widget ) && !empty( $widget ) )
				{
					
					foreach( $widget as $key => $value )
					{
					
						if( $widget[$key] === 'on' )
						{
							$widget[$key] = true;
						}
						
					}
					
				}
	
				ob_start();
				$this->wp_widget->form( $widget );
				$form = ob_get_clean();
	
				preg_match_all( '| name=\"widget-( .+ )\[( .* )\]\[( .* )\]\"|iU', $form, $fields );
	
				if( isset( $fields[3] ) && count( $fields[3] ) > 0 )
				{
					
					$count = count( $fields[3] );
	
					for( $i = 0; $i < $count; $i++ )
					{
						$form = str_replace( $fields[0][$i], ' name="'.$this->get_field_name( $fields[3][$i] ).'"', $form );
					}
					
				}
	
				return $form;
				
			}/* ( $widget )() */
			
			/* ============================================================================ */
			
		}/* class flab_wp_widget */
		
	}
	
	if( !class_exists( 'flab_builder_sidebar_widget' ) )
	{
		
		/**
		 * Our FLAB widgets which extend the simple WP_Widget class - allows us to include ALL THE WIDGETZ
		 *
		 * @package FLAB
		 * @author iamfriendly
		 * @version 1.0
		 * @since 1.0
		 */
		
		class flab_builder_sidebar_widget extends WP_Widget
		{
		
			protected $builder_class;
			protected $builder_widget;
	
			public function widget( $args, $instance )
			{
				echo $this->builder_widget->widget( $instance );
			}
			
			/* ============================================================================ */
	
			public function update( $new_instance, $old_instance )
			{
				return $new_instance;
			}
			
			/* ============================================================================ */
	
			public function form( $instance )
			{
				echo $this->builder_widget->wp_admin_form( $instance, $this->id_base, $this->number );
			}
			
			/* ============================================================================ */
			
		}/* class flab_builder_sidebar_widget */
		
	}
	
	flab::import( 'flab.modules.builder-widget' );

?>
