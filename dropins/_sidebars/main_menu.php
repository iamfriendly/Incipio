<?php
	
	register_sidebar(
		array(
			'id' => 'main_menu',
			'name' => __( 'Main Menu Row', THEMENAME ),
			'description' => __( 'Your main menu sits below the logo bar', THEMENAME ),
			'before_widget' => '<div id="%1$s" class="columns widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widget-title"><span class="title">',
			'after_title' => '</span></h4>'
		)
	);
	
	/* ===========================================================================================

	Example Usage
	
	<?php dynamic_sidebar( 'main_menu' ); ?>

    =========================================================================================== */
	
?>