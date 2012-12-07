<?php

	if( !class_exists( 'chemistry_potion_jquery_tabs' ) )
	{

		class chemistry_potion_jquery_tabs extends chemistry_potion_tabs_and_accordion_abstract_class
		{

			/**
			 * Register this potion
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param None
			 * @return None
			 */
			
			public function __construct()
			{

				parent::__construct( 'tabs', __( 'Tabs' , 'chemistry' ) );
				$this->label = __( 'Some beautiful javascript tabs for your content' , 'chemistry' );
				$this->multi_type = 'tabs';

			}/* __construct() */



			static function uglify( $string )
			{
				return strtolower( preg_replace( '/[^A-z0-9]/', '_', $string ) );
			}

		}/* class chemistry_potion_jquery_tabs */

	}/* class_exists( 'chemistry_potion_jquery_tabs' ) */

?>