<?php

	/* ================================================================================ */

	/**
	 * Add a new tab to the media upload. Called 'Images' which allows us to output the images in a nice grid fasion
	 *
	 * @package
	 * @author iamfriendly
	 * @version 1.0
	 * @since 1.0
	 */

	if( !class_exists( 'flab_add_images_tab') )
	{
		
		class flab_add_images_tab extends flab_add_images_tab_layout {}
	
		new flab_add_images_tab( 'images', __( 'Friendly Layout Builder Images', THEMENAME ), TRUE );
		
	}
	
	/* ================================================================================ */

?>