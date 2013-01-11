<?php
	
	register_sidebar(
		array(
			'id' => 'logo_bar',
			'name' => __( 'Logo Row', THEMENAME ),
			'description' => __( 'The logo bar sits at the top of your page. We use it for the logo and Social Sharing icons', THEMENAME ),
			'before_widget' => '<div id="%1$s" class="columns widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widget-title"><span class="title">',
			'after_title' => '</span></h4>'
		)
	);
	
	/* ===========================================================================================

	Example Usage
	
	<?php dynamic_sidebar( 'logo_bar' ); ?>

    =========================================================================================== */
	
?>