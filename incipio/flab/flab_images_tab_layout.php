<?php 

	/* ================================================================================ */

	if(  !class_exists( 'flab_add_images_tab_layout' ) )
	{
	
		class flab_add_images_tab_layout
		{
		
			/**
			 * Some vars for our new images tab on the media uploader
			 * OOP Version of the code found: http://axcoto.com/blog/article/307
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
			 
			/* ============================================================================ */
		
			protected $image_tab_slug = '';
			protected $image_tab_title = '';
			protected $user_can_upload = false;
			protected $available_images = array();
			protected $wp_query_images = array();
			
			/* ============================================================================ */
	
			public function __construct( $image_tab_slug, $image_tab_title, $user_can_upload = false )
			{
			
				$this->slug = $image_tab_slug;
				$this->title = $image_tab_title;
				$this->can_upload = $user_can_upload;
	
				add_filter( 'media_upload_tabs', array( &$this, 'flab_build_tab_filter' ) );
				add_action( 'media_upload_'.$this->slug, array( &$this, 'flab_handle_images_tab_menu_action' ) );
				add_action( 'admin_head', array( &$this, 'flab_image_tab_header_action' ) );
				
			}
			
			/* ============================================================================ */
	
			/**
			 * Add our new tab into the tabs array
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public function flab_build_tab_filter( $tabs )
			{
			
				$newtab[$this->slug] = $this->title;
	
				return array_merge( $tabs, $newtab );
				
			}/* flab_build_tab_filter() */
			
			/* ============================================================================ */
	
			/**
			 * Function to actually call the wp_iframe
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public function flab_handle_images_tab_menu_action()
			{
				
				if( $this->slug == $_GET['tab'] )
				{
					return wp_iframe( array( $this, 'media_process' ) );
				}
				
			}/* flab_handle_images_tab_menu_action() */
			
			/* ============================================================================ */
	
			/**
			 * Function to handle the media upload on our new tab in the iframe
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public function media_process()
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
				$count_posts = $wpdb->get_var( 'SELECT COUNT(*) FROM `'.$wpdb->posts.'` WHERE `post_type`=\'attachment\' && SUBSTRING(`post_mime_type`, 1, 5)=\'image\'' );
	
				if( $count_posts > 0 )
				{
					$count_posts = number_format( $count_posts );
				}
	
				$pages = intval( ceil( $count_posts / $per_page ) );
	
				?>
				
					<script type="text/javascript">eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('(0($){0 m(b){2 c=r;2 d=b*c;2 e=d+c;$("3.4 5").7(0(){2 a=$(1).j("9");a.8("g","");$(1).v()});$("3.4 5").w(d,e).7(0(){2 a=$(1).j("9");a.8("g",a.k("l"));$(1).H()})}$(0(){$("3.4 5").h(0(){o(!p){$(1).q("6")}s{$("3.4 5.6").t("6");$(1).u("6")}i f});$("x.y").h(0(){2 a=[];$("3.4 5.6 a").7(0(){a.z($(1).8("A"))});2 b=B.C||D||E||F;b.G(a);i f})})})(n);',44,44,'function|this|var|ul|images|li|selected|each|attr|img||||||false|src|click|return|find|data|url|show_page|jQuery|if|single|toggleClass|42|else|removeClass|addClass|hide|slice|input|insert|push|href|window|dialogArguments|opener|parent|top|send_to_editor|show'.split('|'),0,{}))</script>
					
					<form id="filter">
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
										echo '<a href="'.$this->get_page_link(1).'" class="page-numbers">1</a>';
										echo '<span class="page-numbers dots">...</span>';
									}
									
									for ( $i = $page - 3; $i < $page; $i++ )
									{
										if( $i >= 1 )
										{
										echo '<a href="'.$this->get_page_link( $i ).'" class="page-numbers">'.$i.'</a>';
										}
									}
									
									echo '<a href="'.$this->get_page_link( $page ).'" class="page-numbers current">'.$page.'</a>';
									
									for ( $i = $page + 1; $i < $page + 4; $i++ )
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
						</div>
					</form>
				
					<?php if( is_array( $this->images ) && count( $this->images > 0 ) ) : ?>
				
					<form action="" method="post" id="image-form" class="media-upload-form type-form">
						
						<h3 class="media-title"><?php flab::lang( 'Choose images' ); ?></h3>
						<ul class="images">
							<?php
								foreach( $this->images as $image )
								{
									echo '<li><a href="'.$image['image'].'"><img src="'.$image['thumbnail'].'" width="79" height="79" /></a></li>';
								}
							?>
						</ul>
						<p class="ml-submit" style="clear: both;"><input type="submit" name="insert" class="insert button alignright savebutton" value="<?php flab::lang( 'Insert images' ); ?>"  /></p>
					</form>
					
					<?php else : ?>
					
						<h3 class="media-title"><?php _e( "No images in media library", THEMENAME ); ?></h3>
						<p><?php _e( "There are currently no images in your media library. Please upload some :)", THEMENAME ); ?></p>
					
					<?php endif; ?>
				
					<?php if( $this->can_upload ): ?>
						<form enctype="multipart/form-data" method="post" action="" class="media-upload-form type-form validate" id="file-form">
	
							<?php media_upload_form(); ?>
	
							<script type="text/javascript">eval(function(p,a,c,k,e,r){e=function(c){return c.toString(a)};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('c(2($){b a=$(".e-5.6");7(a.8>0){a.4(2(){d({3:f.3.h(/[^0-9]/g,\'\')},\'\')})}i();j=0;k=1});',21,21,'||function|id|each|item|preloaded|if|length|||var|jQuery|prepareMediaItem|media|this||replace|updateMediaForm|post_id|shortform'.split('|'),0,{}))</script>
							<input type="hidden" name="post_id" id="post_id" value="0" />
							<?php wp_nonce_field( 'media-form' ); ?>
							<div id="media-items" class="hide-if-no-js"> </div>
							<?php submit_button( __( 'Save changes' ), 'button savebutton hide-if-no-js', 'save' ); ?>
						</form>
						
					<?php endif;
		
			}/* media_process() */
			
			/* ============================================================================ */
	
			/**
			 * Fairly horrible hack to add some css and js to the header
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			public function flab_image_tab_header_action()
			{
			
				echo '<style type="text/css">
					html, body#media-upload { min-width: 650px !important; }
					form#image-form { width: 100%; }
					ul.images { margin: 0; padding: 0; }
					ul.images li { display: block; float: left; width: 83px; height: 83px; padding: 2; margin: 3px; background-color: #ccc; }
					ul.images li.selected { background-color: #333; }
					ul.images li img { padding-left: 2px; padding-top: 2px; }
					p.ml-submit input.insert { display: block !important; }
				</style>
				<script type="text/javascript">
					var single = '.( ( isset($_GET['single'] ) && $_GET['single'] == 'true' ) ? 'true' : 'false' ).';
				</script>';
				
			}/* flab_image_tab_header_action() */
			
			/* ============================================================================ */
	
			/**
			 * Get x number of images on a page
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			protected function get_images( $page, $per_page )
			{
			
				$wp_query_images = new WP_Query( array(
				
					'post_type' => 'attachment',
					'post_mime_type' => 'image',
					'post_status' => 'inherit',
					'posts_per_page' => $per_page,
					'offset' => ( $page - 1 ) * $per_page
					
				) );
	
				foreach( $wp_query_images->posts as $image )
				{
					$this->images[] = array(  'image' => wp_get_attachment_url( $image->ID ), 'thumbnail' => wp_get_attachment_thumb_url( $image->ID ) );
				}
				
			}/* get_images() */
			
			/* ============================================================================ */
	
			/**
			 * Get the link for the page so we're able to isert correctly
			 *
			 * @package FLAB
			 * @author iamfriendly
			 * @version 1.0
			 * @since 1.0
			 */
	
			protected function get_page_link( $page )
			{
			
				$data = $_GET;
				
				$data['pag'] = $page;
				$url = 'media-upload.php?';
				
				foreach( $data as $k => $v )
				{
					$url .= '&' . $k . '=' . $v;
				}
				
				return $url;
				
			}/* get_page_link() */
			
			/* ============================================================================ */
		
		}/* flab_add_images_tab_layout() */
		
	}

?>