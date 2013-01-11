<?php

	/* ======================================================================================

	Loader for our custom sidebars

	====================================================================================== */
	
	$folder = "_sidebars/";
	$includes = array(
		
		'primary'
		
	);
	
	
	foreach( $includes as $file_to_include )
	{

		if( file_exists( locate_template( '/dropins/' . $folder . $file_to_include . ".php", false  ) ) ) :
		
			include locate_template( '/dropins/' . $folder . $file_to_include . ".php" );
	
		endif;

	}
	
	/* ================================================================================ */

?>