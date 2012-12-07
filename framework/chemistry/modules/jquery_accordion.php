<?php

	if( !class_exists( 'chemistry_potion_jquery_accordion' ) )
	{

		class chemistry_potion_jquery_accordion extends chemistry_potion_tabs_and_accordion_abstract_class
		{

			/**
			 * Register this potion - pass it off to the multi type method
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param None
			 * @return None
			 */
			
			public function __construct()
			{

				parent::__construct( 'accordion', __( 'Accordion' , 'chemistry' ) );
				$this->label = __( 'Think your content would look great in an Accordion?' , 'chemistry' );
				$this->multi_type = 'acc';

			}/* __construct() */



			static function uglify( $string )
			{
				return strtolower( preg_replace( '/[^A-z0-9]/', '_', $string ) );
			}

		}/* class chemistry_potion_jquery_accordion */

	}/* !class_exists( 'chemistry_potion_jquery_accordion' ) */

?>