<?php

	/* ================================================================================ */
	
	if( !class_exists( 'flab_slider_ready_widget' ) )
	{
	
		class flab_slider_ready_widget extends flab_builder_widget
		{
		
			/**
			 * Return the actual 'rows' for our modules
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
		
			protected function get_tiles( $widget, $meta = array( ) )
			{
				
				$tiles = flab_tile::get_tiles( array( 'order' => $widget['order'], 'orderby' => $widget['orderby'], 'numberposts' => $widget['numberposts'], 'meta' => $meta ), $widget['term'] );
	
				return $tiles;
				
			}/* get_tiles() */
			
			/* ============================================================================ */
	
			/**
			 * For some modules we need to grab different posts and content, including texonomies etc.
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			protected function get_posts( $widget, $post_type, $meta = array( ), $select_by = '' )
			{
			
				$args = array( 'post_type' => $post_type, 'order' => $widget['order'], 'orderby' => $widget['orderby'], 'numberposts' => $widget['numberposts'], 'meta' => $meta );
	
				if( $post_type == 'post' || $post_type == 'page' )
				{
					$args['text_opt'] = 'excerpt';
				}
	
				if( isset( $widget['select'] ) && $widget['select'] == 'featured' )
				{
					
					$args['meta_key'] = 'flab_featured';
					$args['meta_value'] = 'on';
					
				}
	
				if( isset( $widget['taxonomy'] ) && !empty( $widget['taxonomy'] ) && isset( $widget['term'] ) && !empty( $widget['term'] ) )
				{
					
					$args['tax_query'] = array( 
						
						array( 
						
							'taxonomy' => $widget['taxonomy'],
							'field' => 'slug',
							'terms' => array( $widget['term'] )
						)
						
					 );
					 
				}
	
				$posts = array( );
	
				if( isset( $widget['select'] ) && $widget['select'] == 'related' && is_singular( $post_type ) )
				{
					
					$taxonomy = array( 'category', 'post_tag' );
	
					if( $post_type != 'post' && !empty( $widget['taxonomy'] ) )
					{
						$taxonomy = $widget['taxonomy'];
					}
	
					$posts = flab::flab_get_related_posts( $args, $taxonomy );
					
				}
	
				if( empty( $posts ) )
				{
					$posts = flab::get_posts( $args );
				}
	
				return $posts;
				
			}/* get_posts() */
			
			/* ============================================================================ */
	
			/**
			 * Returns our classes for different scrolls, autoplays, heights etc.
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			protected function get_classes( $widget )
			{
				$classes = array( );
	
				$classes[] = 'flab-grid';
	
				if( isset( $widget['slider'] ) && $widget['slider'] == 'on' )
				{
					
					$classes[] = 'flab-slider';
	
					if( isset( $widget['autoplay'] ) && $widget['autoplay'] == 'on' )
					{
						
						$classes[] = 'flab-autoplay-1';
						$classes[] = 'flab-autoplay-interval-'.$widget['autoplay_interval'];
	
						if( isset( $widget['autoplay_invert'] ) && $widget['autoplay_invert'] == 'on' )
						{
							$classes[] = 'flab-autoplay-invert-1';
						}
						
					}
					else
					{
						$classes[] = 'flab-autoplay-0';
					}
	
					if( isset( $widget['scroll'] ) && !empty( $widget['scroll'] ) )
					{
						$classes[] = 'flab-scroll-axis-'.$widget['scroll'];
					}
	
					if( isset( $widget['transition'] ) && !empty( $widget['transition'] ) )
					{
						$classes[] = 'flab-transition-'.$widget['transition'];
					}
	
					if( isset( $widget['grid_height'] ) && !empty( $widget['grid_height'] ) )
					{
						$classes[] = 'flab-grid-height-'.$widget['grid_height'];
					}
	
					if( $widget['navigation'] == 1 )
					{
						$classes[] = 'flab-ctrl-arrows-1';
					}
					else if( $widget['navigation'] == 2 )
					{
						$classes[] = 'flab-ctrl-pag-1';
					}
					else if( $widget['navigation'] == 3 )
					{
						
						$classes[] = 'flab-ctrl-pag-1';
						$classes[] = 'flab-ctrl-arrows-1';
						
					}
					
				}
	
				return $classes;
				
			}/* get_classes() */
			
			/* ============================================================================ */
	
			/**
			 * Generic function to enable us to have generic options for sliders
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			protected function form_slider( $widget )
			{
			
				$output = '';
				$scroll_options = '';
				$col_options = '';
				$row_options = '';
				$autoplay_interval_options = '';
				$navigation_options = '';
				$transition_options = '';
	
				$transitions = array( 
				
					'slide' => __( 'Slide' ),
					'slideIn' => __( 'Slide in' ),
					'slideOut' => __( 'Slide out' ),
					'switch' => __( 'Switch' ),
					'random' => __( 'Random' )
					
				 );
	
				foreach( $transitions as $value => $label )
				{
					
					$transition_options .= '<option value="'.$value.'"'.( ( isset( $widget['transition'] ) && $widget['transition'] == $value ) ? ' selected="selected"' : '' ).'>'.$label.'</option>';
					
				}
	
				foreach( array( 'x' => __( 'Horizontal' ), 'y' => __( 'Vertical' ), 'z' => __( 'Fade' ), 'random' => __( 'Random' ) ) as $value => $label )
				{
					
					$scroll_options .= '<option value="'.$value.'"'.( ( isset( $widget['scroll'] ) && $widget['scroll'] == $value ) ? ' selected="selected"' : '' ).'>'.$label.'</option>';
					
				}
	
				for( $i = 0; $i < 6; $i++ )
				{
					
					$col_options .= '<option value="'.( $i + 1 ).'"'.( ( isset( $widget['columns'] ) && $widget['columns'] == ( $i + 1 ) ) ? ' selected="selected"' : '' ).'>'.( $i + 1 ).'</option>';
					
				}
	
				for( $i = 0; $i < 6; $i++ )
				{
					
					$row_options .= '<option value="'.( $i + 1 ).'"'.( ( isset( $widget['rows'] ) && $widget['rows'] == ( $i + 1 ) ) ? ' selected="selected"' : '' ).'>'.( $i + 1 ).'</option>';
					
				}
	
				foreach( array( 1, 3, 5, 10, 15, 30, 60 ) as $value )
				{
					
					$autoplay_interval_options .= '<option value="'.$value.'"'.( ( isset( $widget['autoplay_interval'] ) && $widget['autoplay_interval'] == $value ) ? ' selected="selected"' : '' ).'>'.$value.__( " seconds" ).'</option>';
					
				}
	
				foreach( array( '0' => __( 'Disabled' ), '1' => __( 'Prev/Next buttons' ), '2' => __( 'Pagination' ), '3' => __( 'Prev/Next buttons, pagination' ) ) as $value => $label )
				{
					
					$navigation_options .= '<option value="'.$value.'"'.( ( isset( $widget['navigation'] ) && $widget['navigation'] == $value ) ? ' selected="selected"' : '' ).'>'.$label.'</option>';
					
				}
	
				$output .= '<div class="cols-2"><div class="col"><label>'.__( 'Columns' ).' <select'.$this->get_field_atts( 'columns' ).' name="'.$this->get_field_name( 'columns' ).'" value="'.( isset( $widget['columns'] ) ? $widget['columns'] : '' ).'">'.$col_options.'</select></label></div>';
				
				$output .= '<div class="col"><label>'.__( 'Rows' ).' <select'.$this->get_field_atts( 'rows' ).' name="'.$this->get_field_name( 'rows' ).'" value="'.( isset( $widget['rows'] ) ? $widget['rows'] : '' ).'">'.$row_options.'</select></label></div></div>';
				
				$output .= '<label><input type="checkbox"'.$this->get_field_atts( 'slider' ).' name="'.$this->get_field_name( 'slider' ).'"'.( isset( $widget['slider'] ) ? ' checked="checked"' : '' ).' /> '.__( 'Slider' ).'</label>';
				
				$output .= '<div class="cols-3"><div class="col"><label>'.__( 'Navigation' ).' <select'.$this->get_field_atts( 'navigation' ).' name="'.$this->get_field_name( 'navigation' ).'" value="'.( isset( $widget['navigation'] ) ? $widget['navigation'] : '' ).'">'.$navigation_options.'</select></label></div>';
				
				$output .= '<div class="col"><label>'.__( 'Scroll' ).' <select'.$this->get_field_atts( 'scroll' ).' name="'.$this->get_field_name( 'scroll' ).'" value="'.( isset( $widget['scroll'] ) ? $widget['scroll'] : '' ).'">'.$scroll_options.'</select></label></div>';
				
				$output .= '<div class="col"><label>'.__( 'Transition' ).' <select'.$this->get_field_atts( 'transition' ).' name="'.$this->get_field_name( 'transition' ).'" value="'.( isset( $widget['transition'] ) ? $widget['transition'] : '' ).'">'.$transition_options.'</select></label></div></div>';
				
				$output .= '<div class="cols-3"><div class="col"><label><input type="checkbox"'.$this->get_field_atts( 'autoplay' ).' name="'.$this->get_field_name( 'autoplay' ).'"'.( isset( $widget['autoplay'] ) ? ' checked="checked"' : '' ).' /> '.__( 'Autoplay' ).'</label></div>';
				
				$output .= '<div class="col"><label>'.__( 'Autoplay interval' ).' <select'.$this->get_field_atts( 'autoplay_interval' ).' name="'.$this->get_field_name( 'autoplay_interval' ).'" value="'.( isset( $widget['autoplay_interval'] ) ? $widget['autoplay_interval'] : '' ).'">'.$autoplay_interval_options.'</select></label></div></div>';
	
				return $output;
				
			}/* form_slider() */
			
			/* ============================================================================ */
	
			/**
			 * Quick option for when you want to display the same thing on several modules. Initially just to disable space
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			protected function form_common( $widget )
			{
				
				$output = '';
				$output .= '<label><input type="checkbox"'.$this->get_field_atts( 'disable_spacing' ).' name="'.$this->get_field_name( 'disable_spacing' ).'"'.( isset( $widget['disable_spacing'] ) ? ' checked="checked"' : '' ).' /> '.__( 'Disable spacing' ).' </label>';
	
				return $output;
				
			}/* form_common() */
			
			/* ============================================================================ */
	
			/**
			 * Helper function for different dropdowns, mainly for orders
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			protected function form_posts( $widget, $post_type = 'post', $taxonomy = 'category' )
			{
			
				$output = '';
				$terms_options = '';
				$orderby_options = '';
				$order_options = '';
				$count_options = '';
	
				$taxonomy_object = get_taxonomy( $taxonomy );
				$terms = get_terms( $taxonomy );
	
				$terms_options = '<option value="">'.__( 'All' ).'</option>';
	
				foreach( $terms as $term )
				{
					
					$terms_options .= '<option value="'.$term->slug.'"'.( ( isset( $widget['term'] ) && $widget['term'] == $term->slug ) ? ' selected="selected"' : '' ).'>'.$term->name.'</option>';
					
				}
	
				$orderby = array(
				
					'none' => __( 'None' ),
					'ID' => __( 'ID' ),
					'title' => __( 'Title' ),
					'date' => __( 'Date' ),
					'modified' => __( 'Last Modified' ),
					'parent' => __( 'Parent' ),
					'rand' => __( 'Random' ),
					'menu_order' => __( 'Menu order' )

				 );
	
				foreach( $orderby as $value => $label )
				{
					
					$orderby_options .= '<option value="'.$value.'"'.( ( isset( $widget['orderby'] ) && $widget['orderby'] == $value ) ? ' selected="selected"' : '' ).'>'.$label.'</option>';
					
				}
	
				$order = array(
				
					'ASC' => __( 'Ascending' ),
					'DESC' => __( 'Descending' )
					
				 );
	
				foreach( $order as $value => $label )
				{
					
					$order_options .= '<option value="'.$value.'"'.( ( isset( $widget['order'] ) && $widget['order'] == $value ) ? ' selected="selected"' : '' ).'>'.$label.'</option>';
					
				}
	
				$count_options .= '<option value="-1"'.( ( isset( $widget['numberposts'] ) && $widget['numberposts'] == '-1' ) ? ' selected="selected"' : '' ).'>'.__( 'All' ).'</option>';
	
				for( $i = 0; $i < 30; $i++ )
				{
					$count_options .= '<option value="'.( $i + 1 ).'"'.( ( isset( $widget['numberposts'] ) && $widget['numberposts'] == ( $i + 1 ) ) ? ' selected="selected"' : '' ).'>'.( $i + 1 ).'</option>';
				}
	
				$output .= '<input type="hidden"'.$this->get_field_atts( 'taxonomy' ).' name="'.$this->get_field_name( 'taxonomy' ).'" value="'.$taxonomy.'" />';
				
				$output .= '<label>'.$taxonomy_object->labels->name.' <select'.$this->get_field_atts( 'term' ).' name="'.$this->get_field_name( 'term' ).'" value="'.( isset( $widget['term'] ) ? $widget['term'] : '' ).'">'.$terms_options.'</select></label>';
				
				$output .= '<label><input type="checkbox"'.$this->get_field_atts( 'disable_spacing' ).' name="'.$this->get_field_name( 'disable_spacing' ).'"'.( isset( $widget['disable_spacing'] ) ? ' checked="checked"' : '' ).' /> '.__( 'Disable spacing' ).' </label>';
				
				$output .= '<div class="cols-3"><div class="col"><label>'.__( 'Order by' ).' <select'.$this->get_field_atts( 'orderby' ).' name="'.$this->get_field_name( 'orderby' ).'" value="'.( isset( $widget['orderby'] ) ? $widget['orderby'] : '' ).'">'.$orderby_options.'</select></label></div>';
				
				$output .= '<div class="col"><label>'.__( 'Order' ).' <select'.$this->get_field_atts( 'order' ).' name="'.$this->get_field_name( 'order' ).'" value="'.( isset( $widget['order'] ) ? $widget['order'] : '' ).'">'.$order_options.'</select></label></div>';
				
				$output .= '<div class="col"><label>'.__( 'Count' ).' <select'.$this->get_field_atts( 'numberposts' ).' name="'.$this->get_field_name( 'numberposts' ).'" value="'.( isset( $widget['numberposts'] ) ? $widget['numberposts'] : '' ).'">'.$count_options.'</select></label></div></div>';
	
				return $output;
				
			}/* form_posts() */
			
			/* ============================================================================ */
			
		}/* class flab_slider_ready_widget */
		
	}
	
	/* ================================================================================ */

?>