jQuery(document).ready(function ($) {


	/* ===========================================================================================

	Tooltips

	=========================================================================================== */
	
	
	$(this).tooltips();
	
	
	/* ===========================================================================================

	Tabs

	=========================================================================================== */
	
	
	if( $( 'dl.tabs' ).length != 0 ){
		
		function activateTab( $tab ) {
			var $activeTab = $tab.closest( 'dl' ).find( '.active' ),
					contentLocation = $tab.attr( 'href' ) + 'Tab';
					
			// Strip off the current url that IE adds
			contentLocation = contentLocation.replace(/^.+#/, '#');
	
			//Make Tab Active
			$( $activeTab ).removeClass( 'active' );
			$( contentLocation ).closest( '.active' ).removeClass( 'active' );
			$( $tab ).parent().addClass( 'active' );
	
			//Show Tab Content
			$( contentLocation ).closest( '.tabs-content' ).children( 'li' ).hide();
			$( contentLocation ).css( 'display', 'block' );
		}
	
	  $( 'dl.tabs dd a' ).live( 'click', function( event ){
	    
	    activateTab( $( this ) );
	    return false;
	    
	  });
	
		if( window.location.hash ){
			
			activateTab( $( 'a[href="' + window.location.hash + '"]' ) );
			
		}else{
		
			$( 'dl.tabs' ).each( function(){
				
				$( this ).find( 'dd:first-of-type' ).addClass( 'active' );
				$( this ).next().children( 'li:first' ).addClass( 'active' ).css( 'display', 'block' );

				
			} );
		
		}
		
	}
	
	
	/* ===========================================================================================

	Accordions. Chris Coyier - legend. (Modded a bit so you can have more than 1 on a page)

	=========================================================================================== */

	
	if( $( 'dl.accordion' ).length != 0 ){
	  
		var allPanels = $( '.accordion > dd' ).hide();
		$( 'dd.active' ).slideDown();
		
		$( '.accordion > dt > a' ).click( function() {
		
			$link = $( this );
			$target_dd =  $link.parent().next();
			
			if( !$target_dd.hasClass( 'active' ) ){
				
				$link.parent().parent().find( 'dt.active' ).each( function(){
					
					$( this ).removeClass( 'active' );
					$( this ).next().removeClass( 'active' ).slideUp();
					
				} );
				
				$link.parent().addClass( 'active' );
				
				 $target_dd.addClass( 'active' ).slideDown();
				
				
			}
		
			return false;
		
		} );
	
	}
	
	
	/* ===========================================================================================

	ResponsiveSlides Slider

	=========================================================================================== */
	
	if( $( '.rslides' ).length != 0 ){
	
		$(".rslides").responsiveSlides({
	        auto: false,
	        pager: true,
	        nav: true,
	        speed: 500
	      });
	
	}
	
});


//When someone clicks in the search box, clear if it's the default value
function clearText( field ){

	if( field.defaultValue === field.value ){ field.value = ''; }
	else if( field.value === '' ){ field.value = field.defaultValue; }

}