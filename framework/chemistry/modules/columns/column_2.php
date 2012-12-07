<?php

	if( !class_exists( 'chemistry_potion_column_2' ) )
	{

		class chemistry_potion_column_2 extends chemistry_potion_column_abstract_class
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

				parent::__construct( 'row-2', __( '2 Columns' , 'chemistry' ) );
				$this->label = __( '2 evenly-spaced columns' , 'chemistry' );
				$this->core = true;
				$this->cols = '2';
				$this->col_count = 2;

			}/* __construct() */

		}/* class chemistry_potion_column_2 */

	}/* !class_exists( 'chemistry_potion_column_2' ) */

?>