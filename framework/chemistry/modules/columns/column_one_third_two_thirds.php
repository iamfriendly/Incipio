<?php

	if( !class_exists( 'chemistry_potion_column_one_third_two_thirds' ) )
	{

		class chemistry_potion_column_one_third_two_thirds extends chemistry_potion_column_abstract_class
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
				parent::__construct( 'two_cols_third_two_third', __( '&#8531; + &#8532; Columns' , 'chemistry' ) );
				$this->label = __( '2 Columns; &#8531; - &#8532;' , 'chemistry' );
				$this->core = true;
				$this->col_count = 2;
				$this->splits = array( 1 => "4", 2 => "8" );

			}/* __construct() */

		}/* class chemistry_potion_column_one_third_two_thirds */

	}/* !class_exists( 'chemistry_potion_column_one_third_two_thirds' ) */

?>