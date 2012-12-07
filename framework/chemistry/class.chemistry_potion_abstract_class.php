<?php

	/* ======================================================================================

	

	====================================================================================== */

	if( !class_exists( 'chemistry_potion_abstract_class' ) )
	{

		class chemistry_potion_abstract_class
		{

			/**
			 * Our classes and actions that we'll be applying our methods to
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param 
			 * @return 
			 */
			

			public static $class = 'chemistry_potion';


			/* =========================================================================== */


			/**
			 * Get the class name presented by our call_user_func() calls and then get the 
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param None
			 * @return (string) The class or (object) the backtrace details which then returns (string) class
			 */
			
			public function get_class()
			{

				/*
					Jigoshop's singleton class has a 'jigoshop_class_tools' method which causes a fatal
					memory error. This can also happen with the forked woocommerce. If either of these classes
					exist, we use theirs, not ours
				*/

				if( function_exists( 'get_called_class' ) && !class_exists( 'jigoshop_class_tools' ) && !class_exists( 'woocommerce_class_tools' ) )
				{

					return get_called_class();

				}
				else
				{

					/*
						OK we have no issues with other classes, so let's see which potion has called this method 
						and return the objects for it, so we can save/add styles/scripts easily from our
						abstracted class. Initially used the reflection API but it's just not supported fully enough.
						debug_backtrace() it is, then!
					*/

					$objects = array();
					$traces = debug_backtrace();

					foreach( $traces as $trace )
					{

						//Check we're actually coming from a call_user_func() call and if so return the class
						if( isset( $trace['function'] ) && substr( $trace['function'], 0, 14 ) == 'call_user_func' && isset( $trace['args'][0][0] ) )
							return $trace['args'][0][0];
						else if( isset( $trace['object'] ) ) //If we've been re-routed back to ourself
							if( is_object( $trace['object'] ) )
								$objects[] = $trace['object'];

					}

					//If we're re-routing back to ourself, make sure we're just sending the first instance
					if( count( $objects ) )
						return get_class( $objects[0] );

				}

			}/* get_class() */


			/* =========================================================================== */


			/**
			 * Make sure we keep a record of which class we're being called by
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param 
			 * @return 
			 */
			
			public function chemistry_set_class_singleton( $class )
			{

				self::$class = $class;

			}/* chemistry_set_class_singleton() */


			/* =========================================================================== */


			/**
			 * Once called, set up our actions and filters for this potion. Not used just yet.
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param 
			 * @return 
			 */
			
			public function potion(){}/* potion() */


			/* =========================================================================== */

		}/* class chemistry_potion_abstract_class() */

	}/* !class_exists( 'chemistry_potion_abstract_class' ) */

?>