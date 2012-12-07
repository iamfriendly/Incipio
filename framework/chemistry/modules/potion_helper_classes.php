<?php

	if( !class_exists( 'chemistry_slider_ready_widget' ) )
	{

		class chemistry_slider_ready_widget extends chemistry_molecule_widget
		{
			
			/**
			 * Get the data for our slides
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (array) $widget - Widget config
			 * @param (string) $post_type - which post type are we outputting?
			 * @param (array) $meta - Any meta-specific searching going on?
			 * @param (string) $select_by - time/date
			 * @return $posts - array of posts
			 */
			
			protected function retrieve_posts( $widget, $post_type, $meta = array(), $select_by = '' )
			{

				//Compare passed options to our defaults
				$widget = chemistry_molecule_widget::extend( array( 

					'select' => '',
					'taxonomy' => '',
					'term' => ''

				 ), $widget );

				//Build our query_posts args
				$args = array( 'post_type' => $post_type, 'order' => $widget['order'], 'orderby' => $widget['orderby'], 'numberposts' => $widget['numberposts'], 'meta' => $meta );

				//if we want a post or a page, have an option of an exerpt
				if( $post_type == 'post' || $post_type == 'page' )
					$args['text_opt'] = 'excerpt';

				//Any tax-specific stuff?
				if( !empty( $widget['taxonomy'] ) && !empty( $widget['term'] ) )
				{

					$args['tax_query'] = array( 

						array( 

							'taxonomy' => $widget['taxonomy'],
							'field' => 'slug',
							'terms' => array( $widget['term'] )

						 )

					 );

				}

				//Start afresh
				$posts = array();

				if( $widget['select'] == 'related' && is_singular( $post_type ) )
				{

					$taxonomy = array( 'category', 'post_tag' );

					if( $post_type != 'post' && !empty( $widget['taxonomy'] ) )
						$taxonomy = $widget['taxonomy'];

					$posts = Chemistry::retrieve_related_posts( $args, $taxonomy );

				}

				if( empty( $posts ) )
					$posts = Chemistry::retrieve_posts( $args );

				return $posts;

			}/* retrieve_posts() */


			/* =========================================================================== */
			
			/**
			 * Build the different classes for our slider
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.3
			 * @param (array) $widget - Widget config
			 * @return (array) $classes
			 */
			
			protected function get_classes( $widget )
			{

				//Compare passed options to our defaults
				$widget = chemistry_molecule_widget::extend( array( 

					'slider' => '',
					'autoplay' => '',
					'autoplay_invert' => '',
					'autoplay_interval' => '',
					'scroll' => '',
					'transition' => '',
					'grid_height' => '',
					'navigation' => '',

				 ), $widget );

				//Build our classes as an array
				$classes = array();

				//Always a grid
				$classes[] = 'grid';

				//Are we a slider?
				if( $widget['slider'] == 'on' )
				{

					$classes[] = 'slider';

					//With autoplay?
					if( $widget['autoplay'] == 'on' )
					{

						$classes[] = 'autoplay-1';
						$classes[] = 'autoplay-interval-' . $widget['autoplay_interval'];

						if( $widget['autoplay_invert'] == 'on' )
							$classes[] = 'autoplay-invert-1';

					}
					else
					{

						$classes[] = 'non-autoplay';

					}

					//Are we scrolling? which direction?
					if( !empty( $widget['scroll'] ) )
					{

						$classes[] = 'scroll-axis-' . $widget['scroll'];

					}

					//What style of transition?
					if( !empty( $widget['transition'] ) )
					{

						$classes[] = 'transition-' . $widget['transition'];

					}

					//Have we set a specific height?
					if( !empty( $widget['grid_height'] ) )
					{

						$classes[] = 'grid-height-' . $widget['grid_height'];

					}

					//Are we showing our arrows for nav?
					if( $widget['navigation'] == 1 )
					{

						$classes[] = 'ctrl-arrows-1';

					}
					else if( $widget['navigation'] == 2 )
					{

						//Or our pagination?
						$classes[] = 'ctrl-pag-1';

					}
					else if( $widget['navigation'] == 3 )
					{

						//Or both! Greedy
						$classes[] = 'ctrl-pag-1';
						$classes[] = 'ctrl-arrows-1';

					}

				}

				//Send them!
				return $classes;

			}/* get_classes() */


			/* =========================================================================== */
			
			/**
			 * The markup for our sliders
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (array) $widget - Widget config
			 * @return Markup
			 */
			
			protected function form_slider( $widget )
			{

				//What sort of transition?
				$transition = array( 

					'slide' => __( 'Slide' , 'chemistry' ),
					'slideIn' => __( 'Slide in' , 'chemistry' ),
					'slideOut' => __( 'Slide out' , 'chemistry' ),
					'switch' => __( 'Switch' , 'chemistry' ),
					'random' => __( 'Random' , 'chemistry' )

				 );

				//If we have a scroll, which way?
				$scroll = array( 

					'x' => __( 'Horizontal' , 'chemistry' ),
					'y' => __( 'Vertical' , 'chemistry' ),
					'z' => __( 'Fade' , 'chemistry' ),
					'random' => __( 'Random' , 'chemistry' )

				 );

				//Should we autoplay?
				$autoplay_interval = array();

				foreach( array( 1, 3, 5, 10, 15, 30, 60 ) as $value )
					$autoplay_interval[$value] = ( $value == 1 ? $value . __( ' second', 'chemistry' ) : $value . __( ' seconds', 'chemistry' ) );

				//Do we have/need next/show nav or pagination
				$navigation = array( 

					'0' => __( 'Disabled' , 'chemistry' ),
					'1' => __( 'Prev/Next buttons' , 'chemistry' ),
					'2' => __( 'Pagination' , 'chemistry' ),
					'3' => __( 'Prev/Next buttons, pagination' , 'chemistry' )

				 );

				//Our markup
				return '<h2 class="chemistry-tab-title">' . __( 'Slider Settings' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<div class="cols cols-1">
							<div class="col">
								<label>' . $this->field( 'checkbox', 'slider', $widget, array( 'class' => 'chemistry-cond chemistry-group-10' ) ) . ' <span class="label-title">' . __( 'Slider' , 'chemistry' ) . '</span></label>
							</div>
						</div>
						<div class="chemistry-cond-on chemistry-group-10">
							<div class="cols-3">
								<div class="col"><label><span class="label-title">' . __( 'Navigation' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'navigation', $widget, array( 'options' => $navigation ) ) . '</label></div>
								<div class="col"><label><span class="label-title">' . __( 'Scroll' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'scroll', $widget, array( 'options' => $scroll ) ) . '</label></div>
								<div class="col"><label><span class="label-title">' . __( 'Transition' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'transition', $widget, array( 'options' => $transition ) ) . '</label></div>
							</div>
							<div class="cols-3">
								<div class="col">
									<label>' . $this->field( 'checkbox', 'autoplay', $widget, array( 'class' => 'chemistry-cond chemistry-group-11' ) ) . ' <span class="label-title">' . __( 'Autoplay' , 'chemistry' ) . '</span></label>
								</div>
								<div class="col">
									<label class="chemistry-cond-on chemistry-group-11">' . $this->field( 'checkbox', 'autoplay_invert', $widget ) . ' <span class="label-title">' . __( 'Invert Autoplay Direction' , 'chemistry' ) . '</span></label>
								</div>
								<div class="col">
									<label class="chemistry-cond-on chemistry-group-11"><span class="label-title">' . __( 'Autoplay interval' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'autoplay_interval', $widget, array( 'options' => $autoplay_interval ) ) . '</label>
								</div>
							</div>
						</div>
					</div>';

			}/* form_slider() */


			/* =========================================================================== */
			

			protected function form_posts( $widget, $post_type = 'post', $taxonomy = 'category' )
			{

				if( $post_type == 'tile' && !class_exists( 'chemistry_tile' ) )
					return '';

				if( !empty( $taxonomy ) )
				{
					
					$taxonomy_object = get_taxonomy( $taxonomy );
					$term_objects = get_terms( $taxonomy );
					$terms = array();
					$terms[''] = __( 'All' , 'chemistry' );

					foreach( $term_objects as $term )
						$terms[$term->slug] = $term->name;

				}

				$orderby = array( 

					'none' => __( 'None' , 'chemistry' ),
					'ID' => __( 'ID' , 'chemistry' ),
					'title' => __( 'Title' , 'chemistry' ),
					'date' => __( 'Date' , 'chemistry' ),
					'modified' => __( 'Modified' , 'chemistry' ),
					'parent' => __( 'Parent' , 'chemistry' ),
					'rand' => __( 'Random' , 'chemistry' ),
					'menu_order' => __( 'Menu order' , 'chemistry' )

				 );

				$order = array( 

					'ASC' => __( 'Ascending' , 'chemistry' ),
					'DESC' => __( 'Descending' , 'chemistry' )

				 );

				$count = array();
				$count['-1'] = __( 'All' , 'chemistry' );

				for( $i = 1; $i <= 30; $i++ )
					$count[$i] = $i;

				if( ! is_array( $widget ) or empty( $widget ) )
					$widget['numberposts'] = 5;

				//Form markup
				return $this->field( 'hidden', 'taxonomy', $taxonomy ) . '
				' . (  !empty( $taxonomy ) ? '<label><span class="label-title">' . $taxonomy_object->labels->name . ' </span> ' . $this->field( 'select', 'term', $widget, array( 'options' => $terms ) ) . '</label>' : '' ) . '
				<div class="cols-3">
					<div class="col"><label><span class="label-title">' . __( 'Order by' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'orderby', $widget, array( 'options' => $orderby ) ) . '</label></div>
					<div class="col"><label><span class="label-title">' . __( 'Order' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'order', $widget, array( 'options' => $order ) ) . '</label></div>
					<div class="col"><label><span class="label-title">' . __( 'Count' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'numberposts', $widget, array( 'options' => $count ) ) . '</label></div>
				</div>';

			}/* form_posts() */

		}/* class chemistry_slider_ready_widget */

	}/* !class_exists( 'chemistry_slider_ready_widget' ) */

	

	/* =================================================================================== */
	


	if( !class_exists( 'chemistry_potion_tabs_and_accordion_abstract_class' ) )
	{

		class chemistry_potion_tabs_and_accordion_abstract_class extends chemistry_molecule_widget
		{

			/**
			 * Current just for tabs (tab) and accordions (acc)
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 */
			
			protected $multi_type;


			/* =========================================================================== */
			
			/**
			 * The markup for our output
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (array) $widget - Our widget contents
			 * @return Markup
			 */
			
			public function widget( $widget )
			{

				//Compare passed options to our defaults
				$widget = chemistry_molecule_widget::extend( array(

					'style' => 1,
					'constrain' => 'on',
					'type' => 'x',
					'current' => 0,
					'classes' => '',
				 ), $widget );

				//We need a type, numbnuts


				if( empty( $widget['type'] ) )
					$widget['type'] = 'horizontal';

				//Some default classes
				$classes = array();

				if( $this->multi_type == 'tabs' && $widget['type'] == 'y' )
					$classes[] = 'vertical';

				if( $this->multi_type == 'tabs' && $widget['type'] == 'x' )
					$classes[] = 'horizontal';


				if( $this->multi_type == 'tabs' )
				{

					$count = count( $widget['tabs_content'] );
				
					$output = '<div ' . $this->_class( $classes, $widget['classes'] ) . ' >';

					$output .= '<dl class="tabs contained ' . ( ( $widget['type'] == 'y' ) ? "vertical" : "" ) . '">';
					
						for ( $i = 0; $i < $count; $i++ )
						{
						
							if( !empty( $widget['tabs_title'][$i] ) || !empty( $widget['tabs_content'][$i] ) )
							{
								
								$output .= '<dd class="' . ( ( !empty( $widget['current'] ) && $widget['current'] == $i ) ? 'active' : '' ) . '">';
									$output .= '<a href="#' . $this->uglify( $widget['tabs_title'][$i] ) . '">' . $widget['tabs_title'][$i] . '</a>';
								$output .= '</dd>';
								
							}
							
						}
					
					$output .= '</dl>';
					
					
					
					$output .= '<ul class="tabs-content contained">';
					
						for ( $i = 0; $i < $count; $i++ )
						{
						
							if( !empty( $widget['tabs_title'][$i] ) || !empty( $widget['tabs_content'][$i] ) )
							{
								
								$output .= '<li id="' . $this->uglify( $widget['tabs_title'][$i] ) . 'Tab">';
									$output .= wpautop( do_shortcode( $widget['tabs_content'][$i] ) );
								$output .= '</li>';
								
							}
							
						}
					
					$output .= '</ul>';

					$output .= '</div>';

				}
				else
				{

					$output = '<div ' . $this->_class( $classes, $widget['classes'] ) . '>';
				
						$output .= '<dl class="accordion">';
					
							$count = count( $widget['tabs_content'] );
							
							for ( $i = 0; $i < $count; $i++ )
							{
								
								if( !empty( $widget['tabs_title'][$i] ) || !empty( $widget['tabs_content'][$i] ) )
								{
									
									$output .= '<dt class="' . ( ( !empty( $widget['current'] ) && $widget['current'] == $i ) ? ' active' : '' ) . '"><a href="">' . $widget['tabs_title'][$i] . '</a></dt>';
									$output .= '<dd class="' . ( ( !empty( $widget['current'] ) && $widget['current'] == $i ) ? ' active' : '' ) . '">' . wpautop( do_shortcode( $widget['tabs_content'][$i] ) ) .  '</dd>';
									
								}
								
							}
						
						$output .= '</dl>';
					
					$output .= '</div>';

				}


				//I love David Bowie
				return $output;

			}/* widget() */


			/* =========================================================================== */
			
			/**
			 * When we add a variable number of "items" (such as images to sliders or groups
			 * of fields to Services or Roundabout sliders) then we have this method which 
			 * allows us to contain all of the markup for each single 'item' which is added
			 * when someone presses 'Add New'
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (array) $widget - Which widget we're in
			 * @param (int) $i - The count of items added
			 * @return Markup for each item
			 */

			public function group_item( $widget, $i )
			{

				return '<div class="col"' . ( empty( $widget ) ? ' style="display: none;"' : '' ) . '><div class="group-item">
					<div class="group-item-title">' . __( 'Item' , 'chemistry' ) . '</div>
					<div class="group-item-content">
						<label><span class="label-title">' . __( 'Title' , 'chemistry' ) . '</span> ' . $this->group_field( 'text', 'tabs_title', $i, $widget ) . '</label>
						<label><span class="label-title">' . __( 'Content' , 'chemistry' ) . '</span> ' . $this->group_field( 'textarea', 'tabs_content', $i, $widget ) . '</label>
					</div>
					<div class="group-item-actions">
						<button name="molecule-widget-tab-rich" class="molecule-widget-group-item-rich">' . __( 'Rich Text Editor' , 'chemistry' ) . '</button>
						<button name="molecule-widget-tab-remove" class="molecule-widget-group-item-remove">' . __( 'Remove' , 'chemistry' ) . '</button>
					</div>
				</div></div>';

			}/* group_item() */


			/* =========================================================================== */
			
			/**
			 * The admin form for this widget
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (array) $widget - The specific details for this widget
			 * @return The markup
			 */
			
			public function form( $widget )
			{

				//Compare passed options to our defaults
				$widget = chemistry_molecule_widget::extend( array
				( 
					'style' => 1,
					'constrain' => 'on',
					'type' => 'x',
					'current' => 0,
					'classes' => '',
				 ), $widget );

				//Orientation
				$types = array( 

					'' => __( 'Default' , 'chemistry' ),
					'x' => __( 'Horizontal' , 'chemistry' ),
					'y' => __( 'Vertical' , 'chemistry' )

				 );

				$styles = apply_filters( 'chemistry_multi_styles', array( 

					'1' => __( 'Style 1' , 'chemistry' ),
					'2' => __( 'Style 2' , 'chemistry' ),

				 ), $this->multi_type );

				$count = 0;

				//Ensure we have content to output
				if( isset( $widget['tabs_content'] ) && count( $widget['tabs_content'] ) > 0 )
				{

					$count = 0;

					for( $i = 0; $i < count( $widget['tabs_content'] ); $i++ )
						if( !empty( $widget['tabs_title'][$i] ) || !empty( $widget['tabs_content'][$i] ) )
							$count++;

				}

				//Start clean
				$tabs = '';

				//Build markup based on our group_item
				if( isset( $widget['tabs_content'] ) )
				{

					$column = 0;

					for( $i = 0; $i < count( $widget['tabs_content'] ); $i++ )
						if( !empty( $widget['tabs_title'][$i] ) || !empty( $widget['tabs_content'][$i] ) )
							$tabs .= $this->group_item( $widget, $i );

				}

				//Form markup
				return '<fieldset class="chemistry-form">
					<h2 class="chemistry-tab-title">' . __( 'General' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<div class="cols-2">
							<div class="col">
								<label><span class="label-title">' . __( 'Style' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'style', $widget, array( 'options' => $styles ) ) . '</label>
							</div>
							<div class="col">
								' . ( $this->multi_type == 'tabs' ? '<label><span class="label-title">' . __( 'Type' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'type', $widget, array( 'options' => $types ) ) . '</label>' : '' ) . '
								' . ( $this->multi_type == 'acc' ? '<label class="label-alt-1">' . $this->field( 'checkbox', 'constrain', $widget ) . ' <span class="label-title">' . __( 'Constrain' , 'chemistry' ) . '</span></label>' : '' ) . '
							</div>
						</div>
					</div>
					<h2 class="chemistry-tab-title">' . __( 'Add' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<div class="chemistry-form sortable-content  group-content-wrap">
							<div class="buttonset-1">
								<button name="molecule-widget-group-item-add" class="molecule-widget-group-item-add button button-secondary alignright">' . __( 'Add New' , 'chemistry' ) . '</button>
							</div>
							<div class="group-prototype">' . $this->group_item( array(), -1 ) . '</div>
							<div class="group-content">
								<div class="cols-3 cols">
									' . $tabs.'
								</div>
							</div>
						</div>
					</div>
					<h2 class="chemistry-tab-title">' . __( 'Other' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<label><span class="label-title">' . __( 'Additional classes' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'classes', $widget ) . '</label>
					</div>
				</fieldset>';

			}/* form() */

		}/* class chemistry_potion_tabs_and_accordion_abstract_class */

	}/* !class_exists( 'chemistry_potion_tabs_and_accordion_abstract_class' ) */

	
	/* =================================================================================== */
	

	if( !class_exists( 'chemistry_posts_feed_widget' ) )
	{

		class chemistry_posts_feed_widget extends chemistry_slider_ready_widget
		{

			/**
			 * What post type and what taxonomy are we using? Set in the method calling this class
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 */
			
			protected $post_type = 'post';
			protected $post_taxonomy = 'category';


			/* =========================================================================== */
			
			/**
			 * Markup for the - really quite complicated - post feed
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.3
			 * @param (array) $widget - This widget config
			 * @return Markup
			 */
			
			public function widget( $widget )
			{

				//Start clean
				$output = '';

				//Compare passed options to our defaults
				$widget = chemistry_molecule_widget::extend( array( 

					'align' => '',
					'width' => '',
					'ratio' => 100,
					'image_mode' => 'auto',
					'frame' => '',
					'enable_title' => '',
					'disable_lightbox' => '',
					'style' => 1,
					'rows' => 1,
					'columns' => 1,
					'disable_spacing' => '',
					'select' => '',
					'content_hide' => '',
					'classes' => ''

				 ), $widget );

				//Pass the details to our retrieve_post method to build the query
				$posts = $this->retrieve_posts( $widget, $this->post_type, array( 'preview_image', 'preview_alt', 'featured' ) );

				//Apply some classes
				$classes = array_merge( array( 'widget', 'blog-feed-' . $widget['style'] ), $this->get_classes( $widget ) );

				if( !empty( $widget['frame'] ) )
					$classes[] = 'frame-style-' . $widget['frame'];

				if( $widget['enable_title'] == 'on' )
					$classes[] = 'gallery-img-title-1';

				$classes[] = 'media-height-ratio-' . $widget['ratio'];
				$classes[] = 'media-height-' . $widget['height'];
				$classes[] = 'image-stretch-mode-' . $widget['image_mode'];


				//Set out dimensions and units extensions
				if( !empty( $widget['width'] ) )
					$widget['width'] = Chemistry::check_units_on_end_of_string( $widget['width'], 'px' );

				if( !empty( $widget['align'] ) )
					$classes[] = 'align' . $widget['align'];

				//Numbers, please.
				$widget['image_width'] = intval( $widget['image_width'] );
				$widget['image_height'] = intval( $widget['image_height'] );
				$widget['image_crop_width'] = intval( $widget['image_crop_width'] );
				$widget['image_crop_height'] = intval( $widget['image_crop_height'] );


				//It's all gone wrong (no singular post type, sort your life out)
				if( $widget['select'] == 'related' && !is_singular( $this->post_type ) )
					return '';

				//Style specific markup
				if( $widget['style'] == 2 )
				{

					//Open up
					$output .= '<div' . $this->_class( $classes, $widget['classes'] ) . 'style="' . (  !empty( $widget['width'] ) ? 'width: ' . $widget['width'] : '' ) . '">';
					$output .= '<div' . $this->_class( array( 'cols', 'cols-' . $widget['columns'], 'rows-' . $widget['rows'], 'spacing-' . ( $widget['disable_spacing'] == 'on' ? 0 : 1 ) ) ) . '>';

					//Each post style
					foreach( $posts as $p )
					{

						$output .= '<div' . $this->_class( 'col' ) . '>';
						$output .= '<div' . $this->_class( 'blog-feed-2-item' ) . '>';

						if( !isset( $p['meta']['preview_image'] ) || empty( $p['meta']['preview_image'] ) )
						{

							$images = Chemistry::get_or_set_meta( 'images', true, $p['id'] );

							if( is_array( $images ) && count( $images ) > 0 && !empty( $images[0]['image_url'] ) )
							{

								$p['meta']['preview_image'] = $images[0]['image_url'];
								$p['meta']['preview_alt'] = $images[0]['image_alt'];

							}

						}

						if( isset( $p['meta']['preview_image'] ) && !empty( $p['meta']['preview_image'] ) )
							if( $widget['image_crop_width'] > 0 || $widget['image_crop_height'] > 0 )
								$p['meta']['preview_image'] = Chemistry::get_or_make_thumbnail( Chemistry::get_raw_image_url( $p['meta']['preview_image'] ), $widget['image_crop_width'], $widget['image_crop_height'] );

						if( empty( $p['meta']['preview_image'] ) )
						{

							$thumbnail = wp_get_attachment_url( get_post_thumbnail_id( $p['id'] ) );

							if( !empty( $thumbnail ) )
								$p['meta']['preview_image'] = $thumbnail;
							else
								$p['meta']['preview_image'] = Chemistry::path( 'assets/images/placeholder-empty.png', true );

						}

						$output .= '<div' . $this->_class( 'media-img' ) . '><img src="'.Chemistry::chemistry_image( $p['meta']['preview_image'], 'preview' ) . '" alt="' . $p['meta']['preview_alt'] . '"' . ( $widget['image_width'] > 0 ? ' width="' . $widget['image_width'] . '"' : '' ) . ( $widget['image_height'] > 0 ? ' height="' . $widget['image_height'] . '"' : '' ) . ' />';

						if( $widget['frame'] == 2 )
							$output .= '<div' . $this->_class( 'media-helper' ) . '></div>';

						$output .= '</div>';

						$output .= '<div' . $this->_class( 'properties' ) . '>';
						$output .= '<div' . $this->_class( array( 'author', 'meta' ) ) . '>' . $p['author_link'] . '</div>';
						$output .= '<div' . $this->_class( array( 'pubdate', 'meta' ) ) . ' pubdate datetime="' . $p['date_ymd'] . '">' . $p['date'] . '</div>';
						$output .= '<p' . $this->_class( array( 'comment', 'meta' ) ) . '><a href="' . $p['permalink'] . '#comments">'.get_comments_number( $p['id'] ) . '</a></p>';

						if( $this->post_type == 'post' || !empty( $this->post_taxonomy ) )
						{

							$tags = wp_get_object_terms( $p['id'], ( $this->post_type == 'post' ? 'post_tag' : $this->post_taxonomy ), array( 'hide_empty' => true ) );
							$tags_output = '';

							if( !empty( $tags ) )
								foreach( $tags as $tag )
									$tags_output[] = '<a href="'.get_term_link( $tag ) . '">' . $tag->name . ' </a>';

							if( !empty( $tags_output ) )
								$output .= '<p' . $this->_class( array( 'tags', 'meta' ) ) . '>'.implode( ', ', $tags_output ) . '</a>';

						}

						$output .= '</div>';

						if( !empty( $widget['trim_words'] ) && $widget['trim_words'] > 0 )
							$p['content'] = Chemistry::limit_to_x_words( $p['content'], $widget['trim_words'], true );

						$output .= '<div' . $this->_class( 'header' ) . '>';
						$output .= '<h2><a href="' . $p['permalink'] . '">' . $p['title'] . '</a></h2>';
						$output .= '</div>';

						if( empty( $widget['content_hide'] ) )
							$output .= '<div' . $this->_class( 'intro' ) . '>' . wpautop( $p['content'] ) . '</div>';

						if( empty( $widget['button_hide'] ) )
							$output .= '<a href="' . $p['permalink'] . '"' . $this->_class( 'alignright' ) . '>' . __( 'View' , 'chemistry' ) . '</a>';

						if( $p['meta']['featured'] == 'on' )
							$output .= '				<div' . $this->_class( 'featured-1' ) . '><div></div></div>';

						$output .= '</div>';
						$output .= '</div>';
					}

					$output .= '</div>';
					$output .= '</div>';

				}
				else
				{

					$output .= '<div' . $this->_class( $classes, $widget['classes'] ) . '>';
					$output .= '<div' . $this->_class( array( 'cols', 'cols-' . $widget['columns'], 'rows-' . $widget['rows'], 'spacing-' . ( $widget['disable_spacing'] == 'on' ? 0 : 1 ) ) ) . '>';

					foreach( $posts as $p )
					{

						$output .= '<div' . $this->_class( 'col' ) . '>';
						$output .= '<div' . $this->_class( 'blog-feed-1-item' ) . '>';

						if( !isset( $widget['preview_hide'] ) || $widget['preview_hide'] != 'on' )
						{

							if( !isset( $p['meta']['preview_image'] ) || empty( $p['meta']['preview_image'] ) )
							{

								$images = Chemistry::get_or_set_meta( 'images', true, $p['id'] );

								if( is_array( $images ) && count( $images ) > 0 && !empty( $images[0]['image_url'] ) )
								{

									$p['meta']['preview_image'] = $images[0]['image_url'];
									$p['meta']['preview_alt'] = $images[0]['image_alt'];

								}

							}

							if( isset( $p['meta']['preview_image'] ) && !empty( $p['meta']['preview_image'] ) )
							{

								if( $widget['image_crop_width'] > 0 || $widget['image_crop_height'] > 0 )
									$p['meta']['preview_image'] = Chemistry::get_or_make_thumbnail( Chemistry::get_raw_image_url( $p['meta']['preview_image'] ), $widget['image_crop_width'], $widget['image_crop_height'] );

							}

							if( empty( $p['meta']['preview_image'] ) )
							{

								$thumbnail = wp_get_attachment_url( get_post_thumbnail_id( $p['id'] ) );

								if( !empty( $thumbnail ) )
									$p['meta']['preview_image'] = $thumbnail;
								else
									$p['meta']['preview_image'] = Chemistry::path( 'assets/images/placeholder-empty.png', true );

							}

							$output .= '<div' . $this->_class( 'media-img' ) . '><img src="'.Chemistry::chemistry_image( $p['meta']['preview_image'], 'preview' ) . '" alt="' . $p['meta']['preview_alt'] . '"' . ( $widget['image_width'] > 0 ? ' width="' . $widget['image_width'] . '"' : '' ) . ( $widget['image_height'] > 0 ? ' height="' . $widget['image_height'] . '"' : '' ) . ' />';

							if( $widget['frame'] == 2 )
								$output .= '<div' . $this->_class( 'media-helper' ) . '></div>';

							$output .= '</div>';

						}

						$output .= '<div' . $this->_class( 'header' ) . '>';
						$output .= '<h2><a href="' . $p['permalink'] . '">' . $p['title'] . '</a></h2>';
						$output .= '</div>';

						if( empty( $widget['content_hide'] ) )
						{

							$output .= '<div' . $this->_class( 'properties' ) . '">';
							$output .= '<div' . $this->_class( 'pubdate' ) . ' pubdate datetime="' . $p['date_ymd'] . '">'.Chemistry::twitter_time_ago( $p['timestamp'] ) . '</div>';
							$output .= '</div>';

							if( !empty( $widget['trim_words'] ) && $widget['trim_words'] > 0 )
								$p['content'] = Chemistry::limit_to_x_words( $p['content'], $widget['trim_words'], true );

							$output .= '<div' . $this->_class( 'intro' ) . '>' . wpautop( $p['content'] ) . '</div>';

						}


						if( empty( $widget['button_hide'] ) )
							$output .= '<a href="' . $p['permalink'] . '"' . $this->_class( 'alignright' ) . '>' . __( 'Read more' , 'chemistry' ) . '</a>';

						if( $p['meta']['featured'] == 'on' )
							$output .= '<div' . $this->_class( 'featured-1' ) . '><div></div></div>';

						$output .= '</div>';
						$output .= '</div>';

					}

					$output .= '</div>';
					$output .= '</div>';

				}

				//*phew*
				return $output;

			}/* widget() */


			/* =========================================================================== */
			
			/**
			 * The admin form for this widget
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (array) $widget - The specific details for this widget
			 * @return The markup
			 */
			
			public function form( $widget )
			{

				//What style of feed?
				$styles = array( 

					1 => __( 'Post style' , 'chemistry' ),
					2 => __( 'Gallery style' , 'chemistry' )

				 );

				//How many words to trim to
				$trims = array(

					5,
					10,
					15,
					20,
					25,
					30,
					40,
					50,
					75,
					100,
					150,
					200

				 );

				//Setup a dummy
				$_trims = array();

				//
				foreach( $trims as $words )
					$_trims[$words] = $words . __( ' words', 'chemistry' );

				$trims = $_trims;

				//What sort of relationship?
				$select = array( 

					'' => __( 'All' , 'chemistry' ),
					'related' => __( 'Related' , 'chemistry' ),
					'featured' => __( 'Featured' , 'chemistry' ),
					'popular' => __( 'Popular' , 'chemistry' ),
					'random' => __( 'Random' , 'chemistry' )

				 );


				/*if( ! is_array( $widget ) || empty( $widget ) )
					$widget['ratio'] = 100;*/

				//Build markup
				return '<fieldset class="chemistry-form">
					<h2 class="chemistry-tab-title">' . __( 'General' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						' . $this->form_widget_general( $widget ) . '
						<div class="cols cols-2">
							<div class="col">
								<label><span class="label-title">' . __( 'Style' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'style', $widget, array( 'options' => $styles ) ) . '</label>
							</div>
							<div class="col">
								<label><span class="label-title">' . __( 'Trim words' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'trim_words', $widget, array( 'options' => $trims ) ) . '</label>
							</div>
						</div>
						<div class="cols-3">
							<div class="col">
								<label>' . $this->field( 'checkbox', 'content_hide', $widget ) . ' <span class="label-title">' . __( 'Hide excerpts' , 'chemistry' ) . '</span></label>
							</div>
							<div class="col">
								<label>' . $this->field( 'checkbox', 'button_hide', $widget ) . ' <span class="label-title">' . __( 'Hide \'more\' button' , 'chemistry' ) . '</span></label>
							</div>
							<div class="col">
								<label>' . $this->field( 'checkbox', 'preview_hide', $widget ) . ' <span class="label-title">' . __( 'Hide preview image' , 'chemistry' ) . '</span></label>
							</div>
						</div>
					</div>
					<h2 class="chemistry-tab-title">' . __( 'Feed Settings' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						' . $this->form_posts( $widget, $this->post_type, $this->post_taxonomy ) . '
						<div class="cols cols-1">
							<div class="col">
								<label><span class="label-title">' . __( 'Select by' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'select', $widget, array( 'options' => $select ) ) . '</label>
							</div>
						</div>
					</div>
					<h2 class="chemistry-tab-title">' . __( 'Preview Image Settings' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						' . $this->form_media_frame( $widget ) . '
						' . $this->form_image_dimensions( $widget ) . '
					</div>
					' . $this->form_common( $widget ) . '
					' . $this->form_slider( $widget ) . '
					<h2 class="chemistry-tab-title">' . __( 'Other' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<label><span class="label-title">' . __( 'Additional classes' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'classes', $widget ) . '</label>
					</div>
				</fieldset>';

			}/* form() */

		}/* class chemistry_posts_feed_widget */

	}/* !class_exists( 'chemistry_posts_feed_widget' ) */


	/* =================================================================================== */


	if( !class_exists( 'chemistry_potion_column_abstract_class' ) )
	{

		class chemistry_potion_column_abstract_class extends chemistry_molecule_widget
		{

			/**
			 * Some vars for us to use throughout the columns. $cols is basically an ID
			 * $col_count is the number of columns in this potion and $splits is for
			 * non-linear columns - it's an array of how the layout looks based on a 12
			 * column layout. (i.e. one quarter is 3 cols, one third is 4 cols etc.)
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.1
			 */
			

			protected $cols;
			protected $col_count;
			protected $splits;


			/* =========================================================================== */


			/**
			 * Convert the 'number' of columns to words
			 *
			 * Almost entirely ripped from http://www.pwsdb.com/pgm/?p=112 (thanks greg!)
			 *
			 * @package Chemistry
			 * @author Richard Tape
			 * @since 0.1
			 * @param (int) $number - the number to convert to words
			 * @return (string) - The words of the number
			 */
	
			public function convert_num_to_words( $number )
			{
				
				if( ( $number < 0 ) || ( $number > 999999999 ) )
					throw new Exception( "Number is out of range" );

				$Gn = floor( $number / 1000000 );  /* Millions ( giga ) */
				$number -= $Gn * 1000000;
				$kn = floor( $number / 1000 );     /* Thousands ( kilo ) */
				$number -= $kn * 1000;
				$Hn = floor( $number / 100 );      /* Hundreds ( hecto ) */
				$number -= $Hn * 100;
				$Dn = floor( $number / 10 );       /* Tens ( deca ) */
				$n = $number % 10;               /* Ones */ 

				$result = ""; 

				if( $Gn )
					$result .= number_to_words( $Gn ) . " Million";

				if( $kn )
					$result .= ( empty( $result ) ? "" : " " ) . number_to_words( $kn ) . " Thousand";

				if( $Hn )
					$result .= ( empty( $result ) ? "" : " " ) . number_to_words( $Hn ) . " Hundred";

				$ones = array( "", "One", "Two", "Three", "Four", "Five", "Six",
				"Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
				"Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen",
				"Nineteen" );

				$tens = array( "", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty",
				"Seventy", "Eigthy", "Ninety" ); 

				if( $Dn || $n )
				{

					if( !empty( $result ) )
						$result .= " and ";

					if( $Dn < 2 )
					{
						$result .= $ones[$Dn * 10 + $n];
					}
					else
					{

						$result .= $tens[$Dn];

						if( $n )
							$result .= "-" . $ones[$n];

					}

				}

				if( empty( $result ) )
					$result = "zero"; 

				return strtolower( $result ) ;
				
			}/* convert_num_to_words */


			/* =========================================================================== */


			/**
			 * Outputs the appropriate markup for our columns to be in line with our less-based
			 * framework.
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.3
			 * @param (array) $widget - The widget we're talking about
			 * @return The markup
			 */
			
			public function widget( $widget )
			{

				//All rows must start with a class of 'row'. 'display' is somewhat legacy
				$output = '<div class="row display">';
	
				//If we have non-linear columns
				if( is_array( $this->splits ) )
				{
					
					foreach( $this->splits as $i =>$num_cols )
					{
						
						$output .= '<div class="' . $this->convert_num_to_words( $num_cols ) . ' columns">';
					
							$output .= ( isset( $widget['col-' . $i] ) ? $widget['col-' . $i] : '' );
					
						$output .= '</div>';
						
					}
					
				}
				else
				{
				
					//We just need x-number of linear columns
					for ( $i = 1; $i <= $this->col_count; $i++ )
					{
						
						$output .= '<div class="' . $this->convert_num_to_words( ( 12 / $this->cols ) ) . ' columns">' . ( isset( $widget['col-' . $i] ) ? $widget['col-' . $i] : '' ) . '</div>';	
						
					}
					
				}
	
				$output .= '</div>';
	
				return $output;

			}/* widget() */


			/* =========================================================================== */


			/**
			 * The admin form for this widget
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (array) $widget - The specific details for this widget
			 * @return The markup
			 */
			
			public function form( $widget )
			{

				//Some containers
				$cols = '<div class="molecule-widget-row cols-' . $this->cols . '">';
				$options = '<div class="molecule-widget-row-options cols-' . $this->cols . '">';

				//For each column, we need to output a container which allows the user to add more modules
				for( $i = 1; $i <= $this->col_count; $i++ )
				{

					$cols .= '<div class="col molecule-widget-column">' . ( isset( $widget['col-' . $i] ) ? $widget['col-' . $i] : '' ) . '</div>';
					$options .= ' <div class="col molecule-widget-column-options"><button name="molecule-widget-add" class="molecule-widget-add"><span>' . __( 'Add module in this column' , 'chemistry' ) . '</span></button></div>';

				}

				//Close ourselves off
				$cols .= '</div>';
				$options .= '</div>';

				return $cols . $options;

			}/* form() */

		}/* class chemistry_potion_column_abstract_class */

	}/* !class_exists( 'chemistry_potion_column_abstract_class' ) */

?>