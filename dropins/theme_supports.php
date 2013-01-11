<?php

	/* ======================================================================================

	This file determines what this theme supports by calling add_theme_support() for the
	different elements of WordPress and our framework. It can be overridden in a child theme
	or doesn't need to exists at all, in which case we use the defaults in the main Incipio
	class in functions.php.

	====================================================================================== */

	add_theme_support( 'theme-options' );
	add_theme_support( 'layout-builder' );

	//This is immediately removed if the WP SEO plugin is installed (see loader.php)
	add_theme_support( 'incipio-seo' );

	//We register theme support for post types. These are the ones that are shown by the themeistsposttype
	//plugin when that is activated
	add_theme_support( 'custom-post-types', array( 'project' ) );

	//Also add support for taxonomies, similar to above
	add_theme_support( 'custom-taxonomies', array( 'project' => array( 'clients', 'project-type' ) ) );

	add_theme_support( 'post-formats', array( 'aside', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat', 'gallery' ) );
	add_theme_support( 'post-thumbnails', array( 'post', 'page', 'project' ) );
	add_theme_support( 'automatic-feed-links' );

	add_theme_support( 'help-in-options-panel' );

	add_theme_support( 
	    'custom-widgets', 
      	array(
      		'call-to-action-row', 
      		'image-widget', 
      		'latest-blog-posts', 
      		'quick-flickr-widget',
      		'mailchimp-widget',
      		'simple-twitter-widget',
      		'dribbble'
      	)
	);

	add_theme_support( 
		'chemistry-potions',
		array(

			'chemistry_potion_column_1',
		    'chemistry_potion_column_2',
		    'chemistry_potion_column_3',
		    'chemistry_potion_column_4',
		    'chemistry_potion_column_5',
		    'chemistry_potion_column_6',
		    'chemistry_potion_column_two_thirds_one_third',
		    'chemistry_potion_column_one_third_two_thirds',
		    'chemistry_potion_column_three_quarters_one_quarter',
		    'chemistry_potion_column_one_quarter_three_quarters',
		    'chemistry_potion_column_one_half_one_quarter_one_quarter',
		    'chemistry_potion_column_one_quarter_one_half_one_quarter',
		    'chemistry_potion_column_one_quarter_one_quarter_one_half',
		    
		    'chemistry_potion_plain_text',
		    'chemistry_potion_single_image',
		    'chemistry_potion_wysiwyg',
		    'chemistry_potion_heading_tag',
		    'chemistry_potion_message_box',
		    'chemistry_potion_quote_and_cite',
		    'chemistry_potion_custom_list',
		    'chemistry_potion_button',
		    'chemistry_potion_horizontal_rule',
		    'chemistry_potion_custom_html',
		    'chemistry_potion_video',
		    'chemistry_potion_testimonial',
		    'chemistry_potion_custom_gallery',
		    'chemistry_potion_multipurpose_title_text_image',
		    'chemistry_potion_price_table',
		    'chemistry_potion_data_table',
		    'chemistry_potion_jquery_tabs',
		    'chemistry_potion_jquery_accordion',
		    'chemistry_potion_twitter_feed',
		    'chemistry_potion_nivo_slider',
		    'chemistry_potion_roundabout_slider',
		    'chemistry_potion_google_map',
		    'chemistry_potion_simple_post_list'

		)
	);

	

?>