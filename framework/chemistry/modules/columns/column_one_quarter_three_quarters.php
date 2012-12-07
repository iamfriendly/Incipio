<?php

	if( !class_exists( 'chemistry_potion_column_one_quarter_three_quarters' ) )
	{

		class chemistry_potion_column_one_quarter_three_quarters extends chemistry_potion_column_abstract_class
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

				parent::__construct( 'two_cols_quarter_three_quarter', __( '&#188; + &#190; Columns' , 'chemistry' ) );
				$this->label = __( '2 Columns; &#188; - &#190;' , 'chemistry' );
				$this->core = true;
				$this->col_count = 2;
				$this->splits = array( 1 => "3", 2 => "9" );
			}/* __construct() */

		}/* class chemistry_potion_column_one_quarter_three_quarters */

	}/* !class_exists( 'chemistry_potion_column_one_quarter_three_quarters' ) */

?>