<?php

	/* ======================================================================================

	This is the 'main' sidebar template file. It outputs the primary sidebar. Our Themeists
	Sidebar Plugin allows you to replace any sidebar with any other, which is rather spiffing.
	However, if people aren't using that plugin then they just get the 'primary' sidebar here.

	====================================================================================== */

?>

<?php do_action( 'incipio_above_main_sidebar' ); ?>


<?php do_action( 'incipio_open_main_sidebar_aside_tag' ); ?>

	<?php do_action( 'incipio_inside_main_sidebar' ); ?>

<?php do_action( 'incipio_close_main_sidebar_aside_tag' ); ?>


<?php do_action( 'incipio_below_main_sidebar' ); ?>