<?php
	
	/* ======================================================================================

	Loader for our custom menus

	====================================================================================== */
	
	$folder = "_menus/";
	$includes = array(
		'main_menu'
	);
	
	foreach( $includes as $file_to_include )
	{

		if( file_exists( locate_template( '/dropins/' . $folder . $file_to_include . ".php", false  ) ) ) :
		
			include locate_template( '/dropins/' . $folder . $file_to_include . ".php" );
	
		endif;

	}
	
?>