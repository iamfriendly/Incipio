<?php
	
	register_sidebar(
		array(
			'id' => 'primary',
			'name' => __( 'Primary', THEMENAME ),
			'description' => __( 'The primary sidebar used on pages and posts. By default on the Right Hand Side', THEMENAME ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widget-title"><span class="title">',
			'after_title' => '</span></h4>'
		)
	);
	
	/* ===========================================================================================

	Example Usage
	
	<?php dynamic_sidebar( 'primary' ); ?>

    =========================================================================================== */
	
?>