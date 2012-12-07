<?php

	if( !class_exists( 'chemistry_potion_column_three_quarters_one_quarter' ) )
	{

		class chemistry_potion_column_three_quarters_one_quarter extends chemistry_potion_column_abstract_class
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

				parent::__construct( 'two_cols_three_quarter_quarter', __( '&#190; + &#188; Columns' , 'chemistry' ) );
				$this->label = __( '2 Columns; &#190; - &#188;' , 'chemistry' );
				$this->core = true;
				$this->col_count = 2;
				$this->splits = array( 1 => "9", 2 => "3" );

			}/* __construct() */

		}/* class chemistry_potion_column_three_quarters_one_quarter */

	}/* !class_exists( 'chemistry_potion_column_three_quarters_one_quarter' ) */

?>