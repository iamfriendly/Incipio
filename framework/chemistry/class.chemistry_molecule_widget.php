<?php

	if( !class_exists( 'chemistry_molecule_widget' ) )
	{

		/**
		 * The raw class for our potions. It's not *quite* an abstract class in so far as
		 * it _does_ get extended by our potions but it also has methods that are run for
		 * all of them. It's intentional and only I know why. Mwahahaha.
		 *
		 *
		 * @author Richard Tape
		 * @package Chemistry
		 * @since 0.1
		 */
		

		class chemistry_molecule_widget
		{

			/**
			 * Our class variables
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 */
			

			protected $id;
			protected $slug;
			protected $title;
			protected $laboratory;
			protected $row;
			protected $column;
			protected $core;
			protected $label;
			protected $excerpt;
			protected $visible;

			protected $after;
			protected $data;


			/* =========================================================================== */


			/**
			 * Set our defaults for each potion, then fire off a call to the molecule
			 * for the data for each
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.1
			 * @param (string) $slug - mainly for classname, but which potion are we using
			 * @param (string) $title - title of this potion
			 * @param (int) $id - unique id
			 * @param (string) $laboratory - where is it (in which laboratory)
			 * @param (string) $row - is it (in) a row?
			 * @param (string) $column - or a column?
			 * @return 
			 */
			
			public function __construct( $slug, $title, $id = null, $laboratory = null, $row = null, $column = null )
			{

				$this->core = false;
				$this->slug = Chemistry::uglify( $slug );
				$this->title = $title;

				$this->id = $id;
				$this->laboratory = $laboratory;
				$this->row = $row;
				$this->column = $column;
				$this->label = '';
				$this->excerpt = '';
				$this->visible = true;
				$this->after = false;
				$this->after_id = '';

				if( $this->id == null )
					$this->id = '___CHEMISTRYID___';

				if( $this->laboratory == null )
					$this->laboratory = '___POSITION___';

				if( $this->row == null )
					$this->row = '___CHEMISTRYROW___';

				if( $this->column == null )
					$this->column = '___CHEMISTRYCOL___';

				chemistry_molecule::use_potion_data( $slug, $title );

			}/* __constrcut() */


			/* =========================================================================== */


			/**
			 * 
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param 
			 * @return 
			 */
			
			public function extend( $defaults, $data )
			{

				foreach( $defaults as $k => $v )
					if( !isset( $data[$k] ) )
						$data[$k] = $v;

				return $data;

			}/* extend() */


			/* =========================================================================== */


			/**
			 * 
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param 
			 * @return 
			 */
			
			public function get_field_name( $name )
			{

				return Chemistry::chemistry_option( 'prefix' ) . 'molecule_widget[' . $this->laboratory . '][' . $this->row . '][' . $this->column . '][' . $this->id . '][' . $name . ']';

			}/* get_field_name() */


			/* =========================================================================== */


			/**
			 * 
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param 
			 * @return 
			 */
			
			public function is_core()
			{

				return $this->core;

			}/* is_core() */


			/* =========================================================================== */


			/**
			 * 
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param 
			 * @return 
			 */
			
			public function get_field_atts( $name )
			{

				$disabled_fields = chemistry_molecule::get_disabled_fields();

				if( in_array( $name, $disabled_fields ) )
					return ' disabled="disabled"';

				return '';

			}/* get_field_atts() */


			/* =========================================================================== */


			/**
			 * 
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param 
			 * @return 
			 */

			public function attr( $attr = array() )
			{

				if( is_array( $attr ) )
				{

					$attrs = '';

					foreach( $attr as $key => $value )
					{

						if( $value === true )
						{

							$attrs .= ' ' . $key;

						}
						elseif( is_array( $value ) || trim( $value ) !== '' )
						{

							if( is_array( $value ) )
								$attrs .= ' ' . $key . '="' . Chemistry::rebase_strip_tags( implode( ' ', $value ) ) . '"';
							else
								$attrs .= ' ' . $key . '="' . Chemistry::rebase_strip_tags( $value ) . '"';

						}

					}

				}
				else
				{

					$attrs = ' ' . $attr;

				}

				$attrs = trim( $attrs );

				return ( !empty( $attrs ) ? ' ' : '' ) . $attrs;

			}/* attr() */


			/* =========================================================================== */


			/**
			 * 
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param 
			 * @return 
			 */
			
			public function _class( $classes = array(), $no_prefix_classes = array() )
			{

				if( !is_array( $classes ) )
					$classes = explode( ' ', $classes );

				if( !is_array( $no_prefix_classes ) )
					$no_prefix_classes = explode( ' ', $no_prefix_classes );

				$class = '';

				if( !empty( $classes ) )
				{

					$_classes = array();

					foreach( $classes as $c )
					{

						$c = trim( $c );

						if( !empty( $c ) )
							$_classes[] = $c;

					}

					if( $_classes[0] == "widget" )
						$_classes[0] = "potion";
					
					$class = 'chemistry-' . implode( ' ' . 'chemistry-', $_classes );

				}

				if( !empty( $no_prefix_classes ) )
				{

					$_no_prefix_classes = array();

					foreach( $no_prefix_classes as $c )
					{

						$c = trim( $c );

						if( !empty( $c ) )
							$_no_prefix_classes[] = $c;

					}

					$class .= ( !empty( $class ) ? ' ' : '' ) . implode( ' ', $_no_prefix_classes );

				}

				return ( !empty( $class ) ? ' class="' . trim( $class ) . '"' : '' );

			}/* _class() */


			/* =========================================================================== */


			/**
			 * 
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param 
			 * @return 
			 */
			
			public function encode( $code )
			{

				return htmlentities( $code, ENT_NOQUOTES );

			}/* encode() */


			/* =========================================================================== */


			/**
			 * 
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param 
			 * @return 
			 */
			
			public function decode( $code )
			{

				return html_entity_decode( $code, ENT_NOQUOTES );

			}/* decode() */


			/* =========================================================================== */


			/**
			 * 
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param 
			 * @return 
			 */
			
			public function tag_open( $tag, $attr = array(), $single = false )
			{

				return '<' . $tag . $this->attr( $attr ) . ( $single ? ' /' : '' ) . '>';

			}/* tag_open() */


			/* =========================================================================== */


			/**
			 * 
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param 
			 * @return 
			 */
			
			public static function tag_close( $tag, $single = false )
			{

				if( $single )
					return '';

				return '</' . $tag.'>';

			}/* tag_close() */


			/* =========================================================================== */


			/**
			 * 
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param 
			 * @return 
			 */
			
			public static function tag( $tag, $content = '', $attr = array(), $single = false )
			{

				return $this->tag_open( $tag, $attr, $single ) . $content.$this->tag_close( $tag, $single );

			}/* tag() */


			/* =========================================================================== */


			/**
			 * 
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param 
			 * @return 
			 */
			
			public function field( $type, $name, $data = null, $options = array() )
			{

				$value = '';

				if( is_array( $data ) )
				{

					$key = $name;
					$key = str_replace( array( '[', ']' ), array( '', '' ), $key );

					if( isset( $data[$name] ) )
						$value = $data[$name];

				}
				else
				{

					$value = $data;

				}

				if( $type == 'select' )
				{

					$option_list = '';

					if( isset( $options['options'] ) )
						foreach( $options['options'] as $k => $v )
							$option_list .= '<option value="' . $k.'"' . ( $value == $k ? ' selected="selected"' : '' ) . '>' . $v.'</option>';

	 				return '<select' . $this->get_field_atts( $name ) . ' name="' . $this->get_field_name( $name ) . '" value="' . $value.'"' . ( ( isset( $options['class'] ) && !empty( $options['class'] ) ) ? ' class="widefat ' . $options['class'] . '"' : '' ) . '>' . $option_list . '</select>';

				}
				else if( $type == 'textarea' )
				{

					if( !isset( $options['rows'] ) || empty( $options['rows'] ) )
						$options['rows'] = 8;

					return '<textarea' . $this->get_field_atts( $name ) . ' name="' . $this->get_field_name( $name ) . '"' . ( ( isset( $options['rows'] ) && !empty( $options['rows'] ) ) ? ' rows="' . $options['rows'] . '"' : '' ) . ( ( isset( $options['cols'] ) && !empty( $options['cols'] ) ) ? ' cols="' . $options['cols'] . '"' : '' ) . ( ( isset( $options['class'] ) && !empty( $options['class'] ) ) ? ' class="widefat ' . $options['class'] . '"' : '' ) . '>'.htmlspecialchars( $value ) . '</textarea>';
				}
				else
				{

					return '<input' . $this->get_field_atts( $name ) . ' name="' . $this->get_field_name( $name ) . '" type="' . $type.'"' . ( ( isset( $options['class'] ) && !empty( $options['class'] ) ) ? ' class="widefat ' . $options['class'] . '"' : '' ) . ( $type == 'checkbox' ? ( $value == 'on' ? ' checked="checked"' : '' ) : ' value="' . $value.'"' ) . ' />';

				}

			}/* field() */


			/* =========================================================================== */


			/**
			 * 
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param 
			 * @return 
			 */
			
			public function group_field( $type, $name, $index, $data = null, $options = array() )
			{

				if( $data == null || !isset( $data[$name] ) || !isset( $data[$name][$index] ) )
					$value = null;
				else
					$value = $data[$name][$index];

				return $this->field( $type, $name . '][', $value, $options );

			}/* group_field() */


			/* =========================================================================== */


			/**
			 * 
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param 
			 * @return 
			 */
			
			public function group_item( $widget, $index )
			{

				return '';

			}/* group_item() */


			/* =========================================================================== */


			/**
			 * 
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param 
			 * @return 
			 */
			
			public function is_after()
			{

				return $this->after;

			}/* is_after() */

			/* =========================================================================== */

			public function get_slug()
			{

				return $this->slug;

			}/* get_slug() */

			/* =========================================================================== */

			public function get_title()
			{

				return $this->title;

			}/* get_title() */

			/* =========================================================================== */

			public function get_id()
			{

				return $this->id;

			}/* get_id() */

			/* =========================================================================== */

			public function set_id( $id )
			{

				$this->id = $id;

			}/* set_id() */

			/* =========================================================================== */

			public function get_laboratory()
			{

				return $this->laboratory;

			}/* get_laboratory */

			/* =========================================================================== */

			public function set_laboratory( $laboratory )
			{

				$this->laboratory = $laboratory;

			}/* set_laboratory() */

			/* =========================================================================== */

			public function get_row()
			{

				return $this->row;

			}/* get_row() */

			/* =========================================================================== */

			public function set_row( $row )
			{

				$this->row = $row;

			}/* set_row() */

			/* =========================================================================== */

			public function get_column()
			{

				return $this->column;

			}/* get_column() */

			/* =========================================================================== */

			public function set_column( $column )
			{

				$this->column = $column;

			}/* set_column() */

			/* =========================================================================== */

			public function get_label()
			{

				return $this->label;

			}/* get_label() */

			/* =========================================================================== */

			public function get_excerpt()
			{

				return '';

			}/* get_excerpt() */

			/* =========================================================================== */

			public function set_label( $label )
			{

				$this->label = $label;

			}/* set_label() */

			/* =========================================================================== */

			public function show()
			{

				$this->visible = true;

			}/* show() */

			/* =========================================================================== */

			public function hide()
			{

				$this->visible = false;

			}/* hide() */

			/* =========================================================================== */

			public function content_filter( $widget, $content )
			{

				return $content;

			}/* content_filter() */

			/* =========================================================================== */

			public function widget( $widget )
			{

				return '';

			}/* widget() */

			/* =========================================================================== */

			public function form( $widget )
			{

				return '';

			}/* form() */

			/* =========================================================================== */


			/**
			 * Basic Output for each potion in the editor.
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param 
			 * @return 
			 */
			
			public function admin_form( $widget = null )
			{

				return '<div class="molecule-widget-wrapper ' . ( $this->id != '___CHEMISTRYID___' ? 'initialized ' : '' ) . ( $this->is_core() ? 'molecule-widget-core ' : '' ) . 'molecule-widget-type-' . $this->get_slug() . ( !$this->visible ? ' hide' : '' ) . '">
					<div class="molecule-widget potion-' . $this->get_slug() . '">
						<div class="molecule-widget-bar widget widget-top">
							<div class="molecule-widget-title">' . $this->get_title() . '</div>
							<div class="molecule-widget-excerpt">' . $this->get_excerpt() . '</div>
							<div class="molecule-widget-label">' . $this->get_label() . '</div>
							<div class="molecule-widget-actions">
								<a href="#edit" class="edit" title="' . __( 'Edit', 'chemistry' , 'chemistry' ) . '">' . __( 'Edit', 'chemistry' , 'chemistry' ) . '</a>
								<a href="#edit" class="duplicate" title="' . __( 'Duplicate', 'chemistry' , 'chemistry' ) . '">' . __( 'Duplicate', 'chemistry' , 'chemistry' ) . '</a>
								<a href="#remove" class="remove" title="' . __( 'Delete', 'chemistry' , 'chemistry' ) . '">' . __( 'Delete', 'chemistry' , 'chemistry' ) . '</a>
							</div>
						</div>
						' . ( !$this->is_core() ? '<div class="molecule-widget-content closed"><div class="molecule-widget-bar widget widget-top"><div class="molecule-widget-title">' . $this->get_title() . '</div></div><div class="molecule-widget-inner"><div class="molecule-widget-content-form">' . $this->form( $widget ) . '</div><div class="molecule-widget-content-actions">
							<div class="molecule-widget-inner save_or_close">
								<button name="molecule-widget-save" class="save button button-primary">' . __( 'Save', 'chemistry' , 'chemistry' ) . '</button>
								<button name=molecule-modal-close" class="button button-secondary">' . __( 'Close', 'chemistry' ) . '</button>
							</div>
						</div></div></div>' : '' ).
						'<input type="hidden" name="' . Chemistry::chemistry_option( 'prefix' ) . 'molecule_widget[' . $this->laboratory . '][' . $this->row . '][' . $this->column . '][' . $this->id . '][___MODULE___]" value="' . $this->get_slug() . '" />' . ( $this->is_core() ? $this->form( $widget ) . '<input type="hidden" name="' . Chemistry::chemistry_option( 'prefix' ) . 'molecule_widget[' . $this->laboratory . '][' . $this->row . '][' . $this->column . '][' . $this->id . '][__CORE__]" value="true" />' : '' ) . '
					</div>
				</div>';

			}/* admin_form() */


			/* =========================================================================== */

			/**
			 * Several of our potions have a things in common... ABSTRACTION
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param 
			 * @return 
			 */
			
			protected function form_common( $widget )
			{

				$columns = array();
				$rows = array();

				for( $i = 1; $i <= 10; $i++ )
				{

					if( $i == 7 || $i == 9 )
						$i++;

					$columns[$i] = $i;
					$rows[$i] = $i;

				}

				$output = '';
				$output .= '<h2 class="chemistry-tab-title">' . __( 'Grid Settings' , 'chemistry' ) . '</h2>
					<div class="chemistry-tab-content">
						<div class="cols cols-3">
							<div class="col"><label><span class="label-title">' . __( 'Columns' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'columns', $widget, array( 'options' => $columns ) ) . '</label></div>
							<div class="col"><label><span class="label-title">' . __( 'Rows' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'rows', $widget, array( 'options' => $rows ) ) . '</label></div>
							<div class="col">
							<label>' . $this->field( 'checkbox', 'disable_spacing', $widget ) . '<span class="label-title"> ' . __( 'Disable spacing' , 'chemistry' ) . '</span></label>
							</div>
						</div>
					</div>';

				return $output;

			}/* form_common() */


			/* =========================================================================== */


			protected function form_media_frame( $widget )
			{

				$frames = array( 

					'' => __( 'Theme default' , 'chemistry' ),
					'1' => __( 'Frame 1' , 'chemistry' ),
					'2' => __( 'Frame 2' , 'chemistry' )

				 );

				$height = array( 

					'auto' => __( 'Default' , 'chemistry' ),
					'200' => __( '200px' , 'chemistry' ),
					'300' => __( '300px' , 'chemistry' ),
					'400' => __( '400px' , 'chemistry' ),
					'constrain' => __( 'Limit to max 400px' , 'chemistry' )

				 );

				$image_mode = array( 

					'auto' => __( 'Default' , 'chemistry' ),
					'x' => __( 'Stretch X' , 'chemistry' ),
					'y' => __( 'Stretch Y' , 'chemistry' ),
					'fit' => __( 'Fit' , 'chemistry' ),
					'fill' => __( 'Fill' , 'chemistry' )

				 );

				$ratio = array( 

					50 => __( '50%' , 'chemistry' ),
					75 => __( '75%' , 'chemistry' ),
					100 => __( '100%' , 'chemistry' ),
					150 => __( '150%' , 'chemistry' ),
					200 => __( '200%' , 'chemistry' )

				 );

				$output = '';

				$output .= '<div class="cols-2">
						<div class="col">
							<label><span class="label-title">' . __( 'Elements Height' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'height', $widget, array( 'options' => $height, 'class' => 'chemistry-cond chemistry-group-1' ) ) . '</label>
							<label class="chemistry-cond-constrain chemistry-group-1 "><span class="label-title">' . __( 'Constrain Ratio' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'ratio', $widget, array( 'options' => $ratio, 'class' => '' ) ) . '</label>
						</div>
						<div class="col">
							<label><span class="label-title">' . __( 'Frame Style' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'frame', $widget, array( 'options' => $frames ) ) . '</label>
						</div>
					</div>
					<div class="cols-3">
					<div class="col">
						<label><span class="label-title">' . __( 'Image mode' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'image_mode', $widget, array( 'options' => $image_mode ) ) . '</label>
					</div>
					<div class="col">
						<label class="label-alt-1">' . $this->field( 'checkbox', 'disable_lightbox', $widget ) . ' <span class="label-title">' . __( 'Disable lightbox' , 'chemistry' ) . '</span></label>
					</div>
					<div class="col">
						<label>' . $this->field( 'checkbox', 'enable_title', $widget ) . ' <span class="label-title">' . __( 'Enable titles' , 'chemistry' ) . '</span></label>
					</div>
				</div>';

				return $output;

			}/* form_media_frame() */

			/* =========================================================================== */

			protected function form_image_dimensions( $widget )
			{

				$output = '';

				$output .= '<div class="cols-2">
					<div class="col">
						<label><span class="label-title">' . __( 'Image width' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'image_width', $widget ) . '</label>
					</div>
					<div class="col">
						<label><span class="label-title">' . __( 'Image height' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'image_height', $widget ) . '</label>
					</div>
				</div>
				<div class="cols-2 cols">
					<div class="col">
						<label><span class="label-title">' . __( 'Image crop width' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'image_crop_width', $widget ) . '</label>
					</div>
					<div class="col">
						<label><span class="label-title">' . __( 'Image crop height' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'image_crop_height', $widget ) . '</label>
					</div>
				</div>';

				return $output;

			}/* form_image_dimensions() */

			/* =========================================================================== */

			protected function form_widget_general( $widget, $height = false )
			{

				$aligns = array( 

					'' => __( 'Default' , 'chemistry' ),
					'left' => __( 'Left' , 'chemistry' ),
					'right' => __( 'Right' , 'chemistry' ),
					'center' => __( 'Center' , 'chemistry' )

				 );

				$output = '';

				$output .= '<div class="cols cols-' . ( $height == true ? 3 : 2 ) . '">
					<div class="col">
						<label><span class="label-title">' . __( 'Alignment' , 'chemistry' ) . '</span> ' . $this->field( 'select', 'align', $widget, array( 'options' => $aligns ) ) . '</label>
					</div>
					<div class="col">
						<label><span class="label-title">' . __( 'Width' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'width', $widget ) . '</label>
					</div>'
					.( $height == true ? '<div class="col">
						<label><span class="label-title">' . __( 'Height' , 'chemistry' ) . '</span> ' . $this->field( 'text', 'height', $widget ) . '</label>
					</div>
					' : '' ) . '
				</div>';

				return $output;

			}/* form_widget_general */

		}/* class chemistry_molecule_widget */

	}/* !class_exists( 'chemistry_molecule_widget' ) */

?>