	<?php

		/* ======================================================================================

		This file is used on every page load of your theme (when someone is visiting the front-end).
		It closes our body tag and any container we have. It also loads the javascript that is 
		targeted at the footer.

		====================================================================================== */

	?>

	<?php do_action( 'incipio_before_main_footer_tag' ); ?>

	<?php do_action( 'incipio_start_main_footer_tag' ); ?>

		<?php do_action( 'incipio_inside_main_footer_tag' ); ?>

	<?php wp_footer(); ?>
	
	<!--[if lt IE 7 ]>
		<script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
	<![endif]-->

</body>

</html>