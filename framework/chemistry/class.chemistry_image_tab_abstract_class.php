<?php

	/* ======================================================================================

	

	====================================================================================== */

	if( !class_exists( 'chemistry_image_tab_abstract_class' ) )
	{
		class chemistry_image_tab_abstract_class
		{
			protected $slug = '';
			protected $title = '';
			protected $can_upload = false;
			protected $images = array();
			protected $query_images = array();

			public function __construct( $slug, $title, $can_upload = false )
			{
				$this->slug = $slug;
				$this->title = $title;
				$this->can_upload = $can_upload;

				add_filter( 'media_upload_tabs', array( &$this, 'chemistry_image_tab_create' ), 99 );
				add_action( 'media_upload_'.$this->slug, array( &$this, 'chemistry_image_tab_handle_iframe' ) );
				add_action( 'admin_head', array( &$this, 'action_header' ) );
			}

			public function chemistry_image_tab_create( $tabs )
			{
				$newtab[$this->slug] = $this->title;

				return array_merge( $tabs, $newtab );
			}

			public function chemistry_image_tab_handle_iframe()
			{
				if( $this->slug == $_GET['tab'] )
				{
					return wp_iframe( array( $this, 'chemistry_image_tab_handle_media' ) );
				}
			}

			public function chemistry_image_tab_handle_media()
			{
				global $wpdb;

				if( $this->slug != $_GET['tab'] )
				{
					return;
				}

				media_upload_header();

				$page = 1;

				if( isset( $_GET['pag'] ) && $_GET['pag'] > 1 )
				{
					$page = intval( $_GET['pag'] );
				}

				$per_page = 42;

				$this->get_images( $page, $per_page );
				$count_posts = $wpdb->get_var( 'SELECT COUNT( * ) FROM `'.$wpdb->posts.'` WHERE `post_type`=\'attachment\' && SUBSTRING( `post_mime_type`, 1, 5 )=\'image\'' );

				if( $count_posts > 0 )
				{
					$count_posts = number_format( $count_posts );
				}

				$pages = intval( ceil( $count_posts / $per_page ) );

				?><script type="text/javascript">
					( function( $ )
					{
						function show_page( page )
						{
							var per_page = 28;
							var start = page * per_page;
							var end = start + per_page;

							$( "ul.images li" ).each(  function()
							{
								var $img = $( this ).find( "img" );

								$img.attr( "src", "" );
								$( this ).hide();
							} );

							$( "ul.images li" ).slice( start, end ).each(  function()
							{
								var $img = $( this ).find( "img" );
								$img.attr( "src", $img.data( "url" ) );
								$( this ).show();
							} );
						}

						$(  function()
						{
							$( "ul.images li" ).click(  function()
							{
								if( !single )
								{
									$( this ).toggleClass( "selected" );
								}
								else
								{
									$( "ul.images li.selected" ).removeClass( "selected" );
									$( this ).addClass( "selected" );
								}

								return false;
							} );

							$( "button[name=insert]" ).click(  function()
							{
								var images = [];

								$( "ul.images li.selected a" ).each(  function()
								{
									images.push( $( this ).attr( "href" ) );
								} );

								var win = window.dialogArguments || opener || parent || top;
								win.send_to_editor( images );

								return false;
							} );
						} );
					} )( jQuery );
				</script><form id="filter">
					<div class="tablenav">
						<div class="tablenav-pages">
						<?php
							if( $pages > 1 )
							{
								if( $page > 1 )
								{
									echo '<a href="'.$this->get_page_link( $page - 1 ).'" class="prev">&laquo;</a>';
								}

								if( $page > 4 )
								{
									echo '<a href="'.$this->get_page_link( 1 ).'" class="page-numbers">1</a>';
									echo '<span class="page-numbers dots">...</span>';
								}

								for( $i = $page - 3; $i < $page; $i++ )
								{
									if( $i >= 1 )
									{
										echo '<a href="'.$this->get_page_link( $i ).'" class="page-numbers">'.$i.'</a>';
									}
								}

								echo '<a href="'.$this->get_page_link( $page ).'" class="page-numbers current">'.$page.'</a>';

								for( $i = $page + 1; $i < $page + 4; $i++ )
								{
									if( $i <= $pages )
									{
										echo '<a href="'.$this->get_page_link( $i ).'" class="page-numbers">'.$i.'</a>';
									}
								}

								if( $page < $pages - 3 )
								{
									echo '<span class="page-numbers dots">...</span>';
									echo '<a href="'.get_pagenum_link( $pages ).'" class="page-numbers">'.$pages.'</a>';
								}

								if( $page < $pages )
								{
									echo '<a href="'.get_pagenum_link( $page + 1 ).'" class="next">&raquo;</a>';
								}

							}
						?>
						</div>
					</form>
				<form action="" method="post" id="image-form" class="media-upload-form type-form">
				<h3 class="media-title"><?php _e( 'Choose images', 'chemistry' ); ?></h3>
				<ul class="images">
				<?php
					foreach( $this->images as $image )
					{
						echo '<li><a href="'.$image['image'].'"><img src="'.$image['thumbnail'].'" width="100" height="100" /></a></li>';
					}
				?>
				</ul>
				<fieldset class="chemistry-form">
					<div class="buttonset-1">
						<button type="submit" class="button button-secondary alignright" name="insert"><?php _e( 'Insert images', 'chemistry' ); ?></button>
					</div>
				</fieldset>
				</form>
				<?php if( $this->can_upload ): ?>
				<form enctype="multipart/form-data" method="post" action="" class="media-upload-form type-form validate" id="file-form">

		<?php media_upload_form(); ?>

		<script type="text/javascript">
		jQuery( function( $ ){
			var preloaded = $( ".media-item.preloaded" );
			if(  preloaded.length > 0  ) {
				preloaded.each( function(){prepareMediaItem( {id:this.id.replace( /[^0-9]/g, '' )},'' );} );
			}
			updateMediaForm();
			post_id = 0;
			shortform = 1;
		} );
		</script>
		<input type="hidden" name="post_id" id="post_id" value="0" />
		<?php wp_nonce_field( 'media-form' ); ?>
		<div id="media-items" class="hide-if-no-js"> </div>
		<?php submit_button(  __(  'Save all changes'  ), 'button savebutton hide-if-no-js', 'save'  ); ?>
		</form><?php endif;
			}

			public function action_header()
			{
				echo '<style type="text/css">
					html, body#media-upload { min-width: 650px !important; }
					form#image-form { width: 96%; margin-top: 20px; margin: 1em; }
					ul.images { margin: 0; padding: 0; }
					ul.images li { display: block; float: left; width: 110px; height: 110px; padding: 5px; margin: 3px; background-color: rgb(247,247,247); border: 1px solid rgb(223,223,223); }
					ul.images li:hover{ background-color: rgb(223,223,223); }
					ul.images li.selected { background-color: #333; }
					ul.images li img { padding-left: 3px; padding-top: 3px; }
					p.ml-submit input.insert { display: block !important; }
					.media-upload-form{ width: 96%; margin-left: 2%; }
					.media-upload-form fieldset.chemistry-form { padding-top: 5px; }
					#sidemenu{ font-weight: normal; margin: 0 5px; left: 0; bottom: -1px; float: none; overflow: hidden; }
					h3.media-title{ font-family: Georgia,"Times New Roman",Times,serif; font-weight: normal; color: rgb(90, 90, 90); font-size: 1.6em; }
					#images, ul.images{ overflow: hidden; }
					#image-form button[type="submit"]{ background: rgb(243, 243, 243);
background-image: -webkit-gradient(linear,left top,left bottom,from(rgb(254, 254, 254)),to(rgb(244, 244, 244)));
background-image: -webkit-linear-gradient(top,rgb(254, 254, 254),rgb(244, 244, 244));
background-image: -moz-linear-gradient(top,rgb(254, 254, 254),rgb(244, 244, 244));
background-image: -o-linear-gradient(top,rgb(254, 254, 254),rgb(244, 244, 244));
background-image: linear-gradient(to bottom,rgb(254, 254, 254),rgb(244, 244, 244));
border-color: rgb(187, 187, 187);
color: rgb(51, 51, 51);
text-shadow: 0 1px 0 white; font-weight: normal; font-size: 12px;  padding: 0 10px 1px; box-sizing: border-box; white-space: nowrap; -webkit-appearance: none; border-radius: 3px; height: 23px; line-height: 1; }
					#image-form button[type="submit"]:hover{ border-color: rgb(153,153,153); }
				</style>
				<script type="text/javascript">
					var single = '.( ( isset( $_GET['single'] ) && $_GET['single'] == 'true' ) ? 'true' : 'false' ).';
				</script>';
			}

			protected function get_images( $page, $per_page )
			{
				$query_images = new WP_Query(  array
				( 
					'post_type' => 'attachment',
					'post_mime_type' => 'image',
					'post_status' => 'inherit',
					'posts_per_page' => $per_page,
					'offset' => ( $page - 1 ) * $per_page
				 ) );

				foreach( $query_images->posts as $image )
				{
					$this->images[] = array( 'image' => wp_get_attachment_url( $image->ID ), 'thumbnail' => wp_get_attachment_thumb_url( $image->ID ) );
				}
			}

			protected function get_page_link( $page )
			{
				$data = $_GET;

				$data['pag'] = $page;
				$url = 'media-upload.php?';

				foreach( $data as $k => $v )
				{
					$url .= '&'.$k.'='.$v;
				}

				return $url;
			}
		}
	}

?>