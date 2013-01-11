<?php

	/* ======================================================================================

	Loader for our custom sidebars

	====================================================================================== */
	
	$folder = "_sidebars/";
	$includes = array(
	
		/* Register primary first as WP has a habbit of dumping all widgets into the 1st sidebar on setup */
		'primary',

		'logo_bar',
		'main_menu'
		
		
	);
	
	
	foreach( $includes as $file_to_include )
	{

		if( file_exists( locate_template( '/dropins/' . $folder . $file_to_include . ".php", false  ) ) ) :
		
			include locate_template( '/dropins/' . $folder . $file_to_include . ".php" );
	
		endif;

	}
	
	/* ================================================================================ */

?>