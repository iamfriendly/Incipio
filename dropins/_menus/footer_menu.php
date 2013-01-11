<?php

	function ff_register_footer_menu()
	{
		
		register_nav_menu( 'footer-menu', __( 'Footer Menu', THEMENAME ) );
		
	}/* ff_register_footer_menu() */
	
	
	add_action( 'init', 'ff_register_footer_menu' );

	/* ===========================================================================================

	Example Usage
	
	<?php wp_nav_menu( array( 'theme_location' => 'footer-menu' ) ); ?>
	
	<?php
	
	$defaults = array(
		  
		'theme_location'	=> '',
		'menu'				=> '', 
		'container'			=> 'div', 
		'container_class'	=> 'menu-{menu slug}-container', 
		'container_id'		=> '',
		'menu_class'		=> 'menu', 
		'menu_id'			=> '',
		'echo'				=> true,
		'fallback_cb'		=> 'wp_page_menu',
		'before'				=> '',
		'after'				=> '',
		'link_before'		=> '',
		'link_after'			=> '',
		'items_wrap'		=> '<ul id=\"%1$s\" class=\"%2$s\">%3$s</ul>',
		'depth'				=> 0,
		'walker'				=> ''
		  
	);
		
	?>

    =========================================================================================== */
	
?>