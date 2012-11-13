<?php

	 /* ================================================================================ */
	
	/**
	 * More easily insert newlines. Set up as a definition as per
	 * http://zoop.googlecode.com/svn-history/r448/trunk/framework/core/build/functions.php
	 *
	 * long desc
	 * @package
	 * @author iamfriendly
	 * @version 1.0
	 * @since 1.0
	 */
	if( !defined( "_n" ) )
	{
		define( "_n", "\n" );
	}
	
	if( !function_exists( '_en' ) )
	{
			
		function _en()
		{
		
			echo _n;
			
		}/* _en() */

	}
	
	/* ================================================================================ */
	
	if( !function_exists( '_t' ) )
	{
		
		/**
		 * Add a tab a certain number of times
		 * Idea from http://www.phpkode.com/source/p/pocomy/PoCoMy-0.9.2/php-base-sys-zero/core.class.php
		 *
		 * @package FLAB
		 * @author iamfriendly
		 * @version 1.0
		 * @since 1.0
		 */
		function _t( $tab = 0 )
		{
			
			return str_repeat( "\t", $tab );
		
		}/* _t() */
	
	}
	
	/* ================================================================================ */

	/**
	 * Need to get the path to the root of WP. Try and use ABSPATH if it is defined, else we have to guess a little
	 *
	 * @package FLAB
	 * @author iamfriendly
	 * @version 1.0
	 * @since 1.0
	 */
	 
	/* ================================================================================ */

	$wp_root = ( defined( 'ABSPATH' ) ) ? ABSPATH : dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) . '/';
	
	//Globalise the $flab paths
	global $flab_global_object;
	$flab_global_object = array( 
	
		'flab_root' => dirname( __FILE__ ) . '/',
		'theme_root' => dirname( dirname( __FILE__ ) ) . '/',
		'wp_root' => $wp_root
		
	 );
	
	/* ================================================================================ */

	/**
	 * If we're initialised and WP itself hasn't been loaded, load it and set up some defaults and intercept requests
	 *
	 * @package FLAB
	 * @author iamfriendly
	 * @version 1.0
	 * @since 1.0
	 */
	 
	 if( function_exists( 'flab_trigger_if_no_wp' ) )
	 {
	 	flab_trigger_if_no_wp();
	 }
	 
	 /* ================================================================================ */


	if( !class_exists( 'flab_generic' ) )
	{
	
		/**
		 * The Friendly LAyout Builder generic class
		 *
		 * @package FLAB
		 * @author iamfriendly
		 * @version 1.0
		 * @since 1.0
		 */
	
		class flab_generic
		{
		
			/**
			 * $flab_config - configuration options for the FLAB
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
		
			protected static $flab_config;
			
			/**
			 * We use a LOT of js ( too much, tbh ), so we need config options for these if we use
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
			
			protected static $flab_configjs;
			
			/**
			 * Vat to allow us to manage the setup of the classes (we use this to actually build the class names)
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			protected static $manage_setup;
			
			/**
			 * Basically used for an array to keep track of our groups to allow easily outputting them on the back end
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
			
			protected static $groups = array();
			
			/**
			 * You can probably guess what this is...
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
			
			protected static $widgets = array();
			
			/**
			 * To be used to keep track of shortcodes if we decide to do them
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
			
			protected static $group = '';
			
			/**
			 * Setup utility var to help us create metaboxes easier. Not used at the moment.
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
			
			protected static $meta_boxes = array();
			
			/**
			 * Array of setup info for metaboxes as per http://codex.wordpress.org/Function_Reference/add_meta_box
			 * the context, priority etc.
			 * 
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
			
			protected static $meta_box_perms = array();
			
			/**
			 * Keep track of the Custom Post Types
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
			
			protected static $wp_cpts = array();
			
			/**
			 * Much updated version of our shortcode column builder - properly built with OOP. This allows us to register
			 * different column types
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
			
			protected static $columns = array();
			
			/**
			 * Not really used, but again to allow us to add meta boxes
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
			
			protected static $taxonomy_meta_box = array();
			
			/**
			 * To deal with event bindings
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
			
			protected static $event_holder = array();
			
			/**
			 * Several WP functions have a dependencies argument (wp_enqueue_script etc.) This is a utility var to allow
			 * us to easily handle these
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
			
			protected static $dependencies = array();
	
			/**
			 * Just allows us to test if we've begun loading (without initialising)
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			protected static $begin_load = false;
			
			/**
			 * Are we initialised? you betcha. Not by default though…obviously.
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
			
			protected static $initialised = false;
			
			/**
			 * To keep track of our backraces
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
			
			protected static $bt_array = array();
	
			/* ============================================================================*/
	
			/**
			 * Initialise with a backtrace so we can see if something goes horrible wrong. Also allows us ot inlucde
			 * files more reliably
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function init()
			{
				
				$backtrace 				= debug_backtrace();
				$backtrace_details 		= array_shift( $backtrace );
				$name 					= basename( dirname( $backtrace_details['file'] ) );
	
				if( !self::$initialised )
				{
				
					/**
					 * We've not been initialised, so let's do exactly that. We start off with all of the setup that will ever need
					 * to be done for all of the world. We'll also set some config vars to utilise throughout our class
					 *
					 * @package FLAB
					 * @author iamfriendly
					 * @version 1.0
					 * @since 1.0
					 */
				
					global $flab_global_object;
	
					self::$flab_configjs = array();
					
					/**
					 * Main config options
					 *
					 * @package FLAB
					 * @author iamfriendly
					 * @version 1.0
					 * @since 1.0
					 */
	
					self::$flab_config = array( 
					
						'app_root' => $flab_global_object['theme_root'],
						'cache' => false,
						'cache_lifetime' => 604800,
						'cache_data' => false,
						'deps' => array(),
						'dir' => basename( dirname( dirname( __FILE__ ) ) ),
						'ext' => 'php',
						'image_div_class' => 'frame frame-1',
						'is_theme' => true,
						'new_img_tab_filter_function' => array( 'flab', 'flab_pass_image_editor_images_through_filter' ),
						'pagination' => true,
						'prefix' => 'flab_',
						'root' => $flab_global_object['flab_root'],
						'thumbnail_prefix' => '-thumb',
						'upload_dir' => 'friendly-layout-builder-cache-files',
						'upload_url' => '',
						'wp_root' => $flab_global_object['wp_root']
						
					 );
					 
					 /**
					 * Actions. Filter. Lots of. All need doing :/
					 *
					 * @package
					 * @author iamfriendly
					 * @version 1.0
					 * @since 1.0
					 */
	
					add_action( 'init', array( 'flab_generic', 'setup' ), 990 );
					add_filter( 'widget_text', 'do_shortcode' );
					add_filter( 'image_send_to_editor', array( 'flab', 'flab_pass_image_upload_through_filter' ), 10, 8 );
					add_filter( 'media_send_to_editor', array( 'flab', 'flab_pass_media_upload_through_filter' ), 10, 3 );
					add_filter( 'media_upload_form_url', array( 'flab', 'flab_pass_through_url_upload_filter' ), 10, 2 );
	
					/**
					 * CACHE EVERYTHING INCLUDING THE CACHE OF THE CACHE. Well, some stuff, anyway
					 *
					 * @package FLAB
					 * @author iamfriendly
					 * @version 1.0
					 * @since 1.0
					 */
					
					$upload_dir = wp_upload_dir();
					$upload_path = $upload_dir['basedir'];
	
					if( !is_dir( $upload_path . '/flab-cache-files') )
					{
						mkdir( $upload_path . '/flab-cache-files', 0755 );
					}
	
					if( !file_exists( $upload_path . '/flab-cache-files/.htaccess' ) )
					{
						flab::flab_maker_of_things( $upload_path . '/flab-cache-files/.htaccess', 'order allow,deny' . _n . 'deny from all' . _n );
					}
	
					self::$initialised = true;
					
				}
				
			}/* init() */
	
	
			/* ============================================================================*/
	
			/**
			 * Lots of setup to do with both the front-end and back-end and setting up actions and filters.
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function setup()
			{
			
				if( !self::$begin_load )
				{
	
					$uploads = wp_upload_dir();
	
					if( $uploads['error'] !== false )
					{
						wp_die( $uploads['error'] );
					}
	
					$upload_dir = $uploads['basedir'];
	
					if( is_writable( $uploads['basedir'] ) )
					{
					
						$upload_dir .= '/'.self::config( 'upload_dir' );
	
						if( !file_exists( $upload_dir ) )
						{
							
							//If the cache dir hasn't been made, let's go ahead and make it.
							mkdir( $upload_dir );
							
						}
						
					}
	
					//Set our config to know where the new cache folder is
					self::config( 'upload_url', $uploads['baseurl'].'/'.self::config( 'upload_dir' ) );
					self::config( 'upload_dir', $upload_dir );
	
					foreach( self::$widgets as $module )
					{
						
						$module_node = explode( '.', $module );
						$module_name = array_pop( $module_node );
	
						$module_class = self::config( 'prefix' ).str_replace( '-', '_', self::slug( str_replace( '.', '-', $module ) ) );
	
						if( class_exists( $module_class ) )
						{
						
							call_user_func_array( array( $module_class, 'set_class' ), array( $module_class ) );
	
							self::flab_module_go( str_replace( self::config( 'prefix' ), '', $module_class ).'.__module' );
							self::flab_module_go( str_replace( self::config( 'prefix' ), '', $module_class ).'.init' );
							
						}
						
					}
	
					if( is_admin() )
					{
					
						foreach( self::$taxonomy_meta_box as $tax_type => $tax_data )
						{
						
							foreach( $tax_data as $taxonomy_meta_box => $taxonomy_meta_box_data )
							{
							
								$metabox = self::slug( $taxonomy_meta_box );
								$flab_meta_box_class = str_replace( '-', '_', $metabox );
								$flab_meta_box_class = self::config( 'prefix' ).'metabox_'.$flab_meta_box_class;
	
								if( !class_exists( $flab_meta_box_class ) )
								{
									self::import( '!admin.flab.metabox.'.$metabox );
								}
	
								if( class_exists( $flab_meta_box_class ) )
								{
								
									call_user_func_array( array( $flab_meta_box_class, 'set_class' ), array( $flab_meta_box_class ) );
									call_user_func( array( $flab_meta_box_class, 'init' ) );
	
									add_action( $tax_type.'_edit_form', array( $flab_meta_box_class, 'body' ), 10, 2 );
									add_action( $tax_type.'_add_form_fields', array( $flab_meta_box_class, 'body' ), 10, 2 );
	
									add_action( 'edited_'.$tax_type, array( $flab_meta_box_class, 'save' ), 10, 2 );
									add_action( 'create_'.$tax_type, array( $flab_meta_box_class, 'save' ), 10, 2 );
									
								}
								
							}
							
						}
	
						$column_callbacks = array();
	
						foreach( self::$columns as $column_type => $column_data )
						{
						
							$prefix = '';
	
							if( $column_type == 'post' || $column_type == 'page' )
							{
								$column_type .= 's';
							}
							else
							{
								$prefix = 'edit-';
							}
	
							foreach( $column_data as $column_name => $column )
							{
							
								if( $column['callback'] !== true && !$column['callback'] === false )
								{
								
									if( !isset( $column_callbacks[$column_type] ) )
									{
										$column_callbacks[$column_type] = array();
									}
	
									if( !in_array( $column['callback'], $column_callbacks[$column_type] ) )
									{
										
										add_action( 'manage_'.$column_type.( ( $column_type == 'posts' || $column_type == 'pages' ) ? '' : '_posts' ).'_custom_column', $column['callback'] );
	
										$column_callbacks[$column_type][] = $column['callback'];
										
									}
									
								}
								
							}
							
						}
	
						if( count( self::$columns ) > 0 )
						{
						
							foreach( self::$columns as $column_type => $column_data )
							{
							
								$prefix = '';
	
								if( $column_type == 'post' || $column_type == 'page' )
								{
									$column_type .= 's';
								}
								else
								{
									$prefix = 'edit-';
								}
	
							}
							
						}
	
						self::flab_admin_make_action();
	
						if( basename( $_SERVER['SCRIPT_FILENAME'] ) == 'themes.php' && self::config( 'is_theme' ) )
						{
						
							if( isset( $_GET['activated'] ) && $_GET['activated'] == 'true' )
							{
							
								if( self::config( 'activate_callback' ) != null )
								{
									call_user_func( self::config( 'activate_callback' ) );
								}
	
								do_action( 'flab_activate' );
	
								global $wp_rewrite;
	
								$wp_rewrite->flush_rules();
								
							}
	
							if( isset( $_GET['action'] ) && $_GET['action'] == 'activate' && isset( $_GET['template'] ) && $_GET['template'] != self::config( 'dir' ) )
							{
							
								if( self::config( 'deactivate_callback' ) != null )
								{
									call_user_func( self::config( 'deactivate_callback' ) );
								}
	
								do_action( 'flab_deactivate' );
								
							}
							
						}
						
					}
	
					do_action( 'flab_setup' );
	
					if( !empty( $_GET ) )
					{
						self::fire_action_on_submit( 'get', array( $_GET ) );
					}
	
					if( !empty( $_POST ) )
					{
						self::fire_action_on_submit( 'post', array( $_POST ) );
					}
	
					if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )
					{
						self::fire_action_on_submit( 'ajax', array( $_GET ) );
					}
	
					self::$begin_load = true;
					
				}
				
			}/* setup() */
			
			/* ================================================================================ */
	
			/**
			 * Function to allow easily import of files. Prepend file name with !to include_once
			 * Mainly taken from a combination of comments from http://php.net/manual/en/function.require-once.php
			 * and the Fuel PHP Framework
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
			 
			public static function import( $file_path )
			{
			
				//require() by default, unless prepended with !
				$once = false;
	
				if( substr($file_path, 0, 1) == '!' )
				{
					
					$once = true;
					$file_path = trim( $file_path, '!' );
					
				}

				//Replace periods in our file path with '/' so we can scan our directories
				$file_path = str_replace( '.', trim('/', '.' ), $file_path );
	
				if( count( self::$bt_array ) > 1 )
				{
				
					foreach( self::$bt_array as $backtrace_details => $data )
					{
						
						if( $data['is_theme'] )
						{
							if( file_exists( STYLESHEETPATH . '/' . $file_path . '.' . self::config( 'ext' ) ) )
							{
								
								if( $once )
								{
									
									include_once( STYLESHEETPATH . '/' . $file_path . '.' . self::config( 'ext' ) );
									return;
									
								}
								else
								{
								
									include( STYLESHEETPATH . '/' . $file_path . '.' . self::config( 'ext' ) );
									return;
									
								}
								
							}
							
						}
	
						if( file_exists($data['app_root'] . $file_path . '.' . self::config( 'ext' ) ) )
						{
							
							if( $once )
							{
								include_once( $data['app_root'] . $file_path . '.' . self::config( 'ext' ) );
							}
							else
							{
								include($data['app_root'] . $file_path . '.' . self::config( 'ext' ) );
							}
							
						}
						else if( file_exists( $data['app_root'] . 'flab/' . $file_path . '.' . self::config( 'ext' ) ) )
						{
							
							if( $once )
							{
								include_once( $data['app_root'] . 'flab/' . $file_path . '.' . self::config( 'ext' ) );
							}
							else
							{
								include( $data['app_root'] . 'flab/' . $file_path . '.' . self::config( 'ext' ) );
							}
							
						}
						
					}
					
				}
	
				if( self::config( 'is_theme' ) && TEMPLATEPATH != STYLESHEETPATH )
				{
					
					if( file_exists( STYLESHEETPATH . '/' . $file_path . '.' . self::config( 'ext' ) ) )
					{
					
						if( $once )
						{
							
							include_once( STYLESHEETPATH . '/' . $file_path . '.' . self::config( 'ext' ) );
							return;
							
						}
						else
						{
							
							include( STYLESHEETPATH . '/' . $file_path . '.' . self::config( 'ext' ) );
							return;
							
						}
					}
					
				}
	
				if( file_exists( self::config( 'app_root' ) . $file_path . '.' . self::config( 'ext' ) ) )
				{
					
					if( $once )
					{
						include_once( self::config( 'app_root' ) . $file_path . '.' . self::config( 'ext' ) );
					}
					else
					{
						include( self::config( 'app_root' ) . $file_path . '.' . self::config( 'ext' ) );
					}
					
				}
				else if( file_exists(self::config('app_root').'flab/'.$file_path.'.'.self::config('ext')))
				{
					
					if( $once )
					{
						include_once( self::config( 'app_root' ) . 'flab/' . $file_path . '.' . self::config( 'ext' ) );
					}
					else
					{
						include( self::config( 'app_root' ) . 'flab/' . $file_path . '.' . self::config( 'ext' ) );
					}
					
				}
				
			}/* import() */
	
			/* ============================================================================ */
	
			/**
			 * Set up of all of our config options from our config up top
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function config( $option_name, $config_value = NULL )
			{
				
				if( $config_value !== NULL || ( is_array( $option_name ) && empty( $config_value ) ) )
				{
					
					if( is_array( $option_name ) )
					{
						
						foreach( $option_name as $k => $v )
						{
							self::config( $k, $v );
						}
						
					}
					else
					{
						self::$flab_config[$option_name] = $config_value;
					}
					
				}
				else
				{
					
					if( isset( self::$flab_config[$option_name] ) )
					{
						return self::$flab_config[$option_name];
					}
	
					return false;
					
				}
				
			}/* config() */
	
			/* ============================================================================ */
	
			/**
			 * Allow us to intercept requests and actually call them
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function fire_action_on_submit( $event, $args = array() )
			{
				
				if( isset( self::$event_holder[$event] ) )
				{
					foreach( self::$event_holder[$event] as $callback )
					{
						call_user_func_array($callback, $args);
					}
				}
				
			}/* trigger() */
	
			/* ============================================================================ */
	
			/**
			 * A helper function which is basically a wrapper for wp_enqueue_script
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function load_script( $slug = '', $path = '', $dependencies = array(), $ver = false )
			{
			
				if( is_array( $slug ) )
				{
					
					foreach( $slug as $script )
					{
						
						$script = array_merge( array( 'slug' => '', 'path' => false, 'deps' => array(), 'version' => false ), $script );
						self::load_script( $script['slug'], $script['path'], $script['deps'], $script['version'] );
						
					}
					
				}
				else
				{
				
					if( !empty( $path ) && substr( $path, 0, 7 ) != 'http://' )
					{
						$path = self::path($path, true);
					}
	
					if( empty( $slug ) )
					{
						$slug = self::slug( str_replace( '.', '-', basename( $path, '.js' ) ) );
					}
	
					wp_enqueue_script( $slug, ( empty( $path ) ? false : $path ), $dependencies, $ver );
					
				}
				
			}/* load_script() */
	
			/* ============================================================================ */
	
			/**
			 * Basically a wrapper for wp_enqueue_style which we can utilise in widgets/shortcodes
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
		
			public static function load_stylesheet( $slug = '', $path = false, $dependencies = array(), $ver = false, $conditional = '' )
			{
			
				if( is_array( $slug ) )
				{
					
					foreach( $slug as $style )
					{
						
						$style = array_merge( array( 
							'slug' => '', 
							'path' => false, 
							'deps' => array(), 
							'version' => false, 
							'conditional' => ''
						), $style);
	
						self::load_stylesheet( $style['slug'], $style['path'], $style['deps'], $style['version'], $style['conditional'] );
						
					}
					
				}
				else
				{
					
					if( !empty( $path ) && substr( $path, 0, 7 ) != 'http://' )
					{
						$path = self::path( $path, true );
					}
	
					if( empty( $slug ) )
					{
						$slug = self::slug( str_replace( '.', '-', basename( $path, '.css' ) ) );
					}
	
					if( !empty( $conditional ) )
					{
						
						global $wp_styles;
	
						wp_register_style( $slug, ( empty( $path ) ? false : $path ) );
						$wp_styles->add_data( $slug, 'conditional', $conditional );
						
					}
	
					wp_enqueue_style( $slug, ( empty( $path ) ? false : $path ), $dependencies, $ver );
					
				}
				
			}/* load_stylesheet() */
	
			/* ============================================================================ */
	
			/**
			 * This is where the magic happens. Our shortcode/Widget module builder starts here.
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function flab_load_module( $module )
			{
	
				if( !in_array( $module, self::$widgets ) )
				{
					
					$module_node = explode( '.', $module );
					$module_name = array_pop( $module_node );
	
					$module_class = self::config( 'prefix' ) . str_replace( '-', '_', self::slug( str_replace( '.', '-', $module ) ) );
	
					if( !class_exists( $module_class ) )
					{
						self::import( 'modules.' . $module );
					}
	
					self::$widgets[] = $module;
					
				}
				
			}/* flab_load_module() */
	
			/* ============================================================================ */
	
			/**
			 * Set up the module called
			 *
			 * long desc
			 * @package
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function flab_module_go()
			{
			
				$args = func_get_args();
				$name = array_shift( $args );
				$node = explode( '.', $name );
				$method = array_pop( $node );
	
				if( count( $node ) == 1 )
				{
					
					if( self::flab_module_instantiated(implode('.', $node)))
					{
						return call_user_func_array( array( self::config( 'prefix' ) . str_replace( '-', '_', self::slug( str_replace( '.', '-', implode( '.', $node ) ) ) ), $method ), $args );
					}
					
				}
	
				return false;
				
			}/* flab_module_go() */
			
			/* ============================================================================ */
	
			/**
			 * Check if the module we're instantiating actually exists as a class
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function flab_module_instantiated($name)
			{
				return class_exists( self::config( 'prefix' ) . str_replace( '-', '_', self::slug( str_replace( '.', '-', $name ) ) ) );
			}/* flab_module_instantiated() */
			
			/* ============================================================================ */
	
			/**
			 * Helper function to get the ID of the page we're on
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function flab_get_current_id()
			{
			
				global $post;
	
				$id = NULL;
	
				if( is_single() )
				{
					$id = $post->ID;
				}
				else if( is_page() || get_option( 'show_on_front' ) != 'posts' )
				{
				
					if( is_home() && get_option( 'show_on_front' ) != 'posts' )
					{
						$id = get_option('page_for_posts');
					}
					else
					{
						
						if( $post )
						{
							$id = $post->ID;
						}
						
					}
					
				}
	
				return $id;
				
			}/* flab_get_current_id() */
			
			/* ============================================================================ */
	
			/**
			 * Wrapper and helper function to get posts - upward bubbling from widgets if they can't find posts
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function get_posts( $args = 'numberposts=1&post_type=post&text_opt=excerpt', $time_format = 'F j, Y')
			{
			
				global $post;
				$last_post = $post;
	
				$result = array();
	
				if( !is_array( $args ) )
				{
					parse_str( $args, $args );
				}
	
				$defaults = array( 'text_opt' => 'content' );
				$args = array_merge( $defaults, $args );
				$meta = array();
	
				if( isset( $args['meta'] ) )
				{
					
					$meta = $args['meta'];
					unset($args['meta']);
					
				}
	
				$custom_order = false;
	
				if( isset( $args['post__in'] ) && !empty( $args['post__in'] ) && $args['orderby'] == 'custom' )
				{
					
					unset($args['orderby']);
					$custom_order = true;
					
				}
	
				$posts = get_posts( $args );
				$result = array();
	
				if( !empty($posts))
				{
					
					foreach( $posts as $post )
					{
						
						setup_postdata( $post );
						array_push($result, array(
						
							'id' => get_the_ID(),
							'permalink' => get_permalink(),
							'title' => get_the_title(),
							'author' => get_the_author(),
							'date' => get_the_date(),
							'date_ymd' => get_the_date('Y-m-d'),
							'timestamp' => get_the_time('U'),
							'author_link' => get_the_author_link(),
							'content' => ($args['text_opt'] == 'excerpt' ? get_the_excerpt() : ($args['text_opt'] == 'content' ? get_the_content() : ''))
							
						));
						
					}
					
				}
	
				if( !empty( $meta ) )
				{
				
					$result_meta = array();
	
					foreach( $result as $p )
					{
						$p['meta'] = array();
	
						foreach( $meta as $meta_key )
						{
							$p['meta'][$meta_key] = self::meta( $meta_key, true, $p['id'] );
						}
	
						$result_meta[] = $p;
					}
	
					$result = $result_meta;
					
				}
	
				if( $custom_order )
				{
				
					$posts_ = array();
					$ids = array();
					$ids_ = $args['post__in'];
					$counter = 0;
	
					foreach( $ids_ as $k => $v )
					{
						$ids[$v] = $counter;
						$counter++;
					}
	
					foreach( $result as $p )
					{
						$posts_[intval( $ids[$p['id']] )] = $p;
					}
	
					$result = $posts_;
	
					ksort( $result );
					
				}
	
				wp_reset_query();
				$post = $last_post;
	
				return $result;
				
			}/* get_posts */
	
			/* ============================================================================ */
	
			/**
			 * Helper function for the related posts widget
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function flab_get_related_posts( $args, $taxonomies = array('category', 'post_tag'), $relation = 'OR', $operator = 'LIKE' )
			{
			
				global $post;
				$related_posts = array();
	
				$tax_query = array( 'relation' => $relation );
	
				if( !is_array( $taxonomies ) )
				{
					$taxonomies = array( $taxonomies );
				}
	
				foreach( $taxonomies as $tax )
				{
					$terms = wp_get_object_terms( $post->ID, $tax );
	
					if( $terms )
					{
						
						$terms_ids = array();
	
						foreach( $terms as $term )
						{
							$terms_ids[] = $term->term_id;
						}
	
						$tax_query[] = array(
						
							'taxonomy' => $tax,
							'field' => 'id',
							'terms' => $terms_ids,
							'operator' => $operator
							
						);
					}
					
				}
	
				$args['tax_query'] = $tax_query;
				$args['post__not_in'] = array( $post->ID, $post->post_parent );
	
				$related_posts = self::get_posts( $args );
	
				return $related_posts;
				
			}/* flab_get_related_posts() */
	
			/* ============================================================================ */
	
			/**
			 * Captions: No. Sorry. Not happening on our tabs.
			 *
			 * long desc
			 * @package
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function disable_captions()
			{
				return true;
			}/* disable_captions() */
	
			/* ============================================================================ */
	
			/**
			 * Filter the images in the image editor to twist them in ways only mad men can possibly envisage
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function flab_pass_image_editor_images_through_filter( $html, $id, $caption, $title, $align, $url, $size, $alt )
			{
			
				$output = '';
	
				preg_match( '/(href=[\'"])(.*?)([\'"])/i', $html, $href );
				preg_match( '/(src=[\'"])(.*?)([\'"])/i', $html, $src );
				preg_match( '/(width=[\'"])(.*?)([\'"])/i', $html, $width );
				preg_match( '/(height=[\'"])(.*?)([\'"])/i', $html, $height );
	
				$href = ( isset( $href[2] ) ? $href[2] : NULL );
				$src = ( isset($src[2] ) ? $src[2] : NULL );
				$width = ( isset($width[2] ) ? $width[2] : NULL );
				$height = ( isset($height[2] ) ? $height[2] : NULL );
	
				if( !empty( $href ) && !empty( $src ) )
				{
					$output .= '<a href="' . self::flab_overlay_url( $href ) . '" class="' . self::config( 'image_div_class' ) . ' align' . $align . '">';
				}
	
				if( !empty( $src ) )
				{
					$output .= '<img src="' . $src . '" ' . ( empty( $href ) ? 'class="' . self::config( 'image_div_class' ) . ' align' . $align . '" ' : '' ) . 'alt="' . $alt . '"' . ( !empty( $width ) ? ' width="' . $width . '"' : '' ) . ( !empty( $height ) ? ' height="' . $height . '"' : '' ) . ' title="' . $title . '" />';
				}
	
				if( !empty( $href ) && !empty( $src ) )
				{
					$output .= '</a>';
				}
	
				if( !empty( $output ) )
				{
					return $output;
				}
	
				return $html;
				
			}/* flab_pass_image_editor_images_through_filter() */
	
			/* ============================================================================ */
	
			/**
			 * We need to filter images sent from our tab to the editor
			 *
			 * long desc
			 * @package
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function flab_pass_image_upload_through_filter( $html, $id, $caption, $title, $align, $url, $size, $alt )
			{
			
				$output = '';
	
				if( isset($_GET['flab'] ) && $_GET['flab'] == 'true' )
				{
				
					if( self::config( 'thumb_on_send' ) && isset( $_GET['width'] ) && !empty( $_GET['width'] ) && isset( $_GET['height'] ) && !empty( $_GET['height'] ) )
					{
						
						$uploads = wp_upload_dir();
	
						preg_match( '/attachment_id=(\d+)/i', $url, $attachment );
	
						if( count( $attachment ) > 0 )
						{
							$url = wp_get_attachment_url( $attachment[1] );
						}
	
						$image = $url;
						$thumbnail = $url;
	
						preg_match_all( '/(src=[\'|"])(.*?)([\'|"])/i', $html, $src );
	
						if( count($src[2] ) > 0 )
						{
							$thumbnail = $src[2][0];
						}
	
						$thumbnail_path = str_replace( $uploads['baseurl'], $uploads['basedir'], $thumbnail );
						$thumbnail_size = getimagesize( $thumbnail_path );
	
						$width = explode( '_', $_GET['width'] );
						$height = explode( '_', $_GET['height'] );
						$width_count = count( $width );
						$height_count = count( $height );
	
						if( $width_count == $height_count )
						{
							
							$thumbnail_url = '';
	
							for ( $i = 0; $i < $width_count; $i++ )
							{
								
								if( $i == 0)
								{
									$thumbnail_url = self::flab_get_thumb_img( $image, $width[$i], $height[$i] );
								}
								else
								{
									self::flab_get_thumb_img( $image, $width[$i], $height[$i] );
								}
	
								if( $width[$i] == $thumbnail_size[0] && $height[$i] == $thumbnail_size[1] )
								{
									$thumbnail_url = $thumbnail;
								}
								
							}
	
							$output = $thumbnail_url;
							
						}
						else
						{
							$output = self::flab_get_thumb_img( $image, $width[0], $height[0] );
						}
						
					}
					else
					{
						$output = $url;
					}
	
					if( !empty( $output ) )
					{
						
						if( isset( $_GET['output'] ) && $_GET['output'] == 'html' )
						{
							return '<a href="' . self::flab_overlay_url( $url ) . '" class="' . self::config( 'image_div_class' ) . ' align' . $align . '"><img src="' . $output . '" alt="' . $alt . '" title="' . $title . '" /></a>';
						}
						else
						{
							return $output;
						}
						
					}
					
				}
	
				if( self::config( 'new_img_tab_filter_function' ) != NULL )
				{
					$html = call_user_func_array( self::config( 'new_img_tab_filter_function' ), array( $html, $id, $caption, $title, $align, $url, $size, $alt ) );
				}
	
				return $html;
				
			}/* flab_pass_image_upload_through_filter() */
	
			/* ============================================================================ */
	
			/**
			 * Filter the media uploaded in our tab
			 *
			 * long desc
			 * @package
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function flab_pass_media_upload_through_filter( $html, $id, $attachment )
			{
				
				$output = '';
	
				if( isset( $_GET['flab'] ) && $_GET['flab'] == 'true' && ( !isset( $_GET['type'] ) || $_GET['type'] != 'image' ) )
				{
					
					$output = $html;
	
					if( !empty( $output ) )
					{
						
						if( isset($_GET['output']) && $_GET['output'] == 'html' )
						{
							return $output;
						}
						else
						{
							
							preg_match_all( '/(href=[\'|"])(.*?)([\'|"])/i', $html, $href );
	
							if( count( $href[2]) > 0 )
							{
								return $href[2][0];
							}
							
						}
						
					}
					
				}
	
				return $html;
				
			}/* flab_pass_media_upload_through_filter() */
	
			/* ============================================================================ */
	
			/**
			 * We need to ensure we're filtering images uploaded via an external URL. This function is applied to the
			 * 'media_upload_form_url' filter.
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function flab_pass_through_url_upload_filter( $url, $type )
			{
			
				if( isset( $_GET['flab'] ) && $_GET['flab'] == 'true' )
				{
					
					$url .= '&flab=true';
	
					if( isset( $_GET['width'] ) )
					{
						$url .= '&width=' . $_GET['width'];
					}
	
					if( isset( $_GET['height'] ) )
					{
						$url .= '&height=' . $_GET['height'];
					}
	
					if( isset( $_GET['output'] ) )
					{
						$url .= '&output=' . $_GET['output'];
					}
	
					if( isset( $_GET['single'] ) )
					{
						$url .= '&single=' . $_GET['single'];
					}
	
					if( isset( $_GET['tab'] ) )
					{
						$url .= '&tab=' . $_GET['tab'];
					}
					
				}
	
				return $url;
				
			}/* flab_pass_through_url_upload_filter() */
	
			/* ============================================================================ */
	
			/**
			 * Language Swapping
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function lang()
			{
				
				$args = func_get_args();
				$format = array_shift($args);
	
				vprintf(__($format, trim(self::config('prefix'), '_')), $args);
				
			}/* lang */
	
			/* ============================================================================ */
	
			/**
			 * Language swapping. Need to use __, _e etc. in future
			 * TODO: Use -_, __
			 * 
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function langr()
			{
			
				$args = func_get_args();
				$format = array_shift( $args );
	
				return vsprintf( __( $format, trim( self::config( 'prefix' ), '_' ) ), $args );
				
			}/* langr() */
			
			/* ============================================================================ */
			
			/**
			 * wrapper for _x() function
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
			
			public static function langx( $message, $context, $return = false )
			{
			
				$output = _x( $message, $context, trim( self::config( 'prefix' ), '_' ) );
	
				if( $return )
				{
					return $output;
				}
	
				echo $output;
				
			}
	
			/* ============================================================================ */
	
			/**
			 * Helper function to deal with urls, used in shortcodes
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function url( $requested_url, $return = false )
			{
			
				$requested_url = self::info( 'url', true ) . '/' . trim( $requested_url, '/' ) . '/';
	
				if( $return )
				{
					return $requested_url;
				}
				else
				{
					echo $requested_url;
				}
				
			}
	
			/* ============================================================================ */
	
			/**
			 * Helper function to give us the correct urls for different items
			 *
			 * long desc
			 * @package
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function path( $requested_path, $return = false )
			{
				
				if( count( self::$bt_array ) > 1 )
				{
				
					foreach( self::$bt_array as $backtrace_details => $data )
					{
					
						if( TEMPLATEPATH != STYLESHEETPATH && file_exists( STYLESHEETPATH . '/' . trim( $requested_path, '/' ) ) )
						{
						
							if( $return)
							{
								return get_template_directory_uri() . '/' . trim( $requested_path, '/' );
							}
							else
							{
								echo get_template_directory_uri() . '/' . trim( $requested_path, '/' );
								return;
							}
							
						}
	
						$dir = rtrim( $data['app_root'] . $requested_path, '/' );
	
						if( is_dir( $dir ) || file_exists( $dir ) )
						{
						
							if( $return )
							{
								return WP_CONTENT_URL . '/themes/' . $backtrace_details . '/' . trim( $requested_path, '/' );
							}
							else
							{
								
								echo WP_CONTENT_URL . '/themes/' . $backtrace_details . '/' . trim( $requested_path, '/' );
								return;
								
							}
							
						}
						
					}
					
				}
	
				if( TEMPLATEPATH != STYLESHEETPATH && file_exists( STYLESHEETPATH . '/' . trim( $requested_path, '/' ) ) )
				{
				
					if( $return )
					{
						return get_template_directory_uri() . '/' . trim( $requested_path, '/' );
					}
					else
					{
						echo get_template_directory_uri() . '/' . trim( $requested_path, '/' );
					}
	
					return;
					
				}
				
	
				$requested_path = WP_CONTENT_URL . '/themes' . '/' . self::config( 'dir' ) . '/' . trim( $requested_path, '/' );
	
				if( $return)
				{
					return $requested_path;
				}
				else
				{
					echo $requested_path;
				}
				
			}/* path() */
			
			/* ============================================================================ */
	
			/**
			 * Simple helper function for get_bloginfo() to help us get some of it's params nice and easily and cache it
			 *
			 * @package
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function info( $tag, $return = false )
			{
				
				if( self::config( 'cache_data' ) )
				{
					$cache = self::flab_cache_it( 'flab::info::' . $tag, NULL );
	
					if( $cache !== false )
					{
						if( $return )
						{
							return $cache;
						}
						else
						{
							echo $cache;
						}
	
						return;
					}
				}
	
				$info = get_bloginfo( $tag );
	
				if( self::config('cache_data') )
				{
					self::flab_cache_it( 'flab::info::' . $tag, $info );
				}
	
				if( $return )
				{
					return $info;
				}
				else
				{
					echo $info;
				}
				
			}/* info() */
	
			/* ============================================================================ */
	
			/**
			 * HElper function to deal with any updating of site options, rather than our theme options
			 *
			 * long desc
			 * @package
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function option( $key, $value = NULL )
			{
			
				if( $value === NULL )
				{
					
					if( self::config( 'cache_data' ) )
					{
						
						$cache = self::flab_cache_it( 'flab::option::' . $key, NULL );
	
						if( $cache !== false )
						{
							return $cache;
						}
						
					}
	
					$option = get_option( self::config( 'prefix' ) . $key );
	
					if( self::config( 'cache_data' ) )
					{
						self::flab_cache_it( 'flab::option::' . $key, $option );
					}
	
					return $option;
					
				}
				else
				{
				
					update_option( self::config( 'prefix' ) . $key, $value );
	
					if( self::config( 'cache_data' ) )
					{
						self::flab_cache_it( 'flab::option::'.$key, $value );
					}
					
				}
	
				return '';
				
			}/* option() */
	
			/* ============================================================================ */
	
			/**
			 * Helper function to update, cache or retrieve meta information (post meta)
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function meta( $key, $single = true, $id = NULL, $update = false )
			{
				
				if( $id == NULL && get_option( 'show_on_front' ) != 'posts' )
				{
					
					if( is_home() && get_option('show_on_front') != 'posts' )
					{
						$id = get_option( 'page_for_posts' );
					}
					
				}
	
				if( !$update )
				{
					
					if( self::config( 'cache_data' ) )
					{
						
						$cache = self::flab_cache_it( 'flab::meta::' . $key . $id, NULL );
	
						if( $cache !== false )
						{
							return $cache;
						}
						
					}
	
					global $post;
	
					$fields = get_post_meta($id != NULL ? $id : $post->ID, self::config('prefix').$key, $single);

					if( self::config( 'cache_data' ) )
					{
						self::flab_cache_it( 'flab::meta' . $key . $id, $fields );
					}
					
					return $fields;

				}
				else
				{
					
					global $post;
					update_post_meta( $id != NULL ? $id : $post->ID, self::config( 'prefix' ) . $key, $single );
	
				}
	
				return NULL;
				
			}/* meta() */
	
			/* ============================================================================ */
	
			/**
			 * Basically converts a time into "x-time ago".
			 * TODO: Rewrite to use the WP function http://codex.wordpress.org/Function_Reference/human_time_diff
			 *
			 * @package
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function twitter_time_ago( $time )
			{
				
				$delta = time() - $time;
	
				if( $delta < 60 )
				{
					return __( 'less than a minute ago', THEMENAME );
				}
				else if( $delta < 120 )
				{
					return __( 'about a minute ago', THEMENAME );
				}
				else if( $delta < ( 2700 ) )
				{
					return self::langr( '%s minutes ago', floor( $delta / 60 ) );
				}
				else if( $delta < ( 5400 ) )
				{
					return __( 'about an hour ago', THEMENAME );
				}
				else if( $delta < ( 86400 ) )
				{
					return self::langr( 'about %s hours ago', floor( $delta / 3600 ) );
				}
				else if( $delta < ( 172800 ) )
				{
					return __( '1 day ago', THEMENAME );
				}
				else
				{
					return self::langr( '%s days ago', floor( $delta / 86400 ) );
				}
				
			}/* twitter_time_ago() */
	
			/* ============================================================================ */
			
			/**
			 * Flickr Feed through their API and cache it
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function flab_flickr_feed( $id = null, $count = 1, $tags = '' )
			{
				
				$feed = array();
	
				if( $id == null )
				{
					$id = self::option( 'social_flickr' );
				}
	
				if( $id != '' )
				{
				
					$cache = self::flab_cache_it( 'flickr_feed', null );
	
					if( $cache !== false )
					{
						return $cache;
					}
	
					$flickr = self::flab_api_get_uri( 'http://api.flickr.com/services/feeds/photos_public.gne?id='.$id.'&format=php_serial'.( !empty( $tags ) ? '&tags='.$tags : '' ) );
					$flickr = unserialize( $flickr );
	
					if( $count <= 0 )
					{
						$count = 20;
					}
	
					if( $flickr !== false )
					{
						
						for ( $i = 0; $i < $count; $i++ )
						{
							
							$feed[] = array( 
								
								'title' => $flickr['items'][$i]['title'],
								'link' => $flickr['items'][$i]['url'],
								'image' => $flickr['items'][$i]['photo_url'],
								'thumbnail' => $flickr['items'][$i]['t_url']
								
							 );
							 
						}
						
					}
					
				}
	
				self::flab_cache_it( 'flickr_feed', $feed );
	
				return $feed;
				
			}/* flab_flickr_feed() */
	
			/* ============================================================================ */
	
			/**
			 * Output for the Google Map Widget.
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function flab_google_map( $location = null, $width = null, $height = null, $zoom = 14, $view = 0, $return = false )
			{
	
				if( $view < 0 || $view > 2 )
				{
					$view = 0;
				}
	
				$views = array( 'm', 'k', 'p' );
	
				$location = htmlspecialchars( $location );
	
				$map = '<iframe' . ( $width != null ? ' width="' . $width . '"' : '' ) . ( $height != null ? ' height="' . $height . '"' : '' ) . ' frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?q=' . $location . '&t=' . $views[$view] . '&z=' . $zoom . '&output=embed"></iframe>';
	
				if( $return )
				{
					return $map;
				}
	
				echo $map;
				
			}/* flab_google_map */
			
			/* ============================================================================ */
	
			/**
			 * Loader for the twitter widget. Mainly taken from our previous shortcode, which was taken from the 
			 * Twitter widget plugin, but fixed the issue with missing the first character
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
			 
			/* ============================================================================ */
	
			public static function twitter_feed( $username = null, $count = 1 )
			{
	
				$tweets = array();
	
				if( $username != '' )
				{
				
					$cache = self::flab_cache_it( 'twitter_tweets' . $username . $count, null );
	
					if( $cache !== false && is_array( $cache ) && count( $cache ) > 0 )
					{
						return $cache;
					}
	
					$twitter = self::flab_api_get_uri( 'http://twitter.com/statuses/user_timeline/' . $username . '.json?count='.$count );
					$twitter = json_decode( $twitter );
	
					foreach( $twitter as $tweet )
					{
						
						$tweet->text = strip_tags( $tweet->text );

						$tweet->text = ' '.preg_replace( "/(([[:alnum:]]+:\/\/)|www\.)([^[:space:]]*)([[:alnum:]#?\/&=])/i", "<a href=\"\\1\\3\\4\" target=\"_blank\">\\1\\3\\4</a>", $tweet->text );
						$tweet->text = preg_replace( "/(([a-z0-9_]|\\-|\\.)+@([^[:space:]]*)([[:alnum:]-]))/i", "<a href=\"mailto:\\1\">\\1</a>", $tweet->text );
						$tweet->text = preg_replace( "/ +@([a-z0-9_]*) ?/i", " <a href=\"http://twitter.com/\\1\">@\\1</a> ", $tweet->text );
						$tweet->text = preg_replace( "/ +#([a-z0-9_]*) ?/i", " <a href=\"http://twitter.com/search?q=%23\\1\">#\\1</a> ", $tweet->text );
						$tweet->text = preg_replace( "/>(([[:alnum:]]+:\/\/)|www\.)([^[:space:]]{30,40})([^[:space:]]*)([^[:space:]]{10,20})([[:alnum:]#?\/&=])</", ">\\3...\\5\\6<", $tweet->text );
						$tweet->text = trim( $tweet->text );

						if( $tweet->text != '' )
						{
							
							array_push($tweets, array(
								
								'tweet' => $tweet->text,
								'time' => self::twitter_time_ago( strtotime( str_replace( '+0000', '', $tweet->created_at ) ) ),
								'link' => 'http://twitter.com/'.$username.'/statuses/'.$tweet->id
								
							));
							
						}
						
					}

				}
	
				self::flab_cache_it( 'twitter_tweets' . $username . $count, $tweets );
	
				return $tweets;
				
			}/* twitter_feed() */
	
			/* ============================================================================ */
	
			/**
			 * Different 'things' require a different setup for the overlay which need different paramaters
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function flab_overlay_url( $url )
			{
			
				$url = trim( $url );
		
				//Get the domain from the url
				preg_match( '@^(?:http://)?(?:www.)?([^/]+)@i', $url, $matches );
	
				//Now run through the different video providers
	
				if( $matches[1] == 'video.google.com' )
				{
					
					$params = explode( '?', $url );
					parse_str( html_entity_decode( $params[1]), $params );
	
					foreach( $params as $k => $v )
					{
						
						if( strtolower( $k ) == 'docid' )
						{
							$url = 'http://video.google.com/googleplayer.swf?docid='.$v;
	
							return $url.'" rel="shadowbox;width=480;height=290;player=iframe';
						}
						
					}
					
				}
				else if( $matches[1] == 'vimeo.com' )
				{
					
					preg_match('/(\d+)/', $url, $id);
	
					if( !empty( $id[1] ) )
					{
						$url = 'http://player.vimeo.com/video/'.$id[1];
	
						return $url.'" rel="shadowbox;width=480;height=290;player=iframe';
					}
					
				}
				else if( $matches[1] == 'blip.tv' )
				{
					
					preg_match( '/file\/(\d+)\//', $url, $id );
	
					if( !empty($id[1]))
					{
						$url = 'http://blip.tv/play/'.$id[1];
	
						return $url.'" rel="shadowbox;width=480;height=290;player=iframe';
					}
					
				}
				else if( $matches[1] == 'youtube.com' )
				{
					
					$params = explode('?', $url);
					parse_str( html_entity_decode( $params[1] ), $params );
	
					foreach( $params as $k => $v )
					{
						
						if( strtolower( $k ) == 'v' )
						{
							$url = 'http://www.youtube.com/embed/'.$v;
	
							return $url.'" rel="shadowbox;width=480;height=290;player=iframe';
						}
						
					}
					
				}
				
				//Or are we on an image?
	
				preg_match('/(?i)\.(jpg|png|gif)$/', $url, $ext);
	
				if( !empty( $ext) )
				{
					return $url.'" rel="shadowbox';
				}
	
				return $url;
				
			}/* flab_overlay_url() */
			
			/* ============================================================================ */
	
			/**
			 * Mainly used for image sliders, js-related image things
			 *
			 * @package
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function img( $img, $source_id = '' )
			{
				
				$img = apply_filters( 'flab_img', $img, $source_id );
	
				return $img;
				
			}
	
			/* ============================================================================ */
	
			/**
			 * If we want to cache some stuff (mainly flickr, twitter, feedburner etc.)
			 * Some from: http://phpcollection.com/creating-a-cache-file-in-php-for-api-requests/
			 * @package
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function flab_cache_it( $flab_cache_item_key, $what_to_cache = null, $time = null )
			{
				
				if( $time === null )
				{
					$time = self::config('cache_lifetime');
				}
	
				$path = WP_CONTENT_DIR.'/flab-cache/';
	
				if( !is_dir( $path ) || !is_writable( $path ) )
				{
					return false;
				}
	
				$file = $path . md5( $flab_cache_item_key );
	
				if( $what_to_cache != null )
				{
					file_put_contents( $file, serialize( $what_to_cache ) );
				}
				else
				{
				
					if( file_exists( $file ) && is_file( $file ) )
					{
						
						if( (filemtime( $file ) + $time ) > time() )
						{
							return unserialize( file_get_contents( $file ) );
						}
						
					}
	
					return false;
				}
	
				return true;
				
			}/* flab_cache_it() */
	
			/* ============================================================================ */
	
			/**
			 * Helper function to make a "slug" from a string
			 * Mainly taken from http://www.intrepidstudios.com/blog/2009/2/10/function-to-generate-a-url-friendly-string.aspx
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
		
			public static function slug( $passed_string )
			{
			
				$passed_string = preg_replace( '/[^a-zA-Z0-9\/_|+ -]/', '', $passed_string );
				$passed_string = strtolower( trim( $passed_string, '-' ) );
				$passed_string = preg_replace( '/[_|+ -]+/', '-', $passed_string );
				$passed_string = preg_replace( '/(\/)+/', '/', $passed_string );
	
				return rtrim( $passed_string, '/' );
				
			}/* slug */
	
			/* ============================================================================ */
	
			/**
			 * Strip only certain tags from the data that is passed
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function flab_strip_certain_tags( $data, $tags = null )
			{
				
				$regexp = '#\s*<( /?\w+ )\s+( ?:on\w+\s*=\s*( ["\'\s] )?.+?\( \1?.+?\1?\ );?\1?|style=["\'].+?["\'] )\s*>#is';
	
				return preg_replace( $regexp, '<${1}>', strip_tags( $data, $tags ) );
				
			}/* flab_strip_certain_tags() */
			
			/* ============================================================================ */
			
			/**
			 * Just strip the tags, used by the lists module
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
			 
			public static function strip_only( $data, $tags )
			{
			
				if( !is_array( $tags ) )
				{
				
					$tags = ( strpos($tags, '>' ) !== false ? explode( '>', str_replace( '<', '', $tags ) ) : array( $tags ) );
	
					if( end( $tags ) == '' )
					{
						array_pop( $tags );
					}
					
				}
	
				foreach( $tags as $tag )
				{
					$data = preg_replace( '#</?'.$tag.'[^>]*>#is', '', $data );
				}
	
				return $data;
				
			}/* strip_only() */
	
			/* ============================================================================ */
	
			/**
			 * Helper function to grab the contents of a passed URL. Mainly used for widgets that access an API (Flickr)
			 * Basically uses curl if it exists, otherwise uses file_get_contents
			 * ToDo: Rewrite this to use the WP URL API
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function flab_api_get_uri( $url )
			{
			
				if( function_exists( 'curl_init' ) )
				{
					
					$curl = curl_init();
					curl_setopt( $curl, CURLOPT_URL, $url );
					curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
					$content = curl_exec( $curl );
					curl_close( $curl );
					
				}
				else
				{
					$content = file_get_contents( $url );
				}
	
				return $content;
				
			}/* flab_api_get_uri() */
	
	
			/* ============================================================================ */
			
			/**
			 * Take a string and make it into something we can use as a slug
			 *
			 * long desc
			 * @package
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function turn_into_slug( $input, $output_array = false )
			{
			
				if( !is_array( $input ) )
				{
					$input = explode( ',', $input );
				}
	
				$output = array();
	
				foreach( $input as $part )
				{
					$output[] = self::slug( trim( $part ) );
				}
	
				if( !$output_array )
				{
					return implode( ',', $output );
				}
	
				return $output;
				
			}/* turn_into_slug() */
			
			/* ============================================================================ */
	
			/**
			 * Helper function (used by the lists shortcode initially, but built so we can do this generically)
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function flab_set_attribute_on_element( $element, $attr, $value, $html, $append = false )
			{
			
				preg_match( '/( <'.$element.'( .* )> )( .* )( <.*'.$element.'> )/ismU', $html, $elements );
	
				if( empty( $elements ) )
				{
					return $html;
				}
	
				if( !empty( $elements[2] ) )
				{
					
					preg_match( '/( '.$attr.'=[\'|"] )( .*? )( [\'|"] )/i', $elements[1], $attributes );
	
					if( !empty( $attributes[2] ) )
					{
						
						$once = 1;
	
						if( $append )
						{
							return str_replace( $attributes[0], $attributes[1].$attributes[2].( !empty( $attributes[2] ) ? ' ' : '' ).$value.$attributes[3], $html, $once );
						}
	
						return str_replace( $attributes[0], $attributes[1].$value.$attributes[3], $html, $once );
						
					}
					
				}
	
				$element = implode( ' '.$attr.'="'.$value.'">', explode( '>', $elements[1] ) );
	
				$once = 1;
	
				return str_replace( $elements[1], $element, $html, $once );
				
			}/* flab_set_attribute_on_element() */
			
			/* ============================================================================ */
			
			/**
			 * Tests if we're on a "mobile" device. Adapted from http://pastebin.com/Ukr0gfYk
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function is_not_desktop()
			{
			
				$regex = '/( nokia|iphone|android|motorola|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam\-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|symbian|smartphone|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte\-|longcos|pantech|gionee|^sie\-|portalmmm|jig\s browser|hiptop|^ucweb|^benq|haier|^lct|opera\s*mobi|opera\*mini|320x320|240x320|176x220 )/i';
	
				return isset( $_SERVER['HTTP_X_WAP_PROFILE'] ) || isset( $_SERVER['HTTP_PROFILE'] ) || preg_match( $regex, strtolower( $_SERVER['HTTP_USER_AGENT'] ) );
				
			}/* is_not_desktop() */
			
			/* ============================================================================ */
	
			/**
			 * Helper function to help with caching, basically to see if a file or folder exists
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
			 
			public static function reader_of_things( $path )
			{
			
				if( !file_exists( $path ) )
				{
					return false;
				}
	
				if( function_exists( 'file_get_contents' ) )
				{
					return file_get_contents( $path );
				}
	
				if( !$fp = @fopen( $path, FOPEN_READ ) )
				{
					return false;
				}
	
				flock( $fp, LOCK_SH );
				$data =& fread( $fp, filesize( $path ) );
				flock( $fp, LOCK_UN );
				fclose( $fp );
	
				return $data;
				
			}/* reader_of_things() */
			
			/* ============================================================================ */
	
			/**
			 * Helper function to make (or append) files/folders, mainly used for cache-related shenanigans
			 *
			 * @package
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function flab_maker_of_things( $path, $data, $append_it = false )
			{
			
				$flag = $append_it ? 'a' : 'w';
	
				if( !$fp = fopen( $path, $flag ) )
				{
					return false;
				}
	
				if( !$append_it )
				{
					chmod( $path, 0755 );
				}
	
				flock( $fp, LOCK_EX );
				fwrite( $fp, $data );
				flock( $fp, LOCK_UN );
				fclose( $fp );
	
				return true;
				
			}/* flab_maker_of_things() */
			
			/* ============================================================================ */

			/**
			 * As we can't rely on imagick being installed (*sigh*) and don't natively have access to WP's thumb functions
			 * hack together bits from TimThumb, the inbuilt thumb script and from 
			 * http://www.icant.co.uk/articles/phpthumbnails/ and mostly ripped from (which seems to be a combo of the
			 * above)
			 * http://pastebin.com/bT0252RA
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function flab_thumb( $path, $width, $height, $ratio = true, $crop_width = null, $crop_height = null, $output_path = null )
			{
			
				if( !function_exists( 'imagecreatetruecolor' ))
				{
					return $path;
				}
	
				$changed = false;
	
				$r = explode( '.', $path );
				$ext = strtolower( end( $r ) );
	
				if( $ext == 'jpg' || $ext == 'jpeg' )
				{
					$img = @imagecreatefromjpeg( $path );
				} 
				else if( $ext == 'png' )
				{
					$img = @imagecreatefrompng( $path );
				}
				else if( $ext == 'gif' )
				{
					$img = @imagecreatefromgif( $path );
				}
	
				$x = imagesx( $img );
				$y = imagesy( $img );
				$size = getimagesize( $path );
	
				if( $width != null && $height != null )
				{
					$width_n_height = true;
				}
				else
				{
					$width_n_height = false;
				}
	
				if( $width != null || $height != null )
				{
					if( $width == null )
					{
						$width = $size[0];
					}
	
					if( $height == null )
					{
						$height = $size[1];
					}
	
					if( $width != $size[0] )
					{
						$ratio_x = $x / $width;
					}
					else
					{
						$ratio_x = 1;
					}
	
					if( $height != $size[1] )
					{
						$ratio_y = $y / $height;
					}
					else
					{
						$ratio_y = 1;
					}
	
					if( $ratio )
					{
						
						if( $width_n_height )
						{
							
							if( $ratio_y > $ratio_x )
							{
								$height = $y * ( $width / $x );
							}
							else
							{
								$width = $x * ( $height / $y );
							}
							
						}
						else
						{
							
							if( $ratio_y < $ratio_x )
							{
								$height = $y * ( $width / $x );
							}
							else
							{
								$width = $x * ( $height / $y );
							}
							
						}
						
					}
	
					$new_img = imagecreatetruecolor( $width, $height );
	
					if( $size[2] == IMAGETYPE_GIF || $size[2] == IMAGETYPE_PNG )
					{
						
						$index = imagecolortransparent( $img );
	
						if( $index >= 0)
						{
							
							$color = imagecolorsforindex( $img, $index );
							$index = imagecolorallocate( $new_img, $color['red'], $color['green'], $color['blue'] );
							imagefill( $new_img, 0, 0, $index );
							imagecolortransparent( $new_img, $index );
							
						}
						elseif( $size[2] == IMAGETYPE_PNG )
						{
							
							imagealphablending( $new_img, false );
							$color = imagecolorallocatealpha( $new_img, 0, 0, 0, 127 );
							imagefill( $new_img, 0, 0, $color );
							imagesavealpha( $new_img, true );
							
						}
					}
	
					imagecopyresampled( $new_img, $img, 0, 0, 0, 0, $width, $height, $x, $y );
					imagedestroy( $img );
					$img = $new_img;
					$changed = true;
				}
	
				if( $width == null )
				{
					$width = $x;
				}
	
				if( $height == null )
				{
					$height = $y;
				}
	
				if( $crop_width != null || $crop_height != null )
				{
					
					if( $crop_width == null )
					{
						$crop_width = $width;
					}
	
					if( $crop_height == null )
					{
						$crop_height = $height;
					}
	
					$new_img = imagecreatetruecolor( $crop_width, $crop_height );
	
					if( $size[2] == IMAGETYPE_GIF || $size[2] == IMAGETYPE_PNG)
					{
						
						$index = imagecolortransparent( $img );
	
						if( $index >= 0 )
						{
							$color = imagecolorsforindex($img, $index);
							$index = imagecolorallocate( $new_img, $color['red'], $color['green'], $color['blue'] );
							imagefill( $new_img, 0, 0, $index );
							imagecolortransparent( $new_img, $index );
						}
						elseif( $size[2] == IMAGETYPE_PNG )
						{
							imagealphablending( $new_img, false );
							$color = imagecolorallocatealpha( $new_img, 0, 0, 0, 127 );
							imagefill( $new_img, 0, 0, $color );
							imagesavealpha( $new_img, true );
						}
						
					}
	
					$x = ($width - $crop_width) / 2;
					$y = ($height - $crop_height) / 2;
					imagecopyresampled( $new_img, $img, 0, 0, $x, $y, $crop_width, $crop_height, $crop_width, $crop_height );
					imagedestroy( $img );
					$img = $new_img;
	
					$width = $crop_width;
					$height = $crop_height;
					$changed = true;
					
				}
	
				if( $output_path != null )
				{
					
					$path = explode( '.', $output_path );
					array_pop( $path );
					$path = implode( '.', $path ) . '.' . $ext;
					
				}
				else
				{
					
					$path = explode( '.', $path );
					array_pop( $path );
					$path = implode( '.', $path ) . (int)$width . 'x' . (int)$height . '.' . $ext;
					
				}
	
				//Make the images
	
				if( $ext == 'jpg' || $ext == 'jpeg' )
				{
					imagejpeg( $img, $path, 100 );
				}
				else if( $ext == 'png' )
				{
					imagepng( $img, $path );
				}
				else if( $ext == 'gif' )
				{
					imagegif( $img, $path );
				}
	
				return $path;
				
			}/* thumbnail() */
			
			/* ============================================================================ */
	
			/**
			 * Get the 'proper' image path to the thumbnail
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function flab_return_actual_img_path( $raw_thumb_url )
			{
			
				$url_data = parse_url( $raw_thumb_url );
	
				if( $url_data['host'] != $_SERVER['HTTP_HOST'] )
				{
					return $raw_thumb_url;
				}
	
				$raw_thumb_url = $raw_thumb_url;
	
				preg_match_all( '/( '.self::config( 'thumbnail_prefix' ).' )( \d+. )( x )( \d+. )( \.( jpg|jpeg|gif|png ) )/is', basename( $raw_thumb_url ), $matches );
	
				if( isset( $matches[0][0] ) && count( $matches[0][0] ) > 0 )
				{
					
					$uploads = wp_upload_dir();
					$base_url = str_replace( $matches[0][0], $matches[5][0], $raw_thumb_url );
					$base_path = self::config( 'upload_dir' ).'/'.basename( $base_url );
	
					if( file_exists( $base_path ) )
					{
						return $base_url;
					}
					
				}
	
				return $raw_thumb_url;
				
			}/* flab_return_actual_img_path() */
	
			/* ============================================================================ */
	
			/**
			 * Kinda sorta fiddled with several thumbnail scripts to get a small one together. Cheers Hoyt et. al
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function flab_return_img_thumb( $base_url, $width, $height )
			{
			
				$url_data = parse_url( $base_url );
	
				if( $url_data['host'] != $_SERVER['HTTP_HOST'] )
				{
					return $base_url;
				}
	
				$base_url = $base_url;
				$uploads = wp_upload_dir();
	
				$image = $base_url;
				$image_path = str_replace( $uploads['baseurl'], $uploads['basedir'], $image );
				$image_size = getimagesize( $image_path );
				$width = intval( $width );
				$height = intval( $height );
	
				if( $width == 0 || empty( $width ) )
				{
					$width = intval( $height * $image_size[0] / $image_size[1] );
				}
	
				if( $height == 0 || empty( $height ) )
				{
					$height = intval( $width * $image_size[1] / $image_size[0] );
				}
	
				if( $width == $image_size[0] && $height == $image_size[1] )
				{
					return $image;
				}
	
				preg_match_all( '/( '.self::config( 'thumbnail_prefix' ).' )( \d+. )( x )( \d+. )( \.( jpg|jpeg|gif|png ) )/is', basename( $image ), $matches );
	
				if( isset( $matches[0][0] ) && count( $matches[0][0] ) > 0 )
				{
					if( $width == $matches[2][0] && $height == $matches[4][0] )
					{
						return $image;
					}
					else
					{
						return self::flab_return_img_thumb( self::flab_return_actual_img_path( $base_url ), $width, $height );
					}
				}
	
				$pathinfo = pathinfo( $image_path );
				$ext = $pathinfo['extension'];
				$filename = basename( $image_path, '.'.$ext ).self::config( 'thumbnail_prefix' ).$width.'x'.$height.'.'.$ext;
	
				if( file_exists( self::config( 'upload_dir' ).'/'.$filename ) )
				{
					return self::config( 'upload_url' ).'/'.$filename;
				}
	
				if( !file_exists( self::config( 'upload_dir' ).'/'.basename( $image_path ) ) )
				{
					copy( $image_path, self::config( 'upload_dir' ).'/'.basename( $image_path ) );
				}
	
				return str_replace( $uploads['basedir'], $uploads['baseurl'], self::flab_thumb( $image_path, $width, $height, true, $width, $height, self::config( 'upload_dir' ).'/'.$filename ) );
				
			}/* flab_return_img_thumb() */
	
			/* ============================================================================ */
	
			public static function flab_create_field_layout( $name, $rules = array(), $data = null, $prefix = null )
			{
			
				if( $prefix === null )
				{
					$prefix = rtrim( self::config( 'prefix' ), '_') . '_';
				}
				else
				{
					if( $prefix != '' )
					{
						$prefix = rtrim( $prefix, '_' ) . '_';
					}
				}
	
				if( !isset( $rules['relation'] ) )
				{
					$rules['relation'] = 'option';
				}
	
				$value = '';
	
				if( $rules['type'] != 'submit' )
				{
					
					if( $data === null)
					{
						
						if( $rules['relation'] == 'option' )
						{
							$value = get_option( $prefix . $name );
						}
						else if( $rules['relation'] == 'meta' )
						{
							global $post;
	
							$value = get_post_meta( $post->ID, $prefix.$name, true );
						}
						
					}
					else
					{
						
						if( is_array( $data ) )
						{
							
							if( isset( $data[$prefix.str_replace( '[]', '', $name )] ) )
							{
								$value = $data[$prefix.str_replace( '[]', '', $name )];
							}
							else if( isset( $data[str_replace( '[]', '', $name )] ) )
							{
								$value = $data[str_replace( '[]', '', $name )];
							}
							
						}
						else
						{
							$value = $data;
						}
						
					}
	
					if( !empty($value))
					{
						$value = htmlspecialchars( stripslashes( $value ) );
					}
					
				}
	
				if( !isset( $rules['value'] ) )
				{
					$rules['value'] = '';
				}
	
				if( empty( $value ) && isset( $rules['use_default'] ) && $rules['use_default'] && $rules['type'] != 'checkbox' )
				{
					$value = htmlspecialchars ( stripslashes( $rules['value'] ) );
				}
				
				//Only hidden sorted at the moment
	
				if( $rules['type'] == 'hidden' )
				{
					
					return '<input type="hidden" name="' . $prefix.$name . '"' . ( isset( $rules['id'] ) ? ' id="' . $rules['id'] . '"' : '' ) . ( isset ( $rules['class'] ) ? ' class="' . $rules['class'] . '"' : '' ) . ' value="' . $value . '"' . ( isset( $rules['style'] ) ? ' style="' . $rules['style'] . '"' : '' ) . ' />';
					
				}
				
			}/* flab_create_field_layout() */
			
			/* ============================================================================ */
	
			/**
			 * Update the meta data for this field if it's sent
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function flab_update_meta_option_from_field( $data, $rules, $prefix = null )
			{
			
				if( $prefix === null )
				{
					$prefix = rtrim( self::config( 'prefix' ), '_' ).'_';
				}
				else
				{
				
					if( $prefix != '' )
					{
						$prefix = rtrim( $prefix, '_' ).'_';
					}
					
				}
	
				foreach( $rules as $rule => $fields )
				{
				
					foreach( $fields as $field )
					{
					
						if( !isset( $field['relation'] ) )
						{
							$field['relation'] = 'option';
						}
	
						if( isset( $data[$prefix.$field['name']] ) )
						{
						
							if( $field['relation'] == 'option' )
							{
								$current = get_option( $prefix.$field['name'] );
							}
							else if( $field['relation'] == 'meta' )
							{
								
								global $post;
	
								$current = get_post_meta( $post->ID, $prefix.$field['name'], true );
								
							}
	
							if( $data[$prefix.$field['name']] != $current )
							{
							
								if( $field['relation'] == 'option' )
								{
									update_option( $prefix.$field['name'], $data[$prefix.$field['name']] );
								}
								else if( $field['relation'] == 'meta' )
								{
									
									global $post;
	
									update_post_meta( $post->ID, $prefix.$field['name'], $data[$prefix.$field['name']] );
									
								}
								
							}
							else if( empty( $data[$prefix.$field['name']] ) && empty( $current ) )
							{
							
								if( $field['relation'] == 'option' )
								{
									update_option( $prefix.$field['name'], $field['value'] );
								}
								else if( $field['relation'] == 'meta' )
								{
								
									global $post;
	
									update_post_meta( $post->ID, $prefix.$field['name'], $field['value'] );
									
								}
								
							}
							
						}
						else
						{
						
							if( $field['relation'] == 'option' )
							{
								update_option( $prefix.$field['name'], $field['value'] );
							}
							else if( $field['relation'] == 'meta' )
							{
							
								global $post;
	
								update_post_meta( $post->ID, $prefix.$field['name'], $field['value'] );
								
							}
							
						}
						
					}
					
				}
	
				return false;
				
			}/* flab_update_meta_option_from_field() */
			
			/* ============================================================================ */
	
			/**
			 * Setup the actions with our functions
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function flab_admin_make_action()
			{

				add_action( 'admin_head', array( 'flab', 'flab_admin_head_action' ) );
	
				add_action( 'admin_print_scripts', array( 'flab', 'flab_admin_scripts_action' ) );
				add_action( 'admin_print_styles', array( 'flab', 'flab_admin_styles_action' ) );
	
				self::$group = ( isset( $_GET['page'] ) ? $_GET['page'] : '' );
	
				self::$group = preg_replace( '/'.self::slug( self::config( 'prefix' ) ).'/', '', self::$group, 1 );
	
				self::import( '!admin.flab.'.self::$group );
				self::$manage_setup = self::config( 'prefix' ).'panel_'.str_replace( '-', '_', self::$group );
	
				if( class_exists( self::$manage_setup ) )
				{
				
					$manage_setup = self::$manage_setup;
					call_user_func( array( $manage_setup, 'init' ) );
					
				}
	
				add_action( 'admin_menu', array( 'flab', 'flab_admin_create_meta_box_action' ) );
				
			}/* flab_admin_make_action() */
			
			/* ============================================================================ */
	
			/**
			 * Helper function for the javascript to give us access to a global flab object
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function flab_admin_head_action()
			{
				
				$jsconfig = '';
	
				foreach( self::$flab_configjs as $k => $v )
				{
				
					if( is_bool( $v ) )
					{
						$jsconfig .= ', '.$k.': '.( $v ? 'true' : 'false' );
					}
					else if( is_numeric( $v ) )
					{
						$jsconfig .= ', '.$k.': '.$v;
					}
					else
					{
						$jsconfig .= ', '.$k.': \''.$v.'\'';
					}
					
				}
	
				echo '<script type="text/javascript">var flab = { placeholder: { img: \''.flab::path( 'flab/assets/images/placeholder.png', true ).'\', video: \''.flab::path( 'flab/assets/images/placeholder-video.png', true ).'\', empty: \''.flab::path( 'flab/assets/images/placeholder-empty.png', true ).'\'}, path: \''.self::path( '/', true ).'\', templatepath: \''.get_template_directory_uri().'/\', stylesheetpath: \''.get_template_directory_uri().'/\', upload_preview: null, custom_insert: null, upload_callback: null, upload_caller: null, upload_target: null, prefix: \''.self::config( 'prefix' ).'\''.$jsconfig.' };</script>';
	
				if( isset( $_POST['reset'] ) )
				{
				
					if( class_exists( self::$manage_setup ) )
					{
						
						$manage_setup = self::$manage_setup;
						call_user_func( array( $manage_setup, 'reset' ) );
						
					}
					
				}
	
				if( isset( $_POST['save'] ) )
				{
					
					if( class_exists( self::$manage_setup ) )
					{
						
						$manage_setup = self::$manage_setup;
						call_user_func( array( $manage_setup, 'save' ) );
						
					}
					
				}

				if( class_exists( self::$manage_setup ) )
				{
					
					$manage_setup = self::$manage_setup;
	
					call_user_func( array( $manage_setup, 'header' ) );
					
				}
				
			}/* flab_admin_head_action() */
	
			/* ============================================================================ */
	
			/**
			 * Load the necessary javascript stuff
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function flab_admin_scripts_action()
			{
			
				self::load_script( array(
				
					array( 'slug' => 'jquery' ),
					array( 'slug' => 'thickbox' ),
					array( 'slug' => 'media-upload' ),
					array( 'slug' => 'farbtastic' ),
					array( 
						'path' => 'flab/assets/js/flab.js',
						'deps' => array( 'jquery', 'thickbox', 'media-upload', 'farbtastic' )
					 )
					 
				 ) );
				
			}/* flab_admin_scripts_action() */
	
			/* ============================================================================ */
	
			/**
			 * We need some Styles loaded, thank you please
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function flab_admin_styles_action()
			{
			
				self::load_stylesheet( array(
				
					array( 'slug' => 'thickbox' ),
					array( 'slug' => 'farbtastic' ),
					array( 'path' => 'flab/assets/css/flab.css' )
					
				 ) );
				 
			}/* flab_admin_styles_action() */
	
			/* ============================================================================ */
	
			/**
			 * Output the actual body of the meta box and handle all the possible dependencies and errors
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function flab_meta_box_body_action()
			{
			
				$output = '';
	
				$errors = array();
	
				foreach( self::$dependencies as $type => $dependencies )
				{
					
					foreach( $dependencies as $dependency )
					{
					
						$data = $dependency['requirment'];
						$message = $dependency['error'];
	
						if( $message == null )
						{
							$message = 'No. Heres why: '.$type.' '.( is_array( $data ) ? implode( ' ', $data ) : $data );
						}
	
						switch ( $type )
						{
						
							case 'class':
								if( !class_exists( $data ) )
								{
									$errors[] = $message;
								}
							break;
	
							case 'function':
								if( !function_exists( $data ) )
								{
									$errors[] = $message;
								}
							break;
	
							case 'directory':
								if( !is_dir( $data ) )
								{
									$errors[] = $message;
								}
							break;
	
							case 'writable':
								if( !is_writable( $data ) )
								{
									$errors[] = $message;
								}
							break;
	
							case 'readable':
								if( !is_readable( $data ) )
								{
									$errors[] = $message;
								}
							break;
	
							case 'file':
								if( !file_exists( $data ) )
								{
									$errors[] = $message;
								}
							break;
	
							case 'php':
								if( version_compare( phpversion(), $data, '<' ) )
								{
									$errors[] = $message;
								}
							break;
	
							case 'wp':
								global $wp_version;
	
								if( version_compare( $wp_version, $data, '<' ) )
								{
									$errors[] = $message;
								}
							break;
	
							case 'php_ini':
								if( isset( $data[0] ) && isset( $data[1] ) )
								{
									
									if( ini_get( $data[0] ) != $data[1] )
									{
										$errors[] = $message;
									}
									
								}
								else
								{
									$errors[] = $message;
								}
							break;
						}
						
					}
					
				}
	
				/**
				 * Errors Section
				 *
				 * @package FLAB
				 * @author iamfriendly
				 * @version 1.0
				 * @since 1.0
				 */
	
				if( count( $errors ) > 0 )
				{
					$output .= '<div id="message" class="error">';
				}
	
				foreach( $errors as $error )
				{
					$output .= '<p>'.$error.'</p>';
				}
	
				if( count( $errors ) > 0 )
				{
					$output .= '</div>';
				}
				
				/**
				 * Actual output
				 *
				 * @package FLAB
				 * @author iamfriendly
				 * @version 1.0
				 * @since 1.0
				 */
	
				$output .= '<div class="wrap"><form method="post" class="flab-form" enctype="multipart/form-data" encoding="multipart/form-data">';
	
				if( class_exists( self::$manage_setup ) )
				{
				
					$manage_setup = self::$manage_setup;
					$output .= call_user_func( array( $manage_setup, 'body' ) );
	
					if( call_user_func( array( $manage_setup, 'use_controls' ) ) )
					{
					
						$output .= '<fieldset><div class="buttonset-1"><button type="submit" class="button-1 button-1-1 alignright icon-save" name="save">'.flab::langr( 'Save' ).'</button><button type="submit" class="button-1 button-1-1 alignright icon-reset confirm" name="reset">'.flab::langr( 'Reset' ).'</button></div></fieldset>';
						
					}
					
				}
	
				$output .= '</form>';
				$output .= '</div>';
	
				echo $output;
				
			}/* flab_meta_box_body_action() */
	
			/* ============================================================================ */
	
			/**
			 * Set up the metaboxes
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function flab_create_admin_meta_box( $name, $options = array() )
			{
	
				$this_defaults = array( 
				
					'permissions' => array( 'post', 'page' ), 
					'context' => 'normal', 
					'priority' => 'high'
				
				);
	
				if( !array_key_exists( $name, self::$meta_boxes ) )
				{
					self::$meta_boxes[$name] = array_merge( $this_defaults, $options );
				}
				else
				{
				
					$merged_data = array_merge( $this_defaults, $options );
	
					self::$meta_boxes[$name]['permissions'] = array_merge( self::$meta_boxes[$name]['permissions'], $merged_data['permissions'] );
					self::$meta_boxes[$name]['context'] = self::$meta_boxes[$name]['context'];
					self::$meta_boxes[$name]['permissions'] = self::$meta_boxes[$name]['permissions'];
					
				}
				
			}/* flab_create_admin_meta_box() */
			
			/* ============================================================================ */
	
			/**
			 * Check and return our permissions
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function flab_get_admin_meta_box_perms( $meta_box_class )
			{
			
				return self::$meta_box_perms[$meta_box_class];
				
			}/* flab_get_admin_meta_box_perms() */
			
			
			/* ============================================================================ */
	
			/**
			 * Metabox function which allows us to add simple metaboxes very quickly. Not used just yet.
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function flab_admin_create_meta_box_action()
			{
				
				foreach( self::$meta_boxes as $metabox_name => $metabox_options )
				{
				
					/**
					 * Get the name of the class from the filename and change appropriately
					 *
					 * @package FLAB
					 * @author iamfriendly
					 * @version 1.0
					 * @since 1.0
					 */
				
					$metabox = self::slug( $metabox_name );
					$flab_meta_box_class = str_replace( '-', '_', $metabox );
					$flab_meta_box_class = self::config( 'prefix' ).'metabox_'.$flab_meta_box_class;
	
					if( !class_exists( $flab_meta_box_class ) )
					{
						self::import( '!admin.flab.metabox.'.$metabox );
					}
	
					if( class_exists( $flab_meta_box_class ) )
					{
					
						call_user_func_array( array( $flab_meta_box_class, 'set_class' ), array( $flab_meta_box_class ) );
						self::$meta_box_perms[$flab_meta_box_class] = $metabox_options['permissions'];
	
						call_user_func( array( $flab_meta_box_class, 'init' ) );
	
						$post_types = array_merge( array( 'post', 'page' ), array_keys( self::$wp_cpts ) );
	
						foreach( $metabox_options['permissions'] as $permission )
						{
							
							if( substr( $permission, 0, 3 ) != 'id:' && substr( $permission, 0, 9 ) != 'template:' )
							{
								
								add_meta_box( $metabox, isset( $metabox_options['title'] ) ? $metabox_options['title'] : $metabox_name, array( $flab_meta_box_class, 'action_body' ), $permission, $metabox_options['context'], $metabox_options['priority'] );
								
							}
							else
							{
							
								foreach( $post_types as $type )
								{
									
									add_meta_box( $metabox, isset( $metabox_options['title'] ) ? $metabox_options['title'] : $metabox_name, array( $flab_meta_box_class, 'action_body' ), $type, $metabox_options['context'], $metabox_options['priority'] );
									
								}
								
							}
							
						}
	
						/**
						 * And finally, we can hook into save and shove our scripts/styles in. And WE. ARE. DONE!
						 *
						 * @package FLAB
						 * @author iamfriendly
						 * @version 1.0
						 * @since 1.0
						 */
	
						add_action( 'save_post', array( $flab_meta_box_class, 'action_save' ) );
						add_action( 'admin_head', array( $flab_meta_box_class, 'action_header' ) );
	
						add_action( 'admin_print_scripts', array( $flab_meta_box_class, 'action_scripts' ) );
						add_action( 'admin_print_styles', array( $flab_meta_box_class, 'action_styles' ) );
						
					}
					
				}
				
			}/* flab_admin_create_meta_box_action() */

		}/* class flab_generic */
	
	}

	/* ================================================================================ */
	
	/**
	 * Import our other classes for widgets, layers, shortcodes, meta boxes ( FUTURE ) etc.
	 *
	 * @package FLAB
	 * @author iamfriendly
	 * @version 1.0
	 * @since 1.0
	 */
	 
	 /* ================================================================================ */

	include( 'flab_plugin_layout.php' );

	 /* ================================================================================ */

	include( 'flab_plugin.php' );

	 /* ================================================================================ */

	include( 'flab_widget_layout.php' );

	 /* ================================================================================ */

	include( 'flab_widget.php' );

	 /* ================================================================================ */

	include( 'flab_meta_box_layout.php' );

	 /* ================================================================================ */

	include( 'flab_meta_box.php' );

	 /* ================================================================================ */

	include( 'flab_images_tab_layout.php' );

	 /* ================================================================================ */

	include( 'flab_images_tab.php' );

	 /* ================================================================================ */

?>
