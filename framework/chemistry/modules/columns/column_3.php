<?php

	if( !class_exists( 'chemistry_potion_column_3' ) )
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

		class chemistry_potion_column_3 extends chemistry_potion_column_abstract_class
		{

			public function __construct()
			{

				parent::__construct( 'row-3', __( '3 Columns' , 'chemistry' ) );
				$this->label = __( '3 evenly-spaced columns' , 'chemistry' );
				$this->core = true;
				$this->cols = '3';
				$this->col_count = 3;

			}/* __construct() */

		}/* class chemistry_potion_column_3 */

	}/* !class_exists( 'chemistry_potion_column_3' ) */

?>