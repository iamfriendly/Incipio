<?php

	/* ======================================================================================

	Create our new overlay tab by extending our image tab abstract class and then creating a 
	new instance of it. This one doesn't require any amendments to do the default as it is
	it basically what the abstract class was written for, however in the future, if we 
	decide to add more overlay tabs then we'd be able to extend it further.

	====================================================================================== */


	if( !class_exists( 'chemistry_images_tab' ) )
	{

		class chemistry_images_tab extends chemistry_image_tab_abstract_class{}

		new chemistry_images_tab( 'images', __( 'Images' ), true );

	}/* !class_exists( 'chemistry_images_tab' ) */

?>