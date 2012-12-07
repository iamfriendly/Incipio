<?php

	/* ======================================================================================

	Each potion is an extension of the abstract class and, at the moment, the metabox 
	abstract class is an extension of this, too. In the future we'll be moving away from this
	to use the CMB metabox class. Abstraction is the key, here, we're able to easily extend
	based on our separate abstract classes.

	====================================================================================== */

	if( !class_exists( 'chemistry_potion' ) )
	{

		class chemistry_potion extends chemistry_potion_abstract_class{}

	}/* !class_exists( 'chemistry_potion' ) */

?>