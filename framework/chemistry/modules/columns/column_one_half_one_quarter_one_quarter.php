<?php

	if( !class_exists( 'chemistry_potion_column_one_half_one_quarter_one_quarter' ) )
	{

		class chemistry_potion_column_one_half_one_quarter_one_quarter extends chemistry_potion_column_abstract_class
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
				parent::__construct( 'three_cols_half_quarter_quarter', __( '&#189; + &#188; + &#188; Cols' , 'chemistry' ) );
				$this->label = __( '3 Columns; &#189; - &#188; - &#188;' , 'chemistry' );
				$this->core = true;
				$this->col_count = 3;
				$this->splits = array( 1 => "6", 2 => "3", 3 => "3" );

			}/* __construct() */

		}/* class chemistry_potion_column_one_half_one_quarter_one_quarter */

	}/* !class_exists( 'chemistry_potion_column_one_half_one_quarter_one_quarter' ) */

?>