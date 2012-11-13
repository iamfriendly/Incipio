<?php

	/* ================================================================================= */

	/**
	 * Use our widget class to create a meta box layout. Don't use these at the moment, but might in the future
	 *
	 * @package FLAB
	 * @author iamfriendly
	 * @version 1.0
	 * @since 1.0
	 */

	if( !class_exists('flab_metabox_prototype') && class_exists('flab_plugin'))
	{
	
		class flab_metabox_prototype extends flab_plugin
		{
		
			/**
			 * Initialise the flab metabox
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
	
			/**
			 * Hook into the header action
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function action_header()
			{
				
				$class = self::get_class();
	
				if( call_user_func( array( $class, 'is_permitted' ) ) )
				{
					echo call_user_func( array( $class, 'header' ) );
				}
				
			}/* action_header() */
	
			/* ============================================================================ */
	
			/**
			 * Need something up top? Shortback and sides, perhaps
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function header()
			{
	
			}/* header() */
			
			/* ============================================================================ */
	
			/**
			 * Hook into the action for the body
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function action_body()
			{
				
				$class = self::get_class();
	
				if( call_user_func( array( $class, 'is_permitted' ) ) )
				{
					echo call_user_func( array( $class, 'body' ) );
				}
				
			}/* action_body() */
			
			/* ============================================================================ */
	
			/**
			 * Got anything into the body of the metabox/widget/shortcode/thing/doodad
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function body()
			{
	
			}/* body() */
			
			/* ============================================================================ */
	
			/**
			 * Hook into the scripts action
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function action_scripts()
			{
				
				$class = self::get_class();
	
				if( call_user_func( array( $class, 'is_permitted' ) ) )
				{
					call_user_func( array( $class, 'scripts' ) );
				}
				
			}/* action_scripts() */
			
			/* ============================================================================ */
	
			/**
			 * Need any scripts?
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function scripts()
			{
	
			}/* scripts() */
			
			/* ============================================================================ */
	
			/**
			 * Hook into the styles action
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function action_styles()
			{
				
				$class = self::get_class();
	
				if( call_user_func( array( $class, 'is_permitted' ) ) )
				{
					call_user_func( array( $class, 'styles' ) );
				}
				
			}/* action_styles() */
			
			/* ============================================================================ */
	
			/**
			 * Any styles to add?
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function styles()
			{
	
			}/* styles() */
			
			/* ============================================================================ */
	
			/**
			 * Need to reset?
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function reset()
			{
	
			}/* reset() */
			
			/* ============================================================================ */
	
			/**
			 * Hook into the save action
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function action_save()
			{
				
				$class = self::get_class();
	
				if( call_user_func( array( $class, 'is_permitted' ) ) )
				{
					call_user_func( array( $class, 'save' ) );
				}
				
			}/* action_save() */
			
			/* ============================================================================ */
	
			/**
			 * Save metabox func
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function save()
			{
	
			}/* save() */
			
			/* ============================================================================ */
	
			/**
			 * Are we allowed to see/do this?
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function is_permitted()
			{
			
				$class = self::get_class();
				$permissions = flab::flab_get_admin_meta_box_perms( $class );
	
				global $post;
	
				$permitted = FALSE;
	
				if( isset( $post ) )
				{
				
					foreach( $permissions as $permission )
					{
						
						if( $permission == $post->post_type )
						{
							$permitted = TRUE;
						}
						else if( substr( $permission, 0, 9 ) == 'template:' )
						{
						
							$template = substr( $permission, 9 );
	
							if(  $template == get_post_meta( $post->ID, '_wp_page_template', TRUE ) )
							{
								$permitted = TRUE;
							}
							
						}
						else if( substr( $permission, 0, 3 ) == 'id:' )
						{
						
							$id = substr( $permission, 3 );
	
							if( $id == $post->ID )
							{
								$permitted = TRUE;
							}
							
						}
						
					}
					
				}
	
				return $permitted;
				
			}/* is_permitted() */
			
			/* ============================================================================ */
	
			/**
			 * Some widgets require a setup action
			 *
			 * @package
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public static function __module()
			{
	
			}/* __module() */
			
			/* ============================================================================ */
			
		}
		
	}

?>