<?php

/* ================================================================================ */

	/**
	 * If we're initialised and WP itself hasn't been loaded, load it and set up some defaults and intercept requests
	 *
	 * @package FLAB
	 * @author iamfriendly
	 * @version 1.0
	 * @since 1.0
	 */
	 
	if( !function_exists( 'flab_trigger_if_no_wp' ) ) :
	 
		function flab_trigger_if_no_wp()
		{

			if( basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) && !defined( 'ABSPATH' ) )
			{
			
				require_once( $flab['wp_root'] . 'wp-load.php' );
				
				//Intercept server requests
				if( !empty( $_GET ) )
				{
					flab_generic::fire_action_on_submit( 'flab.get', array( $_GET ) );
				}
				
				if( !empty( $_POST ) )
				{
					flab_generic::fire_action_on_submit( 'flab.post', array($_POST) );
				}
				
				if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )
				{
					flab_generic::fire_action_on_submit( 'flab.ajax', array( $_GET ) );
				}
				
				exit;
			
			}
		
		}/* flab_trigger_if_no_wp() */
		
	endif;

/* ================================================================================ */

	/**
	 * Add the necessary filters and actions to we can actually do what we need to do
	 *
	 * @package FLAB
	 * @author iamfriendly
	 * @version 1.0
	 * @since 1.0
	 */

	if( !function_exists( 'flab_add_filters_and_actions' ) ) :

		function flab_add_filters_and_actions()
		{
		
			add_filter( 'the_editor', array( 'flab_builder', 'editor_tab' ), 99 );
			add_filter( 'the_content', array( 'flab_builder', 'editor_content' ), 999 );
			add_filter( 'posts_where_request', array( 'flab_builder', 'builder_search' ) );
			add_filter( 'posts_join_request', array( 'flab_builder', 'builder_search_join' ) );
			add_action( 'template_redirect', array( 'flab_builder', 'builder_setup' ), 99 );
			add_action( 'admin_head', array( 'flab_builder', 'header' ) );
			add_action( 'flab_setup', array( 'flab_builder', 'builder_sidebar_widgets_init' ), 1 );
			add_action( 'flab_setup', array( 'flab_builder', 'widgets_init' ), 2 );
			add_action( 'flab_setup', array( 'flab_builder', 'sidebar_builder_widgets_init' ), 3 );
		
		}/* flab_add_filters_and_actions() */
		
	endif;

/* ================================================================================ */

	/**
		 * Setup our builder on all relevant post types
		 *
		 * @package FLAB
		 * @author iamfriendly
		 * @version 1.0
		 * @since 1.0
		 */

	if( !function_exists( 'flab_builder_init' ) )
	{
	
		function flab_builder_init()
		{
		
			$built_in_wp_post_types = array( 'post', 'page' );
			$custom_post_types = array_keys( get_post_types( array( '_builtin' => FALSE) ) );
	
			$builder_post_types = array_merge( $built_in_wp_post_types, $custom_post_types );
			$dont_show_flab_on_these_cpts = flab::option( 'dont_show_flab_on_these_cpts' );
	
			if( isset( $dont_show_flab_on_these_cpts[0]) && !empty( $dont_show_flab_on_these_cpts[0] ) )
			{
			
				$dont_show_flab_on_these_cpts = $dont_show_flab_on_these_cpts[0];
				$builder_post_types = array_diff( $builder_post_types, array_keys( $dont_show_flab_on_these_cpts ) );
				
			}
	
			flab::flab_create_admin_meta_box( 'Builder post', array( 'title' => flab::langx( 'ERGIENGOI', 'metabox name', TRUE), 'permissions' => $builder_post_types, 'context' => 'side' ) );
			
		}
	
		add_action( 'flab_setup', 'flab_builder_init' );
		
	}
	
/* ================================================================================ */
	
	if ( !function_exists( 'flab_load_js' ) )
	{
		
		/**
		 * Load our JS
		 *
		 * @package FLAB
		 * @author iamfriendly
		 * @version 1.0
		 * @since 1.0
		 */
		
		function flab_load_js()
		{
			
			$style_dir = get_template_directory_uri();
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'overlay',  $style_dir . '/flab/assets/js/jquery.colorbox.js', 'jquery', '', true );
			wp_enqueue_script( 'flab',  $style_dir.'/flab/assets/js/flab_builder.js', 'jquery', '', true );
			
		}/* flab_builder_header() */
	
		add_action( 'wp_enqueue_scripts', 'flab_load_js' );
		
	}
	
/* ================================================================================ */

	if ( !function_exists( 'flab_load_css' ) )
	{
		
		/**
		 * Load our CSS
		 *
		 * @package FLAB
		 * @author iamfriendly
		 * @version 1.0
		 * @since 1.0
		 */
		
		function flab_load_css()
		{
			
			$style_dir = get_template_directory_uri();
			
			wp_enqueue_style( 'flab-builder', $style_dir . '/flab/assets/css/flab.css', '', '', '' );
			
		}/* flab_builder_header() */
	
		add_action( 'wp_enqueue_scripts', 'flab_load_css' );
		
	}

/* ================================================================================ */

?>