<?php

	/* ======================================================================================

	A unique identifier is defined to store the options in the database and reference them from the theme.
 	By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 	If the identifier changes, it'll appear as if the options have been reset.

	====================================================================================== */

	function optionsframework_option_name()
	{

		// This gets the theme name from the stylesheet
		$themename = get_option( 'stylesheet' );
		$themename = preg_replace("/\W/", "_", strtolower($themename) );

		$optionsframework_settings = get_option( 'optionsframework' );
		$optionsframework_settings['id'] = $themename;
		update_option( 'optionsframework', $optionsframework_settings );

	}/* optionsframework_option_name() */


	/**
	 * Defines an array of options that will be used to generate the settings page and be saved in the database.
	 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
	 *
	 *
	 * @author Richard Tape
	 * @package Incipio
	 * @since 1.0
	 * @param none
	 * @return array $options - The array of default options
	 */
	

	function optionsframework_options()
	{

		// If using image radio buttons, define a directory path
		$imagepath =  get_template_directory_uri() . '/admin/images/';

		global $options;
		$options = array();

		//Allow us to hook into here from elsewhere (before the default options)
		do_action( 'of_set_options_before_defaults', $options );


		// The basic options tab ============================================================

		$options[] = array(
			'name' => __('Basic', THEMENAME ),
			'class' => '',
			'type' => 'heading'
		);

		//Allow us to hook into this tab externally
		do_action( 'of_set_options_in_basic_start', $options );

		$options[] = array(
			'id' => __( 'basic_info', THEMENAME ),
			'desc' => __('<p>These settings are the ones which we need to customise your theme at the most basic level. Feel free to leave all of the other tabs (unless of course you want to) but these settings will personalise your theme.</p>', THEMENAME ),
			'class' => 'highlight', //Blank, highlight, warning, vital
			'type' => 'info'
		);

		$options[] = array(
			'name' => __('Your logo', THEMENAME ),
			'desc' => __('Upload your logo. It will be resized automatically. Please only use .jpg, .png or .gif files.', THEMENAME ),
			'id' => 'main_logo',
			'type' => 'upload'
		);

		//If the WP SEO plugin is installed we don't need these. If that plugin is installed, then theme support
		//for incipio-seo is removed and the SEO options below won't show.
		if( current_theme_supports( 'incipio-seo' ) ) :

			$options[] = array(
				'name' => __('Google Analytics Tracking ID', THEMENAME ),
				'desc' => __('We just need the "UA" number from your google analytics account. If you have the WP SEO plugin installed you can do all of this from that plugin\'s option page instead (and, in fact this option will not show at all).', THEMENAME ),
				'id' => 'google_analytics_tracking_code',
				'std' => 'UA-1111111-11',
				'type' => 'text'
			);

		endif;

		//Allow us to hook into this tab externally
		do_action( 'of_set_options_in_basic_end', $options );


		// The home page options tab ========================================================

		$options[] = array(
			'name' => __('Home Page', THEMENAME ),
			'class' => '',
			'type' => 'heading'
		);

		//Allow us to hook into this tab externally
		do_action( 'of_set_options_in_home_end', $options );


		// The holding page =================================================================

		if( current_theme_supports( 'holding-page' ) ) :

			$options[] = array(
				'name' => __('Holding Page', THEMENAME ),
				'class' => '',
				'type' => 'heading'
			);

			do_action( 'of_set_options_in_holding_page_end', $options );

		endif;


		// The maintenance page =============================================================

		if( current_theme_supports( 'maintenance-page' ) ) :

			$options[] = array(
				'name' => __('Maintenance', THEMENAME ),
				'class' => '',
				'type' => 'heading'
			);

			do_action( 'of_set_options_in_maintenance_page_end', $options );

		endif;


		// The Help tab =====================================================================

		if( current_theme_supports( 'help-in-options-panel' ) ) :

			$options[] = array(
				'name' => __('Help', THEMENAME ),
				'class' => 'align-right',
				'type' => 'heading'
			);

			//Make a note at the top of the help page
			$options[] = array(
				'id' => __( 'help_info', THEMENAME ),
				'desc' => __('<p>Hi there! We\'re sorry that you\'re having problems with our theme. Firstly, thank you for looking for help. So many people just jump straight to e-mail and ask questions that are answered in our help documentation or here in the help tab. Secondly, if you don\'t find your answer here, please do re-check the extensive help documentation that came with this theme. If that doesn\'t work then jump on over to http://support.themeists.com/ and we\'ll see what we can do for you!</p><p>Below, you\'ll find a list of commonly asked questions, if you <strong>click on the title</strong> of the question, the answer will be shown.</p>', THEMENAME ),
				'class' => 'highlight', //Blank, highlight, warning, vital
				'type' => 'info'
			);

			//Note about importing the xml data
			$options[] = array(
				'name' => __('How do I get the dummy data you have on your demo?', THEMENAME ),
				'desc' => __('<p>In the package that you downloaded when you purchased this theme, you will see a folder called "dummy data". In there, you will see a .xml file. That file is to be used with the WordPress importer. If you are unsure how to do that, please follow the instructions at <a href="http://codex.wordpress.org/Importing_Content#WordPress" title="" target="_blank">http://codex.wordpress.org/Importing_Content#WordPress</a></p>', THEMENAME ),
				'id' => 'question_one',
				'std' => '',
				'type' => 'qna'
			);

			//Sidebar manager
			$options[] = array(
				'name' => __('How do I replace the sidebars on individual posts/pages?', THEMENAME ),
				'desc' => __('<p>In the bundle you downloaded when you got this theme you\'ll find a plugins folder. In there you will see a plugin called "themeists-custom-sidebars". <a href="http://codex.wordpress.org/Managing_Plugins#Installing_Plugins" title="Instrctions to install a plugin">Install</a> that plugin and then activate it. You will then see a new menu under Appearance>Custom Sidebars.</p><p>Please now see the sidebars section in the help documentation that came with this theme (or that particular plugin).</p>', THEMENAME ),
				'id' => 'question_two',
				'std' => '',
				'type' => 'qna'
			);

		endif;

		//Allow us to hook into this tab externally
		do_action( 'of_set_options_in_help_end', $options );


		// The advanced options =============================================================

		do_action( 'of_set_options_in_advanced_page_end', $options );


		// The Contact Page tab =============================================================

		$chosen_theme_components = of_get_option( 'theme_components' );
		if( is_array( $chosen_theme_components ) && array_key_exists( 'use_contact_form', $chosen_theme_components ) && $chosen_theme_components['use_contact_form'] == 1 ) :

			global $current_user;
      		get_currentuserinfo();

			$options[] = array(
				'name' => __('Contact Page', THEMENAME ),
				'class' => '',
				'type' => 'heading'
			);

			$options[] = array(
				'desc' => __( '<p>Your contact form is active. Please complete the following options. To insert your contact form into a page, place the shortcode [CONTACT_FORM] onto its own line in a page or post of your choice.</p>', THEMENAME ),
				'class' => 'highlight', //Blank, highlight, warning, vital
				'type' => 'info'
			);

			$options[] = array(
				'name' => __( 'E-mail address to send contact requests to', THEMENAME ),
				'desc' => __( 'When someone fills in and sends the form on your contact page, the address you put in this box will be sent the details', THEMENAME ),
				'id' => 'contact_form_email_address',
				'std' => $current_user->user_email,
				'type' => 'text'
			);

			$options[] = array(
				'name' => __( 'Success message', THEMENAME ),
				'desc' => __( 'When the form has been successfullly submitted the message you enter in this box will be shown', THEMENAME ),
				'id' => 'contact_form_success_message',
				'std' => __( 'Thank you for getting in touch. We\'ll get back to you as soon as possible.', THEMENAME ),
				'type' => 'textarea'
			);

			$options[] = array(
				'name' => __( 'Error message', THEMENAME ),
				'desc' => __( 'When the form has been unsuccessfullly submitted the message you enter in this box will be shown', THEMENAME ),
				'id' => 'contact_form_error_message',
				'std' => __( 'We couldn\'t send the details this time. Please try again.', THEMENAME ),
				'type' => 'textarea'
			);

			$options[] = array(
				'name' => __( 'Submit button text', THEMENAME ),
				'desc' => __( 'The "submit" button in the form will display this text', THEMENAME ),
				'id' => 'contact_form_submit_button_text',
				'std' => 'Submit',
				'type' => 'text'
			);

			//Allow us to hook into this tab externally
			do_action( 'of_set_options_in_contact_page_end', $options );		

		endif;


		// End default options ==============================================================

		//After the default options
		do_action( 'of_set_options_after_defaults', $options );

		return apply_filters( 'of_options_list', $options );

	}


	/* ======================================================================================

	Example for each of the different *standard* option types. Because the options panel is
	extendable, it's possible that other plugins have added other types, but these are the
	default ones that ship with the options panel

	====================================================================================== */

	/*

		Heading
		-------
		$options[] = array(
			'name' => __('On Right', THEMENAME ),
			'class' => 'align-right', //empty or align-right
			'type' => 'heading'
		);


		Text
		----
		$options[] = array(
			'name' => __('Input Text', THEMENAME ),
			'desc' => __('A text input field.', THEMENAME ),
			'id' => 'example_text',
			'std' => 'Default Value',
			'type' => 'text'
		);


		Textarea
		--------
		options[] = array(
			'name' => __('Textarea', THEMENAME ),
			'desc' => __('Textarea description.', THEMENAME ),
			'id' => 'example_textarea',
			'std' => 'Default Text',
			'type' => 'textarea'
		);


		Select
		------
		$test_array = array(
			'one' => __('One', THEMENAME ),
			'two' => __('Two', THEMENAME ),
			'three' => __('Three', THEMENAME ),
			'four' => __('Four', THEMENAME ),
			'five' => __('Five', THEMENAME )
		);

		$options[] = array(
			'name' => __('Input Select Small', THEMENAME ),
			'desc' => __('Small Select Box.', THEMENAME ),
			'id' => 'example_select',
			'std' => 'three',
			'type' => 'select',
			'class' => 'mini', //mini, tiny, small
			'options' => $test_array
		);

		$options[] = array(
			'name' => __('Input Select Wide', THEMENAME ),
			'desc' => __('A wider select box.', THEMENAME ),
			'id' => 'example_select_wide',
			'std' => 'two',
			'type' => 'select',
			'options' => $test_array
		);


		Radio
		-----
		$test_array = array(
			'one' => __('One', THEMENAME ),
			'two' => __('Two', THEMENAME ),
			'three' => __('Three', THEMENAME ),
			'four' => __('Four', THEMENAME ),
			'five' => __('Five', THEMENAME )
		);

		$options[] = array(
			'name' => __('Input Radio (one)', THEMENAME ),
			'desc' => __('Radio select with default options "one".', THEMENAME ),
			'id' => 'example_radio',
			'std' => 'one',
			'type' => 'radio',
			'options' => $test_array
		);

		
		Info
		----
		$options[] = array(
			'name' => __('Warning Info', THEMENAME ),
			'desc' => __('This is just some example information you can put in the panel.', THEMENAME ),
			'class' => 'highlight', //Blank, highlight, warning, vital
			'type' => 'info'
		);


		Checkbox
		--------
		$options[] = array(
			'name' => __('Input Checkbox', THEMENAME ),
			'desc' => __('Example checkbox, defaults to true.', THEMENAME ),
			'id' => 'example_checkbox',
			'std' => '1',
			'type' => 'checkbox'
		);


		Hidden Group
		------------
		$options[] = array(
			'name' => __('Check to Show a Hidden Text Input', THEMENAME ),
			'desc' => __('Click here and see what happens.', THEMENAME ),
			'id' => 'example_showhidden',
			'group' => 'example_group',
			'type' => 'checkbox'
		);

		$options[] = array(
			'name' => __('Hidden Text Input', THEMENAME ),
			'desc' => __('This option is hidden unless activated by a checkbox click.', THEMENAME ),
			'id' => 'example_text_hidden',
			'std' => 'Hello',
			'class' => 'showontick',
			'group' => 'example_group',
			'type' => 'text'
		);

		$options[] = array(
			'name' => __('Another Hidden Text Input', THEMENAME ),
			'desc' => __('This option is hidden unless activated by a checkbox click.', THEMENAME ),
			'id' => 'example_text_hidden_2',
			'std' => 'Hello',
			'class' => 'showontick',
			'group' => 'example_group',
			'type' => 'text'
		);

		
		Simple uploader
		---------------
		$options[] = array(
			'name' => __('Uploader Test', THEMENAME ),
			'desc' => __('This creates a full size uploader that previews the image.', THEMENAME ),
			'id' => 'example_uploader',
			'type' => 'upload'
		);


		Image Selector
		--------------
		$options[] = array(
			'name' => "Example Image Selector",
			'desc' => "Images for layout.",
			'id' => "example_images",
			'std' => "2c-l-fixed",
			'type' => "images",
			'options' => array(
				'1col-fixed' => $imagepath . '1col.png',
				'2c-l-fixed' => $imagepath . '2cl.png',
				'2c-r-fixed' => $imagepath . '2cr.png')
		);

		
		Background CSS
		--------------
		$options[] = array(
			'name' =>  __('Example Background', THEMENAME ),
			'desc' => __('Change the background CSS.', THEMENAME ),
			'id' => 'example_background',
			'std' => $background_defaults,
			'type' => 'background'
		);


		Multicheck
		----------
		$multicheck_array = array(
			'one' => __('French Toast', THEMENAME ),
			'two' => __('Pancake', THEMENAME ),
			'three' => __('Omelette', THEMENAME ),
			'four' => __('Crepe', THEMENAME ),
			'five' => __('Waffle', THEMENAME )
		);

		// Multicheck Defaults
		$multicheck_defaults = array(
			'one' => '1',
			'five' => '1'
		);

		$options[] = array(
			'name' => __('Multicheck', THEMENAME ),
			'desc' => __('Multicheck description.', THEMENAME ),
			'id' => 'example_multicheck',
			'std' => $multicheck_defaults, // These items get checked by default
			'type' => 'multicheck',
			'options' => $multicheck_array
		);


		Colour Picker
		-------------
		$options[] = array(
			'name' => __('Colorpicker', THEMENAME ),
			'desc' => __('No color selected by default.', THEMENAME ),
			'id' => 'example_colorpicker',
			'std' => '',
			'type' => 'color'
		);
		

		Typography (Default)
		--------------------
		$typography_defaults = array(
			'size' => '15px',
			'face' => 'georgia',
			'style' => 'bold',
			'color' => '#bada55'
		);

		// Typography Options
		$typography_options = array(
			'sizes' => array( '6','12','14','16','20' ),
			'faces' => array( 'Helvetica Neue' => 'Helvetica Neue','Arial' => 'Arial' ),
			'styles' => array( 'normal' => 'Normal','bold' => 'Bold' ),
			'color' => false
		);

		$options[] = array( 'name' => __('Typography', THEMENAME ),
			'desc' => __('Example typography.', THEMENAME ),
			'id' => "example_typography",
			'std' => $typography_defaults,
			'type' => 'typography'
		);
		

		Custom Typography
		-----------------
		$options[] = array(
			'name' => __('Custom Typography', THEMENAME ),
			'desc' => __('Custom typography options.', THEMENAME ),
			'id' => "custom_typography",
			'std' => $typography_defaults,
			'type' => 'typography',
			'options' => $typography_options
		);


		Default Text Editor
		-------------------
		$wp_editor_settings = array(
			'wpautop' => true, // Default
			'textarea_rows' => 5,
			'tinymce' => array( 'plugins' => 'wordpress' )
		);
		
		$options[] = array(
			'name' => __('Default Text Editor', THEMENAME ),
			'desc' => sprintf( __( 'Read more about wp_editor in <a href="%1$s" target="_blank">the WordPress codex</a>', THEMENAME  ), 'http://codex.wordpress.org/Function_Reference/wp_editor' ),
			'id' => 'example_editor',
			'type' => 'editor',
			'settings' => $wp_editor_settings
		);


	*/


	/**
	 * This is an example of how to add custom scripts to the options panel.
	 * This example shows/hides an option when a checkbox is clicked.
	 *
	 * @author Richard Tape
	 * @package Incipio
	 * @since 1.0
	 */
	

	/*function optionsframework_custom_scripts()
	{ 

		?>

		<script type="text/javascript">
		jQuery(document).ready(function($) {

			$('#example_showhidden').click(function() {
		  		$('#section-example_text_hidden').fadeToggle(400);
			});

			if ($('#example_showhidden:checked').val() !== undefined) {
				$('#section-example_text_hidden').show();
			}

		});
		</script>

		<?php

	}

	add_action( 'optionsframework_custom_scripts', 'optionsframework_custom_scripts' );*/



	/**
	 * We need to allow iframe tags in the info type. We do that by first removing the existing filters, then add
	 * our own one. This function removes and re-adds the filters
	 *
	 * @author Richard Tape
	 * @package Incipio
	 * @since 1.0
	 * @param None
	 * @return $output
	 */

	function incipio_allow_iframe_in_info_alter_filters()
	{

		remove_filter( 'of_sanitize_info', 'of_sanitize_allowedposttags' );
		add_filter( 'of_sanitize_info', 'incipio_sanitize_info_add_iframe' );

	}/* incipio_allow_iframe_in_info() */

	add_action( 'admin_init', 'incipio_allow_iframe_in_info_alter_filters', 100 );



	/**
	 * This is the function called by the newly added filter to allow us to add iframes in info options types
	 *
	 * @author Richard Tape
	 * @package 
	 * @since 1.0
	 * @param $input - the info we're sanitizing
	 * @return $output - the sanitized input
	 */
	
	function incipio_sanitize_info_add_iframe( $input )
	{

		global $allowedposttags;

		$custom_allowedposttags["iframe"] = array(
			"src" => array(),
			"width" => array(),
			"height" => array(),
			"frameborder" => array(),
			"webkitAllowFullScreen" => array(),
			"mozallowfullscreen" => array(),
			"allowFullScreen" => array()
		);

		$custom_allowedposttags = array_merge( $custom_allowedposttags, $allowedposttags );

		$output = wp_kses( $input, $custom_allowedposttags );

		return $output;

	}/* incipio_sanitize_info_add_iframe() */