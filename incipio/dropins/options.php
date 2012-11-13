<?php

	/* ======================================================================================

	Our framework comes with a set of options which apply across all themes, but these options
	are specific to this theme. I have added 2 do_action() calls. One before all the example
	options are set and one after. You can add as many do_action() calls as you need in your 
	default theme options panel and then you'll be able to call a function like the one below 
	to add options to those sections (or indeed add entire sections)

	of_set_options_before_defaults

	of_set_options_in_basic_start
	of_set_options_in_basic_end

	of_set_options_in_home_end

	of_set_options_in_holding_page_end

	of_set_options_in_maintenance_page_end

	of_set_options_in_advanced_page_end

	of_set_options_in_help_end

	of_set_options_after_defaults


	Examples of option types can be found at the bottom of this file

	====================================================================================== */


	/**
	 * Our Help/FAQs are kept in a separate file, so let's load those in if the current theme supports it
	 *
	 * @author Richard Tape
	 * @package Incipio
	 * @since 1.0
	 */

	if( current_theme_supports( 'theme-options' ) && current_theme_supports( 'help-in-options-panel' ) )
		require_once locate_template( '/dropins/help.php' );
	





	/* Some examples */

	/*function external_options_example()
	{

		global $options;

		// External Heading ================================================

		$options[] = array(
			'name' => __('External', THEMENAME ),
			'type' => 'heading'
		);

		$options[] = array(
			'name' => __('External Input Text', THEMENAME ),
			'desc' => __('A text input field.', THEMENAME ),
			'id' => 'external_example_text',
			'std' => 'External Value',
			'type' => 'text'
		);

		// End External Heading ============================================

	}

	//add_action( 'of_set_options_after_defaults', 'external_options_example', 10, 1 );


	function external_options_in_basic_example()
	{

		global $options;

		$options[] = array(
			'name' => __('Put here externally', THEMENAME ),
			'desc' => __('A text input field.', THEMENAME ),
			'id' => 'external_example_text_in_basic',
			'std' => 'Basic External Value',
			'type' => 'text'
		);

	}

	//add_action( 'of_set_options_in_basic_start', 'external_options_in_basic_example', 10, 1 ); */



	/**
	 * Adding something to the install options checkboxes. The list of options is run through incipio_options_install_options
	 * and the default checked options are run through incipio_options_install_defaults. Both are arrays.
	 *
	 * @author Richard Tape
	 * @package Incipio
	 * @since 1.0
	 * @param (array) $theme_components_array - The existing theme components options
	 * @return (array) $theme_components_array - The modified options
	 */
	
	/*function incipio_add_to_install_options_checkboxes( $theme_components_array )
	{

		$theme_components_array['extra_theme_component'] = __( 'Added by the theme', THEMENAME );

		return $theme_components_array;

	}

	add_filter( 'incipio_options_install_options', 'incipio_add_to_install_options_checkboxes', 10, 1 );*/



	/**
	 * Make the option that we've just added - 'extra_theme_component' ticked by default on the install screen
	 * by filtering the defaults array
	 *
	 * @author Richard Tape
	 * @package Incipio
	 * @since 1.0
	 * @param (array) $theme_components_defaults - the existing defaults
	 * @return (array) $theme_components_defaults - the modified defaults
	 */

	/*function incipio_make_new_option_default_to_ticked( $theme_components_defaults )
	{

		$theme_components_defaults['extra_theme_component'] = '1';

		return $theme_components_defaults;

	}

	add_filter( 'incipio_options_install_defaults', 'incipio_make_new_option_default_to_ticked', 10, 1 ); */



	/*

	Examples of option types
	------------------------

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
	

?>