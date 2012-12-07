<?php

	if( !class_exists( 'chemistry_potion_column_4' ) )
	{

		class chemistry_potion_column_4 extends chemistry_potion_column_abstract_class
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

				parent::__construct( 'row-4', __( '4 Columns' , 'chemistry' ) );
				$this->label = __( '4 evenly-spaced columns' , 'chemistry' );
				$this->core = true;
				$this->cols = '4';
				$this->col_count = 4;

			}/* __construct() */

		}/* class chemistry_potion_column_4 */

	}/* !class_exists( 'chemistry_potion_column_4' ) */

?>