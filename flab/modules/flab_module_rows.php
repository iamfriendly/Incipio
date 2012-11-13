<?php

	/* ================================================================================ */

	if( !class_exists( 'flab_row2_widget' ) )
	{
	
		/**
		 * Set up the title and description
		 *
		 * @package FLAB
		 * @author iamfriendly
		 * @version 1.0
		 * @since 1.0
		 */
	
		class flab_row2_widget extends flab_row_base_widget
		{
		
			public function __construct( )
			{
				
				parent::__construct( 'row-2', __( '2 Columns' ) );
				$this->label = __( '2 columns that are &frac12; width each.' );
				$this->core = true;
				$this->cols = '2';
				$this->col_count = 2;
				
			}/* __construct( ) */
			
		}/* class flab_row2_widget */
		
	}
	
	/* ================================================================================ */
	
	if( !class_exists( 'flab_row3_widget' ) )
	{
	
		/**
		 * Set up the title and description
		 *
		 * @package FLAB
		 * @author iamfriendly
		 * @version 1.0
		 * @since 1.0
		 */
	
		class flab_row3_widget extends flab_row_base_widget
		{
		
			public function __construct( )
			{
			
				parent::__construct( 'row-3', __( '3 Columns' ) );
				$this->label = __( '3 columns that are &#8531; width each.' );
				$this->core = true;
				$this->cols = '3';
				$this->col_count = 3;
				
			}/* __construct( ) */
			
		}/* class flab_row3_widget */
		
	}
	
	/* ================================================================================ */
	
	if( !class_exists( 'flab_row4_widget' ) )
	{
	
		/**
		 * Set up the title and description
		 *
		 * @package FLAB
		 * @author iamfriendly
		 * @version 1.0
		 * @since 1.0
		 */
	
		class flab_row4_widget extends flab_row_base_widget
		{
		
			public function __construct( )
			{
			
				parent::__construct( 'row-4', __( '4 Columns' ) );
				$this->label = __( '4 columns that are &frac14; width each.' );
				$this->core = true;
				$this->cols = '4';
				$this->col_count = 4;
				
			}/* __construct( ) */
			
		}/* class flab_row4_widget */
		
	}
	
	/* ================================================================================ */
	
	if( !class_exists( 'flab_row5_widget' ) )
	{
	
		class flab_row5_widget extends flab_row_base_widget
		{
		
			/**
			 * Set up the title and description
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
		
			public function __construct( )
			{
			
				parent::__construct( 'row-5', __( '5 Columns' ) );
				$this->label = __( '5 columns that are &#8533; width each.' );
				$this->core = true;
				$this->cols = '5';
				$this->col_count = 5;
				
			}/* __construct( ) */
			
		}/* class flab_row5_widget */
		
	}
	
	/* ================================================================================ */
	
	if( !class_exists( 'flab_row6_widget' ) )
	{
	
		class flab_row6_widget extends flab_row_base_widget
		{
		
			/**
			 * Set up the title and description
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
		
			public function __construct( )
			{
			
				parent::__construct( 'row-6', __( '6 Columns' ) );
				$this->label = __( '6 columns that are &#8537; width each.' );
				$this->core = true;
				$this->cols = '6';
				$this->col_count = 6;
				
			}/* __construct( ) */
			
		}/* class flab_row6_widget */
		
	}
	
	/* ================================================================================ */
	
	if( !class_exists( 'flab_row2d3_1_widget' ) )
	{
	
		class flab_row2d3_1_widget extends flab_row_base_widget
		{
		
			/**
			 * Set up the title and description
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
		
			public function __construct( )
			{
			
				parent::__construct( 'row-2d3-1', __( '&#8532; and &#8531;' ) );
				$this->label = __( '2 columns; One &#8532; and one &#8531;' );
				$this->core = true;
				$this->cols = '2d3-1';
				$this->col_count = 2;
				$this->splits = array( 1 => "8", 2 => "4" );
				
			}/* __construct( ) */
			
		}/* class flab_row2d3_1_widget */
		
	}
	
	/* ================================================================================ */
	
	if( !class_exists( 'flab_row2d3_2_widget' ) )
	{
	
		class flab_row2d3_2_widget extends flab_row_base_widget
		{
		
			/**
			 * Set up the title and description
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
		
			public function __construct( )
			{
			
				parent::__construct( 'row-2d3-2', __( '&#8531; and &#8532;' ) );
				$this->label = __( '2 columns; One &#8531; and one &#8532;' );
				$this->core = true;
				$this->cols = '2d3-2';
				$this->col_count = 2;
				$this->splits = array( 1 => "4", 2 => "8" );
				
			}/* __construct( ) */
			
		}/* class flab_row2d3_2_widget */
		
	}
	
	/* ================================================================================ */
	
	if( !class_exists( 'flab_row3d4_1_widget' ) )
	{
	
		class flab_row3d4_1_widget extends flab_row_base_widget
		{
		
			/**
			 * Set up the title and description
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
		
			public function __construct( )
			{
			
				parent::__construct( 'row-3d4-1', __( '&frac34; and &frac14;' ) );
				$this->label = __( '2 columns; One &frac34; and one &frac14;' );
				$this->core = true;
				$this->cols = '3d4-1';
				$this->col_count = 2;
				$this->splits = array( 1 => "9", 2 => "3" );
				
			}/* __construct( ) */
			
		}/* class flab_row3d4_1_widget */
		
	}
	
	/* ================================================================================ */
	
	if( !class_exists( 'flab_row3d4_2_widget' ) )
	{
	
		class flab_row3d4_2_widget extends flab_row_base_widget
		{
		
			/**
			 * Set up the title and description
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
		
			public function __construct( )
			{
			
				parent::__construct( 'row-3d4-2', __( '&frac14; and &frac34;' ) );
				$this->label = __( '2 columns; One &frac14; and one &frac34;' );
				$this->core = true;
				$this->cols = '3d4-2';
				$this->col_count = 2;
				$this->splits = array( 1 => "3", 2 => "9" );
				
			}/* __construct( ) */
			
		}/* class flab_row3d4_2_widget */
		
	}
	
	/* ================================================================================ */
	
	if( !class_exists( 'flab_row2d4_1_widget' ) )
	{
	
		class flab_row2d4_1_widget extends flab_row_base_widget
		{
		
			/**
			 * Set up the title and description
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
		
			public function __construct( )
			{
			
				parent::__construct( 'row-2d4-1', __( '&frac12; - &frac14; - &frac14;' ) );
				$this->label = __( '3 Columns; &frac12; then &frac14; then &frac14;' );
				$this->core = true;
				$this->cols = '2d4-1';
				$this->col_count = 3;
				$this->splits = array( 1 => "6", 2 => "3", 3 => "3" );
				
			}/* __construct( ) */
			
		}/* class flab_row2d4_1_widget */
		
	}
	
	/* ================================================================================ */
	
	if( !class_exists( 'flab_row2d4_2_widget' ) )
	{
	
		class flab_row2d4_2_widget extends flab_row_base_widget
		{
		
			/**
			 * Set up the title and description
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
		
			public function __construct( )
			{
			
				parent::__construct( 'row-2d4-2', __( '&frac14; - &frac12; - &frac14;' ) );
				$this->label = __( '3 Columns; &frac14; then &frac12; then &frac14;' );
				$this->core = true;
				$this->cols = '2d4-2';
				$this->col_count = 3;
				$this->splits = array( 1 => "3", 2 => "6", 3 => "3" );
				
			}/* __construct( ) */
			
		}/* class flab_row2d4_2_widget */
		
	}
	
	/* ================================================================================ */
	
	if( !class_exists( 'flab_row2d4_3_widget' ) )
	{
	
		class flab_row2d4_3_widget extends flab_row_base_widget
		{
		
			/**
			 * Set up the title and description
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
		
			public function __construct( )
			{
			
				parent::__construct( 'row-2d4-3', __( '&frac14; - &frac14; - &frac12;' ) );
				$this->label = __( '3 Columns; &frac14; then &frac14; then &frac12;' );
				$this->core = true;
				$this->cols = '2d4-3';
				$this->col_count = 3;
				$this->splits = array( 1 => "3", 2 => "3", 3 => "6" );
				
			}/* __construct() */
			
		}/* class flab_row2d4_3_widget */
		
	}
	
	/* ================================================================================ */

?>