<?php

	/* ======================================================================================

	Create a holding class for our metaboxes which is an extension of our metabox abstract 
	class. This class used to be used a lot more but we are transitioning towards using the 
	CMB class we use for the rest of our theme. This class, and file, will soon be moved 
	out of Chemistry.

	====================================================================================== */

	if( !class_exists( 'chemistry_metabox' ) )
	{

		class chemistry_metabox extends chemistry_metabox_abstract_class{}

	}/* !class_exists( 'chemistry_metabox' ) */

?>