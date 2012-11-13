<?php

	/* ================================================================================ */
	
	/**
	 * Instantiate each flab plugin
	 *
	 * @package FLAB
	 * @author iamfriendly
	 * @version 1.0
	 * @since 1.0
	 */
	
	if ( !class_exists( 'flab_plugin' ) )
	{
		class flab_plugin extends flab_plugin_layout {}
	}
	
	/* ================================================================================ */

?>