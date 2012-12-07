<?php


	/* ======================================================================================

	Meet Chemistry. Chemistry is our content layout creator which enables you to have 
	virtually any combination of content layout on your page. If you're reading this file, 
	I will assume you are either interested to see how we have done things or you're a 
	developer looking for filters and actions.

	Simply put, we've used the WordPress widget system to tack on a separate editor. We use
	some drag and drop javascript libraries to enable you to position the different 'widgets'
	(which we call "molecules" or "modules") however you like.

	The overlay that you see when you 'Add Module' is a combination of our molecules, the 
	WordPress core widgets and any widgets added by plugins.

	Each of our molecules are independent classes - just like WordPress widgets (and they
	actually share code with WP core). You can add your own in a plugin by simply
	extending the chemistry_molecule_widget class.

	For a comprehensive action and filter reference, please see our online help at
	http://support.themeists.com/

	====================================================================================== */


	if( !class_exists( 'chemistry' ) )
	{

		/**
		 * The main Chemistry class. Contains all of all our setup for loading CSS/JS as well as
		 * the Chemistry API methods to help our other classes (such as for event firing, post
		 * retrieval, TinyMCE integration etc.) as well as our actions and filters which we
		 * access throughout our framework.
		 *
		 * @author Richard Tape
		 * @package Chemistry
		 * @since 0.1
		 */

		class Chemistry
		{
			
			/**
			 * Our configuration defaults. We use these throughout Chemistry, mainly for paths
			 * prefixes etc.
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 */
			
			protected static $config = array( 

			    'chemistry_version' 			=> '0.77',
			    'chemistry_date' 				=> '2012-08-20',

				'cache' 						=> false,
				'root' 							=> '',
				'chemistry_root_path' 			=> '',
				
				'prefix' 						=> 'chemistry_',
				'chemistry_uploads_directory'	=> 'chemistry_uploads',
				'chemistry_uploads_url'			=> ''

			 );

			/* =========================================================================== */

			/**
			 * Set up an array for our javascript configuration settings
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.5
			 */
			
			static $chemistry_javascript_config = array(

				'molecule_lang', array( 

					'quit' => 'Close window without saving?',
					'changes' => 'Lose editor changes?',
					'sure' => 'Are you sure?'

				)

			);


			/**
			 * Our generic controller. Basically a placeholder for the different classes of potions
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.1
			 */
			
			static $controller;


			/**
			 * Empty array for our potions. This is a legacy name from when we called them 'modules'
			 * on the back end as well as the front.
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.1
			 * @todo Rename to potion
			 */

			static $modules = array();


			/**
			 * Holder for which WordPress page we're on, we can have different metaboxes, fields, potions
			 * etc. on each different screen
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.4
			 */
			
			static $panel = '';


			/**
			 * Array for our different metaboxes in use across the framework. The main one is the Content
			 * Attributes metabox which allows people to load and save templates (which are collections of
			 * potions) 
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @todo Use the CMB Class we now utilise in our framework. Need to swap out old for new.
			 */
			
			static $metaboxes = array();


			/**
			 * The WordPress Metabox API has sets of permissions for metaboxes. This array allows
			 * us to decide which permissions we can use
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 */
			
			static $metabox_permissions = array();


			/**
			 * An array of bindings which allow us to bind actions to events or requests
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.2
			 */

			static $bindings = array();


			/**
			 * The WordPress wysiwyg editor is the bane of my life. The use of TinyMCE makes even the most
			 * hardened of sodier weep in pain. It has so many bugs and so many spurious settings and is so
			 * very difficult to develop for. This allows us to hook into the wysiwyg editors within WP
			 * and add shortcodes etc.
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.1
			 */

			static $wysiwig = array();


			/**
			 * A simple variable to allow us to check if we've already run our setup routine or not
			 * Set to true at the end of chemistry_prepare()
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.1
			 */
			
			static $__setup = false;


			/**
			 * Simple variable to determine if we have initialised ourselves or not. Set to true
			 * at the end of the init() method
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.1
			 */

			static $__init = false;


			/* =========================================================================== */


			/**
			 * And so it all begins. Set up our actions and filters, preapre our globally needed paths
			 * create our cache and uploads directory of they don't exist and attach a get request
			 * handler for our modal window
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.1
			 * @param None
			 * @return None
			 */
			
			public static function init()
			{

				if( !self::$__init )
				{

					//Prepare a global object for the future, currently not used
					global $chemistry;

					//Set up some paths based on where we reside - allows us to easily drop Chemistry into a theme
					self::$config['root'] = dirname( dirname( __FILE__ ) ) . '/';
					self::$config['chemistry_root_path'] = dirname( dirname( __FILE__ ) ) . '/';

					//Set up our actions and filters, mainly for the modal windows
					add_action( 'init', array( 'chemistry', 'chemistry_prepare' ), 990 );
					add_action( 'init', array( 'chemistry', 'initialise_chemistry_wysiwyg' ) );
					
					add_filter( 'tiny_mce_before_init', array( 'chemistry', 'modify_tinymce_config_before_init' ) );
					add_filter( 'tiny_mce_version', array( 'chemistry', 'modify_tinymce_version_num' ) );
					add_filter( 'image_send_to_editor', array( 'chemistry', 'modify_image_sent_to_editor_from_overlay' ), 10, 8 );
					add_filter( 'media_send_to_editor', array( 'chemistry', 'modify_image_send_to_editor' ), 10, 3 );
					add_filter( 'media_upload_form_url', array( 'chemistry', 'modify_image_upload_form_link' ), 10, 2 );

					//Make sure this is last so all FAQs are above it
					add_action( 'of_set_options_in_help_end', array( 'chemistry', 'chemistry_note_in_footer_of_help_options' ), 999, 1 );

					//Create our cache directory
					if( !is_dir( WP_CONTENT_DIR . '/chemistry-cache' ) )
						mkdir( WP_CONTENT_DIR . '/chemistry-cache', 0755 );

					//And add a .htaccess file for added security
					if( !file_exists( WP_CONTENT_DIR . '/chemistry-cache/.htaccess' ) )
						Chemistry::write_to_file( WP_CONTENT_DIR . '/chemistry-cache/.htaccess', 'order allow,deny' . _n . 'deny from all' . _n );

					//Tell the world we are done!
					self::$__init = true;

				}

			}/* init() */


			/* =========================================================================== */


			/**
			 * Add some hints into the Options panel
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 */
			
			public function chemistry_note_in_footer_of_help_options()
			{

				global $options;

				// Version Numbers ============================================

				$theme = wp_get_theme();
				$theme_version = $theme->Version;
				$theme_name = $theme->Name;
				$chemistry_version = Chemistry::chemistry_option( 'chemistry_version' );
				$wordpress_version = get_bloginfo( 'version' );

				$options[] = array(
					'name' => __( 'What versions of things am I running?', THEMENAME ),
					'desc' => __( '<p>If you have been asked by support to tell us exactly which versions of certain things you are running, you will be able to find them listed here:</p><p>Theme: <strong>' . $theme_name . ' ( v' . $theme_version . ' )</strong></p><p>Chemistry: <strong>' . $chemistry_version . '</strong></p><p>WordPress: <strong>' . $wordpress_version . '</strong></p>', THEMENAME ),
					'id' => 'theme_question_one',
					'std' => '',
					'type' => 'qna'
				);

				// Version Numbers  ============================================

			}


			/* =========================================================================== */


			/**
			 * Mostly sanity checking and then loading ourselves. This is hooked into init
			 * so ensure we're not doing anything ridiculously expensive. Sets up several
			 * internal actions, creates some directories which we use if they don't exist,
			 * sets up our admin and deals with the get and post requests. Super.
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.1
			 * @param None
			 * @return Money for nothing?
			 */
			
			public static function chemistry_prepare()
			{

				//We haven't already done this, have we?
				if( !self::$__setup )
				{

					//Where are WordPress's uploads?
					$wp_uploads_dir = wp_upload_dir();

					$upload_dir = $wp_uploads_dir['basedir'];

					//Can we write to this directory?
					if( is_writable( $wp_uploads_dir['basedir'] ) )
					{

						//If so, let's make our own wee directory to put our things. Keeps them neat and tidy
						$upload_dir .= '/' . self::chemistry_option( 'chemistry_uploads_directory' );

						if( !file_exists( $upload_dir ) )
							mkdir( $upload_dir );

					}

					//Let's make sure we have a record of the uploads dir
					self::chemistry_option( 'chemistry_uploads_url', $wp_uploads_dir['baseurl'] . '/' . self::chemistry_option( 'chemistry_uploads_directory' ) );
					self::chemistry_option( 'chemistry_uploads_directory', $upload_dir );

					//Build our potions. 
					foreach( self::$modules as $module )
					{

						$module_node = explode( '.', $module );
						$module_name = array_pop( $module_node );

						$module_class = self::chemistry_option( 'prefix' ) . str_replace( '-', '_', self::uglify( str_replace( '.', '-', $module ) ) );

						if( class_exists( $module_class ) )
						{

							call_user_func_array( array( $module_class, 'chemistry_set_class_singleton' ), array( $module_class ) );

							self::module_run( str_replace( self::chemistry_option( 'prefix' ), '', $module_class ) . '.potion' );
							self::module_run( str_replace( self::chemistry_option( 'prefix' ), '', $module_class ) . '.init' );

						}
					}

					//We have admin-specific css/js/methods, let's make sure we only run them in the admin
					if( is_admin() )
						self::setup_chemistry_admin();

					//And so it begins
					do_action( 'chemistry_setup' );

					//Intercept get/post/ajax requests for our overlays
					if( !empty( $_GET ) )
						self::fired_by( 'get', array( $_GET ) );

					if( !empty( $_POST ) )
						self::fired_by( 'post', array( $_POST ) );

					if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )
						self::fired_by( 'ajax', array( $_GET ) );

					//Make sure we let the world know we are open for business. 
					self::$__setup = true;

				}

			}/* chemistry_prepare() */


			/* =========================================================================== */


			/**
			 * Helper method to allow all of our classes to easily get the Chemistry config
			 * option that they require. Simple key/value pair from our $config array.
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.4
			 * @param (mixed) $key - The key, or array of keys of options we need
			 * @param (mixed) $value - Check the value
			 * @return (string) The appropriate option
			 */
			
			public static function chemistry_option( $key, $value = null )
			{

				//If we haven't set a value to check or we have an array of keys and value is still not set
				if( $value !== null || ( is_array( $key ) && empty( $value ) ) )
				{

					//If we have an array of keys, return back to ourself as individual options
					if( is_array( $key ) )
						foreach( $key as $k => $v )
							self::chemistry_option( $k, $v );
					else
						self::$config[$key] = $value;

				}
				else
				{

					//If we have a value
					if( isset( self::$config[$key] ) )
						return self::$config[$key];

					//Should it all go wrong
					return false;

				}


			}/* chemistry_option() */


			/* =========================================================================== */


			/**
			 * Allow us to easily configure what our js does across Chemistry. This is a getter
			 * and setter method
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.5
			 * @param (string) $key - The array key for the option
			 * @param (string) $value - Are we setting a value?
			 * @return The config or false
			 */
			
			public static function chemistry_optionjs( $key, $value = null )
			{

				//Set the key to the value
				if( $value !== null || ( is_array( $key ) && empty( $value ) ) )
				{

					self::$chemistry_javascript_config[$key] = $value;

				}
				else
				{

					//Get the key's value
					if( isset( self::$chemistry_javascript_config[$key] ) )
						return self::$chemistry_javascript_config[$key];

					//Just in case it all goes horribly wrong
					return false;

				}

			}/* chemistry_optionjs() */


			/* =========================================================================== */


			/**
			 * A simple way to attach a callback to an event, a very simple internal replica of
			 * WordPress's actions/filters
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (string) $event -
			 * @param (string) $callback -
			 * @return 
			 */
			
			/*public static function attach( $event, $callback )
			{

				if( !isset( self::$bindings[$event] ) )
					self::$bindings[$event] = array();

				self::$bindings[$event][] = $callback;

			}*//* attach() */


			/* =========================================================================== */


			/**
			 * Give us an easy way to determine what method is firing an action
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (string) $event - which event to fire post/get/request etc.
			 * @param (array) $args - What has been called (Can be more than one)
			 * @return None
			 */
			
			public static function fired_by( $event, $args = array() )
			{
				
				if( isset( self::$bindings[$event] ) )
					foreach( self::$bindings[$event] as $callback )
						call_user_func_array( $callback, $args );

			}/* fired_by() */


			/* =========================================================================== */


			/**
			 * Call our modules (the classes which create our potions). This is called during
			 * setup and loads the modules/{file}.php - First we check if we've not already
			 * initialised this widget then we go ahead and grab it.
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.1
			 * @param (string) $module - The name of the module we which to add
			 * @return None
			 */
			
			public static function module( $module )
			{

				//If we're not already initialised
				if( !in_array( $module, self::$modules ) )
				{

					//Get the actual module name
					$module_node = explode( '.', $module );
					$module_name = array_pop( $module_node );

					//Get the classname
					$module_class_name = self::chemistry_option( 'prefix' ) . str_replace( '-', '_', self::uglify( str_replace( '.', '-', $module ) ) );

					if( !class_exists( $module_class_name ) )
						require_once locate_template( '/framework/chemistry/modules/' . $module . '.php' );

					//Add it so we don't load it twice
					self::$modules[] = $module;

				}

			}/* module() */


			/* =========================================================================== */


			/**
			 * This is also a sanity check when we're preparing to set up Chemistry. It actually
			 * calls all of our potions (and their respective inits) if they exist.
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.4
			 * @param Several, mainly a class name (potion) and a method to run if necessary
			 * @return The result of the method called or false if we're being silly
			 */
			
			public static function module_run()
			{

				//Get our passed args and split to find which potion and method to call
				$args = func_get_args();
				$name = array_shift( $args );
				$potion = explode( '.', $name );

				//Which method of that potion shall we call?
				$method = array_pop( $potion );

				//One at a time, please
				if( count( $potion ) == 1 )
				{

					if( self::module_exists( implode( '.', $potion ) ) )
						return call_user_func_array( array( self::chemistry_option( 'prefix' ) . str_replace( '-', '_', self::uglify( str_replace( '.', '-', implode( '.', $potion ) ) ) ), $method ), $args );

				}

				return false;

			}/* module_run() */


			/* =========================================================================== */


			/**
			 * When we're setting ourselves up, we need to run a sanity check that the class
			 * we are calling actuallt exists. This is a wrapper for that.
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.3
			 * @param (string) $name - The name of the potion we're checking
			 * @return (bool)
			 */
			
			public static function module_exists( $name )
			{
				
				return class_exists( self::chemistry_option( 'prefix' ) . str_replace( '-', '_', self::uglify( str_replace( '.', '-', $name ) ) ) );

			}/* module_exists() */



			/* =========================================================================== */


			/**
			 * For several of our potions we need to grab some posts. This is a wrapper function for
			 * get_posts() which allows us to build arguments easily and return the posts. Most of this
			 * is from http://chopapp.com/#6vs2aqlz
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.3
			 * @param (mixed) $args - Ideally and array, possibly a string of args to pass to get_posts()
			 * @param (mixed) $time_format - If we are filtering by time, we need a time format
			 * @return (array) The posts
			 * @todo Use WP_Query for caching
			 */
			
			public static function retrieve_posts( $args = 'numberposts=1&post_type=post&text_opt=excerpt', $time_format = 'F j, Y' )
			{

				//Ensure we globalise the post object and store the current post being shown (for custom order)
				global $post;
				$last_post = $post;

				//Start fresh
				$result = array();

				//If $args is a string, parse it as a query string and provide an array
				if( !is_array( $args ) )
					parse_str( $args, $args );

				//Merge some defaults with the passed args
				$defaults = array( 'text_opt' => 'content' );
				$args = array_merge( $defaults, $args );
				
				//Start meta args afresh
				$meta = array();

				//If we're doing a meta query, let's separate those
				if( isset( $args['meta'] ) )
				{

					$meta = $args['meta'];
					unset( $args['meta'] );

				}

				//Not a custom order by default...but
				$custom_order = false;

				//If we have orderby or post__in in our args, we'll need a custom order
				if( isset( $args['post__in'] ) && !empty( $args['post__in'] ) && $args['orderby'] == 'custom' )
				{

					unset( $args['orderby'] );
					$custom_order = true;

				}

				//Now we have a properly formed arg array, let's grab some posts
				$posts = get_posts( $args );

				//Set our output up
				$result = array();

				//As long as we actually have some posts
				if( !empty( $posts ) )
				{

					//For each post, add it to an array which we return later
					foreach( $posts as $post )
					{

						setup_postdata( $post );
						array_push( $result, array( 

							'id' => get_the_ID(),
							'permalink' => get_permalink(),
							'title' => get_the_title(),
							'author' => get_the_author(),
							'date' => get_the_date(),
							'date_ymd' => get_the_date( 'Y-m-d' ),
							'timestamp' => get_the_time( 'U' ),
							'author_link' => get_the_author_link(),
							'content' => ( $args['text_opt'] == 'excerpt' ? get_the_excerpt() : ( $args['text_opt'] == 'content' ? get_the_content() : '' ) )

						 ) );

					}

				}

				//If we *are* doing a meta query
				if( !empty( $meta ) )
				{

					//Start afresh
					$result_meta = array();

					foreach( $result as $p )
					{

						$p['meta'] = array();

						foreach( $meta as $meta_key )
							$p['meta'][$meta_key] = self::get_or_set_meta( $meta_key, true, $p['id'] );

						$result_meta[] = $p;

					}

					$result = $result_meta;

				}

				//Are we doing a custom order? If so, we need to make sure we don't mess up orur orig. post
				if( $custom_order )
				{

					$posts_ = array();
					$ids = array();
					$ids_ = $args['post__in'];
					$counter = 0;

					foreach( $ids_ as $k => $v )
					{

						$ids[$v] = $counter;
						$counter++;

					}

					foreach( $result as $p )
						$posts_[intval( $ids[$p['id']] )] = $p;

					$result = $posts_;

					ksort( $result );

				}

				//Make sure we don't mess up other loops on the page
				wp_reset_query();
				$post = $last_post;

				//Bazinga!
				return $result;

			}/* retrieve_posts() */


			/* =========================================================================== */


			/**
			 * A very simple related posts method in case people don't use a plugin
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (array) $args - Args for our query_posts query
			 * @param (array) $taxonomies - Do we limit to any taxonomies
			 * @param (string) $relation - What sort of relationship AND/OR/NOT etc.
			 * @param (string) $operator - mysql operator
			 * @return The related posts array from chemistry::retrieve_posts()
			 */
			
			public static function retrieve_related_posts( $args, $taxonomies = array( 'category', 'post_tag' ), $relation = 'OR', $operator = 'LIKE' )
			{

				//Let's just make sure we have a post object
				global $post;
				$related_posts = array();

				//Build our taxonomy query
				$tax_query = array( 'relation' => $relation );

				//Ensure we have an array for taxonomies as that's required by query_posts
				if( !is_array( $taxonomies ) )
					$taxonomies = array( $taxonomies );

				foreach( $taxonomies as $tax )
				{

					$terms = wp_get_object_terms( $post->ID, $tax );

					if( $terms )
					{

						$terms_ids = array();

						foreach( $terms as $term )
							$terms_ids[] = $term->term_id;

						$tax_query[] = array( 

							'taxonomy' => $tax,
							'field' => 'id',
							'terms' => $terms_ids,
							'operator' => $operator

						 );

					}

				}

				//Build the args
				$args['tax_query'] = $tax_query;
				$args['post__not_in'] = array( $post->ID, $post->post_parent );

				//Go ahead and get the posts
				$related_posts = self::retrieve_posts( $args );

				return $related_posts;

			}/* retrieve_related_posts() */


			/* =========================================================================== */


			/**
			 * Process option retrieval
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.2
			 * @param (string) $key - Simple key for the option
			 * @param (string) $method - What sort of request is it
			 * @return Appropriate option or false if the world ends
			 */
			
			public static function get_and_process_option_save( $key, $method = '' )
			{

				//Methods sometimes aren't always all uppercase or all lowercase. Let's just be safe.
				$method = strtolower( $method );

				switch ( $method )
				{

					case 'get':
						if( isset( $_GET[self::chemistry_option( 'prefix' ) . $key] ) )
							return $_GET[self::chemistry_option( 'prefix' ) . $key];

					break;

					case 'post':
						if( isset( $_POST[self::chemistry_option( 'prefix' ) . $key] ) )
							return $_POST[self::chemistry_option( 'prefix' ) . $key];

					break;

					case 'file':
						if( isset( $_FILES[self::chemistry_option( 'prefix' ) . $key] ) )
							return $_FILES[self::chemistry_option( 'prefix' ) . $key];

					break;

					default:
						if( isset( $_REQUEST[self::chemistry_option( 'prefix' ) . $key] ) )
							return $_REQUEST[self::chemistry_option( 'prefix' ) . $key];

					break;

				}

				return false;

			}/* get_and_process_option_save() */


			/* =========================================================================== */


			/**
			 * Handle all of the different types of requests and create some options for us
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.1
			 * @param (string) $key - Simple key for the option
			 * @param (string) $method - What sort of request is it
			 * @return None
			 */
			
			public static function process_request_check_empty( $key, $method = '' )
			{

				//Methods sometimes aren't always all uppercase or all lowercase. Let's just be safe.
				$method = strtolower( $method );

				switch ( $method )
				{

					case 'get':
						return isset( $_GET[self::chemistry_option( 'prefix' ) . $key] ) && !empty( $_GET[self::chemistry_option( 'prefix' ) . $key] );
					break;

					case 'post':
						return isset( $_POST[self::chemistry_option( 'prefix' ) . $key] ) && !empty( $_POST[self::chemistry_option( 'prefix' ) . $key] );
					break;

					case 'file':
						return isset( $_FILES[self::chemistry_option( 'prefix' ) . $key] ) && !empty( $_FILES[self::chemistry_option( 'prefix' ) . $key] );
					break;

					default:
						return isset( $_REQUEST[self::chemistry_option( 'prefix' ) . $key] ) && !empty( $_REQUEST[self::chemistry_option( 'prefix' ) . $key] );
					break;

				}

			}/* process_request_check_empty() */


			/* =========================================================================== */


			/**
			 * Intercept the call from the image upload to 'send to editor' by hitting the 
			 * image_send_to_editor filter with a hammer. Bless this filter for having 8
			 * parameters
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.3
			 * @param (string) $html - The html passed to the overlay
			 * @param (string) $id - The post id from the filter
			 * @param (string) $caption - The image caption from the filter
			 * @param (string) $title - The title of the image (title tag)
			 * @param (string) $align - helper for the class for the image alignment
			 * @param (string) $url - the shadowbox url
			 * @param (string) $size - size of the image from the filter
			 * @param (string) $alt - Alt Text for the image
			 * @return (string) The output or the html
			 */
			
			public static function modify_image_sent_to_editor_from_overlay( $html, $id, $caption, $title, $align, $url, $size, $alt )
			{

				//Start afresh
				$output = '';

				//Definitely from Chemistry, right?
				if( isset( $_GET['chemistry'] ) && $_GET['chemistry'] == 'true' )
				{

					//build the output
					$output = $url;

					if( !empty( $output ) )
					{

						//Full overlay url with anchor please
						if( isset( $_GET['output'] ) && $_GET['output'] == 'html' )
							return '<a href="'.self::shadowbox_href( $url ).'" class="align'.$align.'"><img src="'.$output.'" alt="'.$alt.'" title="'.$title.'" /></a>';
						else
							return $output; //Just image src

					}

					//Fire a call to modify_wp_image_editor() with our bits and bobs
					$html = call_user_func_array( array( 'chemistry', 'modify_wp_image_editor' ), array( $html, $id, $caption, $title, $align, $url, $size, $alt ) );

				}

				return $html;

			}/* modify_image_sent_to_editor_from_overlay() */


			/* =========================================================================== */


			/**
			 * If we have html from modify_image_sent_to_editor_from_overlay() then we need
			 * to build an overlay link 
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.3
			 * @param (string) $html - The html passed to the overlay
			 * @param (string) $id - The post id from the filter
			 * @param (string) $caption - The image caption from the filter
			 * @param (string) $title - The title of the image (title tag)
			 * @param (string) $align - helper for the class for the image alignment
			 * @param (string) $url - the shadowbox url
			 * @param (string) $size - size of the image from the filter
			 * @param (string) $alt - Alt Text for the image
			 * @return (string) The html for the overlay
			 */
			
			public static function modify_wp_image_editor( $html, $id, $caption, $title, $align, $url, $size, $alt )
			{

				//Start clean
				$output = '';

				//Run a series of regex to clean up our image and make sure we're actually dealing with an image
				preg_match( '/( href=[\'"] )( .*? )( [\'"] )/i', $html, $href );
				preg_match( '/( src=[\'"] )( .*? )( [\'"] )/i', $html, $src );
				preg_match( '/( width=[\'"] )( .*? )( [\'"] )/i', $html, $width );
				preg_match( '/( height=[\'"] )( .*? )( [\'"] )/i', $html, $height );

				$href = ( isset( $href[2] ) ? $href[2] : null );
				$src = ( isset( $src[2] ) ? $src[2] : null );
				$width = ( isset( $width[2] ) ? $width[2] : null );
				$height = ( isset( $height[2] ) ? $height[2] : null );

				//Build our url
				if( !empty( $href ) && !empty( $src ) )
					$output .= '<a href="' . self::shadowbox_href( $href ) . '" class="align' . $align . '">';

				if( !empty( $src ) )
					$output .= '<img src="' . $src . '" ' . ( empty( $href ) ? 'class="align' . $align . '" ' : '' ) . 'alt="' . $alt.'"' . (  !empty( $width ) ? ' width="' . $width . '"' : '' ) . (  !empty( $height ) ? ' height="' . $height . '"' : '' ) . ' title="' . $title . '" />';

				if( !empty( $href ) && !empty( $src ) )
					$output .= '</a>';

				if( !empty( $output ) )
					return $output;

				return $html;

			}/* modify_wp_image_editor() */


			/* =========================================================================== */


			/**
			 * The last of our functions to deal with our image tab. Basically deals with
			 * the type of output passed from the upload media dialogue. Intercepts it
			 * by filtering media_send_to_editor
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (string) $html - The html passed from the dialogue
			 * @param (string) $id - The ID of the post from the filter
			 * @param (string) $attachment - The attachment object passed from the filter
			 * @return (string) The url modified or the html depending on the output type
			 */
			
			public static function modify_image_send_to_editor( $html, $id, $attachment )
			{

				//Start with a clean slate
				$output = '';

				//Are we coming from the chemistry overlay and are we not on an image
				if( isset( $_GET['chemistry'] ) && $_GET['chemistry'] == 'true' && (  !isset( $_GET['type'] ) || $_GET['type'] != 'image' ) )
				{

					$output = $html;

					if( !empty( $output ) )
					{

						//html please
						if( isset( $_GET['output'] ) && $_GET['output'] == 'html' )
						{
							return $output;
						}
						else
						{

							//Just the url
							preg_match_all( '/( href=[\'|"] )( .*? )( [\'|"] )/i', $html, $href );

							if( count( $href[2] ) > 0 )
								return $href[2][0];

						}

					}

				}

				return $html;

			}/* modify_image_send_to_editor() */


			/* =========================================================================== */


			/**
			 * Sometimes we need to adjust the dimensions of the overlay, we do so by 
			 * building a fully formed url. This function allows us to build that easily
			 * by filtering media_upload_form_url
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.3
			 * @param (string) $url - The url start point
			 * @param (string) $type - Type of overlay (2nd param required by filter)
			 * @return (string) The formed URL
			 */
			
			public static function modify_image_upload_form_link( $url, $type )
			{

				if( isset( $_GET['chemistry'] ) && $_GET['chemistry'] == 'true' )
				{

					//We need to have this otherwise we don't get our overlay
					$url .= '&chemistry=true';

					//Add the width if set
					if( isset( $_GET['width'] ) )
						$url .= '&width=' . $_GET['width'];

					//Add the height if set
					if( isset( $_GET['height'] ) )
						$url .= '&height=' . $_GET['height'];

					//What sort of output is it?
					if( isset( $_GET['output'] ) )
						$url .= '&output=' . $_GET['output'];

					//Is it single use?
					if( isset( $_GET['single'] ) )
						$url .= '&single=' . $_GET['single'];

					//What tab by default?
					if( isset( $_GET['tab'] ) )
						$url .= '&tab=' . $_GET['tab'];

				}

				//Done and done!
				return $url;

			}/* modify_image_upload_form_link() */


			/* =========================================================================== */


			/**
			 * Sometimes we need to check if we are on http/https, or on specific servers or ports
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.2
			 * @param None
			 * @return (string) $url - The correct url
			 */
			
			public static function return_proper_url()
			{

				$url = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' ) ? 'https://' : 'http://';

				if( $_SERVER['SERVER_PORT'] != '80' )
					$url .= $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
				else
					$url .= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

				return $url;

			}/* return_proper_url() */


			/* =========================================================================== */


			/**
			 * Important function. Allows us to get the proper path to our chemistry/ folder
			 * within our theme. We pass the end result through chemistry_path so it's adjustable
			 * from elsehwhere i.e. should we wish to stick this in a plugin in the future
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.1
			 * @param (string) $path - What part after '/chemistry/' are we looking for
			 * @param (bool) $return - return or echo
			 * @return (string) The edited and filtered path
			 * @todo 3.5+ is bringing theme_url()
			 */
			
			public static function path( $path, $return = false )
			{

				//We need to find which theme we're in
				if( function_exists( 'wp_get_theme' ) )
				{

					//3.4+
					$theme_data = wp_get_theme();

					if( !defined( 'THEMENAME' ) )
						define( 'THEMENAME', $theme_data->Template );

				}
				else
				{

					//3.0+
					$theme_data = get_theme_data( get_stylesheet_directory_uri() );

					if( !defined( 'THEMENAME' ) )
						define( 'THEMENAME', strtolower( $theme_data['Name'] ) );

				}

				//Build the path
				$path = WP_CONTENT_URL . '/themes/' . THEMENAME . '/framework/chemistry/' . trim(  $path, '/'  );

				if( $return )
					return apply_filters( 'chemistry_path', $path );
				else
					echo apply_filters( 'chemistry_path', $path );

			}/* path() */


			/* =========================================================================== */


			/**
			 * Sometimes we need to check certain WP options (such as for visual editor/html editor)
			 * This is a helper function to allow us to get or set a WP option's value
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.2
			 * @param (string) $key - Which option do we need?
			 * @param (string) $value - Default?
			 * @return (string) The option or null depending on what we're doing
			 */
			
			public static function get_or_set_option( $key, $value = null )
			{

				if( $value === null )
				{

					$option = get_option( self::chemistry_option( 'prefix' ).$key );

					return $option;

				}
				else
				{

					update_option( self::chemistry_option( 'prefix' ).$key, $value );

				}

				return '';

			}/* get_or_set_option() */


			/* =========================================================================== */


			/**
			 * We use a *lot* of meta in Chemistry. Fortunately it's all proper WP Meta so we can
			 * use their post meta API to handle the getting and setting. Yay for WP. This is
			 * basically a helper function to allow us to get or set meta very easily without having
			 * to worry about the params for *_post_meta
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.1
			 * @param (string) $key - The meta key to fetch
			 * @param (bool) $single - Are we retrieving just one value? Probably are, default to true
			 * @param (string) $id - Do we have a specific ID in mind?
			 * @param (bool) $update - Are we updating or just retrieving?
			 * @return If get_ then return the data, otherwise null after update_ routine
			 */
			
			public static function get_or_set_meta( $key, $single = true, $id = null, $update = false )
			{

				//Hidden is false by default, but...
				$hidden = false;

				//If our $key starts with an underscore, it's hidden
				if( substr( $key, 0, 1 ) == '_' )
				{

					$hidden = true;
					$key = trim( $key, '_' );

				}

				//If we're get_ ing not update_ ing
				if( !$update )
				{

					global $post;

					$fields = get_post_meta( $id != null ? $id : $post->ID, ( $hidden ? '_' : '' ).self::chemistry_option( 'prefix' ) . $key, $single );

					return $fields;

				}
				else
				{

					global $post;

					update_post_meta( $id != null ? $id : $post->ID, ( $hidden ? '_' : '' ) . self::chemistry_option( 'prefix' ) . $key, $single );

				}

				//Fin
				return null;

			}/* get_or_set_meta() */


			/* =========================================================================== */


			/**
			 * Abstract the flickr feed call so we can use it in several places if necessary.
			 * simply polls the flickr api and returns the images based on the tags passed
			 * from cache if it exists, otherwise builds the cache then returns them
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.3
			 * @param (string) $id - The FlickrID of the person 
			 * @param (int) $count - How many to show
			 * @param (string) $tags - Which tags to filter by
			 * @return (array) - Filtered array of feed info
			 */
			
			public static function flickr_feed( $id = null, $count = 1, $tags = '' )
			{

				//Start with an empty array so we're not returning nadda
				$feed = array();

				//Well, we need an ID, otherwise what? We'll just show ALL THE WORLD'S PHOTOS? Fool
				if( $id == null )
					return false;

				//We have a user ID, super, let's move on
				if( $id != '' )
				{

					//Do we have a cached copy?
					$cache = self::cache_it( 'flickr_feed' . $id . $count . $tags, null );

					//If we do, return that
					if( $cache !== false )
						return $cache;

					//Otherwise, build the api url based on the passed params
					$flickr_url = 'http://api.flickr.com/services/feeds/photos_public.gne?id=' . $id . '&format=php_serial' . (  !empty( $tags ) ? '&tags=' . $tags : '' );
					$flickr = wp_remote_retrieve_body( wp_remote_get( $flickr_url ) );
					$flickr = unserialize( $flickr );

					//If we have a negative(!) or 0-count, let's show 10
					if( $count <= 0 )
						$count = 10;

					//We have stuff?
					if( $flickr !== false )
					{

						for( $i = 0; $i < $count; $i++ )
						{

							$feed[] = array( 

								'title' => $flickr['items'][$i]['title'],
								'link' => $flickr['items'][$i]['url'],
								'image' => $flickr['items'][$i]['photo_url'],
								'thumbnail' => $flickr['items'][$i]['t_url']

							 );

						}

					}

				}

				//Cache our feed the return it
				self::cache_it( 'flickr_feed' . $id . $count . $tags, $feed );

				return apply_filters( 'chemistry_flickr_feed', $feed );

			}/* flickr_feed() */


			/* =========================================================================== */


			/**
			 * The markup builder for our Google Map potion. It's basically an iframe with some
			 * parameters and is really nothing special. Considering removing this in favour of 
			 * a fully-fledged google map plugin
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.3
			 * @param (string) - $location - The search parameter for google maps (i.e. where we are showing)
			 * @param (string) - $width - The width of the iframe
			 * @param (string) - $height - the height of the iframe
			 * @param (string) - $zoom - what zoom level
			 * @param (string) - $view - whether to show satelltie, map or terrain
			 * @param (bool) - $hide_bubble - The marker by default has the address, hide it?
			 * @param (bool) - $return - Do we want to echo or return the markup
			 * @return either echo or return the map markup
			 */
			
			public static function google_map( $location = null, $width = null, $height = null, $zoom = 14, $view = 0, $hide_bubble = true, $return = false )
			{

				//We kinda need to know where to centre the map, doofus
				if( $location == null )
					return false;

				//If we have some ridiculous number for the view, let's default to map
				if( $view < 0 || $view > 2 )
					$view = 0;

				//Our 3 possible views
				$views = array( 'm', 'k', 'p' );

				//Ensure there's no nonsense in the passed location
				$location = htmlspecialchars( $location );

				//Build the iframe markup
				$map = '<iframe'.( $width != null ? ' width="'.$width.'"' : '' ).( $height != null ? ' height="'.$height.'"' : '' ).' frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?q='.$location.'&t='.$views[$view].'&z='.$zoom.( $hide_bubble ? '&iwloc=' : '' ).'&output=embed"></iframe>';

				if( $return )
					return apply_filters( 'chemistry_google_map_markup', $map );

				echo apply_filters( 'chemistry_google_map_markup', $map );

			}/* google_map() */


			/* =========================================================================== */


			/**
			 * We have a couple of uses for a twitter feed for various widgets and we may need
			 * this abstracted in future for different molecules. So, let's have it as part of
			 * the main class rather than in an individual molecule. Simply uses the  Twitter API
			 * to get the y-number of latest tweets from x-username. Caches it too so we're not
			 * polling the API on every request as that would just be very silly
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.2
			 * @param (string) $username - The Twitter handle
			 * @param (string) $count - How many tweets do we want to show?
			 * @return (array) $tweets - Array of tweets with text, time and link
			 */
			
			public static function twitter_feed( $username = null, $count = 1 )
			{

				//No username? You're a nincompoop
				if( $username == null )
					return false;

				//Set up an array for us to place our tweets
				$tweets = array();

				if( $username != '' )
				{

					//Set up or check a cache file for us
					$cache = self::cache_it( 'twitter_tweets' . $username . $count, null );

					//If we have some cache and it hasn't expired, let's use that
					if( $cache !== false && is_array( $cache ) && count( $cache ) > 0 )
						return $cache;

					//Build the API url
					$twitter_url = 'http://api.twitter.com/1/statuses/user_timeline.json?include_entities=true&screen_name=' . $username . '&count=' . $count;

					//Poll that url
					$twitter = wp_remote_retrieve_body( wp_remote_get( $twitter_url ) );

					//And decode the result (it's a json object)
					$twitter = json_decode( $twitter );

					//As long as Twitter hasn't burped, let's move on
					if( !isset( $twitter->error ) || !$twitter->error )
					{

						foreach( $twitter as $tweet )
						{

							//Remove any sillyness
							$tweet->text = strip_tags( $tweet->text );

							//Run through some crazy for links/@-mentions, hashtag etc.
							$tweet->text = ' '.preg_replace(  "/( ( [[:alnum:]]+:\/\/ )|www\. )( [^[:space:]]* )( [[:alnum:]#?\/&=] )/i", "<a href=\"\\1\\3\\4\" target=\"_blank\">\\1\\3\\4</a>", $tweet->text );
							$tweet->text = preg_replace(  "/( ( [a-z0-9_]|\\-|\\. )+@( [^[:space:]]* )( [[:alnum:]-] ) )/i", "<a href=\"mailto:\\1\">\\1</a>", $tweet->text );
							$tweet->text = preg_replace(  "/ +@( [a-z0-9_]* ) ?/i", " <a href=\"http://twitter.com/\\1\">@\\1</a> ", $tweet->text );
							$tweet->text = preg_replace(  "/ +#( [a-z0-9_]* ) ?/i", " <a href=\"http://twitter.com/search?q=%23\\1\">#\\1</a> ", $tweet->text );
							$tweet->text = preg_replace( "/>( ( [[:alnum:]]+:\/\/ )|www\. )( [^[:space:]]{30,40} )( [^[:space:]]* )( [^[:space:]]{10,20} )( [[:alnum:]#?\/&=] )</", ">\\3...\\5\\6<", $tweet->text );
							$tweet->text = trim( $tweet->text );

							//Do we - after all our regexes - actually have some content? Well hot darn let's use it
							if( $tweet->text != '' )
							{

								array_push( $tweets, array( 

									'tweet' => $tweet->text,
									'time' => self::twitter_time_ago( strtotime( str_replace( '+0000', '', $tweet->created_at ) ) ),
									'link' => 'http://twitter.com/' . $username . '/statuses/' . $tweet->id

								 ) );

							}

						}

					}

				}

				//Push our new tweets to our cache file
				self::cache_it( 'twitter_tweets' . $username . $count, $tweets );

				//Bingo bango! Return the array of tweets
				return $tweets;

			}/* twitter_feed() */


			/* =========================================================================== */


			/**
			 * Fancy 'Time ago' times for the Twitter feed. This function was a long hash of
			 * horribleness but has now been replaced by the rather wonderful human_time_diff
			 * in WordPress. 
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.3
			 * @param (string) $time - Time of the tweet
			 * @return (string) <some amount of time> ago
			 * @todo Remove this function and replace instances of ::twitter_time_ago with human_time_ago()
			 */
			
			public static function twitter_time_ago( $time )
			{

				return human_time_diff( $time, current_time( 'timestamp' ) ) . __( ' ago', 'chemistry' );

			}/* twitter_time_ago() */


			/* =========================================================================== */


			/**
			 * For our video players or shadow boxes we need some video markup. WP handles most of 
			 * this stuff natively, but for those occassions where it doesn't, we need to 
			 * step in. Simply builds a url based on the passed params
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.2
			 * @param (string) $url - The original url we're checking for videoishness
			 * @param (string) $width - The width of said video
			 * @param (string) $height - The height of said video (normally for iframes) 
			 * @param (string) $class - Any specific classes for the iframe?
			 * @return (string) The iframe markup for our video
			 */
			
			public static function video( $url, $width = null, $height = null, $class = '' )
			{

				//Must start with http:// otherwise the world ends
				if( substr( $url, 0, 4 ) != 'http' )
					$url = 'http://'.$url;

				//Whitespace gives you arachnophobia. Something like that anyway.
				$url = trim( $url );

				//Start with a default
				$video_url = '';

				//Any params we should know about? I hope so ;)
				$url_data = parse_url( $url );

				//www?
				if( isset( $url_data['host'] ) )
					$url_data['host'] = str_replace( 'www.', '', $url_data['host'] );


				//Which provider?
				if( isset( $url_data['host'] ) )
				{

					if( $url_data['host'] == 'youtube.com' )
					{

						$params = explode( '?', $url );
						parse_str( html_entity_decode( $params[1] ), $params );

						foreach( $params as $k => $v )
						{

							if( strtolower( $k ) == 'v' )
							{

								$video_url = 'http://www.youtube.com/embed/'.$v;

								break;

							}

						}

						if( count( $params ) == 0 && strpos( $url, '/embed/' ) !== false )
							$video_url = $url;

					}
					else if( $url_data['host'] == 'vimeo.com' || $url_data['host'] == 'player.vimeo.com' )
					{

						preg_match( '/( \d+ )/', $url, $id );

						if( !empty( $id[1] ) )
							$video_url = 'http://player.vimeo.com/video/'.$id[1];

					}
					else if( $url_data['host'] == 'blip.tv' )
					{

						preg_match( '/file\/( \d+ )\//', $url, $id );

						if( !empty( $id[1] ) )
							$video_url = 'http://blip.tv/play/'.$id[1];

					}

				}

				//No, silly!
				if( empty( $video_url ) )
					return '';

				//Build our markup
				$video_markup =  '<iframe src="'.$video_url.'"'.(  !empty( $class ) ? ' class="'.$class.'"' : '' ).' width="'.(  !empty( $width ) ? $width : '480' ).'" height="'.(  !empty( $height ) ? $height : '290' ).'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';

				return apply_filters( 'chemistry_video_markup', $video_markup, $video_url, $class, $width, $height );

			}/* video() */


			/* =========================================================================== */


			/**
			 * IF we are using the default overlay script for our images/videos, we meed
			 * to pass it a specific url format to play our vids (otherwise, if it's just
			 * an image, we basically just need rel="shadowbox"). We support Vimeo, Youtube,
			 * and blip.tv
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.2
			 * @param 
			 * @return 
			 */
			
			public static function shadowbox_href( $url )
			{

				//Whitespace at the start/end gives you indegestion. True facts.
				$url = trim( $url );

				//Find which delivery service we're using by looking after the www. or http://
				preg_match( '@^( ?:http:// )?( ?:www. )?( [^/]+ )@i', $url, $matches );

				if( $matches[1] == 'youtube.com' )
				{

					$params = explode( '?', $url );
					parse_str( html_entity_decode( $params[1] ), $params );

					foreach( $params as $k => $v )
					{

						if( strtolower( $k ) == 'v' )
						{

							$url = 'http://www.youtube.com/embed/' . $v;

							$height = apply_filters( 'chemistry_shadowbox_video_height', '290', $url );
							$width = apply_filters( 'chemistry_shadowbox_video_width', '480', $url );

							return $url . '" rel="shadowbox;width=' . $width . ';height=' . $height . ';player=iframe';

						}

					}

				}
				else if( $matches[1] == 'vimeo.com' )
				{

					preg_match( '/( \d+ )/', $url, $id );

					if( !empty( $id[1] ) )
					{

						$url = 'http://player.vimeo.com/video/' . $id[1];

						$height = apply_filters( 'chemistry_shadowbox_video_height', '290', $url );
						$width = apply_filters( 'chemistry_shadowbox_video_width', '480', $url );

						return $url . '" rel="shadowbox;width=' . $width . ';height=' . $height . ';player=iframe';

					}

				}
				else if( $matches[1] == 'blip.tv' )
				{

					preg_match( '/file\/( \d+ )\//', $url, $id );

					if( !empty( $id[1] ) )
					{

						$url = 'http://blip.tv/play/' . $id[1];

						$height = apply_filters( 'chemistry_shadowbox_video_height', '290', $url );
						$width = apply_filters( 'chemistry_shadowbox_video_width', '480', $url );

						return $url . '" rel="shadowbox;width=' . $width . ';height=' . $height . ';player=iframe';

					}

				}

				//Just a lonely image?
				preg_match( '/( ?i )\.( jpg|png|gif )$/', $url, $ext );

				if( !empty( $ext ) )
					return $url . '" rel="shadowbox';

				//None of the above? I got 99 problems...etc.
				return $url;

			}/* shadowbox_href() */


			/* =========================================================================== */


			/**
			 * Images, images everywhere. We pass through this file so it has a wee filter
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.1
			 * @param (string) $img - The image path
			 * @param (string) $source_id - The default
			 * @return (string) The filtered image.
			 */
			
			public static function chemistry_image( $img, $source_id = '' )
			{

				$img = apply_filters( 'chemistry_img', $img, $source_id );

				return $img;

			}/* chemistry_image() */


			/* =========================================================================== */


			/**
			 * Helper function to help us cache specific items. Mainly used for our flickr and
			 * twitter widgets, but we can extend this in the future to encompass any content
			 * we have output to help caching plugins.
			 * Returns boolean if we're trying to set it or the data if it already exists
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.4
			 * @param (string) $key - the key for our cache item
			 * @param (string) $content - what we're actually caching
			 * @param (string) $time - any expiration?
			 * @return (bool) or unserialized data of the contents
			 */
			
			public static function cache_it( $key, $content = null, $time = null )
			{

				//Default is 6 Hours
				if( $time === null )
					$time = 21600; //6 Hours

				//Where's our cache directory?
				$path = WP_CONTENT_DIR . '/chemistry-cache/';

				//If the cache dir doesn't exist or we can't write to it, well, life sucks
				if( !is_dir( $path ) || !is_writable( $path ) )
					return false;

				//md5 the key for a wee bit of security
				$file = $path . md5( $key );

				//If it's not empty, write that sucker
				if( $content != null )
				{
					file_put_contents( $file, serialize( $content ) );
				}
				else
				{

					//If it already exists
					if( file_exists( $file ) && is_file( $file ) )
					{

						//And if it is later than the current time (i.e. not out of date)
						if( ( filemtime( $file ) + $time ) > time() )
							return unserialize( file_get_contents( $file ) );

					}

					return false;

				}

				return true;

			}/* cache_it() */


			/* =========================================================================== */


			/**
			 * The TinyMCE wysiwyg editors need some extra bits and bobs (else it reverts to the
			 * rather horrific default). Let's add those in. Hopefully, in WP 3.5+ this won't be
			 * needed. Only applies if the user has rich editing turned on.
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param None
			 * @return None
			 */
			
			public static function initialise_chemistry_wysiwyg()
			{

				if( get_user_option( 'rich_editing' ) )
				{

					add_filter( 'mce_external_plugins', array( 'chemistry', 'modify_tinymce_plugins' ), 5 );
					add_filter( 'mce_buttons_3', array( 'chemistry', 'modify_tinymce_buttons' ), 5 );

				}

			}/* initialise_chemistry_wysiwyg() */


			/* =========================================================================== */


			/**
			 * The javascript that we output for our modal depends on what type we are calling
			 * This catchall function outputs the necessary js with the different objects we need
			 * and then, depending on which type, we output the modal code.
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.1
			 * @param (array) $data - the sets of WYWIWYG Data
			 * @return The js for the modals
			 */
			
			/*public static function on_get_request_handle_wysiwyg_modal( $data )
			{

				if( isset( $data['wysiwig'] ) )
				{

					if( is_user_logged_in() && current_user_can( 'edit_posts' ) )
					{

						header( 'Content-Type: text/javascript; charset=utf-8' );
						$script = '';
						$script .= '( function(){ function chemistry_wysiwig_modalbox( ed, url, width, height ) { ed.windowManager.open( { file: url, width: width, height: height, inline: 1 }, { plugin_url: chemistry.templatepath } ); }function chemistry_wysiwig_edit( ed, open, close ){var content = tinyMCE.activeEditor.selection.getContent();var start = 0;if( content.length == 0 ){var range = ed.selection.getRng( true );content = range.startContainer.textContent;var begin_range = 100;var end_range = 100;if( range.startOffset < begin_range ){begin_range -= range.startOffset;end_range += range.startOffset;}content = content.substring( range.startOffset - begin_range, range.startOffset + end_range );start = range.startOffset;}var shortcode_exp = /\[( [a-zA-Z0-9-_]{1,16} )\]( ( \s|\S )*? )\[\/( [a-zA-Z0-9-_]{1,16} )\]/ig;var results = content.match( shortcode_exp );var shortcode = null;if( results != null && results.length > 0 ){if( results.length > 1 ){var positions = [];for( var i = 0; i < results.length; i++ ){positions.push( content.indexOf( results[i] ) );}var index = 0;var positions = 99999;for( var i = 0; i < results.length; i++ ){if( Math.abs( start - positions[i] ) < positions ){index = i;position = Math.abs( start - positions[i] );}}shortcode = results[index];}else{shortcode = results[0];}}if( shortcode != null ){var shortcode_name = /\[( .*? )\]/.exec( shortcode )[1];tinyMCE.execCommand( \''.self::chemistry_option( 'prefix' ).'\' + shortcode_name + \'_cmd\', false );}}function chemistry_wysiwig_shortcode( ed, tag_open, tag_close ) { var content = tinyMCE.activeEditor.selection.getContent(); tinyMCE.activeEditor.selection.setContent( tag_open + content + tag_close ); }tinymce.create( \'tinymce.plugins.chemistry_wysiwig\',{init: function( ed, url ){';

						foreach( self::$wysiwig as $wysiwig )
						{

							if( $wysiwig['type'] == 'modalbox' )
								$script .= 'ed.addCommand( \''.self::chemistry_option( 'prefix' ).self::uglify( $wysiwig['name'] ).'_cmd\', function() { chemistry_wysiwig_modalbox( ed, \''.$wysiwig['url'].'\', '.$wysiwig['width'].', '.$wysiwig['height'].' ); } );';
							else if( $wysiwig['type'] == 'shortcode' )
								$script .= 'ed.addCommand( \''.self::chemistry_option( 'prefix' ).self::uglify( $wysiwig['name'] ).'_cmd\', function() { chemistry_wysiwig_shortcode( ed, \''.$wysiwig['tag_open'].'\', \''.$wysiwig['tag_close'].'\' ); } );';
							else if( $wysiwig['type'] == 'custom' )
								$script .= 'ed.addCommand( \''.self::chemistry_option( 'prefix' ).self::uglify( $wysiwig['name'] ).'_cmd\', function() { '.$wysiwig['args']['function'].'( ed ); } );';

						}

						foreach( self::$wysiwig as $wysiwig )
						{

							if( $wysiwig['type'] != 'separator' )
							{

								$script .= 'ed.addButton( \''.self::uglify( $wysiwig['name'] ).'\', {
								title: \''.$wysiwig['desc'].'\',
								cmd: \''.Chemistry::chemistry_option( 'prefix' ).self::uglify( $wysiwig['name'] ).'_cmd\',
								image: \''.$wysiwig['icon'].'\'
								} );';

							}

						}

						$script .= '}} );tinymce.PluginManager.add( \'chemistry_wysiwig\', tinymce.plugins.chemistry_wysiwig );} )();';
						echo str_replace( array( "\t", "\n" ), '', $script );
					}

				}

			}*//* on_get_request_handle_wysiwyg_modal() */


			/* =========================================================================== */


			/**
			 * Holder function to modify the tinymce config. Currently empty...not for long
			 * Filters tiny_mce_before_init
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (array) $config - TinyMCE Config 
			 * @return (array) $config - Adjusted TinyMCE Config 
			 */
			
			public static function modify_tinymce_config_before_init( $config )
			{

				//This space intentionally left blank.
				return $config;

			}/* modify_tinymce_config_before_init() */


			/* =========================================================================== */


			/**
			 * Add our overlay as an external plugin to TinyMCE. Filters mce_external_plugins
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (array) $plugins - The existing External TinyMCE plugins
			 * @return (array) $plugins - The modified array of external plugins
			 */
			
			public static function modify_tinymce_plugins( $plugins )
			{

				if( !empty( self::$wysiwig ) )
					$plugins['chemistry_wysiwig'] = self::path( 'chemistry/chemistry.php?wysiwig', true );

				return $plugins;

			}/* modify_tinymce_plugins() */


			/* =========================================================================== */

			/**
			 * Add our extra bits and bobs to the TinyMCE buttons list. This is a filter on
			 * mce_buttons_3
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (array) $buttons - Pre-existing buttons
			 * @return (array) $buttons - $buttons with added $buttons
			 */
			
			public static function modify_tinymce_buttons( $buttons )
			{

				foreach( self::$wysiwig as $button )
					array_push( $buttons, self::uglify( $button['name'] ) );

				return $buttons;

			}/* modify_tinymce_buttons() */


			/* =========================================================================== */


			/**
			 * Give TinyMCE a kick up the bottom to avoid caching issues (yeah, right) and
			 * also to say, 'hey' we did something new! We run this as a filter on
			 * tiny_mce_version
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (int) $version - Original version
			 * @return (int) - Our version plus one!
			 */
			
			public static function modify_tinymce_version_num( $version )
			{

				return ++$version;

			}/* modify_tinymce_version_num() */


			/* =========================================================================== */


			/**
			 * Turn a string into an acceptable slug, i.e. remove most things, limit to
			 * letters, numbers, underscore and dash. Made up from
			 * http://stackoverflow.com/questions/2955251/php-function-to-make-slug-url-string
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.3
			 * @param (string) $string - What we wish to turn into a string
			 * @return Our sluggified-uglified string
			 */
			
			public static function uglify( $string )
			{

				$string = preg_replace( '/[^a-zA-Z0-9\/_|+ -]/', '', $string );
				$string = strtolower( trim( $string, '-' ) );
				$string = preg_replace( '/[_|+ -]+/', '-', $string );
				$string = preg_replace( '/( \/ )+/', '/', $string );

				return rtrim( $string, '/' );

			}/* slug() */


			/* =========================================================================== */


			/**
			 * Limit a string to a certain amount of words, mainly used for content when
			 * we're giving examples or excerpts
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.2
			 * @param (string) $content - What we want to limit
			 * @param (int) $limit - How many words to limit to
			 * @param (bool) $ignore_end - If we reach the end do we add elipsis?
			 * @return 
			 */
			
			public static function limit_to_x_words( $content, $limit, $ignore_end = false )
			{

				//Split our string on spaces to get total words
				$text = strtok( $content, ' ' );
				$total_words = count( explode( ' ', $content ) );
				$output = '';
				$words = 0;

				while( $text )
				{

					$output .= ' '.$text;
					$words++;

					if( $words >= $limit )
					{

						//Add our elipsis if we hit the end of our content
						if( $ignore_end )
						{

							if( $words < $total_words )
								$output .= '&#8230;';

							break;

						}
						else
						{

							if( substr( $text, -1 ) == '!' || substr( $text, -1 ) == '.' )
								break;

						}

					}

					$text = strtok( ' ' );

				}

				return ltrim( $output );

			}/* limit_to_x_words() */


			/* =========================================================================== */


			/**
			 * Double-pass through strip tags mainly as we modify quite a few internal WP functions
			 * and their output. So we need to make sure we're as safe as possible. Some of it from
			 * http://grokbase.com/t/php/php-notes/11am95aq77/note-106238-added-to-function-strip-tags
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 1.0
			 * @param $data - the data to strip
			 * @param $tags - what to remove if any
			 * @return The stripped data
			 */
			
			public static function rebase_strip_tags( $data, $tags = null )
			{
				
				$regexp = '#\s*<( /?\w+ )\s+( ?:on\w+\s*=\s*( ["\'\s] )?.+?\( \1?.+?\1?\ );?\1?|style=["\'].+?["\'] )\s*>#is';

				return preg_replace( $regexp, '<${1}>', strip_tags( $data, $tags ) );

			}/* rebase_strip_tags */


			/* =========================================================================== */


			/**
			 * Sometimes we need to strip *some* tags from some data, not the whole world.
			 * Let's do just that, by allowing us to specify which tags to strip
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.1
			 * @param (string) $data - The data we wish to strip
			 * @param (array) $tags - The tags we wish to strip
			 * @return (string) $data - Our tag-stripped data
			 */
			
			public static function strip_only_some_tags( $data, $tags )
			{

				if( !is_array( $tags ) )
				{

					$tags = ( strpos( $tags, '>' ) !== false ? explode( '>', str_replace( '<', '', $tags ) ) : array( $tags ) );

					if( end( $tags ) == '' )
						array_pop( $tags );

				}

				foreach( $tags as $tag )
					$data = preg_replace( '#</?'.$tag.'[^>]*>#is', '', $data );

				return $data;

			}/* strip_only_some_tags() */


			/* =========================================================================== */


			/**
			 * Frankly, slashes can be a pain in the backside. Sometimes we need to add them
			 * to strings, normally paths. Here's a helper method to do that for us.
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.51
			 * @param (string) $data - What we want to add slashes to
			 * @return (string) $data - The thing now with added slashes.
			 */
			
			public static function add_slashes( $data )
			{

				$pattern = array( '\\\'', '\\"', '\\\\', '\\0' );
				$replace = array( '', '', '', '' );

				//We don't have a slash at the end, do we?
				if( preg_match( '/[\\\\\'"\\0]/', str_replace( $pattern, $replace, $data ) ) )
					return addslashes( $data );
				else
					return $data;

			}/* add_slashes() */


			/* =========================================================================== */


			/**
			 * Sometimes we need to tidy up data or markup or variables or urls or the house.
			 * Maybe not the house, but the rest we can do with code. Here's a helper method.
			 * Methods for strip_tags(), htmlspecialchars(), add_slashes(), removing whitepace
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.45
			 * @param (variable) $data - What we're tidying
			 * @param (bool) $strip_tags - are we removing tags?
			 * @param (bool) $htmlspecialchars - do we need to pipe through htmlspecialchars()?
			 * @param (bool) $addslashes - Are we appending slashes?
			 * @param (bool) $whitespace - Are we removing whitespace
			 * @return (variable) $data - the tidied data.
			 */
			
			public static function tidy( $data, $strip_tags = false, $htmlspecialchars = false, $addslashes = true, $whitespace = true )
			{

				//If we are passed an array, run through each one as singlets and pass back to ourselves
				if( is_array( $data ) )
				{
					
					foreach( $data as $key => $value )
						$data[$key] = self::tidy( $value, $strip_tags, $htmlspecialchars, $whitespace );

					return $data;

				}
				else
				{

					if( $strip_tags )
						$data = self::rebase_strip_tags( $data );

					if( $htmlspecialchars )
						$data = htmlspecialchars( $data );

					if( $addslashes )
						$data = self::add_slashes( $data );

					if( !$whitespace )
						$data = str_replace( array( '\r\n', '\n', '\r' ), '', $data );

					if( !is_numeric( $data ) )
						$data = mysql_real_escape_string( $data );

					return $data;

				}

			}/* tidy() */


			/* =========================================================================== */


			/**
			 * A fix for unserializing when values change within the serialized data. Often have
			 * issues with string length. Some issues explained here
			 * http://davidwalsh.name/php-serialize-unserialize-issues and/ in comments on 
			 * http://php.net/manual/en/function.unserialize.php
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.4
			 * @param (string) $data - what to unserialize
			 * @return (string)  - the unserialized data
			 */
			
			public static function unserialize( $data )
			{
	
				return maybe_unserialize( preg_replace( '!s:( \d+ ):"( .*? )";!se', "'s:'.strlen( '$2' ).':\"$2\";'", $data ) );

			}/* unserialize() */


			/* =========================================================================== */


			/**
			 * For our overlays we ask for certain dimensions, sometimes people put px/em etc.
			 * other times they do not (regardless of what help docs you put and what inline 
			 * info help tips you put in place). Let's just handle as many scenarios as we can
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (string) $value - What is it we're checking
			 * @param (string) $default_unit - Do we have a default suffix?
			 * @return (string) Appropriate value and JUST the value
			 */
			
			public static function check_units_on_end_of_string( $value, $default_unit )
			{

				//If empty, run away
				if( $value == '' )
					return $value;

				//Do we actually have something at the end of the value
				preg_match( '/( \d* )( .* )/', $value, $unit );

				//What unit is it?
				$unit = empty( $unit[2] ) ? $default_unit : $unit[2];

				//return the numerical represenation of it.
				$value = intval( $value ) . $unit;

				return $value;

			}/* check_units_on_end_of_string() */


			/* =========================================================================== */


			/**
			 * Read data from a file
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.2
			 * @param (string) $path - Which file are we reading from?
			 * @return (string) $data or false if it all goes wrong
			 */
			
			public static function read( $path )
			{

				//Are we reading something that actually exists?
				if( !file_exists( $path ) )
					return false;

				//Can we use file_get_contents()? If so, do so
				if( function_exists( 'file_get_contents' ) )
					return file_get_contents( $path );

				//Or use the fopen method, if we can't run away (perms)
				if( !$fp = @fopen( $path, FOPEN_READ ) )
					return false;

				//Read and return
				flock( $fp, LOCK_SH );
				$data =& fread( $fp, filesize( $path ) );
				flock( $fp, LOCK_UN );
				fclose( $fp );

				//wewt
				return $data;

			}/* read() */


			/* =========================================================================== */


			/**
			 * Helper method to write some data to a specific file (or append to an existing file)
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.4
			 * @param (string) $path - Filename
			 * @param (string) $data - Data to write
			 * @param (string) $append - Append to existing file or not
			 * @return (bool)
			 */
			
			public static function write_to_file( $path, $data, $append = false )
			{

				$flag = $append ? 'a' : 'w';

				//If we can't open the path, run away
				if( !$fp = fopen( $path, $flag ) )
					return false;

				//If we're appending, we need to be able to write to it, change perms
				if( !$append )
					chmod( $path, 0755 );

				//Do the writing
				flock( $fp, LOCK_EX );
				fwrite( $fp, $data );
				flock( $fp, LOCK_UN );
				fclose( $fp );

				//wewt
				return true;

			}/* write_to_file() */


			/* =========================================================================== */


			/**
			 * Easily the most convoluted and complicated thing in existence. Making thumbnails is
			 * tricky. Especially when you can have bloody millions of options and millions of possible
			 * file types.
			 * Most of it from http://chopapp.com/#d35mkaqj
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.1
			 * @param (string) $path - Path of image to thumbnailify
			 * @param (string) $width - Desired width
			 * @param (string) $height - Desired height
			 * @param (bool) $ratio - Keep the aspect ratio and override width/height crop
			 * @param (string) $crop_width - If cropping, set width
			 * @param (string) $crop_height - If cropping, set height
			 * @param (string) $output_path - Any specific output path?
			 * @return The image thumb path
			 */
			
			public static function thumbnail( $path, $width, $height, $ratio = true, $crop_width = null, $crop_height = null, $output_path = null )
			{

				//We need GD Tools else the whole world crumbles
				if( !function_exists( 'imagecreatetruecolor' ) )
					return $path;

				//Have we done this yet?
				$changed = false;

				//Find the file extension
				$r = explode( '.', $path );
				$ext = strtolower( end( $r ) );

				if( $ext == 'jpg' || $ext == 'jpeg' )
					$img = @imagecreatefromjpeg( $path );
				else if( $ext == 'png' )
					$img = @imagecreatefrompng( $path );
				else if( $ext == 'gif' )
					$img = @imagecreatefromgif( $path );

				//Image dimensions
				$x = imagesx( $img );
				$y = imagesy( $img );
				$size = getimagesize( $path );

				//Have we got full dimensions?
				if( $width != null && $height != null )
					$wandh = true;
				else
					$wandh = false;


				//Lots of logic determining image dimensions of ratios
				if( $width != null || $height != null )
				{

					if( $width == null )
						$width = $size[0];

					if( $height == null )
						$height = $size[1];

					if( $width != $size[0] )
						$ratio_x = $x / $width;
					else
						$ratio_x = 1;

					if( $height != $size[1] )
						$ratio_y = $y / $height;
					else
						$ratio_y = 1;

					if( $ratio )
					{

						if( $wandh )
						{

							if( $ratio_y > $ratio_x )
								$height = $y * ( $width / $x );
							else
								$width = $x * ( $height / $y );

						}
						else
						{

							if( $ratio_y < $ratio_x )
								$height = $y * ( $width / $x );
							else
								$width = $x * ( $height / $y );

						}

					}

					//Make the new image thumb
					$new_img = imagecreatetruecolor( $width, $height );


					if( $size[2] == IMAGETYPE_GIF || $size[2] == IMAGETYPE_PNG )
					{

						$index = imagecolortransparent( $img );

						if( $index >= 0 )
						{

							$color = imagecolorsforindex( $img, $index );
							$index = imagecolorallocate( $new_img, $color['red'], $color['green'], $color['blue'] );
							imagefill( $new_img, 0, 0, $index );
							imagecolortransparent( $new_img, $index );

						}
						elseif( $size[2] == IMAGETYPE_PNG )
						{

							imagealphablending( $new_img, false );
							$color = imagecolorallocatealpha( $new_img, 0, 0, 0, 127 );
							imagefill( $new_img, 0, 0, $color );
							imagesavealpha( $new_img, true );

						}

					}

					//Make the new one and declare success
					imagecopyresampled( $new_img, $img, 0, 0, 0, 0, $width, $height, $x, $y );
					imagedestroy( $img );
					$img = $new_img;
					$changed = true;

				}

				if( $width == null )
					$width = $x;

				if( $height == null )
					$height = $y;

				//If we have at least one dimension for the crop
				if( $crop_width != null || $crop_height != null )
				{

					if( $crop_width == null )
						$crop_width = $width;

					if( $crop_height == null )
						$crop_height = $height;

					$new_img = imagecreatetruecolor( $crop_width, $crop_height );

					//.gif or .png?
					if( $size[2] == IMAGETYPE_GIF || $size[2] == IMAGETYPE_PNG )
					{

						$index = imagecolortransparent( $img );

						if( $index >= 0 )
						{

							$color = imagecolorsforindex( $img, $index );
							$index = imagecolorallocate( $new_img, $color['red'], $color['green'], $color['blue'] );
							imagefill( $new_img, 0, 0, $index );
							imagecolortransparent( $new_img, $index );

						}
						elseif( $size[2] == IMAGETYPE_PNG )
						{

							imagealphablending( $new_img, false );
							$color = imagecolorallocatealpha( $new_img, 0, 0, 0, 127 );
							imagefill( $new_img, 0, 0, $color );
							imagesavealpha( $new_img, true );

						}

					}

					$x = ( $width - $crop_width ) / 2;
					$y = ( $height - $crop_height ) / 2;
					imagecopyresampled( $new_img, $img, 0, 0, $x, $y, $crop_width, $crop_height, $crop_width, $crop_height );
					imagedestroy( $img );
					$img = $new_img;

					$width = $crop_width;
					$height = $crop_height;
					$changed = true;

				}

				//Do we want a specific path?
				if( $output_path != null )
				{

					$path = explode( '.', $output_path );
					array_pop( $path );
					$path = implode( '.', $path ) . '.' . $ext;

				}
				else
				{

					$path = explode( '.', $path );
					array_pop( $path );
					$path = implode( '.', $path ) . (int)$width . 'x' . (int)$height . '.' . $ext;

				}

				//Finally!
				if( $ext == 'jpg' || $ext == 'jpeg' )
					imagejpeg( $img, $path, 100 );
				else if( $ext == 'png' )
					imagepng( $img, $path );
				else if( $ext == 'gif' )
					imagegif( $img, $path );

				return $path;

			}/* thumbnail() */


			/* =========================================================================== */

			/**
			 * Use the WP HTTP API to retrieve and write a remote image
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (string) $url - the url of the image we want to remotely get
			 * @return (string) The url
			 */
			
			public static function get_remote_image( $url )
			{

				return $url;

				//get local image
				$url_data = parse_url( $url );

				//If it's remote, let's grab it and write it to our uploads
				if( $url_data['host'] != $_SERVER['HTTP_HOST'] )
				{

					$remote_image = wp_remote_retrieve_body( wp_remote_get( $url ) );

					self::write_to_file( rtrim( self::chemistry_option( 'chemistry_uploads_directory' ), '/' ).'/'.basename( $url ), $remote_image, false );

					return rtrim( self::chemistry_option( 'chemistry_uploads_url' ), '/' ).'/'.basename( $url );

				}

				return $url;

			}/* get_remote_image() */


			/* =========================================================================== */


			/**
			 * Sometimes we need just the raw image url
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (string) $thumbnail_url - Full url we want the path to
			 * @return (string) the URL
			 */
			
			public static function get_raw_image_url( $thumbnail_url )
			{

				//Parse the URL params
				$url_data = parse_url( $thumbnail_url );

				//If fully complete, break early (if we're not on the same server)
				if( array_key_exists( 'host', $url_data ) && $url_data['host'] != $_SERVER['HTTP_HOST'] )
					return $thumbnail_url;

				//Get the url of the thumb of the passed url
				$thumbnail_url = self::get_remote_image( $thumbnail_url );

				//Check the -thumbnail ending
				preg_match_all( '/( -thumbnail )( \d+. )( x )( \d+. )( \.( jpg|jpeg|gif|png ) )/is', basename( $thumbnail_url ), $matches );

				if( isset( $matches[0][0] ) && count( $matches[0][0] ) > 0 )
				{

					$uploads = wp_upload_dir();
					$base_url = str_replace( $matches[0][0], $matches[5][0], $thumbnail_url );
					$base_path = self::chemistry_option( 'chemistry_uploads_directory' ).'/'.basename( $base_url );

					if( file_exists( $base_path ) )
						return $base_url;

				}

				//Done and done!
				return $thumbnail_url;

			}/* get_raw_image_url() */


			/* =========================================================================== */


			/**
			 * We use thumbnails throughout Chemistry - to do with file uploads in the overlays
			 * and soon in the Content Attributes
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (string) $base_url - URI of image to check or make a thumb for
			 * @param (string) $width - desired width
			 * @param (string) $height - desired height
			 * @return appropriate action
			 */
			
			public static function get_or_make_thumbnail( $base_url, $width, $height )
			{

				//Parse the URL and returns an associative array containing any of the various 
				//components of the URL that are present
				$url_data = parse_url( $base_url );

				//Break out early if everything is present and correct
				if( array_key_exists( 'host', $url_data ) && $url_data['host'] != $_SERVER['HTTP_HOST'] )
					return $base_url;

				//Get the image using WP HTTP API
				$base_url = self::get_remote_image( $base_url );
				$uploads = wp_upload_dir();

				//Get image attributes
				$image = $base_url;
				$image_path = str_replace( $uploads['baseurl'], $uploads['basedir'], $image );
				$image_size = getimagesize( $image_path );
				$width = intval( $width );
				$height = intval( $height );

				//Calc width
				if( $width == 0 || empty( $width ) )
					$width = intval( $height * $image_size[0] / $image_size[1] );

				//Calc height
				if( $height == 0 || empty( $height ) )
					$height = intval( $width * $image_size[1] / $image_size[0] );

				//IS this already a thumb?
				if( $width == $image_size[0] && $height == $image_size[1] )
					return $image;

				//Find -thumbnail in our image
				preg_match_all( '/( -thumbnail )( \d+. )( x )( \d+. )( \.( jpg|jpeg|gif|png ) )/is', basename( $image ), $matches );

				if( isset( $matches[0][0] ) && count( $matches[0][0] ) > 0 )
				{

					if( $width == $matches[2][0] && $height == $matches[4][0] )
						return $image;
					else
						return self::get_or_make_thumbnail( self::get_raw_image_url( $base_url ), $width, $height );

				}

				//Set our filename
				$pathinfo = pathinfo( $image_path );
				$ext = $pathinfo['extension'];
				$filename = basename( $image_path, '.' . $ext ) . '-thumbnail' . $width . 'x' . $height . '.' . $ext;

				if( file_exists( self::chemistry_option( 'chemistry_uploads_directory' ) . '/' . $filename ) )
					return self::chemistry_option( 'chemistry_uploads_url' ) . '/' . $filename;

				//And we are done!
				return str_replace( $uploads['basedir'], $uploads['baseurl'], self::thumbnail( $image_path, $width, $height, true, $width, $height, self::chemistry_option( 'chemistry_uploads_directory' ) . '/' . $filename ) );

			}/* get_or_make_thumbnail() */


			/* =========================================================================== */



			/**
			 * Output our metabox fields. This is mostly taken from the original version of the
			 * options framework http://wptheming.com/options-framework-plugin/ we only really
			 * use the dropdown at the moment for the template loader. Outputs markup based on
			 * what type of option it is
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (string) $name - the name/id of this field
			 * @param (array) $rules - what type are we
			 * @param (array) $data - the data from the field
			 * @param (string) $prefix - are we prefixed (for grouping and hiding)
			 * @return The appropriate markup
			 */
			
			public static function chemistry_metabox_field( $name, $rules = array(), $data = null, $prefix = null )
			{

				if( $prefix === null )
				{
					$prefix = rtrim( self::chemistry_option( 'prefix' ), '_' ) . '_';
				}
				else
				{

					if( $prefix != '' )
						$prefix = rtrim( $prefix, '_' ) . '_';

				}

				if( !isset( $rules['relation'] ) )
					$rules['relation'] = 'option';

				//Default is non-hidden
				$hidden = false;

				//If we want it to be hidden from WP then we need an underscore to start with
				if( substr( $name, 0, 1 ) == '_' )
				{
					$hidden = true;
					$name = trim( $name, '_' );
				}

				//In case the value is blank, let's set it as a blank string
				$value = '';

				//If this isn't a submit button, let's handle the data and value returns
				if( $rules['type'] != 'submit' )
				{

					if( $data === null )
					{
						
						global $post;
						$value = get_post_meta( $post->ID, ( $hidden ? '_' : '' ) . $prefix . $name, true );

					}
					else
					{

						if( is_array( $data ) )
						{

							if( isset( $data[( $hidden ? '_' : '' ) . $prefix.str_replace( '[]', '', $name )] ) )
								$value = $data[( $hidden ? '_' : '' ) . $prefix.str_replace( '[]', '', $name )];
							else if( isset( $data[str_replace( '[]', '', $name )] ) )
								$value = $data[str_replace( '[]', '', $name )];

						}
						else
						{
							$value = $data;
						}

					}

					if( !empty( $value ) )
					{

						if( is_array( $value ) )
							$value = htmlspecialchars( stripslashes( $value['name'] ) );
						else
							$value = htmlspecialchars( stripslashes( $value ) );

					}
						

				}

				//If the value isn't set, let's have a blank string so we don't have any array_key issues
				if( !isset( $rules['value'] ) )
					$rules['value'] = '';

				//If the value is blank AND we say use default AND there *is* a default
				if( empty( $value ) && isset( $rules['use_default'] ) && $rules['use_default'] )
					$value = htmlspecialchars( stripslashes( $rules['value'] ) );

				//Text field
				if( $rules['type'] == 'text' )
				{

					return '<input type="text" ' . ( isset( $rules['placeholder'] ) ? ' placeholder="' . $rules['placeholder'] . '"' : '' ) . ' name="' . $prefix . $name . '"' . ( isset( $rules['id'] ) ? ' id="' . $rules['id'] . '"' : '' ) . ( isset( $rules['class'] ) ? ' class="' . $rules['class'] . '"' : '' ) . ' value="' . $value . '"' . ( isset( $rules['style'] ) ? ' style="' . $rules['style'] . '"' : '' ) . ' />';

				}
				else if( $rules['type'] == 'hidden' )
				{

					return '<input type="hidden" name="' . $prefix . $name . '"' . ( isset( $rules['id'] ) ? ' id="'.$rules['id'] . '"' : '' ) . ( isset( $rules['class'] ) ? ' class="' . $rules['class'] . '"' : '' ) . ' value="' . $value . '"' . ( isset( $rules['style'] ) ? ' style="' . $rules['style'] . '"' : '' ) . ' />';

				}
				else if( $rules['type'] == 'select' )
				{

					$select = '<select name="' . $prefix . $name . '"' . ( isset( $rules['id'] ) ? ' id="' . $rules['id'] . '"' : '' ) . ( isset( $rules['class'] ) ? ' class="widefat ' . $rules['class'] . '"' : '' ) . ' value="' . $value . '"' . ( isset( $rules['style'] ) ? ' style="' . $rules['style'] . '"' : '' ) . '>';
					$group = '';
					$last_group = '';

					if( isset( $rules['options'] ) )
					{

						foreach( $rules['options'] as $option_key => $option_value )
						{

							if( isset( $option_value['group'] ) && $option_value['group'] != $group )
							{

								if( $group != '' )
									$select .= '</optgroup>';

								$group = $option_value['group'];

								$select .= '<optgroup label="' . $group . '">';

							}

							$select .= '<option value="' . $option_key . '"' . ( $option_key == $value ? ' selected="selected"' : '' ) . '>' . (  !is_array( $option_value ) ? $option_value : $option_value['name'] ) . '</option>';

						}

					}

					if( $group != '' )
						$select .= '</optgroup>';

					$select .= '</select>';

					return $select;

				}
				else if( $rules['type'] == 'submit' )
				{

					$value = $rules['value'];

					return '<button type="submit" name="' . $prefix . $name . '"' . ( isset( $rules['id'] ) ? ' id="' . $rules['id'] . '"' : '' ).' class="button button-secondary chemistry-metabox-submit'.( isset( $rules['class'] ) ? ' ' . $rules['class'] : '' ) . '"' . ( isset( $rules['style'] ) ? ' style="' . $rules['style'] . '"' : '' ) . ' value="true">' . $rules['value'] . '</button>';

				}


			}/* chemistry_metabox_field() */


			/* =========================================================================== */


			/**
			 * For our metaboxes, handle the save routine based on what sort of fields and 
			 * data is in them.
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (mixed) $data - What is being saved
			 * @param (array) $rules - type of options
			 * @param (string) $prefix - What is being saved
			 * @return false or run the appropriate save action
			 */
			

			public static function chemistry_metabox_save( $data, $rules, $prefix = null )
			{

				if( $prefix === null )
				{
					$prefix = rtrim( self::chemistry_option( 'prefix' ), '_' ) . '_';
				}
				else
				{

					if( $prefix != '' )
						$prefix = rtrim( $prefix, '_' ) . '_';

				}

				foreach( $rules as $rule => $fields )
				{

					foreach( $fields as $field )
					{

						//Do we want this metadata to be hidden from the 'custom fields' list? If so,
						//WP needs it to start with an underscore
						$hidden = false;

						if( substr( $field['name'], 0, 1 ) == '_' )
						{

							$hidden = true;
							$field['name'] = trim( $field['name'], '_' );

						}

						if( isset( $data[( $hidden ? '_' : '' ) . $prefix . $field['name']] ) )
						{

							global $post;

							$current = get_post_meta( $post->ID, ( $hidden ? '_' : '' ) . $prefix . $field['name'], true );


							if( $data[( $hidden ? '_' : '' ) . $prefix . $field['name']] != $current )
							{

								global $post;

								update_post_meta( $post->ID, ( $hidden ? '_' : '' ) . $prefix . $field['name'], $data[( $hidden ? '_' : '' ) . $prefix . $field['name']] );


							}
							else if( empty( $data[( $hidden ? '_' : '' ) . $prefix . $field['name']] ) && empty( $current ) )
							{

								global $post;

								update_post_meta( $post->ID, ( $hidden ? '_' : '' ) . $prefix . $field['name'], $field['value'] );


							}

						}
						else
						{

							global $post;

							if( isset( $post->ID ) )
								update_post_meta( $post->ID, ( $hidden ? '_' : '' ) . $prefix . $field['name'], $field['value'] );

						}

					}

				}

				return false;

			}/* chemistry_metabox_save() */


			/* =========================================================================== */


			/**
			 * Set up the necessary actions (with their relevant callbacks) on the appropriate pages
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param None
			 * @return None
			 */
			
			public static function setup_chemistry_admin()
			{

				add_action( 'admin_head', array( 'chemistry', 'chemistry_admin_header_setup' ) );
				add_action( 'admin_print_scripts', array( 'chemistry', 'chemistry_admin_scripts_load' ) );
				add_action( 'admin_print_styles', array( 'chemistry', 'chemistry_admin_styles_load' ) );
				add_action( 'admin_menu', array( 'chemistry', 'chemistry_add_admin_metaboxes' ) );

			}/* setup_chemistry_admin() */


			/* =========================================================================== */

			/**
			 * 
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param None
			 * @return None
			 * @todo Localise this properly
			 */

			public static function chemistry_admin_header_setup()
			{

				//In case we're empty
				$jsconfig = '';

				//for each of our js config, prepare ourselves based on what type of var it is
				foreach( self::$chemistry_javascript_config as $k => $v )
				{

					if( is_bool( $v ) )
						$jsconfig .= ', '.$k.': '.( $v ? 'true' : 'false' );
					else if( is_numeric( $v ) )
						$jsconfig .= ', '.$k.': '.$v;
					else if( is_array( $v ) )
						$jsconfig .= ', '.$k.': '.json_encode( $v );
					else
						$jsconfig .= ', '.$k.': \''.$v.'\'';

				}

				//Output some js vars that we need throughout Chemistry
				echo '<!-- Output by Chemistry. The content layout builder. -->';
				echo '<script type="text/javascript">';
					echo 'var chemistry = {placeholder: {img: \''.Chemistry::path('assets/images/logo-transparent-black.png', true).'\',video: \''.Chemistry::path('assets/images/logo-transparent-black.png', true).'\',empty: \''.Chemistry::path('assets/images/logo-transparent-black.png', true).'\'},path: \''.self::path('/', true).'\',templatepath: \''.get_template_directory_uri().'/\',stylesheetpath: \''.get_stylesheet_directory_uri().'/\',upload_callback: null,upload_caller: null,allowed_resources: null,upload_space: null,blog_id: null,upload_target: null,$_GET: [],editor: null,prefix: \''.self::chemistry_option('prefix').'\''.$jsconfig.'}; var __GET = window.location.href.slice( window.location.href.indexOf( "?" ) + 1 ).split( "&" );for( var i = 0; i < __GET.length; i++ ) { var hash = __GET[i].split( "=" ); chemistry.$_GET.push( hash[0] ); chemistry.$_GET[hash[0]] = hash[1]; }';
				echo '</script>';
				echo '<!-- End Chemistry Config -->';

				//If we're saving, run the save routine of the appropriate caller
				if( isset( $_POST['save'] ) )
				{

					if( class_exists( self::$controller ) )
					{

						$controller = self::$controller;
						call_user_func( array( $controller, 'save' ) );

					}

				}

				//Run the reset method if called
				if( isset( $_POST['reset'] ) )
				{

					if( class_exists( self::$controller ) )
					{

						$controller = self::$controller;
						call_user_func( array( $controller, 'reset' ) );

					}

				}

				//Run the header() method for each instance
				if( class_exists( self::$controller ) )
				{

					$controller = self::$controller;

					call_user_func( array( $controller, 'header' ) );

				}

			}/* chemistry_admin_header_setup() */


			/* =========================================================================== */


			/**
			 * Load our javascript, thickbox for the overlay (and media-upload). Farbtastic for the
			 * colour picker (to be removed for CMB)
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 */

			public static function chemistry_admin_scripts_load()
			{

				global $hook_suffix;
				/*
					We only want to load our custom js on Add New posts/pages and custom post types screens
					For Add new post/page/CPT, $hook_suffix is post-new.php
					For Edit post/page/CPT, it's post.php
				*/

				if( $hook_suffix == "post.php" || $hook_suffix == "post-new.php" )
				{

					wp_enqueue_script( 'jquery' );
					wp_enqueue_script( 'thickbox' );
					wp_enqueue_script( 'media-upload' );
					wp_enqueue_script( 'farbtastic' );
					wp_enqueue_script( 'chemistry_js', Chemistry::path( '/assets/js/chemistry.js', true ), array( 'jquery', 'thickbox', 'media-upload', 'farbtastic' ), self::chemistry_option( 'chemistry_version' ) );

				}

			}/* chemistry_admin_scripts_load() */


			/* =========================================================================== */


			/**
			 * Load our CSS - we need thickbox for the overlay and farbtastic for the colour pickers
			 * in our metaboxes. 
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param None
			 * @return None
			 * @todo Remove farbtastic as it's separated in CMB class
			 */

			public static function chemistry_admin_styles_load()
			{

				global $hook_suffix;

				/*
					We only want to load our custom js on Add New posts/pages and custom post types screens
					For Add new post/page/CPT, $hook_suffix is post-new.php
					For Edit post/page/CPT, it's post.php
				*/

				if( $hook_suffix == "post.php" || $hook_suffix == "post-new.php" )
				{
				
					wp_enqueue_style( 'thickbox' );
					wp_enqueue_style( 'farbtastic' );

				}

			}/* chemistry_admin_styles_load() */


			/* =========================================================================== */


			/**
			 * Called by our config to add the metaboxes we want
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param (string) $name
			 * @param (array) $options
			 * @return None
			 * @todo Replace with CMB Class
			 */

			public static function add_admin_chemistry_metabox( $name, $options = array() )
			{

				//If not set when called, what should our defaults be?
				$defaults = array( 'permissions' => array( 'post', 'page' ), 'context' => 'normal', 'priority' => 'default' );

				//If it doesn't exist, add it to our $metaboxes array
				if( !array_key_exists( $name, self::$metaboxes ) )
				{

					self::$metaboxes[$name] = array_merge( $defaults, $options );

				}
				else
				{

					$merged_data = array_merge( $defaults, $options );

					self::$metaboxes[$name]['permissions'] = array_merge( self::$metaboxes[$name]['permissions'], $merged_data['permissions'] );
					self::$metaboxes[$name]['context'] = self::$metaboxes[$name]['context'];
					self::$metaboxes[$name]['permissions'] = self::$metaboxes[$name]['permissions'];

				}

			}/* add_admin_chemistry_metabox() */


			/* =========================================================================== */


			/**
			 * Return the requested metabox or all of them if no name set. Used for each of
			 * our molecules.
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.4
			 * @param (string) $name - Name of the metabox
			 * @return The metabox requested or all of them
			 */

			public static function admin_metabox_get( $name = null )
			{

				if( $name != null )
					return self::$metaboxes[$name];

				return self::$metaboxes;

			}/* admin_metabox_get() */


			/* =========================================================================== */


			/**
			 * Load and display our metaboxes with appropriate permissions and priorities
			 * as set in the $metabox variable - each is a separate file which is loaded from
			 * admin/metabox/{class}
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.4
			 * @todo Replace with CMB Class
			 */

			public static function chemistry_add_admin_metaboxes()
			{

				foreach( self::$metaboxes as $metabox_id => $metabox_config )
				{

					//Get the actual name of the class
					$metabox = self::uglify( $metabox_id );
					$metabox_class = str_replace( '-', '_', $metabox );
					$metabox_class = self::chemistry_option( 'prefix' ).$metabox_class;

					//var_dump($metabox_class);
					//Load the class
					if( !class_exists( $metabox_class ) )
						require_once locate_template( '/framework/chemistry/admin/metabox/' . $metabox . '.php' );


					//If it exists, instantiate ourselves
					if( class_exists( $metabox_class ) )
					{

						call_user_func_array( array( $metabox_class, 'chemistry_set_class_singleton' ), array( $metabox_class ) );
						self::$metabox_permissions[$metabox_class] = $metabox_config['permissions'];

						//Call the init method for each class
						call_user_func( array( $metabox_class, 'init' ) );

						//Default post types
						$post_types = array( 'post', 'page' );

						//Add the metaboxes
						foreach( $metabox_config['permissions'] as $permission )
						{

							if( substr( $permission, 0, 3 ) != 'id:' && substr( $permission, 0, 9 ) != 'template:' )
							{

								$metabox_title = isset( $metabox_config['title'] ) ? $metabox_config['title'] : $metabox_id;

								add_meta_box( $metabox, $metabox_title, array( $metabox_class, 'potion_body' ), $permission, $metabox_config['context'], $metabox_config['priority'] );

							}
							else
							{

								foreach( $post_types as $type )
								{

									$metabox_title = isset( $metabox_config['title'] ) ? $metabox_config['title'] : $metabox_id;

									add_meta_box( $metabox, $metabox_title, array( $metabox_class, 'potion_body' ), $type, $metabox_config['context'], $metabox_config['priority'] );

								}

							}

						}

						//Each extended class has 4 methods for dealing with saving and loading of styles/scripts
						//Call them
						add_action( 'save_post', array( $metabox_class, 'postion_save_routine' ) );
						add_action( 'admin_head', array( $metabox_class, 'potion_header' ) );

						add_action( 'admin_print_scripts', array( $metabox_class, 'potion_header_scripts' ) );
						add_action( 'admin_print_styles', array( $metabox_class, 'potion_header_styles' ) );

					}

				}

			}/* chemistry_add_admin_metaboxes */

		}/* class Chemistry */

	}/* class_exists( 'Chemistry' ) */



	/* =================================================================================== */


	/**
	 * Let's load our abstract classes which allow us to extend into procuding our potions,
	 * metaboxes and extra editor tabs
	 *
	 * @author Richard Tape
	 * @package Chemistry
	 * @since 0.7
	 */
	
	require_once locate_template( '/framework/chemistry/class.chemistry_potion_abstract_class.php' );

	require_once locate_template( '/framework/chemistry/class.chemistry_potion.php' );

	require_once locate_template( '/framework/chemistry/class.chemistry_metabox_abstract_class.php' );

	require_once locate_template( '/framework/chemistry/class.chemistry_metabox.php' );

	require_once locate_template( '/framework/chemistry/class.chemistry_image_tab_abstract_class.php' );
	
	require_once locate_template( '/framework/chemistry/class.chemistry_images_tab.php' );


	/**
	 * Load our theme-specific configuration options. This framework directory somes with a file called
	 * config_overrides.php but this file location is filtered, so you can easily edit that - and hence 
	 * the overides - in your theme
	 *
	 * @author Richard Tape
	 * @package Chemistry
	 * @since 0.7
	 */
	
	require_once locate_template( apply_filters( 'chemistry_config_overrides', '/framework/chemistry/config_overrides.php' ) );
	
	

	/* ======================================================================================

	All of our set up is now done. We need to actually intialise ourselves and then launch our
	potions

	====================================================================================== */

	Chemistry::init();

	Chemistry::module( 'molecule' );


?>