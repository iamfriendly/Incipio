<?php

	/* ================================================================================ */
	
	if( !class_exists( 'flab_post_content_widget' ) )
	{
	
		class flab_post_content_widget extends flab_builder_widget
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
				
				parent::__construct( 'post_content', __( 'Content of Post' ) );
				$this->label = __( 'This will include all of your post\'s content in this row' );
				
			}/* __construct */
			
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
				
				global $post;
	
				return "<div class='row display'>" . apply_filters( 'the_content', $post->post_content ) . "</div>";
				
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
				
				return '<p>'.__( 'This module will display content from your post editor.' ).'</p>';
				
			}/* form() */
			
			/* ============================================================================ */
			
		}/* class flab_post_content_widget */
		
	}
	
	/* ================================================================================ */

?>