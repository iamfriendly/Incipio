<?php

	if( !class_exists( 'chemistry_potion_column_6' ) )
	{

		class chemistry_potion_column_6 extends chemistry_potion_column_abstract_class
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

				parent::__construct( 'row-6', __( '6 Columns' , 'chemistry' ) );
				$this->label = __( '6 evenly-spaced columns' , 'chemistry' );
				$this->core = true;
				$this->cols = '6';
				$this->col_count = 6;

			}/* __construct() */

		}/* class chemistry_potion_column_6 */

	}/* !class_exists( 'chemistry_potion_column_6' ) */

?>