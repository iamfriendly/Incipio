<?php

	if( !class_exists( 'chemistry_potion_column_1' ) )
	{

		class chemistry_potion_column_1 extends chemistry_potion_column_abstract_class
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

				parent::__construct( 'row-1', __( '1 Column' , 'chemistry' ) );
				$this->label = __( '1 single, full-width column' , 'chemistry' );
				$this->core = true;
				$this->cols = '1';
				$this->col_count = 1;

			}/* __construct() */

		}/* class chemistry_potion_column_1 */

	}/* !class_exists( 'chemistry_potion_column_1' ) */

?>