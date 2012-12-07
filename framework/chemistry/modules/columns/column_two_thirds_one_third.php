<?php

	if( !class_exists( 'chemistry_potion_column_two_thirds_one_third' ) )
	{

		class chemistry_potion_column_two_thirds_one_third extends chemistry_potion_column_abstract_class
		{

			/**
			 * Column potions just need to replace the __construct method to register the column layout
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 * @param None
			 * @return None
			 */

			public function __construct()
			{

				parent::__construct( 'two_cols_two_third_third', __( '&#8532; + &#8531; Columns' , 'chemistry' ) );
				$this->label = __( '2 Columns; &#8532; - &#8531;' , 'chemistry' );
				$this->core = true;
				$this->col_count = 2;
				$this->splits = array( 1 => "8", 2 => "4" );
			}/* __construct() */

		}/* class chemistry_potion_column_two_thirds_one_third */

	}/* !class_exists( 'chemistry_potion_column_two_thirds_one_third' ) */

?>