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
		include( $folder . $file_to_include . ".php" );
	}
	
?>