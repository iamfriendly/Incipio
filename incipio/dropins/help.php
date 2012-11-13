<?php

	/* ======================================================================================

	We have a 'help' tab in the options panel which can contain FAQs, notes, videos etc. As
	there may be quite a lot, we'll keep them separate. This file is only loaded if the 
	current theme supports the options panel and help in the options panel.

	of_set_options_before_defaults

	of_set_options_in_basic_start
	of_set_options_in_basic_end

	of_set_options_in_home_end

	of_set_options_in_holding_page_end

	of_set_options_in_maintenance_page_end

	of_set_options_in_advanced_page_end

	of_set_options_in_help_end

	of_set_options_after_defaults

	Don't forget to globalise $options first

	====================================================================================== */

	/*	
	function incipio_example_in_help_options()
	{

		global $options;

		// PSD Files ============================================

		$options[] = array(
			'name' => __( 'Added from /dropins/help.php', THEMENAME ),
			'desc' => __( '<p>This help thing has been added by the theme - you can find it in /dropins/help.php</p>', THEMENAME ),
			'id' => 'theme_question_one',
			'std' => '',
			'type' => 'qna'
		);

		// End PSD Files ============================================

	}

	add_action( 'of_set_options_in_help_end', 'incipio_example_in_help_options', 10, 1 );
	*/

?>