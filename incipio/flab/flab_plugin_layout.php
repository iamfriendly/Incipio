<?php

	/* ================================================================================ */

	if ( !class_exists( 'flab_plugin_layout' ) )
	{
	
		/**
		 * Basic layout for extra plugins - based on the WP Widgets class with extensibility in mind
		 *
		 * @package FLAB
		 * @author iamfriendly
		 * @version 1.0
		 * @since 1.0
		 */
	
		class flab_plugin_layout
		{
		
			public static $flab_widget_class = 'flab_plugin';
			public static $flab_widget_actions = array(
				'init',
				'wp_head',
				'wp_print_scripts',
				'wp_print_styles',
				'wp_footer',
				'admin_init',
				'admin_print_scripts',
				'admin_print_styles',
				'admin_footer'
			);
			
			/**
			 * Gets the name of the class the static method is called in. 5.3 has built-in function get_called_class()
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function get_class()
			{
			
				if (function_exists( 'get_called_class' ) )
				{
					return get_called_class();
				}
				else
				{
				
					$objects = array();
					$traces = debug_backtrace();
	
					foreach( $traces as $trace )
					{
					
						if( isset( $trace['function'] ) && substr( $trace['function'], 0, 14 ) == 'call_user_func' && isset( $trace['args'][0][0] ) )
						{
							return $trace['args'][0][0];
						}
						else if( isset( $trace['object'] ) )
						{
							
							if( is_object( $trace['object'] ) )
							{
								$objects[] = $trace['object'];
							}
						}
						
					}
	
					if( count( $objects ) )
					{
						return get_class( $objects[0] );
					}
					
				}
				
			}
			
			/* ============================================================================ */
	
			/**
			 * Set the class name to the one used in the new plugin
			 *
			 * long desc
			 * @package
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function set_class( $flab_widget_class )
			{
				self::$flab_widget_class = $flab_widget_class;
			}
			
			/* ============================================================================ */
	
			/**
			 * Set up the functions associated with the actions for this flab widget
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function __module()
			{
				
				foreach( self::$flab_widget_actions as $action )
				{
					
					if( substr( $action, 0, 3 ) != 'wp_' )
					{
					
						if( method_exists( self::$flab_widget_class, 'wp_'.$action ) )
						{
							add_action( $action, array( self::$flab_widget_class, 'wp_'.$action ), 99 );
						}
						
					}
					else
					{
						
						if ( method_exists( self::$flab_widget_class, $action ) )
						{
							add_action( $action, array( self::$flab_widget_class, $action ), 99 );
						}
						
					}
					
				}
				
			}/* __module() */
			
			/* ============================================================================ */
	
			/**
			 * Set up the functions which we can use in the widgets to add our scripts and styles (and perform actions)
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function init()
			{
	
			}/* init() */
			
			/* ============================================================================ */
	
			public static function wp_init()
			{
	
			}/* wp_init() */
			
			/* ============================================================================ */
	
			public static function wp_head()
			{
	
			}/* wp_head() */
			
			/* ============================================================================ */
	
			public static function wp_admin_init()
			{
	
			}/* wp_admin_init() */
			
			/* ============================================================================ */
	
			public static function wp_admin_head()
			{
	
			}/* wp_admin_head() */
			
			/* ============================================================================ */
	
			public static function wp_print_scripts()
			{
	
			}/* wp_print_scripts() */
			
			/* ============================================================================ */
	
			public static function wp_print_styles()
			{
	
			}/* wp_print_styles() */
			
			/* ============================================================================ */
	
			public static function wp_admin_print_scripts()
			{
	
			}/* wp_admin_print_scripts() */
			
			/* ============================================================================ */
	
			public static function wp_admin_print_styles()
			{
	
			}/* wp_admin_print_styles() */
			
			/* ============================================================================ */
	
			public static function wp_admin_footer()
			{
	
			}/* wp_admin_footer() */
			
			/* ============================================================================ */
	
			public static function wp_footer()
			{
	
			}/* wp_footer() */
			
			/* ============================================================================ */
			
		}/* class flab_plugin_layout() */
		
	}

	/* ================================================================================ */

?>