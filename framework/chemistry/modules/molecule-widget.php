<?php

	
	/**
	 * Our potions have several helper classes - multipurpose, abstracted classes which
	 * make registering them nice and easy. Mainly for sliders and columns. Let's load
	 * that class
	 *
	 * @author Richard Tape
	 * @package Chemistry
	 * @since 0.3
	 */
	
	require_once locate_template( '/framework/chemistry/modules/potion_helper_classes.php' );

	/* =================================================================================== */


	/**
	 * This is an array of the potions we would like to use. This is, by default, a full list of
	 * all of the potions we have in Chemistry. It's run through a filter, so themes can pick and
	 * choose exactly what they want to show.
	 *
	 * @author Richard Tape
	 * @package Chemistry
	 * @since 0.7
	 */

	$all_potions = array(

	    'chemistry_potion_column_1' => true,
	    'chemistry_potion_column_2' => true,
	    'chemistry_potion_column_3' => true,
	    'chemistry_potion_column_4' => true,
	    'chemistry_potion_column_5' => true,
	    'chemistry_potion_column_6' => true,
	    'chemistry_potion_column_two_thirds_one_third' => true,
	    'chemistry_potion_column_one_third_two_thirds' => true,
	    'chemistry_potion_column_three_quarters_one_quarter' => true,
	    'chemistry_potion_column_one_quarter_three_quarters' => true,
	    'chemistry_potion_column_one_half_one_quarter_one_quarter' => true,
	    'chemistry_potion_column_one_quarter_one_half_one_quarter' => true,
	    'chemistry_potion_column_one_quarter_one_quarter_one_half' => true,
	    
	    'chemistry_potion_plain_text' => true,
	    'chemistry_potion_single_image' => true,
	    'chemistry_potion_wysiwyg' => true,
	    'chemistry_potion_heading_tag' => true,
	    'chemistry_potion_message_box' => true,
	    'chemistry_potion_quote_and_cite' => true,
	    'chemistry_potion_custom_list' => true,
	    'chemistry_potion_button' => true,
	    'chemistry_potion_horizontal_rule' => true,
	    'chemistry_potion_custom_html' => true,
	    'chemistry_potion_video' => true,
	    'chemistry_potion_testimonial' => true,
	    'chemistry_potion_custom_gallery' => true,
	    'chemistry_potion_multipurpose_title_text_image' => true,
	    'chemistry_potion_price_table' => true,
	    'chemistry_potion_data_table' => true,
	    'chemistry_potion_jquery_tabs' => true,
	    'chemistry_potion_jquery_accordion' => true,
	    'chemistry_potion_twitter_feed' => true,
	    'chemistry_potion_nivo_slider' => true,
	    'chemistry_potion_roundabout_slider' => true,
	    'chemistry_potion_google_map' => true,
	    'chemistry_potion_simple_post_list' => true
	    
	);

	//Run it through a filter so we can amend elsewhere
	$potions_to_use = apply_filters( 'chemistry_potions', $all_potions );

	//Let's run an action so that we can plug in here (probably won't use this by default as columns should be first)
	do_action( 'chemistry_external_potions_load_before_default', $potions_to_use );

	//Run through each one
	foreach( $potions_to_use as $potion => $register )
	{

		//If we're using it
		if( $register === true )
		{

			//load the file with the class in it
			//split the file on 'chemistry_potion' if straight after that is 'column' then we load
			//from /columns/ otherwise we load from this directory
			$potion_pieces = explode( 'chemistry_potion_', $potion );

			//Load our files
			if( substr( $potion_pieces[1], 0, 6 ) == "column" )
				require_once locate_template( '/framework/chemistry/modules/columns/' . $potion_pieces[1] . '.php' );
			else
				require_once locate_template( '/framework/chemistry/modules/' . $potion_pieces[1] . '.php' );


			//we're loaded, so instantiate
			chemistry_molecule::use_potion( $potion );

		}

	}

	//Let's run an action so that we can plug in here
	do_action( 'chemistry_external_potions', $potions_to_use );

?>