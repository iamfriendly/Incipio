<?php

	if( !class_exists( 'chemistry_potion_simple_post_list' ) )
	{

		class chemistry_potion_simple_post_list extends chemistry_posts_feed_widget
		{

			/**
			 * Register this potion. Post lists are easy, they're just passed off to the post feed method
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param None
			 * @return None
			 */
			
			public function __construct()
			{

				parent::__construct( 'post-feed', __( 'Post feed' , 'chemistry' ) );
				$this->label = __( 'Add related or recent posts from blog feed' , 'chemistry' );
				$this->post_type = 'post';
				$this->post_taxonomy = 'category';

			}/* __construct() */

		}/* class chemistry_potion_simple_post_list */

	}/* !class_exists( 'chemistry_potion_simple_post_list' ) */

?>