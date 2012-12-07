<?php

	if( !class_exists( 'chemistry_potion_column_one_quarter_one_quarter_one_half' ) )
	{

		class chemistry_potion_column_one_quarter_one_quarter_one_half extends chemistry_potion_column_abstract_class
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

				parent::__construct( 'three_cols_quarter_quarter_half', __( '&#188; + &#188; + &#189; Cols' , 'chemistry' ) );
				$this->label = __( '3 columns; &#188; - &#188; - &#189;', 'chemistry' , 'chemistry' );
				$this->core = true;
				$this->col_count = 3;
				$this->splits = array( 1 => "3", 2 => "3", 3 => "6" );

			}/* __construct() */

		}/* class chemistry_potion_column_one_quarter_one_quarter_one_half */

	}/* !class_exists( 'chemistry_potion_column_one_quarter_one_quarter_one_half' ) */

?>